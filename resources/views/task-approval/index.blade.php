@extends('layouts.app')

@section('title', 'Task Approval')
@section('page-title', 'Task Approval')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/batch-repository.css') }}?v=5">
    <link rel="stylesheet" href="{{ asset('css/task-approval.css') }}?v=5">
@endpush

@section('content')

    @if (session('status'))
        <div class="tap-flash">{{ session('status') }}</div>
    @endif

    <div class="tap-stats">
        <div class="tap-stat-card">
            <span class="tap-stat-label">Pending Review</span>
            <span class="tap-stat-value">{{ $pendingBatches->count() }}</span>
            <span class="tap-stat-sub">Tasks today</span>
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
            <h3>{{ $canAct ? 'My Approval Tasks' : 'Batches Awaiting Approval (Admin Monitoring)' }}</h3>
        </div>

        <div class="brp-table-wrap">
            <table class="brp-table">
                <thead>
                    <tr>
                        <th>Batch No</th>
                        <th>Line</th>
                        <th>Product</th>
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
                                <a href="{{ route('batch-repository.show', $batch) }}" class="tap-btn-outline">Details</a>
                                @if ($canAct)
                                    <button type="button" class="tap-btn-approve"
                                        data-action="approve"
                                        data-url="{{ route('task-approval.approve', $batch) }}"
                                        data-batch="{{ $batch->batch_number }}"
                                        data-step="{{ $batch->status === 'submitted' ? 'supervisor' : 'manager' }}">
                                        Approve
                                    </button>
                                    <button type="button" class="tap-btn-reject"
                                        data-action="reject"
                                        data-url="{{ route('task-approval.reject', $batch) }}"
                                        data-batch="{{ $batch->batch_number }}"
                                        data-step="{{ $batch->status === 'submitted' ? 'supervisor' : 'manager' }}">
                                        Reject
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="brp-empty">No batches currently awaiting approval.</td>
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

                {{-- Fields khusus Supervisor saat Approve --}}
                <div id="tap-spv-fields" style="display: none; margin-bottom: 1.1rem;">
                    {{-- 1. Laporan Penyimpangan --}}
                    <div class="tap-field-group">
                        <label class="tap-label-strong">Laporan Penyimpangan*</label>
                        <div class="tap-radio-group">
                            <label class="tap-radio-label">
                                <input type="radio" name="laporan_penyimpangan" value="tidak" checked id="radio-penyimpangan-tidak">
                                <span>Tidak</span>
                            </label>
                            <label class="tap-radio-label">
                                <input type="radio" name="laporan_penyimpangan" value="ada" id="radio-penyimpangan-ada">
                                <span>Ada</span>
                            </label>
                        </div>

                        <div id="wrap-penyimpangan-table" style="display: none; margin-top: 0.6rem;">
                            <p class="tap-sub-label">Jika Ada :</p>
                            <table class="tap-inner-table" id="table-penyimpangan">
                                <thead>
                                    <tr>
                                        <th style="width: 48%;">No. Penyimpangan</th>
                                        <th style="width: 44%;">Status Penyimpangan</th>
                                        <th style="width: 8%; text-align: center;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-penyimpangan">
                                    <tr>
                                        <td><input type="text" name="penyimpangan_nomor[]" placeholder="Contoh: DEV/2026/001" class="tap-input-sm"></td>
                                        <td><input type="text" name="penyimpangan_status[]" placeholder="Contoh: Closed" class="tap-input-sm"></td>
                                        <td style="text-align: center;"><button type="button" class="tap-btn-remove-row" title="Hapus baris">&times;</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="tap-btn-add-row" id="btn-add-penyimpangan">+ Tambah Baris</button>
                        </div>
                        <div id="wrap-penyimpangan-na" style="font-size: 0.78rem; color: var(--text-secondary); margin-top: 0.35rem;">
                            <em>Status auto-fill: N/A</em>
                        </div>
                    </div>

                    {{-- 2. Change Control --}}
                    <div class="tap-field-group" style="margin-top: 0.85rem;">
                        <label class="tap-label-strong">Change Control*</label>
                        <div class="tap-radio-group">
                            <label class="tap-radio-label">
                                <input type="radio" name="change_control" value="tidak" checked id="radio-cc-tidak">
                                <span>Tidak</span>
                            </label>
                            <label class="tap-radio-label">
                                <input type="radio" name="change_control" value="ada" id="radio-cc-ada">
                                <span>Ada</span>
                            </label>
                        </div>

                        <div id="wrap-cc-table" style="display: none; margin-top: 0.6rem;">
                            <p class="tap-sub-label">Jika Ada :</p>
                            <table class="tap-inner-table" id="table-cc">
                                <thead>
                                    <tr>
                                        <th style="width: 48%;">No. FUP</th>
                                        <th style="width: 44%;">Status FUP</th>
                                        <th style="width: 8%; text-align: center;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-cc">
                                    <tr>
                                        <td><input type="text" name="change_control_nomor[]" placeholder="Contoh: FUP/2026/001" class="tap-input-sm"></td>
                                        <td><input type="text" name="change_control_status[]" placeholder="Contoh: Closed" class="tap-input-sm"></td>
                                        <td style="text-align: center;"><button type="button" class="tap-btn-remove-row" title="Hapus baris">&times;</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="tap-btn-add-row" id="btn-add-cc">+ Tambah Baris</button>
                        </div>
                        <div id="wrap-cc-na" style="font-size: 0.78rem; color: var(--text-secondary); margin-top: 0.35rem;">
                            <em>Status auto-fill: N/A</em>
                        </div>
                    </div>
                </div>

                <label for="tap-modal-note" id="tap-modal-note-label">Remarks (optional)</label>
                <textarea id="tap-modal-note" name="note" rows="3" placeholder="Enter remarks or reason here..."></textarea>
                <p class="tap-modal-error" id="tap-modal-error"></p>

                <div class="tap-modal-actions">
                    <button type="button" class="tap-btn-outline" id="tap-modal-cancel">Cancel</button>
                    <button type="submit" class="brp-btn-primary" id="tap-modal-submit">Confirm</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/task-approval.js') }}?v=5" defer></script>
@endpush
