{{-- ================= MODAL: EDIT KATEGORI ================= --}}
<div class="ccfg-modal-overlay" id="modal-edit-category">
    <div class="ccfg-modal-box">
        <h3>Edit Kategori Evaluasi</h3>
        <form id="form-edit-category" method="POST">
            @csrf
            @method('PUT')
            <div class="ccfg-field">
                <label for="edit-code">Kode (unik)</label>
                <input type="text" id="edit-code" name="code" maxlength="10" required>
            </div>
            <div class="ccfg-field">
                <label for="edit-name">Nama Kategori</label>
                <input type="text" id="edit-name" name="name" required>
            </div>
            <div class="ccfg-modal-actions">
                <button type="button" class="ccfg-btn-secondary" data-modal-close>Batal</button>
                <button type="submit" class="ccfg-btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
