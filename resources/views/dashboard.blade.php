@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/batch-repository.css') }}">
@endpush

@section('header-actions')
    <a href="{{ route('batch-repository.index') }}" class="brp-btn brp-btn-ghost">
        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="8.5" cy="8.5" r="5.5"/><path d="M17 17l-4.3-4.3"/>
        </svg>
        Cari Batch
    </a>

    @if (in_array(auth()->user()->role ?? null, ['Super Admin', 'Admin']))
        <a href="{{ route('batch-form.create') }}" class="brp-btn brp-btn-primary">
            <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M10 4v12M4 10h12"/>
            </svg>
            Form Baru
        </a>
    @endif
@endsection

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

    {{-- ============ FILTER TAHUN (untuk stat cards) ============ --}}
    <form method="GET" action="{{ route('dashboard') }}" class="brp-filter-bar" style="border-bottom:none; padding: 0 0 0.9rem;">
        <label for="year-select" style="display:flex; align-items:center; font-family:'Poppins', sans-serif; font-size:0.85rem; color: var(--content-text-soft); gap: 0.5rem;">
            Statistik Tahun
        </label>
        <select id="year-select" name="year" onchange="this.form.submit()">
            @foreach ($availableYears as $y)
                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        {{-- preserve quick filters kalau ada, biar gak ke-reset pas ganti tahun --}}
        <input type="hidden" name="q" value="{{ $quickFilters['q'] ?? '' }}">
        <input type="hidden" name="status" value="{{ $quickFilters['status'] ?? '' }}">
        <input type="hidden" name="date_from" value="{{ $quickFilters['date_from'] ?? '' }}">
        <input type="hidden" name="date_to" value="{{ $quickFilters['date_to'] ?? '' }}">
    </form>

    {{-- ============ STAT CARDS ============ --}}
    <div class="brp-stat-grid">

        <div class="brp-stat-card brp-stat-dark">
            <div class="brp-stat-top">
                <span class="brp-stat-icon">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 3.5h7l3 3v10a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-12a1 1 0 0 1 1-1z"/><path d="M12 3.5V7h3"/><path d="M6.8 11h6.4M6.8 13.5h6.4M6.8 8.5h3"/></svg>
                </span>
                <span class="brp-stat-trend brp-trend-up">&uarr; {{ $stats['total_form_this_month'] }} bulan ini</span>
            </div>
            <p class="brp-stat-value">{{ $stats['total_form'] }}</p>
            <p class="brp-stat-label">Total Form</p>
        </div>

        <div class="brp-stat-card brp-stat-cream">
            <div class="brp-stat-top">
                <span class="brp-stat-icon">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M13.5 3.5l3 3-8.3 8.3-3.8.8.8-3.8 8.3-8.3z"/></svg>
                </span>
                <span class="brp-stat-trend brp-trend-neutral">Belum disubmit</span>
            </div>
            <p class="brp-stat-value">{{ $stats['draft'] }}</p>
            <p class="brp-stat-label">Draft</p>
        </div>

        <div class="brp-stat-card brp-stat-orange">
            <div class="brp-stat-top">
                <span class="brp-stat-icon">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="10" cy="10" r="7"/><path d="M10 6v4l2.7 1.6"/></svg>
                </span>
                <span class="brp-stat-trend brp-trend-neutral">Menunggu review</span>
            </div>
            <p class="brp-stat-value">{{ $stats['waiting_spv'] }}</p>
            <p class="brp-stat-label">Waiting SPV</p>
        </div>

        <div class="brp-stat-card brp-stat-blue">
            <div class="brp-stat-top">
                <span class="brp-stat-icon">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="7.5" cy="7" r="2.6"/><path d="M3 16c.5-2.9 2.2-4.5 4.5-4.5S11.5 13.1 12 16"/><path d="M14 8.2l1.5 1.5L19 6.2"/></svg>
                </span>
                <span class="brp-stat-trend brp-trend-neutral">Final approval</span>
            </div>
            <p class="brp-stat-value">{{ $stats['waiting_manager'] }}</p>
            <p class="brp-stat-label">Waiting Manager</p>
        </div>

        <div class="brp-stat-card brp-stat-green">
            <div class="brp-stat-top">
                <span class="brp-stat-icon">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="10" cy="10" r="7"/><path d="M7 10.2l2 2 4-4.3"/></svg>
                </span>
                <span class="brp-stat-trend brp-trend-up">&uarr; {{ $stats['released_recent'] }} baru</span>
            </div>
            <p class="brp-stat-value">{{ $stats['released'] }}</p>
            <p class="brp-stat-label">Released</p>
        </div>

    </div>

    {{-- ============ RINGKASAN STATUS BATCH ============ --}}
    @php
        $segmentTotal = max(array_sum(array_column($segments, 'value')), 1);
    @endphp

    <div class="brp-summary-card">
        <div class="brp-summary-head">
            <h4>Ringkasan Status Batch</h4>
            <span class="brp-summary-total">{{ $stats['total_form'] }} total form</span>
        </div>

        <div class="brp-summary-bar">
            @foreach ($segments as $seg)
                @if ($seg['value'] > 0)
                    <div class="brp-summary-seg {{ $seg['class'] }}"
                         style="width: {{ round(($seg['value'] / $segmentTotal) * 100, 2) }}%"
                         title="{{ $seg['label'] }}: {{ $seg['value'] }}"></div>
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
    </div>

    {{-- ============ BATCH RELEASE FORMS — RECENT ============ --}}
    <div class="brp-card" style="margin-top: 1.25rem;">
        <div class="brp-card-header">
            <h3>Batch Release Forms &mdash; Recent</h3>
            <div class="brp-header-actions">
                <a href="{{ route('batch-repository.index') }}" class="brp-btn-outline">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" style="width:14px;height:14px;"><path d="M6 4h9v9M15 4L4 15"/></svg>
                    Lihat Semua
                </a>
                <a href="{{ route('batch-repository.export-excel', $quickFilters) }}" class="brp-btn-primary">
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width:14px;height:14px;"><path d="M4 2.5A1.5 1.5 0 0 1 5.5 1h5.6L15 4.9v11.6A1.5 1.5 0 0 1 13.5 18h-8A1.5 1.5 0 0 1 4 16.5v-14Z" opacity=".35"/><path d="M7.3 9.2 9.6 12l2.3-2.8"/><path d="M9.6 12V6.5" stroke="currentColor" stroke-width="1.4" fill="none"/></svg>
                    Export Excel
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="brp-filter-bar">
            <input type="text" name="q" value="{{ $quickFilters['q'] ?? '' }}" placeholder="Cari batch no / produk...">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="draft" {{ ($quickFilters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ ($quickFilters['status'] ?? '') === 'submitted' ? 'selected' : '' }}>Waiting Supervisor</option>
                <option value="waiting_manager" {{ ($quickFilters['status'] ?? '') === 'waiting_manager' ? 'selected' : '' }}>Waiting Manager</option>
                <option value="released" {{ ($quickFilters['status'] ?? '') === 'released' ? 'selected' : '' }}>Released</option>
                <option value="rejected" {{ ($quickFilters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <input type="date" name="date_from" value="{{ $quickFilters['date_from'] ?? '' }}">
            <input type="date" name="date_to" value="{{ $quickFilters['date_to'] ?? '' }}">
            <button type="submit" class="brp-btn-primary">Filter</button>
            @if (($quickFilters['q'] ?? null) || ($quickFilters['status'] ?? null) || ($quickFilters['date_from'] ?? null) || ($quickFilters['date_to'] ?? null))
                <a href="{{ route('dashboard') }}" class="brp-btn-outline">Reset</a>
            @endif
        </form>

        <div class="brp-table-wrap">
            <table class="brp-table">
                <thead>
                    <tr>
                        <th>Batch No</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Manufacturer</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentBatches as $batch)
                        <tr>
                            <td class="brp-cell-strong">{{ $batch->batch_number }}</td>
                            <td>{{ $batch->product->product_code ?? '-' }}</td>
                            <td>{{ $batch->product->name ?? '-' }}</td>
                            <td>{{ $batch->manufacturer }}</td>
                            <td><span class="{{ $batch->statusBadgeClass() }}">{{ $batch->statusLabel() }}</span></td>
                            <td>{{ $batch->created_at?->format('d M Y') ?? '—' }}</td>
                            <td>
                                @if ($batch->status === 'draft')
                                    <a href="{{ route('batch-form.edit', $batch) }}" class="brp-btn-outline">
                                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" style="width:14px;height:14px;"><path d="M13.3 3.3a1.5 1.5 0 0 1 2.1 2.1L6 15.1l-2.8.7.7-2.8L13.3 3.3Z"/></svg>
                                        Edit
                                    </a>
                                @else
                                    <a href="{{ route('batch-repository.show', $batch) }}" class="brp-btn-outline">
                                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" style="width:14px;height:14px;"><path d="M1.5 10S4.5 4 10 4s8.5 6 8.5 6-3 6-8.5 6-8.5-6-8.5-6Z"/><circle cx="10" cy="10" r="2.25"/></svg>
                                        View
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="brp-empty">Belum ada batch yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (session('force_password_change'))
        <div id="password-modal" class="brp-modal-overlay">
            <div class="brp-modal-box">
                @if (session('password_change_reason') === 'first_login')
                    <h3>Ganti Password Anda</h3>
                    <p>Ini adalah login pertama Anda. Untuk keamanan, silakan ganti password default Anda.</p>
                @else
                    <h3>Password Anda Sudah Kedaluwarsa</h3>
                    <p>Password Anda sudah digunakan lebih dari 90 hari. Silakan ganti password untuk melanjutkan.</p>
                @endif

                <a href="{{ route('password.change') }}" class="brp-modal-btn">
                    Ganti Password Sekarang
                </a>
            </div>
        </div>
    @endif

@endsection
