<?php

namespace App\Http\Controllers;

use App\Exports\BatchRepositoryExport;
use App\Models\Batch;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BatchRepositoryController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['batch_number', 'product_code', 'status', 'line_code', 'date_from', 'date_to']);

        $query = Batch::query()
            ->filter($filters)
            ->with(['product', 'productionLine.parent', 'supervisor', 'manager', 'creator']);

        // Draft cuma untuk Admin — Supervisor/Manager belum perlu lihat batch yang belum disubmit.
        if (optional($request->user())->role !== 'Admin') {
            $query->where('status', '!=', 'draft');
        }

        $batches = $query
            ->orderByDesc('batch_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('batch-repository.index', [
            'batches' => $batches,
            'filters' => $filters,
        ]);
    }

    public function show(Batch $batch, Request $request)
    {
        if ($batch->status === 'draft' && optional($request->user())->role !== 'Admin') {
            abort(403, 'Draft hanya bisa dilihat oleh Admin.');
        }

        $batch->load([
            'product',
            'productionLine.parent',
            'supervisor',
            'manager',
            'creator',
            'approvals.user',
        ]);

        return view('batch-repository.show', [
            'batch' => $batch,
            'checklistRows' => $this->checklistRowsFor($batch),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['batch_number', 'product_code', 'status', 'line_code', 'date_from', 'date_to']);
        $hideDraft = optional($request->user())->role !== 'Admin';

        return Excel::download(
            new BatchRepositoryExport($filters, $hideDraft),
            'batch-repository-'.now()->format('Ymd_His').'.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['batch_number', 'product_code', 'status', 'line_code', 'date_from', 'date_to']);

        $query = Batch::query()
            ->filter($filters)
            ->with(['product', 'supervisor', 'manager', 'creator']);

        if (optional($request->user())->role !== 'Admin') {
            $query->where('status', '!=', 'draft');
        }

        $batches = $query->orderByDesc('batch_date')->orderByDesc('id')->get();

        $pdf = Pdf::loadView('batch-repository.export-pdf', [
            'batches' => $batches,
            'filters' => $filters,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('batch-repository-'.now()->format('Ymd_His').'.pdf');
    }

    public function pdf(Batch $batch)
    {
        return $this->buildBatchPdf($batch)->stream($batch->batch_number.'.pdf');
    }

    public function pdfDownload(Batch $batch)
    {
        return $this->buildBatchPdf($batch)->download($batch->batch_number.'.pdf');
    }

    /**
     * Diambil lewat query langsung (bukan relasi Eloquent) supaya tidak bergantung
     * pada model BatchEvaluationAnswer / ChecklistQuestion yang belum tersedia di sini.
     */
    private function checklistRowsFor(Batch $batch)
    {
        return DB::table('batch_evaluation_answers as bea')
            ->join('checklist_questions as q', 'q.id', '=', 'bea.checklist_question_id')
            ->join('checklist_categories as c', 'c.id', '=', 'q.checklist_category_id')
            ->where('bea.batch_id', $batch->id)
            ->orderBy('c.code')
            ->orderBy('q.order_no')
            ->select('c.code as category_code', 'c.name as category_name', 'q.question', 'bea.answer')
            ->get()
            ->groupBy('category_code');
    }

    private function buildBatchPdf(Batch $batch)
    {
        $batch->load(['product', 'productionLine', 'supervisor', 'manager', 'creator', 'approvals.user']);

        $lineCode = $batch->productionLine->code ?? null;

        $sopCode = match ($lineCode) {
            'inhouse_bottling' => 'SOP-QA-G012.06L2',
            'inhouse_stripping' => 'SOP-QA-G012.06L1',
            'toll_out' => 'SOP-QA-G012.06L3',
            default => 'SOP-QA-G012.06',
        };

        $formTitle = match ($lineCode) {
            'inhouse_bottling' => 'FORM EVALUASI PELULUSAN PRODUK JADI (BOTTLING)',
            'inhouse_stripping' => 'FORM EVALUASI PELULUSAN PRODUK JADI (STRIPPING)',
            'toll_out' => 'FORM EVALUASI PELULUSAN PRODUK TOLL OUT',
            default => 'FORM EVALUASI PELULUSAN PRODUK JADI',
        };

        return Pdf::loadView('batch-repository.pdf', [
            'batch' => $batch,
            'checklistRows' => $this->checklistRowsFor($batch),
            'sopCode' => $sopCode,
            'formTitle' => $formTitle,
        ])->setPaper('a4');
    }
}
