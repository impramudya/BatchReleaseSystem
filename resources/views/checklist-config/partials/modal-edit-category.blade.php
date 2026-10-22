{{-- ================= MODAL: EDIT CATEGORY ================= --}}
<div class="ccfg-modal-overlay" id="modal-edit-category">
    <div class="ccfg-modal-box">
        <h3>Edit Evaluation Category</h3>
        <form id="form-edit-category" method="POST">
            @csrf
            @method('PUT')
            <div class="ccfg-field">
                <label for="edit-code">Code (unique)</label>
                <input type="text" id="edit-code" name="code" maxlength="10" required>
            </div>
            <div class="ccfg-field">
                <label for="edit-name">Category Name</label>
                <input type="text" id="edit-name" name="name" required>
            </div>
            <div class="ccfg-modal-actions">
                <button type="button" class="ccfg-btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="ccfg-btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
