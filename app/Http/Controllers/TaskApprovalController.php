<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchApproval;
use App\Notifications\BatchStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskApprovalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user?->role;

        $base = Batch::query()->with(['product', 'productionLine', 'creator', 'supervisor', 'manager']);

        if ($role === 'Supervisor') {
            $pendingBatches = (clone $base)
                ->where('status', 'submitted')
                ->where('supervisor_id', $user->id)
                ->orderByDesc('id')
                ->get();
            $canAct = true;
        } elseif ($role === 'Manager') {
            $pendingBatches = (clone $base)
                ->where('status', 'waiting_manager')
                ->where('manager_id', $user->id)
                ->orderByDesc('id')
                ->get();
            $canAct = true;
        } else {
            $pendingBatches = (clone $base)
                ->whereIn('status', ['submitted', 'waiting_manager'])
                ->orderByDesc('id')
                ->get();
            $canAct = false;
        }

        $approvedCount = BatchApproval::query()
            ->when($role === 'Supervisor', fn ($q) => $q->where('step', 'supervisor')->where('user_id', $user->id))
            ->when($role === 'Manager', fn ($q) => $q->where('step', 'manager')->where('user_id', $user->id))
            ->when(! in_array($role, ['Supervisor', 'Manager']), fn ($q) => $q->where('step', '!=', 'admin'))
            ->where('status', 'approved')
            ->count();

        $rejectedCount = BatchApproval::query()
            ->when($role === 'Supervisor', fn ($q) => $q->where('step', 'supervisor')->where('user_id', $user->id))
            ->when($role === 'Manager', fn ($q) => $q->where('step', 'manager')->where('user_id', $user->id))
            ->when(! in_array($role, ['Supervisor', 'Manager']), fn ($q) => $q->where('step', '!=', 'admin'))
            ->where('status', 'rejected')
            ->count();

        return view('task-approval.index', [
            'pendingBatches' => $pendingBatches,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'canAct' => $canAct,
        ]);
    }

    public function approve(Request $request, Batch $batch): RedirectResponse
    {
        $user = $request->user();
        $step = $this->currentStepFor($batch);
        $this->authorizeAction($batch, $user, $step);

        $validated = $request->validate([
            'note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($batch, $user, $step, $validated) {
            BatchApproval::where('batch_id', $batch->id)
                ->where('step', $step)
                ->where('status', 'pending')
                ->update([
                    'user_id' => $user->id,
                    'status' => 'approved',
                    'note' => $validated['note'] ?? null,
                    'acted_at' => now(),
                ]);

            if ($step === 'supervisor') {
                $batch->update(['status' => 'waiting_manager']);

                // Naik ke tahap Manager -> notif ke manager yang dipilih di batch ini
                if ($manager = $batch->manager) {
                    $manager->notify(new BatchStatusNotification(
                        $batch,
                        "Batch #{$batch->batch_number} butuh approval kamu",
                        "Batch #{$batch->batch_number} sudah disetujui Supervisor, menunggu approval Manager.",
                        route('task-approval.index')
                    ));
                }
            } elseif ($step === 'manager') {
                $batch->update(['status' => 'released', 'released_at' => now()]);

                // Selesai semua approval -> notif ke pembuat batch
                if ($creator = $batch->creator) {
                    $creator->notify(new BatchStatusNotification(
                        $batch,
                        "Batch #{$batch->batch_number} sudah di-release",
                        "Batch #{$batch->batch_number} disetujui Manager dan sudah released.",
                        route('batch-repository.show', $batch)
                    ));
                }
            }
        });

        return back()->with('status', 'Batch berhasil di-approve.');
    }

    public function reject(Request $request, Batch $batch): RedirectResponse
    {
        $user = $request->user();
        $step = $this->currentStepFor($batch);
        $this->authorizeAction($batch, $user, $step);

        $validated = $request->validate([
            'note' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($batch, $user, $step, $validated) {
            BatchApproval::where('batch_id', $batch->id)
                ->where('step', $step)
                ->where('status', 'pending')
                ->update([
                    'user_id' => $user->id,
                    'status' => 'rejected',
                    'note' => $validated['note'],
                    'acted_at' => now(),
                ]);

            $batch->update(['status' => 'rejected']);

            // Ditolak di step manapun -> notif ke pembuat batch
            if ($creator = $batch->creator) {
                $creator->notify(new BatchStatusNotification(
                    $batch,
                    "Batch #{$batch->batch_number} ditolak",
                    "Batch #{$batch->batch_number} ditolak pada tahap {$step}. Alasan: {$validated['note']}",
                    route('batch-form.edit', $batch)
                ));
            }
        });

        return back()->with('status', 'Batch ditolak.');
    }

    private function currentStepFor(Batch $batch): string
    {
        return match ($batch->status) {
            'submitted' => 'supervisor',
            'waiting_manager' => 'manager',
            default => abort(422, 'Batch ini tidak sedang menunggu approval.'),
        };
    }

    private function authorizeAction(Batch $batch, $user, string $step): void
    {
        if (! $user) {
            abort(403);
        }

        if ($step === 'supervisor' && ($user->role !== 'Supervisor' || (int) $batch->supervisor_id !== (int) $user->id)) {
            abort(403, 'Anda tidak berhak melakukan approval pada batch ini.');
        }

        if ($step === 'manager' && ($user->role !== 'Manager' || (int) $batch->manager_id !== (int) $user->id)) {
            abort(403, 'Anda tidak berhak melakukan approval pada batch ini.');
        }
    }
}
