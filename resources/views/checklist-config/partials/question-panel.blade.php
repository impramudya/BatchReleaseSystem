{{-- ================= RIGHT: PERTANYAAN ================= --}}
<div class="ccfg-panel ccfg-panel-questions">
    @if ($selectedCategory)
        <div class="ccfg-panel-header">
            <h3>Pertanyaan &mdash; {{ $selectedCategory->code }}. {{ $selectedCategory->name }}</h3>
            <button type="button" class="ccfg-btn-primary"
                data-modal-target="modal-add-question">
                <svg class="ccfg-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 4v12M4 10h12"/></svg>
                Tambah Pertanyaan
            </button>
        </div>

        <div class="ccfg-table-wrap">
            <table class="ccfg-table">
                <thead>
                    <tr>
                        <th class="ccfg-col-no">No</th>
                        <th>Pertanyaan</th>
                        <th class="ccfg-col-status">Status</th>
                        <th class="ccfg-col-action">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($questions as $q)
                        @include('checklist-config.partials.question-row', ['question' => $q, 'depth' => 0, 'number' => $loop->iteration])
                    @empty
                        <tr>
                            <td colspan="4" class="ccfg-empty">Belum ada pertanyaan untuk kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <p class="ccfg-empty">Silakan tambahkan kategori evaluasi terlebih dahulu di panel sebelah kiri.</p>
    @endif
</div>
