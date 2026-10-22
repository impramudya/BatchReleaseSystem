{{-- ================= MODAL: TAMBAH PERTANYAAN / SUB-PERTANYAAN ================= --}}
<div class="ccfg-modal-overlay" id="modal-add-question">
    <div class="ccfg-modal-box">
        <h3 id="add-question-title">Tambah Pertanyaan</h3>
        <p class="ccfg-modal-subtext" id="add-question-context">
            Kategori: {{ $selectedCategory->code }}. {{ $selectedCategory->name }}
        </p>
        <form action="{{ route('checklist-questions.store', $selectedCategory) }}" method="POST">
            @csrf
            <input type="hidden" id="add-question-parent-id" name="parent_id" value="">
            <div class="ccfg-field">
                <label for="add-question">Pertanyaan</label>
                <textarea id="add-question" name="question" rows="3" required></textarea>
            </div>
            <div class="ccfg-field" id="wrap-add-question-details" style="display: none;">
                <label for="add-question-details">Poin-poin / Rincian List (Opsional)</label>
                <textarea id="add-question-details" name="details" rows="3" placeholder="Contoh:&#10;- Identitas dokumen (No.Batch, No. Dokumen Produksi Induk)&#10;- Checklist pemeriksaan kelengkapan dokumen&#10;- Paraf produksi&#10;- Catatan (bila ada)"></textarea>
                <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Masukkan poin-poin bertanda strip (-) jika pertanyaan ini memiliki rincian. Kosongkan jika tidak ada.</span>
            </div>
            <div class="ccfg-field">
                <label for="add-question-status">Status</label>
                <select id="add-question-status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="ccfg-modal-actions">
                <button type="button" class="ccfg-btn-secondary" data-modal-close>Batal</button>
                <button type="submit" class="ccfg-btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
