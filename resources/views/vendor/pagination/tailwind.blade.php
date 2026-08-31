@if ($paginator->hasPages())
    <nav class="brp-pager" role="navigation" aria-label="Pagination Navigation">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="brp-pager-link brp-pager-disabled">&laquo; Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="brp-pager-link">&laquo; Previous</a>
        @endif

        {{-- Nomor halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="brp-pager-link brp-pager-dots">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="brp-pager-link brp-pager-active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="brp-pager-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="brp-pager-link">Next &raquo;</a>
        @else
            <span class="brp-pager-link brp-pager-disabled">Next &raquo;</span>
        @endif

    </nav>

    <p class="brp-pager-summary">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </p>
@endif
