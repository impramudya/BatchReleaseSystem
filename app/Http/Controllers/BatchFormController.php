<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchEvaluationAnswer;
use App\Models\ChecklistCategory;
use App\Models\Product;
use App\Models\ProductionLine;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BatchFormController extends Controller
{
    public function create(?ProductionLine $line = null)
    {
        if (! $line) {
            $fallback = ProductionLine::leaves()->orderBy('order_no')->first();

            if (! $fallback) {
                abort(404, 'Belum ada line produksi terdaftar. Hubungi admin untuk konfigurasi awal.');
            }

            return redirect()->route('batch-form.create', $fallback);
        }

        $line->load([
            'parent',
            'categories' => function ($query) {
                $query->orderBy('code')->with(['questions' => function ($q) {
                    $q->where('status', 'active')->orderBy('order_no');
                }]);
            },
        ]);

        $products = Product::where('status', 'active')->orderBy('name')->get(['id', 'product_code', 'name']);
        $supervisors = User::where('role', 'Supervisor')->orderBy('name')->get(['id', 'name']);
        $managers = User::where('role', 'Manager')->orderBy('name')->get(['id', 'name']);

        return view('batch-form.create', [
            'products' => $products,
            'supervisors' => $supervisors,
            'managers' => $managers,
            'line' => $line,
            'existingAnswers' => collect(),
        ]);
    }

    public function edit(Batch $batch)
    {
        abort_unless($batch->status === 'draft', 403, 'Hanya batch berstatus draft yang dapat diedit.');

        $line = ProductionLine::with([
            'parent',
            'categories' => function ($query) {
                $query->orderBy('code')->with(['questions' => function ($q) {
                    $q->where('status', 'active')->orderBy('order_no');
                }]);
            },
        ])->findOrFail($batch->production_line_id);

        $products = Product::where('status', 'active')->orderBy('name')->get(['id', 'product_code', 'name']);
        $supervisors = User::where('role', 'Supervisor')->orderBy('name')->get(['id', 'name']);
        $managers = User::where('role', 'Manager')->orderBy('name')->get(['id', 'name']);

        $existingAnswers = $batch->answers()->pluck('answer', 'checklist_question_id');

        return view('batch-form.create', [
            'products' => $products,
            'supervisors' => $supervisors,
            'managers' => $managers,
            'line' => $line,
            'batch' => $batch,
            'existingAnswers' => $existingAnswers,
        ]);
    }

    public function update(Request $request, Batch $batch): RedirectResponse
    {
        abort_unless($batch->status === 'draft', 403, 'Hanya batch berstatus draft yang dapat diedit.');

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'batch_number' => ['required', 'string', 'max:100', Rule::unique('batches', 'batch_number')->ignore($batch->id)],
            'manufacturer' => ['required', 'string', 'max:255'],
            'production_line_id' => ['required', 'exists:production_lines,id'],
            'batch_date' => ['required', 'date'],
            'supervisor_id' => ['required', 'exists:users,id'],
            'manager_id' => ['required', 'exists:users,id'],
            'keterangan' => ['nullable', 'string'],
            'action' => ['required', Rule::in(['draft', 'submit'])],
            'answers' => ['array'],
            'answers.*' => [Rule::in(['C', 'NC', 'NA'])],
        ]);

        $line = ProductionLine::findOrFail($validated['production_line_id']);

        $productionType = str_starts_with($line->code, 'inhouse') ? 'in_house' : 'toll_out';

        if ($validated['action'] === 'submit') {
            $activeQuestionIds = ChecklistCategory::query()
                ->where('production_line_id', $line->id)
                ->join('checklist_questions', 'checklist_questions.checklist_category_id', '=', 'checklist_categories.id')
                ->where('checklist_questions.status', 'active')
                ->pluck('checklist_questions.id');

            $answered = collect($validated['answers'] ?? [])->keys()->map(fn ($id) => (int) $id);

            if ($activeQuestionIds->diff($answered)->isNotEmpty()) {
                return back()
                    ->withInput()
                    ->withErrors(['answers' => 'Semua pertanyaan checklist untuk line ini wajib dijawab sebelum submit.']);
            }
        }

        DB::transaction(function () use ($validated, $request, $productionType, $batch) {
            $batch->update([
                'product_id' => $validated['product_id'],
                'batch_number' => $validated['batch_number'],
                'manufacturer' => $validated['manufacturer'],
                'production_type' => $productionType,
                'production_line_id' => $validated['production_line_id'],
                'batch_date' => $validated['batch_date'],
                'supervisor_id' => $validated['supervisor_id'],
                'manager_id' => $validated['manager_id'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => $validated['action'] === 'submit' ? 'submitted' : 'draft',
            ]);

            // Jawaban lama dihapus lalu ditulis ulang, lebih aman daripada updateOrCreate satu-satu
            $batch->answers()->delete();

            foreach ($validated['answers'] ?? [] as $questionId => $answer) {
                BatchEvaluationAnswer::create([
                    'batch_id' => $batch->id,
                    'checklist_question_id' => $questionId,
                    'answer' => $answer,
                ]);
            }

            // Draft yang baru pertama kali disubmit perlu dibuatkan 3 baris approval
            if ($validated['action'] === 'submit' && $batch->approvals()->doesntExist()) {
                $now = now();

                DB::table('batch_approvals')->insert([
                    [
                        'batch_id' => $batch->id,
                        'step' => 'admin',
                        'order_no' => 1,
                        'user_id' => $request->user()?->id,
                        'status' => 'approved',
                        'note' => null,
                        'acted_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'batch_id' => $batch->id,
                        'step' => 'supervisor',
                        'order_no' => 2,
                        'user_id' => $validated['supervisor_id'],
                        'status' => 'pending',
                        'note' => null,
                        'acted_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'batch_id' => $batch->id,
                        'step' => 'manager',
                        'order_no' => 3,
                        'user_id' => $validated['manager_id'],
                        'status' => 'pending',
                        'note' => null,
                        'acted_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                ]);
            }
        });

        $message = $validated['action'] === 'submit'
            ? 'Form evaluasi berhasil disubmit ke supervisor.'
            : 'Perubahan draft berhasil disimpan.';

        return redirect()
            ->route('dashboard')
            ->with('status', $message);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'batch_number' => ['required', 'string', 'max:100', 'unique:batches,batch_number'],
            'manufacturer' => ['required', 'string', 'max:255'],
            'production_line_id' => ['required', 'exists:production_lines,id'],
            'batch_date' => ['required', 'date'],
            'supervisor_id' => ['required', 'exists:users,id'],
            'manager_id' => ['required', 'exists:users,id'],
            'keterangan' => ['nullable', 'string'],
            'action' => ['required', Rule::in(['draft', 'submit'])],
            'answers' => ['array'],
            'answers.*' => [Rule::in(['C', 'NC', 'NA'])],
        ]);

        $line = ProductionLine::findOrFail($validated['production_line_id']);

        $productionType = str_starts_with($line->code, 'inhouse') ? 'in_house' : 'toll_out';

        if ($validated['action'] === 'submit') {
            $activeQuestionIds = ChecklistCategory::query()
                ->where('production_line_id', $line->id)
                ->join('checklist_questions', 'checklist_questions.checklist_category_id', '=', 'checklist_categories.id')
                ->where('checklist_questions.status', 'active')
                ->pluck('checklist_questions.id');

            $answered = collect($validated['answers'] ?? [])->keys()->map(fn ($id) => (int) $id);

            if ($activeQuestionIds->diff($answered)->isNotEmpty()) {
                return back()
                    ->withInput()
                    ->withErrors(['answers' => 'Semua pertanyaan checklist untuk line ini wajib dijawab sebelum submit.']);
            }
        }

        $batch = DB::transaction(function () use ($validated, $request, $productionType) {
            $batch = Batch::create([
                'product_id' => $validated['product_id'],
                'batch_number' => $validated['batch_number'],
                'manufacturer' => $validated['manufacturer'],
                'production_type' => $productionType,
                'production_line_id' => $validated['production_line_id'],
                'batch_date' => $validated['batch_date'],
                'supervisor_id' => $validated['supervisor_id'],
                'manager_id' => $validated['manager_id'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => $validated['action'] === 'submit' ? 'submitted' : 'draft',
                'created_by' => $request->user()?->id,
            ]);

            foreach ($validated['answers'] ?? [] as $questionId => $answer) {
                BatchEvaluationAnswer::create([
                    'batch_id' => $batch->id,
                    'checklist_question_id' => $questionId,
                    'answer' => $answer,
                ]);
            }

            // Saat submit, generate 3 tahap approval sekaligus:
            // admin auto-approved (form dibuat sendiri oleh admin), supervisor & manager menunggu.
            if ($validated['action'] === 'submit') {
                $now = now();

                DB::table('batch_approvals')->insert([
                    [
                        'batch_id' => $batch->id,
                        'step' => 'admin',
                        'order_no' => 1,
                        'user_id' => $request->user()?->id,
                        'status' => 'approved',
                        'note' => null,
                        'acted_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'batch_id' => $batch->id,
                        'step' => 'supervisor',
                        'order_no' => 2,
                        'user_id' => $validated['supervisor_id'],
                        'status' => 'pending',
                        'note' => null,
                        'acted_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'batch_id' => $batch->id,
                        'step' => 'manager',
                        'order_no' => 3,
                        'user_id' => $validated['manager_id'],
                        'status' => 'pending',
                        'note' => null,
                        'acted_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                ]);
            }

            return $batch;
        });

        $message = $validated['action'] === 'submit'
            ? 'Form evaluasi berhasil disubmit ke supervisor.'
            : 'Form evaluasi berhasil disimpan sebagai draft.';

        return redirect()
            ->route('dashboard')
            ->with('status', $message);
    }
}
