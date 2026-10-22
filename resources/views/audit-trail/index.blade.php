@extends('layouts.app')

@section('title', 'Audit Trail - BatchReleasePro')
@section('page-title', 'Audit Trail')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/batch-repository.css') }}?v=4">
    <link rel="stylesheet" href="{{ asset('css/audit-trail.css') }}?v={{ time() }}">
@endpush

@section('content')

    <div class="brp-card">
        <div class="brp-card-header">
            <h3>Audit Trail</h3>
            <div class="brp-header-actions">
                <a href="{{ route('audit-trail.export-excel', request()->query()) }}" class="brp-btn-outline">Export Excel</a>
                <a href="{{ route('audit-trail.export-pdf', request()->query()) }}" class="brp-btn-outline">Export PDF</a>
            </div>
        </div>

        <form method="GET" action="{{ route('audit-trail.index') }}" class="brp-filter-bar" id="audit-filter-form">
            <input type="date" name="date_from" value="{{ $filters['dateFrom'] }}" placeholder="From date">
            <input type="date" name="date_to" value="{{ $filters['dateTo'] }}" placeholder="To date">

            <select name="user_id">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected($filters['userId'] == $user->id)>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>

            <select name="action">
                <option value="">All Actions</option>
                <optgroup label="Batch Operations">
                    <option value="create" @selected($filters['action'] === 'create')>Create Form</option>
                    <option value="approved" @selected($filters['action'] === 'approved')>Approve</option>
                    <option value="rejected" @selected($filters['action'] === 'rejected')>Reject</option>
                </optgroup>
                <optgroup label="Authentication">
                    <option value="login" @selected($filters['action'] === 'login')>Login</option>
                    <option value="logout" @selected($filters['action'] === 'logout')>Logout</option>
                </optgroup>
                <optgroup label="User Administration">
                    <option value="create_user" @selected($filters['action'] === 'create_user')>Add User</option>
                    <option value="edit_user" @selected($filters['action'] === 'edit_user')>Edit User</option>
                    <option value="delete_user" @selected($filters['action'] === 'delete_user')>Delete User</option>
                    <option value="unlock_user" @selected($filters['action'] === 'unlock_user')>Unlock User</option>
                    <option value="reset_password" @selected($filters['action'] === 'reset_password')>Reset Password</option>
                </optgroup>
            </select>

            <a href="{{ route('audit-trail.index') }}" class="brp-btn-outline btn-reset-filter" id="btn-audit-reset" style="{{ ($filters['dateFrom'] || $filters['dateTo'] || $filters['userId'] || $filters['action']) ? '' : 'display:none;' }}">Reset</a>
        </form>

        <div id="audit-trail-results">
            @include('audit-trail.partials.table-content')
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('audit-filter-form');
        const resultsContainer = document.getElementById('audit-trail-results');
        const resetBtn = document.getElementById('btn-audit-reset');
        const exportExcelBtn = document.querySelector('a[href*="export-excel"]');
        const exportPdfBtn = document.querySelector('a[href*="export-pdf"]');

        if (!filterForm || !resultsContainer) return;

        let debounceTimer = null;

        function buildFilterUrl(page) {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams();

            for (const [key, value] of formData.entries()) {
                if (value !== '') {
                    params.append(key, value);
                }
            }
            if (page) {
                params.set('page', page);
            }
            return filterForm.getAttribute('action') + '?' + params.toString();
        }

        function updateControlsVisibility() {
            const dateFrom = filterForm.querySelector('input[name="date_from"]').value;
            const dateTo = filterForm.querySelector('input[name="date_to"]').value;
            const userId = filterForm.querySelector('select[name="user_id"]').value;
            const action = filterForm.querySelector('select[name="action"]').value;

            const isFiltered = dateFrom !== '' || dateTo !== '' || userId !== '' || action !== '';
            if (resetBtn) {
                resetBtn.style.display = isFiltered ? 'inline-flex' : 'none';
            }

            // Sync export links with current query
            const queryString = buildFilterUrl().split('?')[1] || '';
            if (exportExcelBtn) {
                const baseExcel = exportExcelBtn.getAttribute('href').split('?')[0];
                exportExcelBtn.setAttribute('href', baseExcel + (queryString ? '?' + queryString : ''));
            }
            if (exportPdfBtn) {
                const basePdf = exportPdfBtn.getAttribute('href').split('?')[0];
                exportPdfBtn.setAttribute('href', basePdf + (queryString ? '?' + queryString : ''));
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

        filterForm.querySelectorAll('select').forEach(function (sel) {
            sel.addEventListener('change', triggerInstantFilter);
        });

        filterForm.querySelectorAll('input[type="date"]').forEach(function (inp) {
            inp.addEventListener('change', triggerInstantFilter);
            inp.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    if (inp.value.length === 10 || inp.value === '') {
                        triggerInstantFilter();
                    }
                }, 400);
            });
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function (e) {
                e.preventDefault();
                filterForm.reset();
                filterForm.querySelectorAll('input[type="date"]').forEach(inp => inp.value = '');
                filterForm.querySelectorAll('select').forEach(sel => sel.selectedIndex = 0);
                triggerInstantFilter();
            });
        }

        bindPaginationLinks();
        updateControlsVisibility();
    });
    </script>
    @endpush

@endsection
