@extends('layouts.app')

@section('title', 'Batch Details — ' . $batch->batch_number)
@section('page-title', 'Form Details — ' . $batch->batch_number)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/batch-form.css') }}?v=4">
    <link rel="stylesheet" href="{{ asset('css/batch-repository.css') }}?v=4">
@endpush

@section('content')

    <nav class="bfm-breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>&rsaquo;</span>
        <a href="{{ route('batch-repository.index') }}">Batch Repository</a>
        <span>&rsaquo;</span>
        <span>{{ $batch->batch_number }}</span>
    </nav>

    <div class="brp-detail-header">
        <div>
            <h2>{{ $batch->batch_number }} &mdash; {{ $batch->product->name ?? '-' }}</h2>
            <p>{{ $batch->manufacturer }} &middot; {{ $batch->batch_date?->format('d M Y') }}</p>
        </div>
        <div class="brp-detail-header-actions">
            <span class="{{ $batch->statusBadgeClass() }}">{{ $batch->statusLabel() }}</span>
            <a href="{{ route('batch-repository.pdf', $batch) }}" target="_blank" class="brp-btn-outline">View PDF</a>
            <a href="{{ route('batch-repository.pdf-download', $batch) }}" class="brp-btn-primary">Download PDF</a>
        </div>
    </div>

    <div class="brp-card">
        <div class="brp-tabs" data-tabs>
            <button type="button" class="brp-tab active" data-tab-btn="form-evaluasi">Evaluation Form</button>
            <button type="button" class="brp-tab" data-tab-btn="history-approval">Approval History</button>
            <button type="button" class="brp-tab" data-tab-btn="audit-trail">Audit Trail</button>
        </div>

        <div class="brp-info-row">
            <div>
                <span class="brp-info-label">Product Code</span>
                <span class="brp-info-value">{{ $batch->product->product_code ?? '-' }}</span>
            </div>
            <div>
                <span class="brp-info-label">Product Name</span>
                <span class="brp-info-value">{{ $batch->product->name ?? '-' }}</span>
            </div>
            <div>
                <span class="brp-info-label">Batch Number</span>
                <span class="brp-info-value">{{ $batch->batch_number }}</span>
            </div>
            <div>
                <span class="brp-info-label">Manufacturer</span>
                <span class="brp-info-value">{{ $batch->manufacturer }}</span>
            </div>
        </div>

        {{-- ============ TAB: EVALUATION FORM ============ --}}
        <div class="brp-tab-panel active" data-tab-panel="form-evaluasi">
            @php $no = 0; @endphp
            @forelse ($checklistRows as $categoryCode => $rows)
                <div class="bfm-category-bar">
                    <span class="bfm-category-code">{{ $categoryCode }}</span>
                    {{ $rows->first()->category_name }}
                </div>
                @foreach ($rows as $row)
                    @php $no++; @endphp
                    <div class="bfm-question-row">
                        <span class="bfm-question-no">{{ $no }}</span>
                        <span class="bfm-question-text">{{ $row->question }}</span>
                        <span class="brp-answer-pill brp-answer-{{ strtolower($row->answer) }}">{{ $row->answer }}</span>
                    </div>
                @endforeach
            @empty
                <p class="bfm-empty">No checklist answers recorded for this batch (still in draft).</p>
            @endforelse
        </div>

        {{-- ============ TAB: APPROVAL HISTORY ============ --}}
        <div class="brp-tab-panel" data-tab-panel="history-approval">
            @if ($batch->approvals->isEmpty())
                <p class="bfm-empty">This batch is currently in draft and has not entered the approval workflow.</p>
            @else
                <div class="brp-approval-cards">
                    @foreach ($batch->approvals as $approval)
                        <div class="brp-approval-card">
                            <span class="brp-approval-role">{{ $approval->stepLabel() }}</span>
                            <p class="brp-approval-name">{{ $approval->user->name ?? '—' }}</p>
                            <p class="brp-approval-time">
                                {{ $approval->acted_at?->format('d M Y, H:i') ?? '— Pending —' }}
                            </p>
                            <span class="brp-approval-badge brp-approval-{{ $approval->status }}">
                                @if ($approval->status === 'approved') Approved
                                @elseif ($approval->status === 'rejected') Rejected
                                @else Waiting
                                @endif
                            </span>
                            @if ($approval->note)
                                <div class="brp-approval-note">
                                    <strong>Remarks by {{ $approval->stepLabel() }}</strong>
                                    <p>{{ $approval->note }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ============ TAB: AUDIT TRAIL ============ --}}
        <div class="brp-tab-panel" data-tab-panel="audit-trail">
            <ul class="brp-timeline">
                <li>
                    <span class="brp-timeline-dot"></span>
                    <div>
                        <p class="brp-timeline-title">Batch initiated by {{ $batch->creator->name ?? '-' }}</p>
                        <p class="brp-timeline-time">{{ $batch->created_at?->format('d M Y, H:i') }}</p>
                    </div>
                </li>
                @foreach ($batch->approvals as $approval)
                    @if ($approval->acted_at)
                        <li>
                            <span class="brp-timeline-dot brp-timeline-{{ $approval->status }}"></span>
                            <div>
                                <p class="brp-timeline-title">
                                    {{ $approval->stepLabel() }}
                                    @if ($approval->status === 'approved') approved this batch
                                    @elseif ($approval->status === 'rejected') rejected this batch
                                    @endif
                                    ({{ $approval->user->name ?? '-' }})
                                </p>
                                <p class="brp-timeline-time">{{ $approval->acted_at->format('d M Y, H:i') }}</p>
                                @if ($approval->note)
                                    <p class="brp-timeline-note">{{ $approval->note }}</p>
                                @endif
                            </div>
                        </li>
                    @endif
                @endforeach
                @if ($batch->status === 'released')
                    <li>
                        <span class="brp-timeline-dot brp-timeline-approved"></span>
                        <div>
                            <p class="brp-timeline-title">Batch officially Released</p>
                            <p class="brp-timeline-time">{{ $batch->released_at?->format('d M Y, H:i') }}</p>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/batch-repository.js') }}" defer></script>
@endpush
