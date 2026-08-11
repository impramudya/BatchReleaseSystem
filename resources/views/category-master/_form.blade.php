<style>
    .cf-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'poppins', sans-serif;
        font-size: 0.85rem;
        color: var(--content-text-soft);
        margin-bottom: 1.25rem;
    }
    .cf-breadcrumb a {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--content-text-soft);
        text-decoration: none;
        transition: color 0.12s ease;
    }
    .cf-breadcrumb a:hover { color: var(--teal-dark); }
    .cf-breadcrumb .cf-sep { color: var(--content-border); }
    .cf-breadcrumb .cf-current { font-weight: 600; color: var(--content-text); }

    .cf-card {
        background: var(--content-surface);
        border: 1px solid var(--content-border);
        border-radius: 8px;
        overflow: hidden;
    }

    .cf-card-head {
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid var(--content-border);
    }
    .cf-card-title {
        font-family: 'poppins', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.3;
        color: var(--content-text);
        margin: 0;
    }
    .cf-card-sub {
        font-family: 'poppins', sans-serif;
        font-size: 0.8125rem;
        line-height: 1.4;
        color: var(--content-text-soft);
        margin: 0.35rem 0 0;
    }

    .cf-card-body {
        padding: 1.75rem;
    }

    .cf-field { margin-bottom: 1.5rem; }
    .cf-field:last-child { margin-bottom: 0; }

    .cf-field label {
        display: block;
        font-family: 'poppins', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        line-height: 1.4;
        color: var(--content-text);
        margin-bottom: 0.55rem;
    }

    .cf-field input,
    .cf-field textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-family: 'poppins', sans-serif;
        line-height: 1.5;
        color: var(--content-text);
        background: var(--content-bg);
        border: 1px solid var(--content-border);
        border-radius: 6px;
    }

    .cf-field textarea {
        resize: vertical;
        min-height: 96px;
    }

    .cf-field input:focus-visible,
    .cf-field textarea:focus-visible {
        outline: none;
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(14, 124, 123, 0.15);
    }

    .cf-error {
        color: var(--danger);
        font-size: 0.78rem;
        line-height: 1.4;
        margin: 0.45rem 0 0;
    }

    .cf-actions {
        display: flex;
        gap: 0.75rem;
        padding: 1.5rem 1.75rem;
        border-top: 1px solid var(--content-border);
    }

    .cf-submit {
        background: var(--teal);
        color: #fff;
        border: none;
        padding: 0.7rem 1.4rem;
        border-radius: 6px;
        font-family: 'poppins', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.4;
        cursor: pointer;
        transition: background 0.12s ease;
    }
    .cf-submit:hover { background: var(--teal-dark); }
    .cf-submit:active { transform: scale(0.98); }

    .cf-cancel {
        padding: 0.7rem 1.4rem;
        border-radius: 6px;
        font-family: 'poppins', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.4;
        color: var(--content-text-soft);
        border: 1px solid var(--content-border);
        background: transparent;
        text-decoration: none;
        transition: color 0.12s ease, border-color 0.12s ease;
    }
    .cf-cancel:hover { color: var(--content-text); border-color: var(--teal); }

    @media (max-width: 640px) {
        .cf-card-head, .cf-card-body, .cf-actions { padding-left: 1.25rem; padding-right: 1.25rem; }
        .cf-actions { flex-direction: column-reverse; }
        .cf-actions .cf-submit,
        .cf-actions .cf-cancel { width: 100%; text-align: center; }
    }
</style>

<div class="cf-breadcrumb">
    <a href="{{ route('category-master.index') }}">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" width="15" height="15">
            <path d="M12.5 4.5L6 10l6.5 5.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Master Kategori
    </a>
    <span class="cf-sep">/</span>
    <span class="cf-current">{{ $category ? 'Edit Kategori' : 'Tambah Kategori' }}</span>
</div>

<div class="cf-card">
    <div class="cf-card-head">
        <h3 class="cf-card-title">{{ $category ? 'Edit Kategori Produk' : 'Tambah Kategori Baru' }}</h3>
        <p class="cf-card-sub">
            @if ($category)
                Perubahan akan langsung berlaku pada kategori ini.
            @else
                Kategori baru akan langsung tersedia untuk dipilih di Product Master.
            @endif
        </p>
    </div>

    <form method="POST" action="{{ $action }}">
        @csrf
        @if ($method === 'PUT')
            @method('PUT')
        @endif

        <div class="cf-card-body">
            <div class="cf-field">
                <label for="name">Nama Kategori</label>
                <input type="text" id="name" name="name"
                    value="{{ old('name', $category->name ?? '') }}"
                    placeholder="Contoh: Antibiotik" required>
                @error('name') <p class="cf-error">{{ $message }}</p> @enderror
            </div>

            <div class="cf-field">
                <label for="description">Deskripsi (opsional)</label>
                <textarea id="description" name="description" rows="3"
                    placeholder="Deskripsi singkat kategori">{{ old('description', $category->description ?? '') }}</textarea>
                @error('description') <p class="cf-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="cf-actions">
            <button type="submit" class="cf-submit">{{ $submitLabel }}</button>
            <a href="{{ route('category-master.index') }}" class="cf-cancel">Batal</a>
        </div>
    </form>
</div>
