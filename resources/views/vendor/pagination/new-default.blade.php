@if ($paginator->hasPages())
<?php
// config
$link_limit = 5; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if ($paginator->lastPage() > 1)
<div class="pagination-bx">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            <li><a style="cursor: not-allowed;" href="javascript:void(0)"><img class="icon-prev" src="/img/navigation-arrow-left.png" ></a></li>
            @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><img class="icon-prev" src="/img/navigation-arrow-left.png" ></a></li>
            @endif
            <?php
                $half_total_links = floor($link_limit / 2);
                $from = $paginator->currentPage() - $half_total_links;
                $to = $paginator->currentPage() + $half_total_links;
                if ($paginator->currentPage() < $half_total_links) {
                   $to += $half_total_links - $paginator->currentPage();
                }
                if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                    $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
                }
            ?>
            @if(($from - 1) >= 1)
            <li><a href="{{ $paginator->url(1) }}">1</a></li>
            @endif
            @if($from > 1)
            <?php $from = $from+1;?>
            <li class="disabled so-on-dot"><a style="cursor: not-allowed;" href="javascript:void(0)">...</a></li>
            @endif
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)

                @if ($from <= $i && $i <= $to)
                    @if ($i == $paginator->currentPage())
                        <li class="active"><a style="cursor: not-allowed;" href="javascript:void(0)">{{ $i }}</a></li>
                    @else
                        <li><a href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endif
            @endfor
            @if ($paginator->hasMorePages())
                @if(($to + 1) == $paginator->lastPage())
                <li><a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
                @elseif($to < $paginator->lastPage())
                <li class="disabled so-on-dot"><a style="cursor: not-allowed;" href="javascript:void(0)">...</a></li>
                @endif
                @if(($to + 1)< $paginator->lastPage())
                <li><a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
                @endif
            @endif
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next"><img class="icon-next" src="/img/navigation-arrow-right.png" ></a></li>
            @else
            <li><a style="cursor: not-allowed;" href="javascript:void(0)"><img class="icon-next" src="/img/navigation-arrow-right.png" ></a></li>
            @endif 
        </ul>
</div>
@endif
@endif
