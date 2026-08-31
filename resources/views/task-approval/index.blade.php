@extends('layouts.app')

@section('title', 'Task Approval')
@section('page-title', 'Task Approval')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/batch-repository.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task-approval.css') }}">
@endpush

@section('content')

    @if (session('status'))
        <div class="tap-flash">{{ session('status') }}</div>
    @endif

    <div class="tap-stats">
        <div class="tap-stat-card">
            <span class="tap-stat-label">Pending Review</span>
            <span class="tap-stat-value">{{ $pendingBatches->count() }}</span>
            <span class="tap-stat-sub">Task hari ini</span>
        </div>
        <div class="tap-stat-card tap-stat-approved">
            <span class="tap-stat-label">Approved</span>
            <span class="tap-stat-value">{{ $approvedCount }}</span>
        </div>
        <div class="tap-stat-card tap-stat-rejected">
            <span class="tap-stat-label">Rejected</span>
            <span class="tap-stat-value">{{ $rejectedCount }}</span>
        </div>
    </div>

    <div class="brp-card">
        <div class="brp-card-header">
            <h3>{{ $canAct ? 'Task Approval Saya' : 'Batch Menunggu Approval (Pantauan Admin)' }}</h3>
        </div>

        <div class="brp-table-wrap">
            <table class="brp-table">
                <thead>
                    <tr>
                        <th>Batch No</th>
                        <th>Line</th>
                        <th>Produk</th>
                        <th>Submitted By</th>
                        <th>Submitted At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingBatches as $batch)
                        <tr>
                            <td class="brp-cell-strong">{{ $batch->batch_number }}</td>
                            <td><span class="{{ $batch->lineBadgeClass() }}">{{ $batch->lineLabel() }}</span></td>
                            <td>{{ $batch->product->name ?? '-' }}</td>
                            <td>{{ $batch->creator->name ?? '-' }}</td>
                            <td>{{ $batch->created_at?->format('d M Y, H:i') }}</td>
                            <td><span class="{{ $batch->statusBadgeClass() }}">{{ $batch->statusLabel() }}</span></td>
                            <td class="tap-actions">
                                <a href="{{ route('batch-repository.show', $batch) }}" class="tap-btn-outline">Detail</a>
                                @if ($canAct)
                                    <button type="button" class="tap-btn-approve"
                                        data-action="approve"
                                        data-url="{{ route('task-approval.approve', $batch) }}"
                                        data-batch="{{ $batch->batch_number }}">
                                        Approve
                                    </button>
                                    <button type="button" class="tap-btn-reject"
                                        data-action="reject"
                                        data-url="{{ route('task-approval.reject', $batch) }}"
                                        data-batch="{{ $batch->batch_number }}">
                                        Reject
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="brp-empty">Tidak ada batch yang menunggu approval saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Approve/Reject --}}
    <div class="tap-modal-backdrop" id="tap-modal-backdrop">
        <div class="tap-modal">
            <h4 id="tap-modal-title">Approve Batch</h4>
            <p id="tap-modal-batch" class="tap-modal-batch"></p>

            <form method="POST" id="tap-modal-form">
                @csrf
                <label for="tap-modal-note" id="tap-modal-note-label">Catatan (opsional)</label>
                <textarea id="tap-modal-note" name="note" rows="3" placeholder="Tulis catatan di sini..."></textarea>
                <p class="tap-modal-error" id="tap-modal-error"></p>

                <div class="tap-modal-actions">
                    <button type="button" class="brp-btn-outline" id="tap-modal-cancel">Batal</button>
                    <button type="submit" class="brp-btn-primary" id="tap-modal-submit">Approve</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/task-approval.js') }}" defer></script>
@endpush
