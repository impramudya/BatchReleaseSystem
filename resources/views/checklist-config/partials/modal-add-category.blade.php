{{-- ================= MODAL: ADD CATEGORY ================= --}}
<div class="ccfg-modal-overlay" id="modal-add-category">
    <div class="ccfg-modal-box">
        <h3>Add Evaluation Category</h3>
        @if ($selectedLine)
            <p class="ccfg-modal-subtext">For: {{ $selectedLine->label }}</p>
        @endif
        <form action="{{ route('checklist-categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="production_line_id" value="{{ $selectedLine->id ?? '' }}">
            <div class="ccfg-field">
                <label for="add-code">Code (unique within this line)</label>
                <input type="text" id="add-code" name="code" maxlength="10" placeholder="E.g., A" required>
            </div>
            <div class="ccfg-field">
                <label for="add-name">Category Name</label>
                <input type="text" id="add-name" name="name" placeholder="E.g., Master Processing Procedure" required>
            </div>
            <div class="ccfg-modal-actions">
                <button type="button" class="ccfg-btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="ccfg-btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
