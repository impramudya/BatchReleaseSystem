{{-- ================= MODAL: EDIT PERTANYAAN ================= --}}
<div class="ccfg-modal-overlay" id="modal-edit-question">
    <div class="ccfg-modal-box">
        <h3>Edit Pertanyaan</h3>
        <form id="form-edit-question" method="POST">
            @csrf
            @method('PUT')
            <div class="ccfg-field">
                <label for="edit-question">Pertanyaan</label>
                <textarea id="edit-question" name="question" rows="3" required></textarea>
            </div>
            <div class="ccfg-field" id="wrap-edit-question-details">
                <label for="edit-question-details">Poin-poin / Rincian List (Opsional)</label>
                <textarea id="edit-question-details" name="details" rows="3" placeholder="Contoh:&#10;- Identitas dokumen (No.Batch, No. Dokumen Produksi Induk)&#10;- Checklist pemeriksaan kelengkapan dokumen&#10;- Paraf produksi&#10;- Catatan (bila ada)"></textarea>
                <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Masukkan poin-poin bertanda strip (-) jika pertanyaan ini memiliki rincian. Kosongkan jika tidak ada.</span>
            </div>
            <div class="ccfg-field">
                <label for="edit-question-status">Status</label>
                <select id="edit-question-status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="ccfg-modal-actions">
                <button type="button" class="ccfg-btn-secondary" data-modal-close>Batal</button>
                <button type="submit" class="ccfg-btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
