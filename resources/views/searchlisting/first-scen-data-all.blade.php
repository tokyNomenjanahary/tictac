<div class="rightside-filter-hdr">
	@if(isset($ads))
    <h1 class="properties_count d-flex justify-content-center flex-wrap">
        <span class="total bold">{{$ads->total()}}&nbsp;</span> 
        <span class="scenario bolder underline">{{ __('searchlisting.scenario_' . $scenario_id) }}&nbsp;</span>
        @if(!empty($address))
            <span class="text lighter">{{ __('searchlisting.found') }}&nbsp;{{ __('searchlisting.trouve_a') }}&nbsp;</span> 
            <span class="address bolder underline">{{urldecode($address)}}</span> 
        @endif
    </h1>
    @endif
</div>
<div id="listing_container">
	@if(isset($ads))
    @include('searchlisting.first-scen-data')
    @endif
</div>