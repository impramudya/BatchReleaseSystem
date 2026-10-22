@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('css/batch-repository.css') }}?v=4">
@endpush

@section('content')

@php
$segments = [
['label' => 'Draft', 'value' => $stats['draft'], 'class' => 'seg-draft'],
['label' => 'Waiting SPV', 'value' => $stats['waiting_spv'], 'class' => 'seg-spv'],
['label' => 'Waiting Manager', 'value' => $stats['waiting_manager'], 'class' => 'seg-manager'],
['label' => 'Released', 'value' => $stats['released'], 'class' => 'seg-released'],
['label' => 'Rejected', 'value' => $stats['rejected'] ?? 0, 'class' => 'seg-rejected'],
];
@endphp

{{-- ============ YEAR FILTER ============ --}}
<form method="GET" action="{{ route('dashboard') }}" class="brp-filter-bar" style="border-bottom:none; padding: 0 0 0.9rem;">
    <label for="year-select" style="display:flex; align-items:center; font-family:'Poppins', sans-serif; font-size:0.85rem; color: var(--content-text-soft); gap: 0.5rem;">
        Year Statistics
    </label>
    <select id="year-select" name="year" onchange="this.form.submit()">
        @foreach ($availableYears as $y)
        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
    </select>
    <input type="hidden" name="q" value="{{ $quickFilters['q'] ?? '' }}">
    <input type="hidden" name="status" value="{{ $quickFilters['status'] ?? '' }}">
    <input type="hidden" name="date_from" value="{{ $quickFilters['date_from'] ?? '' }}">
    <input type="hidden" name="date_to" value="{{ $quickFilters['date_to'] ?? '' }}">
    <input type="hidden" name="per_page" value="{{ $perPage ?? 5 }}">
</form>

{{-- ============ STAT CARDS ============ --}}
<div class="brp-stat-grid">

    <div class="brp-stat-card brp-stat-dark">
        <div class="brp-stat-top">
            <span class="brp-stat-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M5 3.5h7l3 3v10a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-12a1 1 0 0 1 1-1z" />
                    <path d="M12 3.5V7h3" />
                    <path d="M6.8 11h6.4M6.8 13.5h6.4M6.8 8.5h3" /></svg>
            </span>
            <span class="brp-stat-trend brp-trend-up">&uarr; {{ $stats['total_form_this_month'] }} this month</span>
        </div>
        <p class="brp-stat-value">{{ $stats['total_form'] }}</p>
        <p class="brp-stat-label">Total Forms</p>
    </div>

    <div class="brp-stat-card brp-stat-cream">
        <div class="brp-stat-top">
            <span class="brp-stat-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M13.5 3.5l3 3-8.3 8.3-3.8.8.8-3.8 8.3-8.3z" /></svg>
            </span>
            <span class="brp-stat-trend brp-trend-neutral">Not submitted</span>
        </div>
        <p class="brp-stat-value">{{ $stats['draft'] }}</p>
        <p class="brp-stat-label">Draft</p>
    </div>

    <div class="brp-stat-card brp-stat-orange">
        <div class="brp-stat-top">
            <span class="brp-stat-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                    <circle cx="10" cy="10" r="7" />
                    <path d="M10 6v4l2.7 1.6" /></svg>
            </span>
            <span class="brp-stat-trend brp-trend-neutral">Pending review</span>
        </div>
        <p class="brp-stat-value">{{ $stats['waiting_spv'] }}</p>
        <p class="brp-stat-label">Waiting Supervisor</p>
    </div>

    <div class="brp-stat-card brp-stat-blue">
        <div class="brp-stat-top">
            <span class="brp-stat-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                    <circle cx="7.5" cy="7" r="2.6" />
                    <path d="M3 16c.5-2.9 2.2-4.5 4.5-4.5S11.5 13.1 12 16" />
                    <path d="M14 8.2l1.5 1.5L19 6.2" /></svg>
            </span>
            <span class="brp-stat-trend brp-trend-neutral">Final approval</span>
        </div>
        <p class="brp-stat-value">{{ $stats['waiting_manager'] }}</p>
        <p class="brp-stat-label">Waiting Manager</p>
    </div>

    <div class="brp-stat-card brp-stat-green">
        <div class="brp-stat-top">
            <span class="brp-stat-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                    <circle cx="10" cy="10" r="7" />
                    <path d="M7 10.2l2 2 4-4.3" /></svg>
            </span>
            <span class="brp-stat-trend brp-trend-up">&uarr; {{ $stats['released_recent'] }} recent</span>
        </div>
        <p class="brp-stat-value">{{ $stats['released'] }}</p>
        <p class="brp-stat-label">Released</p>
    </div>

