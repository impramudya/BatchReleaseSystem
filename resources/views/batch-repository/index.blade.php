@extends('layouts.app')

@section('title', 'Batch Repository')
@section('page-title', 'Batch Repository')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/batch-repository.css') }}?v=4">
@endpush

@section('content')

<div class="brp-card">
    <div class="brp-card-header">
        <h3>Batch Repository</h3>
        <div class="brp-header-actions">
            <a href="{{ route('batch-repository.export-excel', $filters) }}" class="brp-btn-outline" id="btn-repo-export-excel">Export Excel</a>
            <a href="{{ route('batch-repository.export-pdf', $filters) }}" class="brp-btn-outline" id="btn-repo-export-pdf">Export PDF</a>
        </div>
    </div>

    <form method="GET" action="{{ route('batch-repository.index') }}" class="brp-filter-bar" id="batch-repository-filter">
        <input type="text" name="batch_number" value="{{ $filters['batch_number'] ?? '' }}" placeholder="Search batch number..." autocomplete="off">
        <select name="status">
            <option value="">All Statuses</option>
            <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }} @if(optional(auth()->user())->role !== 'Admin') style="display:none;" @endif>Draft</option>
            <option value="submitted" {{ ($filters['status'] ?? '') === 'submitted' ? 'selected' : '' }}>Waiting Supervisor</option>
            <option value="waiting_manager" {{ ($filters['status'] ?? '') === 'waiting_manager' ? 'selected' : '' }}>Waiting Manager</option>
            <option value="released" {{ ($filters['status'] ?? '') === 'released' ? 'selected' : '' }}>Released</option>
            <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" title="From Date">
        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" title="To Date">
        <a href="{{ route('batch-repository.index') }}" class="brp-btn-outline btn-reset-filter" id="btn-repo-reset" style="{{ (($filters['batch_number'] ?? null) || ($filters['status'] ?? null) || ($filters['date_from'] ?? null) || ($filters['date_to'] ?? null)) ? '' : 'display:none;' }}">Reset</a>
    </form>

    <div id="batch-repository-results">
        @include('batch-repository.partials.table-content')
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('batch-repository-filter');
    const resultsContainer = document.getElementById('batch-repository-results');
    const resetBtn = document.getElementById('btn-repo-reset');

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
        const batchNumber = filterForm.querySelector('input[name="batch_number"]').value.trim();
        const status = filterForm.querySelector('select[name="status"]').value;
        const dateFrom = filterForm.querySelector('input[name="date_from"]').value;
        const dateTo = filterForm.querySelector('input[name="date_to"]').value;

        const isFiltered = batchNumber !== '' || status !== '' || dateFrom !== '' || dateTo !== '';
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
    const searchInput = filterForm.querySelector('input[name="batch_number"]');
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
            filterForm.querySelector('input[name="batch_number"]').value = '';
            filterForm.querySelector('select[name="status"]').value = '';
            filterForm.querySelector('input[name="date_from"]').value = '';
            filterForm.querySelector('input[name="date_to"]').value = '';
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
