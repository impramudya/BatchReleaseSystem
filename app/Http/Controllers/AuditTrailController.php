<?php

namespace App\Http\Controllers;

use App\Exports\AuditTrailExport;
use App\Models\Batch;
use App\Models\BatchApproval;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $logs = $this->buildLogs($request);

        $perPage = 20;
        $currentPage = $request->input('page', 1);
        $paged = $logs->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paged,
            $logs->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('audit-trail.index', [
            'logs' => $paginator,
            'users' => User::orderBy('name')->get(['id', 'name']),
            'filters' => $this->filters($request),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $logs = $this->buildLogs($request);

        return Excel::download(
            new AuditTrailExport($logs),
            'audit-trail-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $logs = $this->buildLogs($request);
        $filters = $this->filters($request);

        $pdf = Pdf::loadView('audit-trail.pdf', compact('logs', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('audit-trail-' . now()->format('Ymd-His') . '.pdf');
    }

    private function filters(Request $request): array
    {
        return [
            'dateFrom' => $request->input('date_from'),
            'dateTo' => $request->input('date_to'),
            'userId' => $request->input('user_id'),
            'action' => $request->input('action'),
        ];
    }

    private function buildLogs(Request $request): Collection
    {
        $f = $this->filters($request);

        $batches = Batch::query()
            ->when($f['dateFrom'], fn($q) => $q->whereDate('created_at', '>=', $f['dateFrom']))
            ->when($f['dateTo'], fn($q) => $q->whereDate('created_at', '<=', $f['dateTo']))
            ->when($f['userId'], fn($q) => $q->where('created_by', $f['userId']))
            ->get()
            ->map(function ($batch) {
                $creator = $batch->creator ?? User::find($batch->created_by ?? null);

                return [
                    'time' => $batch->created_at,
                    'user' => $creator?->name ?? '-',
                    'action' => 'create',
                    'action_label' => 'Buat Form',
                    'detail' => 'Batch #' . $batch->id . ' dibuat',
                ];
            });

        $approvals = BatchApproval::query()
            ->with(['user', 'batch'])
            ->whereNotNull('acted_at')
            ->when($f['dateFrom'], fn($q) => $q->whereDate('acted_at', '>=', $f['dateFrom']))
            ->when($f['dateTo'], fn($q) => $q->whereDate('acted_at', '<=', $f['dateTo']))
            ->when($f['userId'], fn($q) => $q->where('user_id', $f['userId']))
            ->get()
            ->map(function ($approval) {
                return [
                    'time' => $approval->acted_at,
                    'user' => $approval->user?->name ?? '-',
                    'action' => $approval->status,
                    'action_label' => ucfirst($approval->status) . ' (' . $approval->stepLabel() . ')',
                    'detail' => 'Batch #' . $approval->batch_id
                        . ($approval->note ? ' - Catatan: ' . $approval->note : ''),
                ];
            });

        $logins = LoginLog::query()
            ->with('user')
            ->when($f['dateFrom'], fn($q) => $q->whereDate('created_at', '>=', $f['dateFrom']))
            ->when($f['dateTo'], fn($q) => $q->whereDate('created_at', '<=', $f['dateTo']))
            ->when($f['userId'], fn($q) => $q->where('user_id', $f['userId']))
            ->get()
            ->map(function ($log) {
                return [
                    'time' => $log->created_at,
                    'user' => $log->user?->name ?? '-',
                    'action' => $log->action,
                    'action_label' => ucfirst($log->action),
                    'detail' => $log->ip_address ?? '-',
                ];
            });

        return (new Collection())
            ->concat($batches)
            ->concat($approvals)
            ->concat($logins)
            ->when($f['action'], fn(Collection $c) => $c->where('action', $f['action']))
            ->sortByDesc('time')
            ->values();
    }
}
