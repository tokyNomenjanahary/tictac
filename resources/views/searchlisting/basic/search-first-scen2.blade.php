@extends( ($layout == 'inner') ? 'layouts.appinner' : 'layouts.app' )

<!-- Push a script dynamically from a view -->
@push('styles')
        <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
        <link href="{{ asset('css/filter.css') }}" rel="stylesheet">
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
	<script src="{{ asset('js/visit.js') }}"></script>
	<script src="{{ asset('js/social_filter.js') }}"></script>
    <script src="{{ asset('js/searchroommate.js') }}"></script>
	<script src="{{ asset('js/jquery.timepicker.js') }}"></script>
	<script src="{{ asset('js/facility_search.js') }}"></script>
	{{fb_pixel_search()}}
@endpush

@section('content')
<section class="inner-page-wrap">
    <div class="row-fluid inner-filter-wrap col-xs-12 col-sm-12 col-md-12 no-gutter" align="center">
        <form class="form-inline no-gutter col-lg-12">
            <div class="form-group mx-sm-3 mb-2 col-lg-1 col-md-2 col-sm-5 col-xs-12 hidden-xs filtre-not-important-md filtre-not-important-sm filtre-not-important-sm-1">
                <input type="text" class="form-control custum-element-filter input-sm" name="min_rent" id="min_rent" placeholder="{{__('searchlisting.min_loyer')}}">
            </div>
            <div class="form-group mx-sm-3 mb-2 col-lg-1 col-md-2 col-sm-5 col-xs-12 hidden-xs filtre-not-important-md filtre-not-important-sm filtre-not-important-sm-1">   
                <input type="text" class="form-control custum-element-filter input-sm" name="max_rent" id="max_rent" placeholder="{{__('searchlisting.max_loyer')}}">
            </div>
            <div class="form-group mx-sm-3 mb-2 col-lg-1 col-md-2 col-sm-6 col-xs-12 hidden-sm hidden-xs filtre-not-important-md filtre-not-important-sm">
                <input type="text" class="form-control custum-element-filter input-sm" name="min_area" id="min_area" placeholder="{{__('searchlisting.min_surface')}}">
            </div>
            <div class="form-group mx-sm-3 mb-2 col-lg-1 col-md-2 col-sm-6 col-xs-12 hidden-sm hidden-xs filtre-not-important-md filtre-not-important-sm">   
                <input type="text" class="form-control custum-element-filter input-sm" name="max_area" id="max_area" placeholder="{{__('searchlisting.max_surface')}}">
            </div>
            <div class="form-group mx-sm-3 mb-2 col-lg-1 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs filtre-not-important-md filtre-not-important-md-3 filtre-not-important-sm">
                <select id="select_bedroom" name="select_bedroom" class="form-control input-sm custum-element-filter ">
                    <option selected value="">{{__('searchlisting.bedroom')}}</option>
                    <option value="0">{{ __('searchlisting.any') }}</option>                           
                    <option value="1">1+</option>
                    <option value="2">2+</option>
                    <option value="3">3+</option>                           
                </select>
            </div>
            <div class="form-group mx-sm-3 mb-2 col-lg-1 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-md hidden-xs filtre-not-important">
                <select id="select_bathroom" name="select_bathroom" class="form-control input-sm custum-element-filter">
                    <option selected  value="">{{__('searchlisting.bathroom')}}</option>
                    <option value="0">{{ __('searchlisting.any') }}</option>                           
                    <option value="1">1+</option>
                    <option value="2">2+</option>
                    <option value="3">3+</option>                          
                </select>
            </div>
            <div class="form-group mx-sm-3 mb-2 col-lg-2 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-md hidden-xs filtre-not-important">
                <select multiple id="select_type_propriete" name="select_type_propriete" class="selectpicker form-control custum-element-filter">
                     <option selected  value="">{{ __('searchlisting.property_type') }}</option>
                    @if(!empty($propertyTypes) && count($propertyTypes) > 0)
                    @foreach($propertyTypes as $propertyType)
                    <option value="{{$propertyType->id}}">{{traduct_info_bdd($propertyType->property_type)}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group mx-sm-3 mb-2 col-lg-2 col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-md hidden-xs filtre-not-important">
                <select id="select_kitchen" name="select_kitchen" class="form-control input-sm custum-element-filter">
                    <option selected  value="">{{__('searchlisting.separat_kitchen')}}</option>
                    <option value="0">{{__('searchlisting.no')}}</option>                           
                    <option value="1">{{__('searchlisting.yes')}}</option>
                </select>
            </div>
            <div class="form-group mx-sm-3 mb-2 col-lg-1 col-md-6 col-sm-6 col-xs-12 hidden-sm hidden-md hidden-xs filtre-not-important">
                <select id="select_furnished" name="select_furnished" class="form-control input-sm custum-element-filter">
                    <option selected  value="">{{__('searchlisting.furnished')}}</option>
                    <option value="0">{{__('searchlisting.no')}}</option>                           
                    <option value="1">{{__('searchlisting.yes')}}</option>
                </select>
            </div>
             @if($scenario_id == 1) 
            <div class="form-group mx-sm-3 mb-2 col-lg-1 col-md-6 col-sm-6 col-xs-12 hidden-sm hidden-md hidden-xs filtre-not-important">
                <select id="sous_loc_type" name="sous_loc_type" class="form-control input-sm custum-element-filter">
                    <option selected value="0"> {{__('property.type_location')}} </option>
                    @foreach($sous_type_loc as $data)
                    <option value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="form-group mx-sm mb-2 hidden-lg hidden-md hidden-xs col-sm-2 filtre-not-important-btn-2">
                    <button data-id="plus_sm" class="btn btn-outline-dark btn-sm custum-element-filter" id="plus_de_filtre_sm" >{{__('filters.plus_de_filtre')}}</button>
            </div>
            <div class="form-group mx-sm mb-2 hidden-lg hidden-md hidden-sm col-xs-12 ">
                    <button data-id="plus_xs" class="btn btn-outline-dark btn-sm custum-element-filter" id="plus_de_filtre_xs" >{{__('filters.plus_de_filtre')}}</button>
            </div>
            <div class="form-group mx-sm mb-2 hidden-lg hidden-sm hidden-xs col-md-1 filtre-not-important-btn-1">
                    <button data-id="plus_md" class="btn btn-outline-dark btn-sm custum-element-filter" id="plus_de_filtre_md" >{{__('filters.plus_de_filtre')}}</button>
            </div>
        </form>
       </div>     
       <div class="container container-result custum-row">
        <div class="row-fluid">
            <aside class="col-xs-12 col-sm-4 col-md-3 leftside-filter-outer">
                <div class="white-bg search-left-filter-bx">
                    <div class="search-filter-hdd">
                        <h6>{{ __('searchlisting.filters') }}</h6>
                        <div class="clear-filter"><a href="#">{{ __('searchlisting.clear_filters') }} X</a></div>
                    </div>
                    <div class="filter-mobile-hide">

                         <div class="search-property-type gender-filter">
                            <h6>{{ __('searchlisting.je_cherche') }}</h6>
                              <input type="radio" class="scenario_id_ajax" id="scenario_id_ajax_1" name="scenario_id_ajax" value="1"> {{ __('searchlisting.locataire') }}<br>
                              <input type="radio" class="scenario_id_ajax" id="scenario_id_ajax_3" name="scenario_id_ajax" value="3"> {{ __('searchlisting.rent_property') }}<br>
                              <input type="radio" class="scenario_id_ajax" id="scenario_id_ajax_4" name="scenario_id_ajax" value="4"> {{ __('searchlisting.colocation') }}<br>
                              <input type="radio" class="scenario_id_ajax" id="scenario_id_ajax_2" name="scenario_id_ajax" value="2"> {{ __('searchlisting.colocataire') }}
                        </div>
                        <div class="search-property-type">
                            <h6>{{ __('searchlisting.apercu') }}</h6>
                            <ul class="filter-check-listing">
                                <li>
                                    <input class="custom-checkbox" id="image-chkbox" type="checkbox" value="1" name="prop_type[]">
                                    <label for="image-chkbox">{{ __('searchlisting.with_image') }}</label>
                                </li>
                            </ul>
                        </div>
                        <form id="home-search-sc2" method="GET" action="{{ route('searchadScenId') }}">
                            {{ csrf_field() }}
                            <div class="adress-slider">
                                <label>
                                    <input type="hidden" id="first_latitude" name="latitude" value="@if(isset($lat)) {{$lat}} @endif">
                                    <input type="hidden" id="first_longitude" name="longitude" value="@if(isset($long)) {{$long}} @endif">
                                    <input type="hidden" id="search_scenario_id" name="scenario_id" value="4">
                                </label>
                            </div>
                        </form>
                        <div class="radius-search">
                            <h6>{{ __('searchlisting.radius') }}</h6>
                            <div class="custom-range-slider">
                                <div class="range-slider-value" id="ex6CurrentSliderValLabel"><h6 id="ex6SliderVal">40</h6>{{ __('searchlisting.km') }}</div>
                                <input id="ex6" type="text" data-slider-min="1" data-slider-max="40" data-slider-step="1" data-slider-value="40"/>
                            </div>
                        </div>
						<!--<div class="search-rent-permonth">
                            <h6>{{ __('filters.visit_label') }}</h6>
							<div class="div_visiting_date">
								<div class="rent-filter-bx visit_date">
									
									<div class="form-group">
										<div class="datepicker-outer">
											<div id="datepicker-1" class="custom-datepicker">
												<input class="form-control visiting_date_input date_of_visit datepicker" type="text" placeholder="mm/dd/yyyy" readonly name="birth_date" id="birth_date">
											</div>
										</div>
										
									</div>
									<div class="form-group" style="margin-right:2%;width:43%;float:left;">
											<input type="text" placeholder="{{ __('filters.start_time') }}"  name="start_time[]" class="visiting_date_input form-control start_time_visit time_picker" readonly>
									</div>
									<div class="form-group" style="margin-right:2%;width:43%;float:left;">
											<input type="text"  placeholder="{{ __('filters.end_time') }}" name="start_time[]" class="visiting_date_input form-control end_time_visit time_picker" readonly> 
									</div>
									<span class="btn btn-light close-button">x</span>
								</div>
							</div>
							<button id="btn_visit_date" type="button" class="btn btn-light">{{__('filters.add_visiting_date') }}</button>
                            
							
                        </div>-->
                        <!--<div class="search-property-type">
                            <h6>{{ __('searchlisting.property_type') }}</h6>
                            <ul class="filter-check-listing">
                                @if(!empty($propertyTypes) && count($propertyTypes) > 0)
                                @foreach($propertyTypes as $propertyType)
                                <li>
                                    <input class="custom-checkbox" id="ptype-checkbox-{{$propertyType->id}}" type="checkbox" value="{{$propertyType->id}}" name="prop_type[]">
                                    <label for="ptype-checkbox-{{$propertyType->id}}">{{traduct_info_bdd($propertyType->property_type)}}</label>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                       
                       
                        @if($scenario_id == 2)
                        <div class="search-property-type gender-filter">
                            <h6>{{ __('searchlisting.prefered_gender') }}</h6>
                            <ul class="filter-check-listing">
                                <li>
                                    <input class="custom-checkbox" id="gender-checkbox-1" type="checkbox" value="value2" checked="">
                                    <label for="gender-checkbox-1">{{ __('searchlisting.men') }}</label>
                                </li>
                                <li>
                                    <input class="custom-checkbox" id="gender-checkbox-2" type="checkbox" value="value2" >
                                    <label for="gender-checkbox-2">{{ __('searchlisting.women') }}</label>
                                </li>
                                <li>
                                    <input class="custom-checkbox" id="gender-checkbox-3" type="checkbox" value="value2" >
                                    <label for="gender-checkbox-3">{{ __('searchlisting.any') }}</label>
                                </li>
                            </ul>
                        </div>
                        <div class="search-property-type filter-occup">
                            <h6>{{ __('searchlisting.prefered_occupation') }}</h6>
                            <ul class="filter-check-listing">
                                <li>
                                    <input class="custom-checkbox" id="occup-checkbox-1" type="checkbox" value="value2" checked="">
                                    <label for="occup-checkbox-1">{{ __('searchlisting.student') }}</label>
                                </li>
                                <li>
                                    <input class="custom-checkbox" id="occup-checkbox-2" type="checkbox" value="value2" >
                                    <label for="occup-checkbox-2">{{ __('searchlisting.salaried') }}</label>
                                </li>
                                <li>
                                    <input class="custom-checkbox" id="occup-checkbox-3" type="checkbox" value="value2" >
                                    <label for="occup-checkbox-3">{{ __('searchlisting.any') }}</label>
                                </li>
                            </ul>
                        </div>
<!--                        <div class="search-rent-permonth serach-age-range">
                            <h6>{{ __('Preferred age range') }}</h6>
                            <div class="rent-filter-bx">
                                <input class="form-control rent-filter-bx-first" type="text" name="" placeholder="{{ __('Min') }}." />
                                <input class="form-control rent-filter-bx-second" type="text" name="" placeholder="{{ __('Max') }}." />
                            </div>
                        </div>
                        @endif-->

						@if(Auth::check() && Auth::user()->provider=="facebook")
							<div class="search-property-type filter-fb">
								<h6><i style="font-size : 1.5em;color:rgb(66,103,178);;" class="fa fa-facebook-official"></i><span class="">{{ __('filters.common_friend') }}</span></h6>
								<ul class="filter-check-listing">
									<li>
										<input class="custom-checkbox" id="fb-checkbox-1" type="checkbox" value="value2">
										<label for="fb-checkbox-1">{{ __('filters.with_common_friend') }}</label>
									</li>
									
								</ul>
							</div>
						@endif
						@if($scenario_id == 2 || $scenario_id == 4 || $scenario_id == 5)
							<div class="search-property-type filter-school">
								<h6>{{ __('filters.school') }}</h6>
								<input type="text" class="form-control" placeholder="{{ __('Prefered School') }}" name="school_name" id="school_name"/>
							</div>
							<div class="search-property-type filter-country">
								<h6>{{ __('filters.origin_country') }}</h6>
								<select class="selectpicker" name="country" id="country">
									<option value="0"></option>
									@foreach($countries as $data)
									<option value="{{$data->id}}">{{$data->country_name}}</option>
									@endforeach
								</select>
							</div>
							<div class="search-property-type filter-city">
								<h6>{{ __('filters.origin_city') }}</h6>
								<select class="selectpicker" name="city" id="city">
									<option value="0"></option>
								</select>
							</div>
							<div class="search-property-type filter-lifestyle">
								<h6>{{ __('filters.lifestyle') }}</h6>
								<ul class="filter-check-listing">
									@foreach($lifestyles as $i => $data)
										<li>
											<input class="custom-checkbox lifestyle_checkbox" id="lifestyle-checkbox-{{$data->id}}" value="{{$data->id}}"  type="checkbox">
											<label for="lifestyle-checkbox-{{$data->id}}">{{$data->lifestyle_name}}</label>
										</li>
									@endforeach
								</ul>
							</div>
							<div class="search-property-type filter-interest">
								<h6>{{ __('filters.social_interest') }}</h6>
								<ul class="filter-check-listing">
									@foreach($socialInterests as $i => $data)
										<li>
											<input class="custom-checkbox interest_checkbox"  id="interest-checkbox-{{$data->id}}" value="{{$data->id}}"  type="checkbox">
											<label for="interest-checkbox-{{$data->id}}">{{$data->interest_name}}</label>
										</li>
									@endforeach
								</ul>
							</div>
							<div class="search-property-type filter-occup">
								<h6>{{ __('filters.smoker') }}</h6>
								<div class="custom-selectbx">
									<select class="selectpicker" name="smoker" id="smoker">
										<option value="0">{{ __('filters.smoker_no') }}</option>
										<option value="1">{{ __('filters.smoker_yes') }}</option>
										<option selected value="2">{{ __('filters.smoker_indifferent') }}</option>
									</select>
								</div>
							</div>
						@endif
                         <div class="search-property-type property-rules">
                            <h6>{{ __('searchlisting.property_rules') }}</h6>
                            <ul class="filter-check-listing">
                                @if(!empty($propRules) && count($propRules) > 0)
                                @foreach($propRules as $propRule)
                                <li>
                                    <input class="custom-checkbox" id="prype-checkbox-{{$propRule->id}}" type="checkbox" value="{{$propRule->id}}" name="prop_rule[]">
                                    <label for="prype-checkbox-{{$propRule->id}}">{{traduct_info_bdd($propRule->rules)}}</label>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
						<div class="search-property-type filter-near-by">
							<h6>{{ __('filters.near_by_message1') }}</h6>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
									<input id="address" name="address" class="form-control" type="text" placeholder="{{ __('Enter a location') }}" @if(!empty($ad_details)) value="{{$ad_details->address}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->address}}" @endif>
									<input type="hidden" id="latitude" name="latitude" @if(!empty($ad_details)) value="{{$ad_details->latitude}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->latitude}}" @endif>
									<input type="hidden" id="longitude" name="longitude" @if(!empty($ad_details)) value="{{$ad_details->longitude}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->longitude}}" @endif>
									<input id="actual_address" name="actual_address" type="hidden" @if(!empty($ad_details)) value="{{$ad_details->address}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->address}}" @endif>
								</div>
							</div>
							<div class="form-group">
								<span class="left-oriented">{{ __('filters.near_by_message2') }}</span>
								<select name="near_by_facilities[]" class="from-control js-example-basic-multiple" id="nearByFacilities" multiple="multiple" style="width: 100%">
								</select>
							</div>
							<div class="map-search-bx">
								<div id="gmap" class="google_map"></div>
							</div>
						</div>
                    </div>
                </div>
            </aside>
            <div class="col-xs-12 col-sm-8 col-md-9 rightside-filter-search">
                @include('searchlisting.first-scen-data-all')
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
        $(document).ready(function(e){
            var messages = {"moins_filtre" : "{{__('filters.moins_de_filtre')}}", "plus_filtre" : "{{__('filters.plus_de_filtre')}}"};
            $("#plus_de_filtre_sm").click(function(event){
                event.preventDefault();
                var data_id = $(this).attr("data-id");
                if(data_id == "plus_sm") {
                    $(this).attr("data-id", "moins");
                    $(this).text(messages.moins_filtre);
                    $( '.filtre-not-important').removeClass('hidden-sm')    
                    $( '.filtre-not-important-sm').removeClass('col-sm-5')
                    $( '.filtre-not-important-sm').addClass('col-sm-6')
                    $( '.filtre-not-important-btn-2').removeClass('col-sm-2')
                    $( '.filtre-not-important-btn-2').addClass('col-sm-12')
                    $( '.filtre-not-important-sm').removeClass('hidden-sm')
                 } else {
                    $(this).attr("data-id", "plus_sm");
                    $(this).text(messages.plus_filtre);
                    $( '.filtre-not-important').addClass('hidden-sm') 
                    $( '.filtre-not-important-sm').removeClass('col-sm-6')
                    $( '.filtre-not-important-sm').addClass('col-sm-5')
                    $( '.filtre-not-important-btn-2').removeClass('col-sm-12')
                    $( '.filtre-not-important-btn-2').addClass('col-sm-2')
                    $( '.filtre-not-important-sm').addClass('hidden-sm')
                    $( '.filtre-not-important-sm-1').removeClass('hidden-sm')
                 }
               
            });
            $("#plus_de_filtre_md").click(function(event){
                event.preventDefault();
                var data_id = $(this).attr("data-id");
                if(data_id == "plus_md") {
                    $(this).attr("data-id", "moins");
                    $(this).text(messages.moins_filtre);
                    $( '.filtre-not-important').removeClass('hidden-md')                  
                    $( '.filtre-not-important-md').removeClass('col-md-2')
                    $( '.filtre-not-important-md').addClass('col-md-3')
                    $( '.filtre-not-important-btn-1').removeClass('col-md-1')
                    $( '.filtre-not-important-btn-1').addClass('col-md-12')

                 } else {
                    $(this).attr("data-id", "plus_md");
                    $(this).text(messages.plus_filtre);
                    $( '.filtre-not-important').addClass('hidden-md') 
                    $( '.filtre-not-important-md').removeClass('col-md-3')
                    $( '.filtre-not-important-md').addClass('col-md-2')
                    $( '.filtre-not-important-md-3').removeClass('col-md-2')
                    $( '.filtre-not-important-md-3').addClass('col-md-3')
                    $( '.filtre-not-important-btn-1').removeClass('col-md-12')
                    $( '.filtre-not-important-btn-1').addClass('col-md-1')                 
                 }
               
            });
            $("#plus_de_filtre_xs").click(function(event){
                event.preventDefault();
                var data_id = $(this).attr("data-id");
                if(data_id == "plus_xs") {
                    $(this).attr("data-id", "moins");
                    $(this).text(messages.moins_filtre);
                    $( '.filtre-not-important').removeClass('hidden-xs') 
                    $( '.filtre-not-important-sm').removeClass('hidden-xs')   
                 } else {
                    $(this).attr("data-id", "plus_xs");
                    $(this).text(messages.plus_filtre);
                    $( '.filtre-not-important').addClass('hidden-xs')
                    $( '.filtre-not-important-sm').addClass('hidden-xs')                  
                 }
               
            });
        });
   
</script>
@endpush