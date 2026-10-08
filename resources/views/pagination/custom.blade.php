@if ($paginator->hasPages())
    <nav class="custom-pagination" role="navigation" aria-label="Paginação">
        @if ($paginator->onFirstPage())
            <span class="pagination-link pagination-disabled" aria-disabled="true" aria-label="Anterior">‹</span>
        @else
            <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Anterior">‹</a>
        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pagination-ellipsis">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pagination-link pagination-current" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-link" href="{{ $url }}" aria-label="Ir para página {{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Próxima">›</a>
        @else
            <span class="pagination-link pagination-disabled" aria-disabled="true" aria-label="Próxima">›</span>
        @endif
    </nav>
@endif
