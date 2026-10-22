{{-- ================= MODAL: ADD QUESTION / SUB-QUESTION ================= --}}
<div class="ccfg-modal-overlay" id="modal-add-question">
    <div class="ccfg-modal-box">
        <h3 id="add-question-title">Add Question</h3>
        <p class="ccfg-modal-subtext" id="add-question-context">
            Category: {{ $selectedCategory->code }}. {{ $selectedCategory->name }}
        </p>
        <form action="{{ route('checklist-questions.store', $selectedCategory) }}" method="POST">
            @csrf
            <input type="hidden" id="add-question-parent-id" name="parent_id" value="">
            <div class="ccfg-field">
                <label for="add-question">Question</label>
                <textarea id="add-question" name="question" rows="3" required></textarea>
            </div>
            <div class="ccfg-field" id="wrap-add-question-details" style="display: none;">
                <label for="add-question-details">Bullet Points / List Details (Optional)</label>
                <textarea id="add-question-details" name="details" rows="3" placeholder="Example:&#10;- Document identity (Batch No., Master Production Document No.)&#10;- Document completeness checklist&#10;- Production initials&#10;- Remarks (if any)"></textarea>
                <span style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: block;">Enter bullet points with a hyphen (-) if this question has sub-details. Leave blank if none.</span>
            </div>
            <div class="ccfg-field">
                <label for="add-question-status">Status</label>
                <select id="add-question-status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="ccfg-modal-actions">
                <button type="button" class="ccfg-btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="ccfg-btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
