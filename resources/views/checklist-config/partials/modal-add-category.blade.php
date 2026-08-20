{{-- ================= MODAL: TAMBAH KATEGORI ================= --}}
<div class="ccfg-modal-overlay" id="modal-add-category">
    <div class="ccfg-modal-box">
        <h3>Tambah Kategori Evaluasi</h3>
        @if ($selectedLine)
            <p class="ccfg-modal-subtext">Untuk: {{ $selectedLine->label }}</p>
        @endif
        <form action="{{ route('checklist-categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="production_line_id" value="{{ $selectedLine->id ?? '' }}">
            <div class="ccfg-field">
                <label for="add-code">Kode (unik dalam line ini)</label>
                <input type="text" id="add-code" name="code" maxlength="10" placeholder="Misal: A" required>
            </div>
            <div class="ccfg-field">
                <label for="add-name">Nama Kategori</label>
                <input type="text" id="add-name" name="name" placeholder="Misal: Prosedur Pengolahan Induk" required>
            </div>
            <div class="ccfg-modal-actions">
                <button type="button" class="ccfg-btn-secondary" data-modal-close>Batal</button>
                <button type="submit" class="ccfg-btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
