<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchApproval;
use App\Models\User;
use App\Notifications\BatchStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $validationRules = [
            'note' => ['nullable', 'string'],
        ];

        if ($step === 'supervisor') {
            $validationRules['laporan_penyimpangan'] = ['required', 'in:ada,tidak'];
            $validationRules['change_control'] = ['required', 'in:ada,tidak'];
            $validationRules['penyimpangan_nomor'] = ['nullable', 'array'];
            $validationRules['penyimpangan_status'] = ['nullable', 'array'];
            $validationRules['change_control_nomor'] = ['nullable', 'array'];
            $validationRules['change_control_status'] = ['nullable', 'array'];
        }

        $validated = $request->validate($validationRules);

        $penyimpanganItems = null;
        $changeControlItems = null;

        if ($step === 'supervisor') {
            if ($validated['laporan_penyimpangan'] === 'tidak') {
                $penyimpanganItems = [['nomor' => 'N/A', 'status' => 'N/A']];
            } else {
                $penyimpanganItems = [];
                $nomorArr = $request->input('penyimpangan_nomor', []);
                $statusArr = $request->input('penyimpangan_status', []);
                foreach ($nomorArr as $idx => $nomor) {
                    $nomorVal = trim((string) $nomor);
                    $statusVal = trim((string) ($statusArr[$idx] ?? ''));
                    if ($nomorVal !== '' || $statusVal !== '') {
                        $penyimpanganItems[] = [
                            'nomor' => $nomorVal !== '' ? $nomorVal : 'N/A',
                            'status' => $statusVal !== '' ? $statusVal : 'N/A',
                        ];
                    }
                }
                if (empty($penyimpanganItems)) {
                    $penyimpanganItems = [['nomor' => 'N/A', 'status' => 'N/A']];
                }
            }

            if ($validated['change_control'] === 'tidak') {
                $changeControlItems = [['nomor' => 'N/A', 'status' => 'N/A']];
            } else {
                $changeControlItems = [];
                $fupNomorArr = $request->input('change_control_nomor', []);
                $fupStatusArr = $request->input('change_control_status', []);
                foreach ($fupNomorArr as $idx => $nomor) {
                    $nomorVal = trim((string) $nomor);
                    $statusVal = trim((string) ($fupStatusArr[$idx] ?? ''));
                    if ($nomorVal !== '' || $statusVal !== '') {
                        $changeControlItems[] = [
                            'nomor' => $nomorVal !== '' ? $nomorVal : 'N/A',
                            'status' => $statusVal !== '' ? $statusVal : 'N/A',
                        ];
                    }
                }
                if (empty($changeControlItems)) {
                    $changeControlItems = [['nomor' => 'N/A', 'status' => 'N/A']];
                }
            }
        }

        DB::transaction(function () use ($batch, $user, $step, $validated, $penyimpanganItems, $changeControlItems) {
            BatchApproval::where('batch_id', $batch->id)
                ->where('step', $step)
                ->where('status', 'pending')
                ->update([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'status' => 'approved',
                    'note' => $validated['note'] ?? null,
                    'acted_at' => now(),
                ]);

            if ($step === 'supervisor') {
                $batch->update([
                    'status' => 'waiting_manager',
                    'supervisor_id' => $user->id,
                    'supervisor_name' => $user->name,
                    'laporan_penyimpangan' => $validated['laporan_penyimpangan'],
                    'penyimpangan_items' => $penyimpanganItems,
                    'change_control' => $validated['change_control'],
                    'change_control_items' => $changeControlItems,
                ]);

                // Naik ke tahap Manager -> notif ke manager yang dipilih di batch ini
                $manager = User::find($batch->manager_id) ?: $batch->manager;
                if ($manager) {
                    try {
                        $manager->notify(new BatchStatusNotification(
                            $batch,
                            "[Action Required] Batch #{$batch->batch_number} - Final Approval Required",
                            "Batch #{$batch->batch_number} has been reviewed and approved by Supervisor " . $user->name . " and is now awaiting your final approval.",
                            route('task-approval.index')
                        ));
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Failed sending email to manager: ' . $e->getMessage());
                    }
                }
            } elseif ($step === 'manager') {
                $batch->update([
                    'status' => 'released',
                    'manager_id' => $user->id,
                    'manager_name' => $user->name,
                    'released_at' => now(),
                ]);

                // Selesai semua approval -> notif ke pembuat batch
                $creator = User::find($batch->created_by) ?: $batch->creator;
                if ($creator) {
                    try {
                        $creator->notify(new BatchStatusNotification(
                            $batch,
                            "Batch #{$batch->batch_number} has been Released",
                            "Batch #{$batch->batch_number} has been approved by Manager " . $user->name . " and is now released.",
                            route('batch-repository.show', $batch)
                        ));
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Failed sending email to creator: ' . $e->getMessage());
                    }
                }
            }
        });

        return back()->with('status', 'Batch successfully approved.');
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
                    'user_name' => $user->name,
                    'status' => 'rejected',
                    'note' => $validated['note'],
                    'acted_at' => now(),
                ]);

            $batchUpdates = ['status' => 'rejected'];
            if ($step === 'supervisor') {
                $batchUpdates['supervisor_id'] = $user->id;
                $batchUpdates['supervisor_name'] = $user->name;
            } elseif ($step === 'manager') {
                $batchUpdates['manager_id'] = $user->id;
                $batchUpdates['manager_name'] = $user->name;
            }
            $batch->update($batchUpdates);

            // Ditolak di step manapun -> notif ke pembuat batch
            $creator = User::find($batch->created_by) ?: $batch->creator;
            if ($creator) {
                try {
                    $creator->notify(new BatchStatusNotification(
                        $batch,
                        "Batch #{$batch->batch_number} has been Rejected",
                        "Batch #{$batch->batch_number} was rejected at the " . ucfirst($step) . " stage by " . $user->name . ". Reason: " . $validated['note'],
                        route('batch-form.edit', $batch)
                    ));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Failed sending rejection email: ' . $e->getMessage());
                }
            }
        });

        return back()->with('status', 'Batch has been rejected.');
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
