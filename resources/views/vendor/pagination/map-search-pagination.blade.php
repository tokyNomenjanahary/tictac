@if ($paginator->hasPages())
<div class="sm-next-prev-btn">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <div class="sm-prev porject-btn-1"><a style="cursor: not-allowed;" href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i> {{ __('searchlisting.prev') }}</a></div>
    @else
    <div class="sm-prev porject-btn-1"><a href="{{ $paginator->previousPageUrl() }}"><i class="fa fa-angle-left" aria-hidden="true"></i> {{ __('searchlisting.prev') }}</a></div>
    @endif
    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <div class="sm-next porject-btn-1"><a href="{{ $paginator->nextPageUrl() }}">{{ __('searchlisting.next') }} <i class="fa fa-angle-right" aria-hidden="true"></i></a></div>
    @else
    <div class="sm-next porject-btn-1"><a style="cursor: not-allowed;" href="javascript:void(0)">{{ __('searchlisting.next') }} <i class="fa fa-angle-right" aria-hidden="true"></i></a></div>
    @endif
</div>
@endif