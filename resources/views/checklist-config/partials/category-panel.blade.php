{{-- ================= LEFT: KATEGORI EVALUASI ================= --}}
<div class="ccfg-panel ccfg-panel-categories">
    <div class="ccfg-panel-header">
        <h3>Kategori Evaluasi</h3>
        <button type="button" class="ccfg-btn-primary" data-modal-target="modal-add-category"
            @if (!$selectedLine) disabled @endif>
            <svg class="ccfg-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 4v12M4 10h12"/></svg>
            Tambah
        </button>
    </div>

    <div class="ccfg-category-list">
        @forelse ($categories as $cat)
            <div class="ccfg-category-item {{ $selectedCategory && $selectedCategory->id === $cat->id ? 'is-active' : '' }}">
                <a href="{{ route('checklist-config.index', [$selectedLine, $cat]) }}" class="ccfg-category-link">
                    {{ $cat->code }}. {{ $cat->name }}
                </a>
                <div class="ccfg-category-actions">
                    <button type="button" class="ccfg-icon-btn"
                        data-modal-target="modal-edit-category"
                        data-action="{{ route('checklist-categories.update', $cat) }}"
                        data-code="{{ $cat->code }}"
                        data-name="{{ $cat->name }}"
                        aria-label="Edit kategori">
                        <svg class="ccfg-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12.9 3.9l3.2 3.2L6.5 16.7 3 17.5l.8-3.5 9.1-9.1z"/></svg>
                    </button>
                    <form action="{{ route('checklist-categories.destroy', $cat) }}" method="POST"
                        class="ccfg-delete-form" data-confirm-message="Hapus kategori ini beserta semua pertanyaannya?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ccfg-icon-btn ccfg-icon-btn-danger" aria-label="Hapus kategori">
                            <svg class="ccfg-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6h12M8 6V4.5a1 1 0 011-1h2a1 1 0 011 1V6m2 0-.6 10a1.5 1.5 0 01-1.5 1.4H7.1a1.5 1.5 0 01-1.5-1.4L5 6"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="ccfg-empty">
                @if ($selectedLine)
                    Belum ada kategori evaluasi untuk {{ $selectedLine->label }}. Klik "Tambah" untuk membuat kategori pertama.
                @endif
            </p>
        @endforelse
    </div>
</div>