</div>

{{-- ============ BATCH STATUS SUMMARY ============
@php
$segmentTotal = max(array_sum(array_column($segments, 'value')), 1);
@endphp

<div class="brp-summary-card">
    <div class="brp-summary-head">
        <h4>Batch Status Summary</h4>
        <span class="brp-summary-total">{{ $stats['total_form'] }} total forms</span>
</div>

<div class="brp-summary-bar">
    @foreach ($segments as $seg)
    @if ($seg['value'] > 0)
    <div class="brp-summary-seg {{ $seg['class'] }}" style="width: {{ round(($seg['value'] / $segmentTotal) * 100, 2) }}%" title="{{ $seg['label'] }}: {{ $seg['value'] }}"></div>
    @endif
    @endforeach
</div>

<div class="brp-summary-legend">
    @foreach ($segments as $seg)
    @if ($seg['value'] > 0)
    <span class="brp-legend-item">
        <span class="brp-legend-dot {{ $seg['class'] }}"></span>
        {{ $seg['label'] }} ({{ $seg['value'] }})
    </span>
    @endif
    @endforeach
</div>
</div> --}}

{{-- ============ BATCH RELEASE FORMS — RECENT ============ --}}
<div class="brp-card" style="margin-top: 1.25rem;">
    <div class="brp-card-header">
        <h3>Batch Release Forms &mdash; Recent</h3>
        <div class="brp-header-actions">
            <a href="{{ route('batch-repository.index') }}" class="brp-btn-outline">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" style="width:14px;height:14px;">
                    <path d="M6 4h9v9M15 4L4 15" /></svg>
                View All
            </a>
            <a href="{{ route('batch-repository.export-excel', $quickFilters) }}" class="brp-btn-primary">
                <svg viewBox="0 0 20 20" fill="currentColor" style="width:14px;height:14px;">
                    <path d="M4 2.5A1.5 1.5 0 0 1 5.5 1h5.6L15 4.9v11.6A1.5 1.5 0 0 1 13.5 18h-8A1.5 1.5 0 0 1 4 16.5v-14Z" opacity=".35" />
                    <path d="M7.3 9.2 9.6 12l2.3-2.8" />
                    <path d="M9.6 12V6.5" stroke="currentColor" stroke-width="1.4" fill="none" /></svg>
                Export Excel
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('dashboard') }}" class="brp-filter-bar" id="recent-batches-filter">
        @if (!empty($selectedYear))
        <input type="hidden" name="year" value="{{ $selectedYear }}">
        @endif
        <input type="text" name="q" value="{{ $quickFilters['q'] ?? '' }}" placeholder="Search batch no / product..." autocomplete="off">
        <select name="status">
            <option value="">All Statuses</option>
            <option value="draft" {{ ($quickFilters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="submitted" {{ ($quickFilters['status'] ?? '') === 'submitted' ? 'selected' : '' }}>Waiting Supervisor</option>
            <option value="waiting_manager" {{ ($quickFilters['status'] ?? '') === 'waiting_manager' ? 'selected' : '' }}>Waiting Manager</option>
            <option value="released" {{ ($quickFilters['status'] ?? '') === 'released' ? 'selected' : '' }}>Released</option>
            <option value="rejected" {{ ($quickFilters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <input type="date" name="date_from" value="{{ $quickFilters['date_from'] ?? '' }}" title="From Date">
        <input type="date" name="date_to" value="{{ $quickFilters['date_to'] ?? '' }}" title="To Date">
        <select name="per_page" title="Show rows per page">
            <option value="5" {{ ($perPage ?? 5) == 5 ? 'selected' : '' }}>5 rows</option>
            <option value="10" {{ ($perPage ?? 5) == 10 ? 'selected' : '' }}>10 rows</option>
            <option value="15" {{ ($perPage ?? 5) == 15 ? 'selected' : '' }}>15 rows</option>
            <option value="20" {{ ($perPage ?? 5) == 20 ? 'selected' : '' }}>20 rows</option>
        </select>
        <a href="{{ route('dashboard', ['year' => $selectedYear]) }}" class="brp-btn-outline btn-reset-filter" id="btn-recent-reset" style="{{ (($quickFilters['q'] ?? null) || ($quickFilters['status'] ?? null) || ($quickFilters['date_from'] ?? null) || ($quickFilters['date_to'] ?? null) || ($perPage ?? 5) != 5) ? '' : 'display:none;' }}">Reset</a>
    </form>

    <div id="recent-batches-results">
        @include('dashboard.partials.recent-batches-table')
    </div>
</div>

@if (session('force_password_change'))
<div id="password-modal" class="brp-modal-overlay">
    <div class="brp-modal-box">
        @if (session('password_change_reason') === 'first_login')
        <h3>Change Your Password</h3>
        <p>This is your first login. For security compliance, please change your default password.</p>
        @else
        <h3>Your Password Has Expired</h3>
        <p>Your password has been used for more than 60 days. Please change your password to continue.</p>
        @endif

        <a href="{{ route('password.change') }}" class="brp-modal-btn" style="color: #ffffff !important;">
            Change Password Now
        </a>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('recent-batches-filter');
    const resultsContainer = document.getElementById('recent-batches-results');
    const resetBtn = document.getElementById('btn-recent-reset');

    if (!filterForm || !resultsContainer) return;

    let debounceTimer = null;

    function buildFilterUrl(page = null) {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value !== '' && value !== null) {
                params.append(key, value);
            }
        }
        if (page) {
            params.set('page', page);
        }
        return filterForm.getAttribute('action') + '?' + params.toString();
    }

    function updateControlsVisibility() {
        const q = filterForm.querySelector('input[name="q"]').value.trim();
        const status = filterForm.querySelector('select[name="status"]').value;
        const dateFrom = filterForm.querySelector('input[name="date_from"]').value;
        const dateTo = filterForm.querySelector('input[name="date_to"]').value;
        const perPage = filterForm.querySelector('select[name="per_page"]').value;

        const isFiltered = q !== '' || status !== '' || dateFrom !== '' || dateTo !== '' || perPage !== '5';
        if (resetBtn) {
            resetBtn.style.display = isFiltered ? 'inline-flex' : 'none';
        }
    }

    function loadResults(url) {
        resultsContainer.classList.add('brp-loading');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (response) {
            if (!response.ok) throw new Error('Network error');
            return response.text();
        })
        .then(function (html) {
            resultsContainer.innerHTML = html;
            resultsContainer.classList.remove('brp-loading');
            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, '', url);
            }
            updateControlsVisibility();
            bindPaginationLinks();
        })
        .catch(function (err) {
            console.error('AJAX error:', err);
            resultsContainer.classList.remove('brp-loading');
            window.location.href = url;
        });
    }

    function triggerInstantFilter() {
        clearTimeout(debounceTimer);
        const url = buildFilterUrl();
        loadResults(url);
    }

    function bindPaginationLinks() {
        const links = resultsContainer.querySelectorAll('.brp-pager-controls a, .brp-pagination a');
        links.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                if (href && href !== '#') {
                    loadResults(href);
                }
            });
        });
    }

    // Text search input: Live Debounced (300ms)
    const searchInput = filterForm.querySelector('input[name="q"]');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            updateControlsVisibility();
            debounceTimer = setTimeout(function () {
                triggerInstantFilter();
            }, 300);
        });

        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                triggerInstantFilter();
            }
        });
    }

    // Selects and date inputs: Instant filter on change
    const changeInputs = filterForm.querySelectorAll('select, input[type="date"]');
    changeInputs.forEach(function (el) {
        el.addEventListener('change', function () {
            triggerInstantFilter();
        });
    });

    // Reset button
    if (resetBtn) {
        resetBtn.addEventListener('click', function (e) {
            e.preventDefault();
            filterForm.querySelector('input[name="q"]').value = '';
            filterForm.querySelector('select[name="status"]').value = '';
            filterForm.querySelector('input[name="date_from"]').value = '';
            filterForm.querySelector('input[name="date_to"]').value = '';
            filterForm.querySelector('select[name="per_page"]').value = '5';
            triggerInstantFilter();
        });
    }

    filterForm.addEventListener('submit', function (e) {
        e.preventDefault();
        triggerInstantFilter();
    });

    bindPaginationLinks();
});
</script>
@endpush
