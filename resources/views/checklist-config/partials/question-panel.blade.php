{{-- ================= RIGHT: QUESTIONS ================= --}}
<div class="ccfg-panel ccfg-panel-questions">
    @if ($selectedCategory)
        <div class="ccfg-panel-header">
            <h3>Questions &mdash; {{ $selectedCategory->code }}. {{ $selectedCategory->name }}</h3>
            <button type="button" class="ccfg-btn-primary"
                data-modal-target="modal-add-question">
                <svg class="ccfg-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 4v12M4 10h12"/></svg>
                Add Question
            </button>
        </div>

        <div class="ccfg-table-wrap">
            <table class="ccfg-table">
                <thead>
                    <tr>
                        <th class="ccfg-col-no">No</th>
                        <th>Question</th>
                        <th class="ccfg-col-status">Status</th>
                        <th class="ccfg-col-action">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($questions as $q)
                        @include('checklist-config.partials.question-row', ['question' => $q, 'depth' => 0, 'number' => $loop->iteration])
                    @empty
                        <tr>
                            <td colspan="4" class="ccfg-empty">No questions found for this category.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @elseif ($selectedLine)
        <p class="ccfg-empty">No categories configured for {{ $selectedLine->label }}. Please add a category in the left panel first.</p>
    @else
        <p class="ccfg-empty">Please select a production line from the sidebar first.</p>
    @endif
</div>
