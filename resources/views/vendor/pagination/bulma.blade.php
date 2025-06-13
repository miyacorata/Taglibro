<nav class="pagination is-centered is-small" role="navigation" aria-label="pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <a class="pagination-previous" disabled>
            <i class="fa-solid fa-chevron-left"></i>
        </a>
    @else
        <a class="pagination-previous" href="{{ $paginator->previousPageUrl() }}">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a class="pagination-next" href="{{ $paginator->nextPageUrl() }}">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
    @else
        <a class="pagination-next" disabled>
            <i class="fa-solid fa-chevron-right"></i>
        </a>
    @endif

    <ul class="pagination-list">
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li><span class="pagination-ellipsis">&hellip;</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><a class="pagination-link is-current">{{ $page }}</a></li>
                    @else
                        <li><a class="pagination-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
    </ul>
</nav>
