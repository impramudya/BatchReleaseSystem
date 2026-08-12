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
