@php
    $depth = $depth ?? 0;
    $indent = 1.25 + ($depth * 1.5); 
@endphp

<tr class="{{ $depth > 0 ? 'ccfg-row-sub' : '' }}">
    <td class="ccfg-col-no">{{ $depth === 0 ? ($number ?? '') : '' }}</td>
    <td class="ccfg-question-text {{ $depth > 0 ? 'ccfg-sub-question' : '' }}"
        @if ($depth > 0) style="padding-left: {{ $indent }}rem" @endif>
        @php
            $lines = preg_split('/\r\n|\r|\n/', $question->question);
        @endphp

        @foreach ($lines as $line)
            <div class="ccfg-question-line">
                {{ $line }}
            </div>
        @endforeach
    </td>
    <td class="ccfg-col-status">
        <form action="{{ route('checklist-questions.toggle-status', $question) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="ccfg-status-pill ccfg-status-{{ $question->status }}"
                title="Klik untuk ganti status">
                {{ ucfirst($question->status) }}
            </button>
        </form>
    </td>
    <td class="ccfg-col-action">
        <div class="ccfg-row-actions">
            <button type="button" class="ccfg-icon-btn"
                data-modal-target="modal-add-question"
                data-parent-id="{{ $question->id }}"
                data-parent-label="{{ \Illuminate\Support\Str::limit($question->question, 60) }}"
                aria-label="Tambah sub-pertanyaan" title="Tambah sub-pertanyaan">
                <svg class="ccfg-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 4v12M4 10h12"/></svg>
            </button>
            <button type="button" class="ccfg-icon-btn"
                data-modal-target="modal-edit-question"
                data-action="{{ route('checklist-questions.update', $question) }}"
                data-question="{{ $question->question }}"
                data-status="{{ $question->status }}"
                aria-label="Edit pertanyaan">
                <svg class="ccfg-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12.9 3.9l3.2 3.2L6.5 16.7 3 17.5l.8-3.5 9.1-9.1z"/></svg>
            </button>
            <form action="{{ route('checklist-questions.destroy', $question) }}" method="POST"
                class="ccfg-delete-form" data-confirm-message="Hapus pertanyaan ini beserta semua sub-nya?">
                @csrf
                @method('DELETE')
                <button type="submit" class="ccfg-icon-btn ccfg-icon-btn-danger" aria-label="Hapus pertanyaan">
                    <svg class="ccfg-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6h12M8 6V4.5a1 1 0 011-1h2a1 1 0 011 1V6m2 0-.6 10a1.5 1.5 0 01-1.5 1.4H7.1a1.5 1.5 0 01-1.5-1.4L5 6"/></svg>
                </button>
            </form>
        </div>
    </td>
</tr>

@foreach ($question->children as $child)
    @include('checklist-config.partials.question-row', ['question' => $child, 'depth' => $depth + 1])
@endforeach