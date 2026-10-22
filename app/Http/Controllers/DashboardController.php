<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        $perPage = (int) $request->query('per_page', 5);
        if (!in_array($perPage, [5, 10, 15, 20])) {
            $perPage = 5;
        }

        $quickFilters = [
            'q' => $request->query('q'),
            'status' => $request->query('status'),
            'date_from' => $request->query('date_from'),
            'date_to' => $request->query('date_to'),
            'per_page' => $perPage,
        ];

        $recentBatches = Batch::query()
            ->with(['product:id,name,product_code', 'creator:id,name'])
            ->when(
                !in_array(Auth::user()->role ?? null, ['System Administrator', 'Admin']),
                function ($query) {
                    $query->where('status', '!=', 'draft');
                }
            )
            ->when($quickFilters['q'], function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('batch_number', 'like', "%{$q}%")
                        ->orWhereHas('product', function ($p) use ($q) {
                            $p->where('name', 'like', "%{$q}%")
                                ->orWhere('product_code', 'like', "%{$q}%");
                        });
                });
            })
            ->when($quickFilters['status'], function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($quickFilters['date_from'], function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($quickFilters['date_to'], function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $isTableOnlyAjax = ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->has('partial'))
            && !$request->header('X-BRP-Navigation');

        if ($isTableOnlyAjax) {
            return view('dashboard.partials.recent-batches-table', [
                'recentBatches' => $recentBatches,
                'quickFilters' => $quickFilters,
                'perPage' => $perPage,
                'selectedYear' => $year,
            ]);
        }

        $yearStart = now()->setDate($year, 1, 1)->startOfDay();
        $yearEnd = now()->setDate($year, 12, 31)->endOfDay();
        $startOfMonth = now()->startOfMonth();
        $lastWeek = now()->subDays(7);

        // Single optimized aggregation query replacing 8 separate count queries
        $statsRow = Batch::selectRaw("
            COUNT(CASE WHEN created_at BETWEEN ? AND ? THEN 1 END) as total_form,
            COUNT(CASE WHEN created_at >= ? THEN 1 END) as total_form_this_month,
            COUNT(CASE WHEN status = 'draft' AND created_at BETWEEN ? AND ? THEN 1 END) as draft,
            COUNT(CASE WHEN status = 'submitted' AND created_at BETWEEN ? AND ? THEN 1 END) as waiting_spv,
            COUNT(CASE WHEN status = 'waiting_manager' AND created_at BETWEEN ? AND ? THEN 1 END) as waiting_manager,
            COUNT(CASE WHEN status = 'released' AND created_at BETWEEN ? AND ? THEN 1 END) as released,
            COUNT(CASE WHEN status = 'rejected' AND created_at BETWEEN ? AND ? THEN 1 END) as rejected,
            COUNT(CASE WHEN status = 'released' AND released_at >= ? THEN 1 END) as released_recent
        ", [
            $yearStart, $yearEnd,
            $startOfMonth,
            $yearStart, $yearEnd,
            $yearStart, $yearEnd,
            $yearStart, $yearEnd,
            $yearStart, $yearEnd,
            $yearStart, $yearEnd,
            $lastWeek,
        ])->first();

        $stats = [
            'total_form' => (int) ($statsRow->total_form ?? 0),
            'total_form_this_month' => (int) ($statsRow->total_form_this_month ?? 0),
            'draft' => (int) ($statsRow->draft ?? 0),
            'waiting_spv' => (int) ($statsRow->waiting_spv ?? 0),
            'waiting_manager' => (int) ($statsRow->waiting_manager ?? 0),
            'released' => (int) ($statsRow->released ?? 0),
            'rejected' => (int) ($statsRow->rejected ?? 0),
            'released_recent' => (int) ($statsRow->released_recent ?? 0),
        ];

        $earliestYear = Batch::min('created_at');
        $earliestYear = $earliestYear ? (int) date('Y', strtotime($earliestYear)) : now()->year;
        $availableYears = range(now()->year, $earliestYear);

        return view('dashboard', [
            'stats' => $stats,
            'recentBatches' => $recentBatches,
            'quickFilters' => $quickFilters,
            'perPage' => $perPage,
            'selectedYear' => $year,
            'availableYears' => $availableYears,
        ]);
    }
}
