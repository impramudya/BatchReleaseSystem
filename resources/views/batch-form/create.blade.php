@extends('layouts.app')

@section('title', isset($batch) ? 'Edit Draft — '.$batch->batch_number : 'Finished Product Release Evaluation Form')
@section('page-title', isset($batch) ? 'Edit Draft — '.$batch->batch_number : 'Finished Product Release Evaluation Form')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/batch-form.css') }}?v=4">
@endpush

@section('content')

    <nav class="bfm-breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>&rsaquo;</span>
        <span>Finished Product Release Evaluation Form</span>
    </nav>

    <p class="bfm-line-tag">{{ $line->parent ? $line->parent->name . ' / ' . $line->name : $line->name }}</p>

    @if ($errors->any())
        <div class="bfm-flash bfm-flash-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($batch) ? route('batch-form.update', $batch) : route('batch-form.store') }}" method="POST" id="batch-form">
        @csrf
        @if (isset($batch))
            @method('PUT')
        @endif
        <input type="hidden" name="production_line_id" value="{{ $line->id }}">

        {{-- ================= HEADER FORM ================= --}}
        <div class="bfm-card">
            <div class="bfm-card-header">
                <h3>
                    <svg class="bfm-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 3.5h10v13H5z"/><path d="M7.5 7h5M7.5 10h5M7.5 13h3"/></svg>
                    Form Header &mdash; Batch Information
                </h3>
                <span class="bfm-badge-draft">Draft</span>
            </div>

            <div class="bfm-grid">
                <div class="bfm-field">
                    <label for="product_id">Product Code <span class="bfm-required">*</span></label>
                    <select id="product_id" name="product_id" required>
                        <option value="" disabled selected>&mdash; Select Product &mdash;</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                {{ old('product_id', $batch->product_id ?? null) == $product->id ? 'selected' : '' }}>
                                {{ $product->product_code }} &mdash; {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                <div class="bfm-field">
                    <label for="product_name_display">Product Name</label>
                    <input type="text" id="product_name_display" readonly placeholder="Auto-populated upon selecting Product Code">
                </div>

                <div class="bfm-field">
                    <label for="batch_number">Batch Number <span class="bfm-required">*</span></label>
                    <input type="text" id="batch_number" name="batch_number" value="{{ old('batch_number', $batch->batch_number ?? '') }}"
                        placeholder="e.g., BATCH-2026-048" required>
                    @error('batch_number') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                <div class="bfm-field">
                    <label for="manufacturer">Manufacturer <span class="bfm-required">*</span></label>
                    <input type="text" id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $batch->manufacturer ?? '') }}"
                        placeholder="e.g., PT Saka Farma Laboratories" required>
                    @error('manufacturer') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                <div class="bfm-field">
                    <label for="batch_date">Manufacturing Date <span class="bfm-required">*</span></label>
                    <input type="date" id="batch_date" name="batch_date" value="{{ old('batch_date', isset($batch) ? $batch->batch_date?->toDateString() : now()->toDateString()) }}" required>
                    @error('batch_date') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                <div class="bfm-field">
                    <label for="supervisor_id">Assign Supervisor <span class="bfm-required">*</span></label>
                    <select id="supervisor_id" name="supervisor_id" required>
                        <option value="" disabled selected>&mdash; Select Supervisor &mdash;</option>
                        @foreach ($supervisors as $supervisor)
                            <option value="{{ $supervisor->id }}" {{ old('supervisor_id', $batch->supervisor_id ?? null) == $supervisor->id ? 'selected' : '' }}>
                                {{ $supervisor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supervisor_id') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                <div class="bfm-field">
                    <label for="manager_id">Assign Manager <span class="bfm-required">*</span></label>
                    <select id="manager_id" name="manager_id" required>
                        <option value="" disabled selected>&mdash; Select Manager &mdash;</option>
                        @foreach ($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id', $batch->manager_id ?? null) == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ================= EVALUATION CHECKLIST ================= --}}
        <div class="bfm-card">
            <div class="bfm-card-header">
                <h3>
                    <svg class="bfm-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 5h.01M5 10h.01M5 15h.01"/><path d="M8.5 5h8M8.5 10h8M8.5 15h8"/></svg>
                    Evaluation Checklist
                </h3>
                <span class="bfm-legend">C = Complies &middot; NC = Not Complies &middot; N/A = Not Applicable</span>
            </div>

            @php $checkableCount = 0; @endphp

            @forelse ($line->categories as $category)
                @if ($category->questions->isNotEmpty())
                    <div class="bfm-category-bar">
                        <span class="bfm-category-code">{{ $category->code }}</span>
                        {{ $category->name }}
                    </div>

                    @php $parentNo = 0; @endphp

                    @foreach ($category->questions as $parent)
                        @php
                            $parentNo++;
                            $hasChildren = $parent->children->isNotEmpty();
                        @endphp

                        {{-- Parent row --}}
                        <div class="bfm-question-row" style="{{ $hasChildren ? 'background: rgba(0,0,0,0.02);' : '' }}">
                            <span class="bfm-question-no">{{ $parentNo }}</span>
                            <div class="bfm-question-content">
                                <span class="bfm-question-text" style="{{ $hasChildren ? 'font-weight: 700; color: var(--text-primary);' : '' }}">
                                    {{ $parent->question }}
                                </span>
                                @if (!empty($parent->details))
                                    <div class="bfm-question-details" style="margin-top: 0.35rem; padding-left: 0.8rem; font-size: 0.82rem; color: var(--text-secondary); line-height: 1.45;">
                                        {!! nl2br(e($parent->details)) !!}
                                    </div>
                                @endif
                            </div>
                            @if (!$hasChildren)
                                @php
                                    $checkableCount++;
                                    $answerValue = old("answers.{$parent->id}", $existingAnswers[$parent->id] ?? null);
                                    $noteValue = old("notes.{$parent->id}", $existingNotes[$parent->id] ?? '');
                                @endphp
                                <div class="bfm-answer-group" data-answer-group>
                                    <label class="bfm-answer-pill bfm-answer-c">
                                        <input type="radio" name="answers[{{ $parent->id }}]" value="C"
                                            {{ $answerValue === 'C' ? 'checked' : '' }}>
                                        C
                                    </label>
                                    <label class="bfm-answer-pill bfm-answer-nc">
                                        <input type="radio" name="answers[{{ $parent->id }}]" value="NC"
                                            {{ $answerValue === 'NC' ? 'checked' : '' }}>
                                        NC
                                    </label>
                                    <label class="bfm-answer-pill bfm-answer-na">
                                        <input type="radio" name="answers[{{ $parent->id }}]" value="NA"
                                            {{ $answerValue === 'NA' ? 'checked' : '' }}>
                                        N/A
                                    </label>
                                </div>
                                <div class="bfm-question-note-wrap">
                                    <input type="text"
                                           name="notes[{{ $parent->id }}]"
                                           value="{{ $noteValue }}"
                                           placeholder="Masukkan keterangan..."
                                           class="bfm-question-note-input"
                                           autocomplete="off">
                                </div>
                            @endif
                        </div>

                        {{-- Child rows (memiliki checkmark & keterangan) --}}
                        @foreach ($parent->children as $child)
                            @php
                                $checkableCount++;
                                $childAnsValue = old("answers.{$child->id}", $existingAnswers[$child->id] ?? null);
                                $childNoteValue = old("notes.{$child->id}", $existingNotes[$child->id] ?? '');
                            @endphp
                            <div class="bfm-question-row" style="padding-left: 2rem;">
                                <span class="bfm-question-no"></span>
                                <div class="bfm-question-content">
                                    <span class="bfm-question-text">{{ $child->question }}</span>
                                    @if (!empty($child->details))
                                        <div class="bfm-question-details" style="margin-top: 0.35rem; padding-left: 0.8rem; font-size: 0.82rem; color: var(--text-secondary); line-height: 1.45;">
                                            {!! nl2br(e($child->details)) !!}
                                        </div>
                                    @endif
                                </div>
                                <div class="bfm-answer-group" data-answer-group>
                                    <label class="bfm-answer-pill bfm-answer-c">
                                        <input type="radio" name="answers[{{ $child->id }}]" value="C"
                                            {{ $childAnsValue === 'C' ? 'checked' : '' }}>
                                        C
                                    </label>
                                    <label class="bfm-answer-pill bfm-answer-nc">
                                        <input type="radio" name="answers[{{ $child->id }}]" value="NC"
                                            {{ $childAnsValue === 'NC' ? 'checked' : '' }}>
                                        NC
                                    </label>
                                    <label class="bfm-answer-pill bfm-answer-na">
                                        <input type="radio" name="answers[{{ $child->id }}]" value="NA"
                                            {{ $childAnsValue === 'NA' ? 'checked' : '' }}>
                                        N/A
                                    </label>
                                </div>
                                <div class="bfm-question-note-wrap">
                                    <input type="text"
                                           name="notes[{{ $child->id }}]"
                                           value="{{ $childNoteValue }}"
                                           placeholder="Masukkan keterangan..."
                                           class="bfm-question-note-input"
                                           autocomplete="off">
                                </div>
                            </div>

                            {{-- Grandson rows (TIDAK memiliki checkmark & keterangan) --}}
                            @foreach ($child->children as $grandson)
                                <div class="bfm-question-row" style="padding-left: 3.5rem; background: rgba(0,0,0,0.01);">
                                    <span class="bfm-question-no"></span>
                                    <div class="bfm-question-content">
                                        <span class="bfm-question-text" style="color: var(--text-secondary); font-size: 0.85rem;">
                                            {{ $grandson->question }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach
                @endif
            @empty
                <p class="bfm-empty">No active checklist categories configured for {{ $line->parent ? $line->parent->name . ' / ' . $line->name : $line->name }}. Please configure categories in the Checklist Config menu.</p>
            @endforelse

            @if ($checkableCount > 0)
                <div class="bfm-progress-wrap">
                    <span class="bfm-progress-label">Completion Progress</span>
                    <div class="bfm-progress-track">
                        <div class="bfm-progress-fill" id="progress-fill" style="width: 0%"></div>
                    </div>
                    <span class="bfm-progress-count" id="progress-count">0/{{ $checkableCount }}</span>
                </div>
            @endif
        </div>

        {{-- ================= BATCH VALIDASI ================= --}}
        @php
            $lastCategory = $line->categories->filter(fn($c) => $c->questions->isNotEmpty())->last() ?? $line->categories->sortBy('code')->last();
            $lastCode = $lastCategory ? trim($lastCategory->code) : null;
            $validasiPointCode = ($lastCode && ctype_alpha($lastCode[0]))
                ? chr(ord(strtoupper($lastCode[0])) + 1)
                : 'A';
        @endphp
        <div class="bfm-card" id="point-validasi-card">
            <div class="bfm-card-header">
                <h3>
                    <span class="bfm-category-code" style="background:var(--primary); color:#fff; font-weight:700;">{{ $validasiPointCode }}</span>
                    Validation Batch
                </h3>
            </div>
            <div style="padding: 1.4rem;">
                <div class="bfm-field" style="margin-bottom: 1.25rem;">
                    <label style="margin-bottom: 0.6rem;">Validation Batch <span class="bfm-required">*</span></label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <label class="bfm-answer-pill bfm-answer-c" style="cursor: pointer; padding: 0.5rem 1.25rem; font-size: 0.85rem;">
                            <input type="radio" name="batch_validasi" value="ya" id="batch_validasi_ya"
                                {{ old('batch_validasi', $batch->batch_validasi ?? '') === 'ya' ? 'checked' : '' }}>
                            Yes
                        </label>
                        <label class="bfm-answer-pill bfm-answer-nc" style="cursor: pointer; padding: 0.5rem 1.25rem; font-size: 0.85rem;">
                            <input type="radio" name="batch_validasi" value="tidak" id="batch_validasi_tidak"
                                {{ old('batch_validasi', $batch->batch_validasi ?? '') === 'tidak' ? 'checked' : '' }}>
                            No
                        </label>
                    </div>
                    @error('batch_validasi') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                {{-- Sub-kondisi Jika Ya --}}
                <div id="sub-validasi-container" style="background: #f8fafc; border: 1px solid var(--border); border-radius: 6px; padding: 1.2rem; display: {{ old('batch_validasi', $batch->batch_validasi ?? '') === 'ya' ? 'block' : 'none' }};">
                    <p style="font-weight: 600; font-size: 0.85rem; color: var(--text-secondary); margin: 0 0 1rem 0;">If Yes :</p>
                    <div class="bfm-grid" style="padding: 0; gap: 1.2rem 1.5rem;">
                        <div class="bfm-field">
                            <label style="margin-bottom: 0.6rem;">Validation Report <span class="bfm-required">*</span></label>
                            <div style="display: flex; gap: 1rem; align-items: center; min-height: 40px;">
                                <label class="bfm-answer-pill bfm-answer-c" style="cursor: pointer; padding: 0.45rem 1rem; font-size: 0.82rem;">
                                    <input type="radio" name="laporan_validasi" value="ada" id="laporan_validasi_ada"
                                        {{ old('laporan_validasi', $batch->laporan_validasi ?? '') === 'ada' ? 'checked' : '' }}>
                                    Yes
                                </label>
                                <label class="bfm-answer-pill bfm-answer-nc" style="cursor: pointer; padding: 0.45rem 1rem; font-size: 0.82rem;">
                                    <input type="radio" name="laporan_validasi" value="tidak" id="laporan_validasi_tidak"
                                        {{ old('laporan_validasi', $batch->laporan_validasi ?? '') === 'tidak' ? 'checked' : '' }}>
                                    No
                                </label>
                            </div>
                            @error('laporan_validasi') <p class="bfm-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="bfm-field">
                            <label for="nomor_laporan_validasi">Report Number</label>
                            <input type="text" id="nomor_laporan_validasi" name="nomor_laporan_validasi"
                                   value="{{ old('nomor_laporan_validasi', $batch->nomor_laporan_validasi ?? '') }}"
                                   placeholder="E.g., VAL/2026/089...">
                            @error('nomor_laporan_validasi') <p class="bfm-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= ACTIONS ================= --}}
        <div class="bfm-actions">
            @if (isset($batch))
                <a href="{{ route('batch-repository.index') }}" class="bfm-btn-secondary">Cancel</a>
            @endif
            <button type="submit" name="action" value="draft" class="bfm-btn-secondary">
                <svg class="bfm-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h9l3 3v9H4z"/><path d="M7 4v4h6V4M7 12h6"/></svg>
                {{ isset($batch) ? 'Save Changes' : 'Save Draft' }}
            </button>
            <button type="submit" name="action" value="submit" class="bfm-btn-primary">
                <svg class="bfm-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 10l14-6-6 14-2-6-6-2z"/></svg>
                Submit for Supervisor Review
            </button>
        </div>

    </form>

@endsection

@push('scripts')
    <script src="{{ asset('js/batch-form.js') }}?v=5" defer></script>
@endpush
