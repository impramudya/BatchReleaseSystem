{{-- ================= MODAL: EDIT QUESTION ================= --}}
<div class="ccfg-modal-overlay" id="modal-edit-question">
    <div class="ccfg-modal-box">
        <h3>Edit Question</h3>
        <form id="form-edit-question" method="POST">
            @csrf
            @method('PUT')
            <div class="ccfg-field">
                <label for="edit-question">Question</label>
                <textarea id="edit-question" name="question" rows="3" required></textarea>
            </div>
            <div class="ccfg-field" id="wrap-edit-question-details">
                <label for="edit-question-details">Bullet Points / List Details (Optional)</label>
                <textarea id="edit-question-details" name="details" rows="3" placeholder="Example:&#10;- Document identity (Batch No., Master Production Document No.)&#10;- Document completeness checklist&#10;- Production initials&#10;- Remarks (if any)"></textarea>
                <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Enter bullet points with a hyphen (-) if this question has sub-details. Leave blank if none.</span>
            </div>
            <div class="ccfg-field">
                <label for="edit-question-status">Status</label>
                <select id="edit-question-status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="ccfg-modal-actions">
                <button type="button" class="ccfg-btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="ccfg-btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
