@if ($paginator->hasPages())
    <div class="custom-pagination-container">
        <ul class="custom-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="mypill-item disabled"><span class="mypill-link">&laquo; Prev</span></li>
            @else
                <li class="mypill-item"><a class="mypill-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Prev</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="mypill-item disabled"><span class="mypill-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="mypill-item active"><span class="mypill-link">{{ $page }}</span></li>
                        @else
                            <li class="mypill-item"><a class="mypill-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="mypill-item"><a class="mypill-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next &raquo;</a></li>
            @else
                <li class="mypill-item disabled"><span class="mypill-link">Next &raquo;</span></li>
            @endif
        </ul>
    </div>
@endif
