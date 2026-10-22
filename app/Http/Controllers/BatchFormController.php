<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchEvaluationAnswer;
use App\Models\ChecklistCategory;
use App\Models\ChecklistQuestion;
use App\Models\Product;
use App\Models\ProductionLine;
use App\Models\User;
use App\Notifications\BatchStatusNotification;
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
                    $q->whereNull('parent_id')
                        ->where('status', 'active')
                        ->orderBy('order_no')
                        ->with(['children' => function ($c) {
                            $c->where('status', 'active')
                                ->orderBy('order_no')
                                ->with(['children' => function ($g) {
                                    $g->where('status', 'active')->orderBy('order_no');
                                }]);
                        }]);
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
            'existingNotes' => collect(),
        ]);
    }

    public function edit(Batch $batch)
    {
        abort_unless($batch->status === 'draft', 403, 'Hanya batch berstatus draft yang dapat diedit.');

        $line = ProductionLine::with([
            'parent',
            'categories' => function ($query) {
                $query->orderBy('code')->with(['questions' => function ($q) {
                    $q->whereNull('parent_id')
                        ->where('status', 'active')
                        ->orderBy('order_no')
                        ->with(['children' => function ($c) {
                            $c->where('status', 'active')
                                ->orderBy('order_no')
                                ->with(['children' => function ($g) {
                                    $g->where('status', 'active')->orderBy('order_no');
                                }]);
                        }]);
                }]);
            },
        ])->findOrFail($batch->production_line_id);

        $products = Product::where('status', 'active')->orderBy('name')->get(['id', 'product_code', 'name']);
        $supervisors = User::where('role', 'Supervisor')->orderBy('name')->get(['id', 'name']);
        $managers = User::where('role', 'Manager')->orderBy('name')->get(['id', 'name']);

        $existingAnswers = $batch->answers()->pluck('answer', 'checklist_question_id');
        $existingNotes = $batch->answers()->pluck('keterangan', 'checklist_question_id');

        return view('batch-form.create', [
            'products' => $products,
            'supervisors' => $supervisors,
            'managers' => $managers,
            'line' => $line,
            'batch' => $batch,
            'existingAnswers' => $existingAnswers,
            'existingNotes' => $existingNotes,
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
            'batch_validasi' => [$request->input('action') === 'submit' ? 'required' : 'nullable', Rule::in(['ya', 'tidak'])],
            'laporan_validasi' => [
                Rule::requiredIf(fn () => $request->input('action') === 'submit' && $request->input('batch_validasi') === 'ya'),
                'nullable',
                Rule::in(['ada', 'tidak']),
            ],
            'nomor_laporan_validasi' => ['nullable', 'string', 'max:255'],
            'action' => ['required', Rule::in(['draft', 'submit'])],
            'answers' => ['nullable', 'array'],
            'answers.*' => [Rule::in(['C', 'NC', 'NA'])],
            'notes' => ['nullable', 'array'],
            'notes.*' => ['nullable', 'string', 'max:65535'],
        ], [
            'batch_validasi.required' => 'Pilihan Batch Validasi wajib dipilih sebelum submit.',
            'laporan_validasi.required' => 'Status Laporan Validasi wajib dipilih jika Batch Validasi bernilai Ya.',
        ]);

        $line = ProductionLine::findOrFail($validated['production_line_id']);

        $productionType = str_starts_with($line->code, 'inhouse') ? 'in_house' : 'toll_out';

        if ($validated['action'] === 'submit') {
            $categoryIds = ChecklistCategory::where('production_line_id', $line->id)->pluck('id');

            $activeCheckableQuestionIds = ChecklistQuestion::query()
                ->whereIn('checklist_category_id', $categoryIds)
                ->where('status', 'active')
                ->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->whereNotNull('parent_id')
                            ->whereIn('parent_id', function ($p) {
                                $p->select('id')->from('checklist_questions')->whereNull('parent_id');
                            });
                    })
                    ->orWhere(function ($sub) {
                        $sub->whereNull('parent_id')
                            ->whereNotIn('id', function ($c) {
                                $c->select('parent_id')->from('checklist_questions')->whereNotNull('parent_id');
                            });
                    });
                })
                ->pluck('id');

            $answered = collect($validated['answers'] ?? [])->keys()->map(fn ($id) => (int) $id);

            if ($activeCheckableQuestionIds->diff($answered)->isNotEmpty()) {
                return back()
                    ->withInput()
                    ->withErrors(['answers' => 'Semua pertanyaan checklist untuk line ini wajib dijawab sebelum submit.']);
            }
        }

        DB::transaction(function () use ($validated, $request, $productionType, $batch) {
            $isBatchValidasi = $validated['batch_validasi'] ?? null;
            if ($isBatchValidasi === 'tidak') {
                $laporanValidasi = 'tidak';
                $nomorLaporan = 'N/A';
            } elseif ($isBatchValidasi === 'ya') {
                $laporanValidasi = $validated['laporan_validasi'] ?? null;
                $nomorLaporan = $validated['nomor_laporan_validasi'] ?? null;
            } else {
                $laporanValidasi = null;
                $nomorLaporan = null;
            }

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
                'batch_validasi' => $isBatchValidasi,
                'laporan_validasi' => $laporanValidasi,
                'nomor_laporan_validasi' => $nomorLaporan,
                'laporan_penyimpangan' => 'tidak',
                'penyimpangan_items' => [['nomor' => 'N/A', 'status' => 'N/A']],
                'change_control' => 'tidak',
                'change_control_items' => [['nomor' => 'N/A', 'status' => 'N/A']],
                'status' => $validated['action'] === 'submit' ? 'submitted' : 'draft',
            ]);

            // Jawaban lama dihapus lalu ditulis ulang, lebih aman daripada updateOrCreate satu-satu
            $batch->answers()->delete();

            $notes = $validated['notes'] ?? [];
            $allQuestionIds = collect(array_keys($validated['answers'] ?? []))
                ->merge(array_keys($notes))
                ->unique();

            foreach ($allQuestionIds as $questionId) {
                $ans = $validated['answers'][$questionId] ?? null;
                $note = isset($notes[$questionId]) && trim((string) $notes[$questionId]) !== '' ? trim((string) $notes[$questionId]) : null;

                if ($ans !== null || $note !== null) {
                    BatchEvaluationAnswer::create([
                        'batch_id' => $batch->id,
                        'checklist_question_id' => $questionId,
                        'answer' => $ans,
                        'keterangan' => $note,
                    ]);
                }
            }

            // Saat submit, bekukan snapshot checklist permanen
            if ($validated['action'] === 'submit') {
                $snapshot = $batch->generateChecklistSnapshot($validated['answers'] ?? [], $notes);
                $batch->update(['checklist_snapshot' => $snapshot]);
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

                // Notif ke supervisor yang dipilih di form ini
                if ($supervisor = User::find($validated['supervisor_id'])) {
                    $supervisor->notify(new BatchStatusNotification(
                        $batch,
                        "Batch #{$batch->batch_number} butuh approval kamu",
                        "Batch #{$batch->batch_number} baru disubmit dan menunggu approval Supervisor.",
                        route('task-approval.index')
                    ));
                }
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
            'batch_validasi' => [$request->input('action') === 'submit' ? 'required' : 'nullable', Rule::in(['ya', 'tidak'])],
            'laporan_validasi' => [
                Rule::requiredIf(fn () => $request->input('action') === 'submit' && $request->input('batch_validasi') === 'ya'),
                'nullable',
                Rule::in(['ada', 'tidak']),
            ],
            'nomor_laporan_validasi' => ['nullable', 'string', 'max:255'],
            'action' => ['required', Rule::in(['draft', 'submit'])],
            'answers' => ['nullable', 'array'],
            'answers.*' => [Rule::in(['C', 'NC', 'NA'])],
            'notes' => ['nullable', 'array'],
            'notes.*' => ['nullable', 'string', 'max:65535'],
        ], [
            'batch_validasi.required' => 'Pilihan Batch Validasi wajib dipilih sebelum submit.',
            'laporan_validasi.required' => 'Status Laporan Validasi wajib dipilih jika Batch Validasi bernilai Ya.',
        ]);

        $line = ProductionLine::findOrFail($validated['production_line_id']);

        $productionType = str_starts_with($line->code, 'inhouse') ? 'in_house' : 'toll_out';

        if ($validated['action'] === 'submit') {
            $categoryIds = ChecklistCategory::where('production_line_id', $line->id)->pluck('id');

            $activeCheckableQuestionIds = ChecklistQuestion::query()
                ->whereIn('checklist_category_id', $categoryIds)
                ->where('status', 'active')
                ->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->whereNotNull('parent_id')
                            ->whereIn('parent_id', function ($p) {
                                $p->select('id')->from('checklist_questions')->whereNull('parent_id');
                            });
                    })
                    ->orWhere(function ($sub) {
                        $sub->whereNull('parent_id')
                            ->whereNotIn('id', function ($c) {
                                $c->select('parent_id')->from('checklist_questions')->whereNotNull('parent_id');
                            });
                    });
                })
                ->pluck('id');

            $answered = collect($validated['answers'] ?? [])->keys()->map(fn ($id) => (int) $id);

            if ($activeCheckableQuestionIds->diff($answered)->isNotEmpty()) {
                return back()
                    ->withInput()
                    ->withErrors(['answers' => 'Semua pertanyaan checklist untuk line ini wajib dijawab sebelum submit.']);
            }
        }

        $batch = DB::transaction(function () use ($validated, $request, $productionType) {
            $isBatchValidasi = $validated['batch_validasi'] ?? null;
            if ($isBatchValidasi === 'tidak') {
                $laporanValidasi = 'tidak';
                $nomorLaporan = 'N/A';
            } elseif ($isBatchValidasi === 'ya') {
                $laporanValidasi = $validated['laporan_validasi'] ?? null;
                $nomorLaporan = $validated['nomor_laporan_validasi'] ?? null;
            } else {
                $laporanValidasi = null;
                $nomorLaporan = null;
            }

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
                'batch_validasi' => $isBatchValidasi,
                'laporan_validasi' => $laporanValidasi,
                'nomor_laporan_validasi' => $nomorLaporan,
                'laporan_penyimpangan' => 'tidak',
                'penyimpangan_items' => [['nomor' => 'N/A', 'status' => 'N/A']],
                'change_control' => 'tidak',
                'change_control_items' => [['nomor' => 'N/A', 'status' => 'N/A']],
                'status' => $validated['action'] === 'submit' ? 'submitted' : 'draft',
                'created_by' => $request->user()?->id,
            ]);

            $notes = $validated['notes'] ?? [];
            $allQuestionIds = collect(array_keys($validated['answers'] ?? []))
                ->merge(array_keys($notes))
                ->unique();

            foreach ($allQuestionIds as $questionId) {
                $ans = $validated['answers'][$questionId] ?? null;
                $note = isset($notes[$questionId]) && trim((string) $notes[$questionId]) !== '' ? trim((string) $notes[$questionId]) : null;

                if ($ans !== null || $note !== null) {
                    BatchEvaluationAnswer::create([
                        'batch_id' => $batch->id,
                        'checklist_question_id' => $questionId,
                        'answer' => $ans,
                        'keterangan' => $note,
                    ]);
                }
            }

            // Saat submit, bekukan snapshot checklist permanen
            if ($validated['action'] === 'submit') {
                $snapshot = $batch->generateChecklistSnapshot($validated['answers'] ?? [], $notes);
                $batch->update(['checklist_snapshot' => $snapshot]);
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

                // Notif ke supervisor yang dipilih di form ini
                if ($supervisor = User::find($validated['supervisor_id'])) {
                    $supervisor->notify(new BatchStatusNotification(
                        $batch,
                        "Batch #{$batch->batch_number} butuh approval kamu",
                        "Batch #{$batch->batch_number} baru disubmit dan menunggu approval Supervisor.",
                        route('task-approval.index')
                    ));
                }
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
