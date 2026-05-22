@if ($paginator->hasPages())
<nav style="display:flex;justify-content:center;margin-top:1.5rem;">
    <ul style="display:flex;gap:.3rem;list-style:none;padding:0;margin:0;flex-wrap:wrap;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li><span style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;color:#aaa;border:1px solid #e5e7eb;cursor:default;">&laquo;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;text-decoration:none;color:#2d2d2d;border:1px solid #e5e7eb;">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li><span style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;border:1px solid #e5e7eb;">{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><span style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;background:#0a0a0a;color:#fff;border:1px solid #0a0a0a;">{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}" style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;text-decoration:none;color:#2d2d2d;border:1px solid #e5e7eb;">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;text-decoration:none;color:#2d2d2d;border:1px solid #e5e7eb;">&raquo;</a></li>
        @else
            <li><span style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;color:#aaa;border:1px solid #e5e7eb;cursor:default;">&raquo;</span></li>
        @endif
    </ul>
</nav>
@endif
