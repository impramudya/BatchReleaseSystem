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
