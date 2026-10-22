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
            @forelse ($checklistRows as $categoryCode => $rows)
                <div class="bfm-category-bar">
                    <span class="bfm-category-code">{{ $categoryCode }}</span>
                    {{ $rows->first()->category_name }}
                </div>
                @foreach ($rows as $row)
                    @php
                        $depth = $row->depth ?? 0;
                        $hasCheckmark = !empty($row->is_checkable);
                        $indent = $depth === 2 ? 'padding-left: 2.5rem;' : ($depth === 1 ? 'padding-left: 1.25rem;' : '');
                    @endphp
                    <div class="bfm-question-row {{ $depth > 0 ? 'bfm-question-row-sub' : '' }}" style="{{ $indent }}">
                        <span class="bfm-question-no">{{ $row->parent_no ?? '' }}</span>
                        <div class="bfm-question-content">
                            <span class="bfm-question-text" style="{{ $depth === 0 ? 'font-weight: 600;' : '' }}">
                                {{ $row->question }}
                            </span>
                            @if (!empty($row->details))
                                <div class="bfm-question-details" style="margin-top: 0.35rem; padding-left: 0.8rem; font-size: 0.82rem; color: var(--text-secondary); line-height: 1.45;">
                                    {!! nl2br(e($row->details)) !!}
                                </div>
                            @endif
                            @if ($hasCheckmark && !empty($row->keterangan))
                                <div class="brp-question-keterangan">
                                    <span class="brp-keterangan-label">Keterangan:</span> {{ $row->keterangan }}
                                </div>
                            @endif
                        </div>
                        @if ($hasCheckmark)
                            <span class="brp-answer-pill brp-answer-{{ strtolower($row->answer ?? 'na') }}">{{ $row->answer ?? '—' }}</span>
                        @endif
                    </div>
                @endforeach
            @empty
                <p class="bfm-empty">No checklist answers recorded for this batch (still in draft).</p>
            @endforelse

            {{-- Batch Validasi --}}
            @php
                $lastCatKey = $checklistRows->keys()->filter()->last() ?? $batch->productionLine?->categories?->sortBy('code')->last()?->code;
                $baseCharCode = ($lastCatKey && ctype_alpha(trim($lastCatKey)[0]))
                    ? ord(strtoupper(trim($lastCatKey)[0]))
                    : ord('A') - 1;

                $validasiPointCode = chr($baseCharCode + 1);
                $penyimpanganPointCode = chr($baseCharCode + 2);
                $changeControlPointCode = chr($baseCharCode + 3);
            @endphp
            {{-- Point: Batch Validasi --}}
            <div class="bfm-category-bar" style="margin-top: 1.5rem;">
                <span class="bfm-category-code">{{ $validasiPointCode }}</span>
                Batch Validasi
            </div>
            <div style="background: var(--surface); border: 1px solid var(--border); border-top: none; padding: 1.1rem 1.4rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.6rem;">
                    <span style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">Batch Validasi</span>
                    <span class="brp-answer-pill {{ ($batch->batch_validasi ?? '') === 'ya' ? 'brp-answer-c' : 'brp-answer-na' }}">
                        {{ strtoupper($batch->batch_validasi ?? '—') }}
                    </span>
                </div>
                @php
                    $lapVal = ($batch->batch_validasi === 'tidak') ? 'tidak' : ($batch->laporan_validasi ?? null);
                    $noLapVal = ($batch->batch_validasi === 'tidak') ? 'N/A' : ($batch->nomor_laporan_validasi ?? null);
                @endphp
                @if ($lapVal !== null)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0; border-top: 1px dashed var(--border); font-size: 0.88rem;">
                        <span style="color: var(--text-secondary);">Laporan Validasi:</span>
                        <span style="font-weight: 600; color: var(--text-primary);">{{ ucfirst($lapVal) }}</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0 0 0; border-top: 1px dashed var(--border); font-size: 0.88rem;">
                        <span style="color: var(--text-secondary);">Nomor Laporan:</span>
                        <span style="font-weight: 600; color: var(--text-primary);">{{ $noLapVal ?: '-' }}</span>
                    </div>
                @endif
            </div>

            {{-- Point: Laporan Penyimpangan --}}
            @php
                $penyimpanganVal = ($batch->laporan_penyimpangan === 'ada') ? 'ada' : 'tidak';
                $changeControlVal = ($batch->change_control === 'ada') ? 'ada' : 'tidak';
            @endphp
            <div class="bfm-category-bar" style="margin-top: 1.5rem;">
                <span class="bfm-category-code">{{ $penyimpanganPointCode }}</span>
                Laporan Penyimpangan
            </div>
            <div style="background: var(--surface); border: 1px solid var(--border); border-top: none; padding: 1.1rem 1.4rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.6rem;">
                    <span style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">Laporan Penyimpangan</span>
                    <span class="brp-answer-pill {{ $penyimpanganVal === 'ada' ? 'brp-answer-c' : 'brp-answer-na' }}">
                        {{ strtoupper($penyimpanganVal) }}
                    </span>
                </div>
                <div style="margin-top: 0.8rem; border-top: 1px dashed var(--border); padding-top: 0.8rem;">
                    <table class="brp-table" style="font-size: 0.85rem;">
                        <thead>
                            <tr>
                                <th style="width: 50%;">No. Penyimpangan</th>
                                <th style="width: 50%;">Status Penyimpangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($batch->penyimpangan_items ?? [] as $item)
                                <tr>
                                    <td>{{ $item['nomor'] ?? 'N/A' }}</td>
                                    <td>{{ $item['status'] ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td>N/A</td>
                                    <td>N/A</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Point: Change Control --}}
            <div class="bfm-category-bar" style="margin-top: 1.5rem;">
                <span class="bfm-category-code">{{ $changeControlPointCode }}</span>
                Change Control
            </div>
            <div style="background: var(--surface); border: 1px solid var(--border); border-top: none; padding: 1.1rem 1.4rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.6rem;">
                    <span style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">Change Control</span>
                    <span class="brp-answer-pill {{ $changeControlVal === 'ada' ? 'brp-answer-c' : 'brp-answer-na' }}">
                        {{ strtoupper($changeControlVal) }}
                    </span>
                </div>
                <div style="margin-top: 0.8rem; border-top: 1px dashed var(--border); padding-top: 0.8rem;">
                    <table class="brp-table" style="font-size: 0.85rem;">
                        <thead>
                            <tr>
                                <th style="width: 50%;">No. FUP</th>
                                <th style="width: 50%;">Status FUP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($batch->change_control_items ?? [] as $item)
                                <tr>
                                    <td>{{ $item['nomor'] ?? 'N/A' }}</td>
                                    <td>{{ $item['status'] ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td>N/A</td>
                                    <td>N/A</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
