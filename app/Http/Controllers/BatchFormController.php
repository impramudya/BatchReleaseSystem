<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchEvaluationAnswer;
use App\Models\ChecklistCategory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BatchFormController extends Controller
{

    public function create()
    {
        $products = Product::where('status', 'active')->orderBy('name')->get(['id', 'product_code', 'name']);
        $supervisors = User::where('role', 'Supervisor')->orderBy('name')->get(['id', 'name']);

        $categories = ChecklistCategory::with(['questions' => function ($query) {
            $query->where('status', 'active')->orderBy('order_no');
        }])->orderBy('code')->get();

        return view('batch-form.create', [
            'products' => $products,
            'supervisors' => $supervisors,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'batch_number' => ['required', 'string', 'max:100', 'unique:batches,batch_number'],
            'manufacturer' => ['required', 'string', 'max:255'],
            'production_type' => ['required', Rule::in(['in_house', 'toll_out'])],
            'batch_date' => ['required', 'date'],
            'supervisor_id' => ['required', 'exists:users,id'],
            'keterangan' => ['nullable', 'string'],
            'action' => ['required', Rule::in(['draft', 'submit'])],
            'answers' => ['array'],
            'answers.*' => [Rule::in(['C', 'NC', 'NA'])],
        ]);

        // Kalau disubmit (bukan draft), semua pertanyaan aktif wajib dijawab.
        if ($validated['action'] === 'submit') {
            $activeQuestionIds = ChecklistCategory::query()
                ->join('checklist_questions', 'checklist_questions.checklist_category_id', '=', 'checklist_categories.id')
                ->where('checklist_questions.status', 'active')
                ->pluck('checklist_questions.id');

            $answered = collect($validated['answers'] ?? [])->keys()->map(fn ($id) => (int) $id);

            if ($activeQuestionIds->diff($answered)->isNotEmpty()) {
                return back()
                    ->withInput()
                    ->withErrors(['answers' => 'Semua pertanyaan checklist wajib dijawab sebelum submit.']);
            }
        }

        $batch = DB::transaction(function () use ($validated, $request) {
            $batch = Batch::create([
                'product_id' => $validated['product_id'],
                'batch_number' => $validated['batch_number'],
                'manufacturer' => $validated['manufacturer'],
                'production_type' => $validated['production_type'],
                'batch_date' => $validated['batch_date'],
                'supervisor_id' => $validated['supervisor_id'],
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
