<div class="rightside-filter-hdr">
    @if(isset($ads))
    <h1 class="properties_count">{{$ads->total()}} {{ __('searchlisting.scenario_' . $scenario_id) }} @if(!empty($address)) {{ __('searchlisting.trouve_a') }} {{$address}} @endif</h1>
    @endif
    <div class="row">
        <div class="col-xs-12 listing-grid-option-outer text-right">
            <div class="gird-options-icon-outer">
                <span class="listing-view-icon option-mobile active"><i class="fa-option fa fa-bars" aria-hidden="true"></i>{{__("searchlisting.liste")}}</span>
                <span class="grid-view-icon option-mobile search-option-right"><i class="fa-option fa fa-th" aria-hidden="true"></i>{{__("searchlisting.grid")}}</span>
            </div>
            <div class="custom-selectbx search-dropdown-filter search-dropdown-filter-top">
                <select id="sort" name="sort" class="selectpicker">
                    <option value="1">{{ __('searchlisting.recent') }}</option>
                    <option value="2">{{ __('searchlisting.increase_rent') }}</option>
                    <option value="3">{{ __('searchlisting.decrease_rent') }}</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div id="listing_container">
    @if(isset($ads))
    @include('searchlisting.basic.first-scen-data')
    @endif
</div>
