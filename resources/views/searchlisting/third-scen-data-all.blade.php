<!-- <div> -->
<div class="rightside-filter-hdr">
    @if(isset($ads))
        <h1 class="properties_count d-flex justify-content-center flex-wrap">
            <span class="total bold">{{$ads->total()}}&nbsp;</span> 
            <span class="scenario bolder underline">{{ __('searchlisting.scenario_' . $scenario_id) }}&nbsp;</span>
            
                {{-- <span class="text lighter">{{ __('searchlisting.found') }}&nbsp;{{ __('searchlisting.trouve_a') }}&nbsp;</span>  --}}
                {{-- <span class="address bolder underline">{{urldecode($address)}}</span>  --}}
            @if(!empty($itemLat)) {{ __('searchlisting.trouve_a') }}
            @php  $i=count($itemLat) @endphp
                @forelse ($itemLat as $item)
                @php
                --$i;
                if($i)
                    $end=' ; ';
                else
                    $end=' ';
                @endphp
                {{urldecode($item['add']).$end}}
                @empty
                @if(!empty($address)){{urldecode($address)}}@endif
                @endforelse
            @endif
        </h1>
    @endif
</div>


<div id="listing_container">
	@if(isset($ads))
    @include('searchlisting.third-scen-data')
    @endif
</div>
