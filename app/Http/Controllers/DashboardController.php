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

        $yearStart = now()->setDate($year, 1, 1)->startOfDay();
        $yearEnd = now()->setDate($year, 12, 31)->endOfDay();

        $lastWeek = now()->subDays(7);

        $stats = [
            'total_form' => Batch::whereBetween('created_at', [$yearStart, $yearEnd])->count(),
            'total_form_this_month' => Batch::where('created_at', '>=', now()->startOfMonth())->count(),

            'draft' => Batch::where('status', 'draft')
                ->whereBetween('created_at', [$yearStart, $yearEnd])
                ->count(),
            'waiting_spv' => Batch::where('status', 'submitted')
                ->whereBetween('created_at', [$yearStart, $yearEnd])
                ->count(),
            'waiting_manager' => Batch::where('status', 'waiting_manager')
                ->whereBetween('created_at', [$yearStart, $yearEnd])
                ->count(),
            'released' => Batch::where('status', 'released')
                ->whereBetween('created_at', [$yearStart, $yearEnd])
                ->count(),
            'rejected' => Batch::where('status', 'rejected')
                ->whereBetween('created_at', [$yearStart, $yearEnd])
                ->count(),

            'released_recent' => Batch::where('status', 'released')
                ->where('released_at', '>=', $lastWeek)
                ->count(),
        ];


        $earliestYear = Batch::min('created_at');
        $earliestYear = $earliestYear ? (int) date('Y', strtotime($earliestYear)) : now()->year;
        $availableYears = range(now()->year, $earliestYear);


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
            ->with(['product', 'creator'])
            ->when(
                !in_array(Auth::user()->role ?? null, ['Super Admin', 'Admin']),
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

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->has('partial')) {
            return view('dashboard.partials.recent-batches-table', [
                'recentBatches' => $recentBatches,
                'quickFilters' => $quickFilters,
                'perPage' => $perPage,
                'selectedYear' => $year,
            ]);
        }

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