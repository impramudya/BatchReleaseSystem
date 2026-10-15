<div class="cf-breadcrumb">
    <a href="{{ route('category-master.index') }}">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" width="15" height="15">
            <path d="M12.5 4.5L6 10l6.5 5.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Category Master
    </a>
    <span class="cf-sep">/</span>
    <span class="cf-current">{{ $category ? 'Edit Category' : 'Add Category' }}</span>
</div>

<div class="cf-card">
    <div class="cf-card-head">
        <h3 class="cf-card-title">{{ $category ? 'Edit Product Category' : 'Add New Category' }}</h3>
        <p class="cf-card-sub">
            @if ($category)
                Changes will take effect immediately for this category.
            @else
                The new category will be immediately available for selection in Product Master.
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
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name"
                    value="{{ old('name', $category->name ?? '') }}"
                    placeholder="e.g., Antibiotics" required>
                @error('name') <p class="cf-error">{{ $message }}</p> @enderror
            </div>

            <div class="cf-field">
                <label for="description">Description (optional)</label>
                <textarea id="description" name="description" rows="3"
                    placeholder="Brief description of the category">{{ old('description', $category->description ?? '') }}</textarea>
                @error('description') <p class="cf-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="cf-actions">
            <button type="submit" class="cf-submit">{{ $submitLabel }}</button>
            <a href="{{ route('category-master.index') }}" class="cf-cancel">Cancel</a>
        </div>
    </form>
</div>
