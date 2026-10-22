@if ($paginator->total() > 0)
    <div class="brp-pager-minimal" role="navigation" aria-label="Pagination Navigation">
        <div class="brp-pager-info">
            Showing <span class="brp-pager-highlight">{{ $paginator->firstItem() ?? 0 }}&ndash;{{ $paginator->lastItem() ?? 0 }}</span> of <span class="brp-pager-highlight">{{ $paginator->total() }}</span>
        </div>

        <div class="brp-pager-controls">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="brp-pager-btn brp-pager-disabled" aria-disabled="true" title="Previous page">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.5 15l-5-5 5-5"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="brp-pager-btn" rel="prev" title="Previous page">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.5 15l-5-5 5-5"/>
                    </svg>
                </a>
            @endif

            {{-- Minimalist Counter: 1 / 3 --}}
            <span class="brp-pager-indicator" title="Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}">
                <span class="brp-pager-curr">{{ $paginator->currentPage() }}</span>
                <span class="brp-pager-sep">/</span>
                <span class="brp-pager-total">{{ $paginator->lastPage() }}</span>
            </span>

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="brp-pager-btn" rel="next" title="Next page">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7.5 5l5 5-5 5"/>
                    </svg>
                </a>
            @else
                <span class="brp-pager-btn brp-pager-disabled" aria-disabled="true" title="Next page">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7.5 5l5 5-5 5"/>
                    </svg>
                </span>
            @endif
        </div>
    </div>
@endif
