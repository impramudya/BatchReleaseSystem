@extends('layouts.app')

@section('title', 'Form Evaluasi Pelulusan Produk Jadi')
@section('page-title', 'Form Evaluasi Pelulusan Produk Jadi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/batch-form.css') }}">
@endpush

@section('content')

    <nav class="bfm-breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>&rsaquo;</span>
        <span>Form Evaluasi Pelulusan Produk Jadi</span>
    </nav>

    @if ($errors->any())
        <div class="bfm-flash bfm-flash-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('batch-form.store') }}" method="POST" id="batch-form">
        @csrf

        {{-- ================= HEADER FORM ================= --}}
        <div class="bfm-card">
            <div class="bfm-card-header">
                <h3>
                    <svg class="bfm-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 3.5h10v13H5z"/><path d="M7.5 7h5M7.5 10h5M7.5 13h3"/></svg>
                    Header Form &mdash; Informasi Batch
                </h3>
                <span class="bfm-badge-draft">Draft</span>
            </div>

            <div class="bfm-grid">
                <div class="bfm-field">
                    <label for="product_id">Product Code <span class="bfm-required">*</span></label>
                    <select id="product_id" name="product_id" required>
                        <option value="" disabled selected>&mdash; Pilih Produk &mdash;</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} &mdash; {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                <div class="bfm-field">
                    <label for="product_name_display">Product Name</label>
                    <input type="text" id="product_name_display" readonly placeholder="Otomatis terisi setelah pilih Product Code">
                </div>

                <div class="bfm-field">
                    <label for="batch_number">Batch Number <span class="bfm-required">*</span></label>
                    <input type="text" id="batch_number" name="batch_number" value="{{ old('batch_number') }}"
                        placeholder="Misal: BATCH-2026-048" required>
                    @error('batch_number') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                <div class="bfm-field">
                    <label for="manufacturer">Manufacturer <span class="bfm-required">*</span></label>
                    <input type="text" id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}"
                        placeholder="Misal: PT Saka Farma, Tbk" required>
                    @error('manufacturer') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                <div class="bfm-field">
                    <label for="production_type">Tipe Produksi <span class="bfm-required">*</span></label>
                    <select id="production_type" name="production_type" required>
                        <option value="" disabled {{ old('production_type') ? '' : 'selected' }}>&mdash; Pilih Tipe &mdash;</option>
                        <option value="in_house" {{ old('production_type') === 'in_house' ? 'selected' : '' }}>In House</option>
                        <option value="toll_out" {{ old('production_type') === 'toll_out' ? 'selected' : '' }}>Toll Out</option>
                    </select>
                    @error('production_type') <p class="bfm-error">{{ $message }}</p> @enderror
                    {{-- Placeholder untuk field tambahan khusus Toll Out di masa depan --}}
                    <div id="toll-out-extra-fields" class="bfm-hidden"></div>
                </div>

                <div class="bfm-field">
                    <label for="batch_date">Date <span class="bfm-required">*</span></label>
                    <input type="date" id="batch_date" name="batch_date" value="{{ old('batch_date', now()->toDateString()) }}" required>
                    @error('batch_date') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                <div class="bfm-field">
                    <label for="supervisor_id">Assign Supervisor <span class="bfm-required">*</span></label>
                    <select id="supervisor_id" name="supervisor_id" required>
                        <option value="" disabled selected>&mdash; Pilih Supervisor &mdash;</option>
                        @foreach ($supervisors as $supervisor)
                            <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                {{ $supervisor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supervisor_id') <p class="bfm-error">{{ $message }}</p> @enderror
                </div>

                <div class="bfm-field bfm-field-wide">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan') }}" placeholder="Opsional...">
                </div>
            </div>
        </div>

        {{-- ================= CHECKLIST EVALUASI ================= --}}
        <div class="bfm-card">
            <div class="bfm-card-header">
                <h3>
                    <svg class="bfm-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 5h.01M5 10h.01M5 15h.01"/><path d="M8.5 5h8M8.5 10h8M8.5 15h8"/></svg>
                    Checklist Evaluasi
                </h3>
                <span class="bfm-legend">C = Complies &middot; NC = Not Complies &middot; N/A = Not Applicable</span>
            </div>

            @php $globalNo = 0; @endphp

            @forelse ($categories as $category)
                @if ($category->questions->isNotEmpty())
                    <div class="bfm-category-bar">
                        <span class="bfm-category-code">{{ $category->code }}</span>
                        {{ $category->name }}
                    </div>

                    @foreach ($category->questions as $question)
                        @php $globalNo++; @endphp
                        <div class="bfm-question-row">
                            <span class="bfm-question-no">{{ $globalNo }}</span>
                            <span class="bfm-question-text">{{ $question->question }}</span>
                            <div class="bfm-answer-group" data-answer-group>
                                <label class="bfm-answer-pill bfm-answer-c">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="C"
                                        {{ old("answers.{$question->id}") === 'C' ? 'checked' : '' }}>
                                    C
                                </label>
                                <label class="bfm-answer-pill bfm-answer-nc">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="NC"
                                        {{ old("answers.{$question->id}") === 'NC' ? 'checked' : '' }}>
                                    NC
                                </label>
                                <label class="bfm-answer-pill bfm-answer-na">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="NA"
                                        {{ old("answers.{$question->id}") === 'NA' ? 'checked' : '' }}>
                                    N/A
                                </label>
                            </div>
                        </div>
                    @endforeach
                @endif
            @empty
                <p class="bfm-empty">Belum ada kategori checklist aktif. Tambahkan dulu di menu Checklist Config.</p>
            @endforelse

            @if ($globalNo > 0)
                <div class="bfm-progress-wrap">
                    <span class="bfm-progress-label">Progress Pengisian</span>
                    <div class="bfm-progress-track">
                        <div class="bfm-progress-fill" id="progress-fill" style="width: 0%"></div>
                    </div>
                    <span class="bfm-progress-count" id="progress-count">0/{{ $globalNo }}</span>
                </div>
            @endif
        </div>

        {{-- ================= ACTIONS ================= --}}
        <div class="bfm-actions">
            <button type="submit" name="action" value="draft" class="bfm-btn-secondary">
                <svg class="bfm-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h9l3 3v9H4z"/><path d="M7 4v4h6V4M7 12h6"/></svg>
                Simpan Draft
            </button>
            <button type="submit" name="action" value="submit" class="bfm-btn-primary">
                <svg class="bfm-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 10l14-6-6 14-2-6-6-2z"/></svg>
                Submit &amp; Kirim ke Supervisor
            </button>
        </div>

    </form>

@endsection

@push('scripts')
    <script src="{{ asset('js/batch-form.js') }}" defer></script>
@endpush
