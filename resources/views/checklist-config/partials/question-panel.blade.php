{{-- ================= RIGHT: PERTANYAAN ================= --}}
<div class="ccfg-panel ccfg-panel-questions">
    @if ($selectedCategory)
        <div class="ccfg-panel-header">
            <h3>Pertanyaan &mdash; {{ $selectedCategory->code }}. {{ $selectedCategory->name }}</h3>
            <button type="button" class="ccfg-btn-primary" data-modal-target="modal-add-question">
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
                        <tr>
                            <td class="ccfg-col-no">{{ $loop->iteration }}</td>
                            <td>{{ $q->question }}</td>
                            <td class="ccfg-col-status">
                                <form action="{{ route('checklist-questions.toggle-status', $q) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="ccfg-status-pill ccfg-status-{{ $q->status }}"
                                        title="Klik untuk ganti status">
                                        {{ ucfirst($q->status) }}
                                    </button>
                                </form>
                            </td>
                            <td class="ccfg-col-action">
                                <div class="ccfg-row-actions">
                                    <button type="button" class="ccfg-icon-btn"
                                        data-modal-target="modal-edit-question"
                                        data-action="{{ route('checklist-questions.update', $q) }}"
                                        data-question="{{ $q->question }}"
                                        data-status="{{ $q->status }}"
                                        aria-label="Edit pertanyaan">
                                        <svg class="ccfg-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12.9 3.9l3.2 3.2L6.5 16.7 3 17.5l.8-3.5 9.1-9.1z"/></svg>
                                    </button>
                                    <form action="{{ route('checklist-questions.destroy', $q) }}" method="POST"
                                        class="ccfg-delete-form" data-confirm-message="Hapus pertanyaan ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ccfg-icon-btn ccfg-icon-btn-danger" aria-label="Hapus pertanyaan">
                                            <svg class="ccfg-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6h12M8 6V4.5a1 1 0 011-1h2a1 1 0 011 1V6m2 0-.6 10a1.5 1.5 0 01-1.5 1.4H7.1a1.5 1.5 0 01-1.5-1.4L5 6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
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
