<style>
    .pf-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'poppins', sans-serif;
        font-size: 0.85rem;
        color: var(--content-text-soft);
        margin-bottom: 1.25rem;
    }
    .pf-breadcrumb a {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--content-text-soft);
        text-decoration: none;
        transition: color 0.12s ease;
    }
    .pf-breadcrumb a:hover { color: var(--teal-dark); }
    .pf-breadcrumb .pf-sep { color: var(--content-border); }
    .pf-breadcrumb .pf-current { font-weight: 600; color: var(--content-text); }

    .pf-card {
        background: var(--content-surface);
        border: 1px solid var(--content-border);
        border-radius: 8px;
        overflow: hidden;
    }

    .pf-card-head {
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid var(--content-border);
    }
    .pf-card-title {
        font-family: 'poppins', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.3;
        color: var(--content-text);
        margin: 0;
    }
    .pf-card-sub {
        font-family: 'poppins', sans-serif;
        font-size: 0.8125rem;
        line-height: 1.4;
        color: var(--content-text-soft);
        margin: 0.35rem 0 0;
    }

    .pf-card-body {
        padding: 1.75rem;
    }

    .pf-row {
        display: flex;
        gap: 1.25rem;
    }
    .pf-row .pf-field { flex: 1; min-width: 0; }

    .pf-field { margin-bottom: 1.5rem; }
    .pf-field:last-child { margin-bottom: 0; }

    .pf-field label {
        display: block;
        font-family: 'poppins', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        line-height: 1.4;
        color: var(--content-text);
        margin-bottom: 0.55rem;
    }

    .pf-field input,
    .pf-field textarea,
    .pf-field select {
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

    .pf-field select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 20 20' fill='none' stroke='%23808a86' stroke-width='1.8'%3E%3Cpath d='M5 7.5L10 12.5L15 7.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.9rem center;
        padding-right: 2.5rem;
    }

    .pf-field textarea {
        resize: vertical;
        min-height: 96px;
    }

    .pf-field input:focus-visible,
    .pf-field textarea:focus-visible,
    .pf-field select:focus-visible {
        outline: none;
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(14, 124, 123, 0.15);
    }

    .pf-error {
        color: var(--danger);
        font-size: 0.78rem;
        line-height: 1.4;
        margin: 0.45rem 0 0;
    }

    .pf-actions {
        display: flex;
        gap: 0.75rem;
        padding: 1.5rem 1.75rem;
        border-top: 1px solid var(--content-border);
    }

    .pf-submit {
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
    .pf-submit:hover { background: var(--teal-dark); }
    .pf-submit:active { transform: scale(0.98); }

    .pf-cancel {
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
    .pf-cancel:hover { color: var(--content-text); border-color: var(--teal); }

    @media (max-width: 640px) {
        .pf-card-head, .pf-card-body, .pf-actions { padding-left: 1.25rem; padding-right: 1.25rem; }
        .pf-row { flex-direction: column; gap: 0; }
        .pf-actions { flex-direction: column-reverse; }
        .pf-actions .pf-submit,
        .pf-actions .pf-cancel { width: 100%; text-align: center; }
    }
</style>

<div class="pf-breadcrumb">
    <a href="{{ route('product-master.index') }}">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" width="15" height="15">
            <path d="M12.5 4.5L6 10l6.5 5.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Product Master
    </a>
    <span class="pf-sep">/</span>
    <span class="pf-current">{{ $product ? 'Edit Produk' : 'Tambah Produk' }}</span>
</div>

<div class="pf-card">
    <div class="pf-card-head">
        <h3 class="pf-card-title">{{ $product ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
        <p class="pf-card-sub">
            @if ($product)
                Perubahan akan langsung berlaku pada produk ini.
            @else
                Produk baru akan langsung tersedia di sistem QA.
            @endif
        </p>
    </div>

    <form method="POST" action="{{ $action }}">
        @csrf
        @if ($method === 'PUT')
            @method('PUT')
        @endif

        <div class="pf-card-body">
            <div class="pf-row">
                <div class="pf-field">
                    <label for="product_code">Product Code</label>
                    <input type="text" id="product_code" name="product_code" value="{{ old('product_code', $product->product_code ?? '') }}" placeholder="Contoh: PRD-0053" required>
                    @error('product_code') <p class="pf-error">{{ $message }}</p> @enderror
                </div>
                <div class="pf-field">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active" @selected(old('status', $product->status ?? 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', $product->status ?? '') === 'inactive')>Inactive</option>
                    </select>
                    @error('status') <p class="pf-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pf-field">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="Contoh: Amoxicillin 500mg Kapsul" required>
                @error('name') <p class="pf-error">{{ $message }}</p> @enderror
            </div>

            <div class="pf-field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="pf-error">{{ $message }}</p> @enderror
            </div>

            <div class="pf-field">
                <label for="description">Deskripsi (opsional)</label>
                <textarea id="description" name="description" rows="3" placeholder="Deskripsi singkat produk">{{ old('description', $product->description ?? '') }}</textarea>
                @error('description') <p class="pf-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pf-actions">
            <button type="submit" class="pf-submit">{{ $submitLabel }}</button>
            <a href="{{ route('product-master.index') }}" class="pf-cancel">Batal</a>
        </div>
    </form>
</div>
