@if ($paginator->hasPages())
    <nav class="pagination-nav" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="page-link disabled">Previous</span>
        @else
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Previous</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-link disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @else
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next</a>
        @else
            <span class="page-link disabled">Next</span>
        @endif
    </nav>
@endif
