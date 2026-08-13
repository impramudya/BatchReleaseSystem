{{-- ================= MODAL: TAMBAH PERTANYAAN ================= --}}
{{-- Partial ini hanya di-include kalau $selectedCategory ada (lihat index.blade.php) --}}
<div class="ccfg-modal-overlay" id="modal-add-question">
    <div class="ccfg-modal-box">
        <h3>Tambah Pertanyaan</h3>
        <p class="ccfg-modal-subtext">Kategori: {{ $selectedCategory->code }}. {{ $selectedCategory->name }}</p>
        <form action="{{ route('checklist-questions.store', $selectedCategory) }}" method="POST">
            @csrf
            <div class="ccfg-field">
                <label for="add-question">Pertanyaan</label>
                <textarea id="add-question" name="question" rows="3" required></textarea>
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
