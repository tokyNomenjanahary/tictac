<div class="rightside-filter-hdr">
    @if(isset($ads))
    <h1 class="properties_count"><span id="span-count">{{$ads->total()}}</span> {{ __('searchlisting.scenario_' . $scenario_id) }} @if(!empty($itemLat)) {{ __('searchlisting.trouve_a') }}
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
        @endif</h1>
    @endif
    <div class="row">
        <div id="search_type" class="col-lg-5">
        </div>
        <div class="col-xs-12 col-lg-7 listing-grid-option-outer text-right">
            <div class="gird-options-icon-outer">
@if( $grille == 1)
<span id= "grille-normale" class="listing-view-icon option-mobile active"><i class="fa-option fa fa-bars" aria-hidden="true"></i>{{__("searchlisting.liste")}}</span>
 <!-- <span class="grid-view-icon option-mobile search-option-middle"><i class="fa-option fa fa-th" aria-hidden="true"></i>{{__("searchlisting.grid")}}</span> -->
<span id="grille-classe" class="grid-view-icon option-mobile listing-view-icon-map"><i class="fa-option fa fa-th" aria-hidden="true"></i>{{__("searchlisting.grid")}}</span>
     
@endif
@if( $grille == 2)
<span id= "grille-normale" class="listing-view-ico option-mobile"><i class="fa-option fa fa-bars" aria-hidden="true"></i>{{__("searchlisting.liste")}}</span>
<span id="grille-classe" class="listing-view-icon grid-view-icon option-mobile listing-view-icon-map  active"><i class="fa-option fa fa-th" aria-hidden="true"></i>{{__("searchlisting.grid")}}</span>
               
 @endif
                    <select id="grille-data" class="" style="display:none;">
                                    <option value="0"></option>
                                    <option @if(isset($grille) && $grille == 1) selected @endif value="1">1</option>
                                    <option @if(isset($grille) && $grille == 2) selected @endif value="2">2</option>
                                  </select>
    <span class="listing-view-ico listing-view-icon-map"><a href="{{enleveInscriptionPath() . '?map=true'}}@if($id!=0)&id={{$id}}@endif"><i class="fa-option fas fa-map-marked-alt" aria-hidden="true"></i>{{__("searchlisting.carte")}}</a></span>
            </div>
            <div class="custom-selectbx search-dropdown-filter search-dropdown-filter-top ">
                <select id="sort" name="sort" class="select-box">
                    <option value="1">{{ __('searchlisting.recent') }}</option>               
                    <option value="4">{{ __('searchlisting.ancienne') }}</option>
                    <option value="2">{{ __('searchlisting.increase_rent') }}</option>
                    <option value="3">{{ __('searchlisting.decrease_rent') }}</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div id="listing_container">
    @if(isset($ads))
    @include('searchlisting.basic.third-scen-data')
    @endif
</div>


<style>
    html,
body {
  min-height: 100%;
  margin: 0;
}

body {
  padding: 30px;
  background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  box-sizing: border-box;
}

.select-box {

  position: relative;
    display: block;
    background-color: #fffefe;
    border-radius: 2px;
    padding: 7px;
    width: 100%;
    margin: 0px auto;
    font-family: 'Open Sans', 'Helvetica Neue', 'Segoe UI', 'Calibri', 'Arial', sans-serif;
    font-size: 15px;
    color: #584949;
    border: none;
  
  @media (min-width: 768px) {
    width: 70%;
  }
  
  @media (min-width: 992px) {
    width: 50%;
  }
  
  @media (min-width: 1200px) {
    width: 30%;
  }
  
@keyframes HideList {
  from {
    transform: scaleY(1);
  }
  to {
    transform: scaleY(0);
  }
}
</style>