<?php

namespace App\Http\Controllers;

use App\Exports\BatchRepositoryExport;
use App\Models\Batch;
use App\Models\ChecklistCategory;
use App\Models\ChecklistQuestion;
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
        // 1. Jika batch sudah memiliki checklist_snapshot (terkunci permanen):
        // Selalu gunakan snapshot ini sehingga kebal dari perubahan master checklist config!
        if (! empty($batch->checklist_snapshot)) {
            return collect($batch->checklist_snapshot)->map(function ($rows) {
                return collect($rows)->map(fn ($r) => (object) $r);
            });
        }

        // 2. Jika batch berstatus submitted/waiting/released tapi belum memiliki snapshot (data lama):
        // Buatkan snapshot permanen dan simpan ke database sekarang agar langsung terkunci!
        $snapshot = $batch->generateChecklistSnapshot();
        if ($batch->status !== 'draft' && ! empty($snapshot)) {
            $batch->update(['checklist_snapshot' => $snapshot]);
        }

        return collect($snapshot)->map(function ($rows) {
            return collect($rows)->map(fn ($r) => (object) $r);
        });
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
