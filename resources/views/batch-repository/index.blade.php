@extends('layouts.app')

@section('title', 'Batch Repository')
@section('page-title', 'Batch Repository')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/batch-repository.css') }}">
@endpush

@section('content')

    <div class="brp-card">
        <div class="brp-card-header">
            <h3>Batch Repository</h3>
            <div class="brp-header-actions">
                <a href="{{ route('batch-repository.export-excel', $filters) }}" class="brp-btn-outline">Export Excel</a>
                <a href="{{ route('batch-repository.export-pdf', $filters) }}" class="brp-btn-outline">Export PDF</a>
            </div>
        </div>

        <form method="GET" action="{{ route('batch-repository.index') }}" class="brp-filter-bar">
            <input type="text" name="batch_number" value="{{ $filters['batch_number'] ?? '' }}" placeholder="Batch Number...">
            <input type="text" name="product_code" value="{{ $filters['product_code'] ?? '' }}" placeholder="Product Code...">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }} @if(optional(auth()->user())->role !== 'Admin') style="display:none;" @endif>Draft</option>
                <option value="submitted" {{ ($filters['status'] ?? '') === 'submitted' ? 'selected' : '' }}>Waiting Supervisor</option>
                <option value="waiting_manager" {{ ($filters['status'] ?? '') === 'waiting_manager' ? 'selected' : '' }}>Waiting Manager</option>
                <option value="released" {{ ($filters['status'] ?? '') === 'released' ? 'selected' : '' }}>Released</option>
                <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <select name="line_code">
                <option value="">Semua Line</option>
                <option value="inhouse_stripping" {{ ($filters['line_code'] ?? '') === 'inhouse_stripping' ? 'selected' : '' }}>Inhouse / Stripping</option>
                <option value="inhouse_bottling" {{ ($filters['line_code'] ?? '') === 'inhouse_bottling' ? 'selected' : '' }}>Inhouse / Bottling</option>
                <option value="toll_out" {{ ($filters['line_code'] ?? '') === 'toll_out' ? 'selected' : '' }}>Toll Out</option>
            </select>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
            <button type="submit" class="brp-btn-primary">Filter</button>
        </form>

        <div class="brp-table-wrap">
            <table class="brp-table">
                <thead>
                    <tr>
                        <th>Batch No</th>
                        <th>Line</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Manufacturer</th>
                        <th>Admin</th>
                        <th>Supervisor</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th>Released Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($batches as $batch)
                        <tr>
                            <td class="brp-cell-strong">{{ $batch->batch_number }}</td>
                            <td><span class="{{ $batch->lineBadgeClass() }}">{{ $batch->lineLabel() }}</span></td>
                            <td>{{ $batch->product->product_code ?? '-' }}</td>
                            <td>{{ $batch->product->name ?? '-' }}</td>
                            <td>{{ $batch->manufacturer }}</td>
                            <td>{{ $batch->creator->name ?? '-' }}</td>
                            <td>{{ $batch->supervisor->name ?? '-' }}</td>
                            <td>{{ $batch->manager->name ?? '-' }}</td>
                            <td><span class="{{ $batch->statusBadgeClass() }}">{{ $batch->statusLabel() }}</span></td>
                            <td>{{ $batch->released_at?->format('d M Y') ?? '—' }}</td>
                            <td>
                                @if ($batch->status === 'draft')
                                    <a href="{{ route('batch-form.edit', $batch) }}" class="brp-icon-btn" title="Edit Draft">
                                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13.3 3.3a1.5 1.5 0 0 1 2.1 2.1L6 15.1l-2.8.7.7-2.8L13.3 3.3Z"/></svg>
                                    </a>
                                @else
                                    <a href="{{ route('batch-repository.show', $batch) }}" class="brp-icon-btn" title="Lihat Detail">
                                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1.5 10S4.5 4 10 4s8.5 6 8.5 6-3 6-8.5 6-8.5-6-8.5-6Z"/><circle cx="10" cy="10" r="2.25"/></svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="brp-empty">Belum ada batch yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="brp-pagination">
            {{ $batches->links() }}
        </div>
    </div>

@endsection
