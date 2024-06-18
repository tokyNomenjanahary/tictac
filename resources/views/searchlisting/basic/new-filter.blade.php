<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Varela&display=swap" rel="stylesheet">
@push('scripts')
{{-- <script src="/js/new-filter.js"></script> --}}
<script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1657545033/Bailti/js/new-filter_nj1ghn.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/places.js@1.16.1"></script> --}}
@if(isAlert())
<script type="text/javascript">
    $(document).ready(function(){
        getData();
    });
</script>
@endif
@endpush

@push('styles')
<style>

    .autocomplete-items {
      position: absolute;
      border: 1px solid rgba(0, 0, 0, 0.1);
      box-shadow: 0px 2px 10px 2px rgba(0, 0, 0, 0.1);
      border-top: none;
      background-color: #fff;

      z-index: 99;
      top: calc(100% + 2px);
      left: 0;
      right: 0;
      overflow-x: scroll;
    }

    .autocomplete-items div {

      padding: 10px;
      cursor: pointer;
    }

    .autocomplete-items div:hover {
      /*when hovering an item:*/
      background-color: rgba(0, 0, 0, 0.1);
    }

    .autocomplete-items .autocomplete-active {
      /*when navigating through the items using the arrow keys:*/
      background-color: rgba(0, 0, 0, 0.1);
    }

    </style>
@endpush


@if(isAlert())
<?php $filters = getAlert();?>
<input type="hidden" id="isModif" value="{{getParameter('idAlert')}}" name="">
@endif
<input type="hidden" value="{{getSearchFilters('page')}}" id="page-index" name="">
<div id="top-filter-container" class="top-filter-container row-fluid inner-filter-wrap col-xs-12 col-sm-12 col-md-12 no-gutter" align="center" style="margin-top:0px;">

        <input type="hidden" id="type-design" value="basic" name="">
        <form id="form-filter-container" class="form-inline no-gutter col-lg-12">
            <a class="filter-item-left d-flex flex-column" href="{{ url()->previous() }}">
                <i class="fa fa-arrow-left"></i>
                <span>{{ __('searchlisting.retour') }}</span>
            </a>
            <div class="filter-div">
                <a href="javascript:" left-position="0" id="scenario-search" class="filter-parent filter-top-button web-filter">
                    <i class="fi fi-home filter-icon"></i>
                    <span class="filter-title filter-title-scenario">
                        @if(isset($scenario_id) && $scenario_id == 3){{ __('searchlisting.locataire') }}@endif
                        @if(isset($scenario_id) && $scenario_id == 4){{ __('searchlisting.colocataire') }}@endif
                        @if(isset($scenario_id) && $scenario_id == 1){{ __('searchlisting.rent_property') }}@endif
                        @if(isset($scenario_id) && $scenario_id == 2){{ __('searchlisting.colocation') }}@endif
                        @if(isset($scenario_id) && $scenario_id == 5){{ __('searchlisting.monter_colocation') }}@endif
                    </span>
                </a>
                <div id="filter-div-container" class="filter-div-content">
                    <div class="entete-search-mobile helper-search-mobile-top">
                        <div>
                           <div class="filter-title-mobile-filter">{{ __('searchlisting.votre_recherche') }}</div>
                           <div class="mobile-menu-container">
                            <a href="javascript:" id="home1" data-id="div-scenario-search" class="mobile-menu active  menu-filter-on-mobile">
                               <i class="fi fi-home filter-icon"></i>
                            </a>

                            <a href="javascript:" id="lordee" data-id="div-map-search" class="mobile-menu lordee1classe menu-filter-on-mobile">
                               <i class="fi fi-map-marker-alt filter-icon"></i>
                            </a>

                            <a href="javascript:" data-id="div-budget-search" class="mobile-menu menu-filter-on-mobile">
                                <i class="fi fi-euro filter-icon"></i>
                            </a>
                            @if($scenario_id!=4)
                            <a href="javascript:" data-id="div-pieces-search" class="mobile-menu menu-filter-on-mobile">
                                <i class="fi fi-room filter-icon"></i>
                            </a>
                            @endif
                            <a href="javascript:" data-id="div-colocataire-search" class="mobile-menu menu-filter-on-mobile">
                                <i class="fi @if($scenario_id!=1)fi-persons @else fi-heart @endif filter-icon"></i>
                            </a>
                        </div>
                        </div>
                    </div>
                    <div class="footer-search-mobile helper-search-mobile">
                        <div>
                            <div class="content-footer-search-mobile">
                                <div class="btn-appliquer-mobile">
                                    <button type="button" id="button-appliquer-filter" class="btn button-appliquer">
                                        {{ __('searchlisting.appliquer') }}
                                    </button>
                                </div>
                                <div class="btn-annuler-filter-mobile">
                                    <i class="fi fi-close filter-icon-close"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="cache-shadow-scenario" class="content-body">
                        <div id="div-scenario-search" class="filter-content @if(isset($map)) map-scenario-filter @endif active">
                            <div class="cache-shadow"></div>
                            <div class="row content-left first-element-filter">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.je_cherche') }}
                                </span>
                                <div class="filter-child-content radio-box-filter" id="filtre-modif">
                                <a href="javascript:"  class="scenario_id_ajax logement_entier type-scenario-filter new-filtre-scen @if(isset($scenario_id) && $scenario_id == 1) active @endif" value="1">{{ __('searchlisting.rent_property') }}</a>
                                    <a href="javascript:" class="scenario_id_ajax type-scenario-filter new-filtre-scen @if(isset($scenario_id) && $scenario_id == 2) active @endif" value="2">{{ __('searchlisting.colocation') }}</a>
                                    {{-- @if(!isset($map)) --}}
                                    <a href="javascript:" class="@if(isset($scenario_id) && $scenario_id == 3) active @endif scenario_id_ajax type-scenario-filter type-scenario-filter-first new-filtre-scen" value="3" >{{ __('searchlisting.locataire') }}</a>
                                    <a href="javascript:" class="scenario_id_ajax type-scenario-filter-last new-filtre-scen @if(isset($scenario_id) && $scenario_id == 4) active @endif" value="4">{{ __('searchlisting.colocataire') }}</a>
                                    <a href="javascript:" class=" box-monter-colocation scenario_id_ajax type-scenario-filter-last new-filtre-scen @if(isset($scenario_id) && $scenario_id == 5) active @endif" value="5">{{ __('searchlisting.monter_colocation') }}</a>
                                    {{-- @endif --}}
                                </div>
                                <div>
                                  <ul class="filter-check-listing">
                                        <li>
                                            <input class="custom-checkbox" id="urgent-chkbox" type="checkbox" value="1" name="prop_urgent[]" @if(isAlert() && $filters['urgent'] == 1) checked="" @endif>
                                            <label for="urgent-chkbox">
                                            <?php echo __('searchlisting.urgent_ads'); ?>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @if($scenario_id == 1 ||$scenario_id ==2)
                            <div class="row content-left div-type-location">
                                <span class="filter-child-title filter-child">
                                    {{ __('property.type_location') }}
                                </span>
                                <div>
                                  <div class="custom-selectbx">
                                    <?php $sous_type_loc=getTypeLocation(); ?>
                                    <select id="sous_loc_type" name="sous_loc_type" title="{{__('filters.no_selected')}}" class="selectpicker">
                                        <option value="0"></option>
                                        @foreach($sous_type_loc as $data)
                                        <option @if(isAlert() && $filters['sous_type_loc'] == $data->id) selected="" @endif value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            </div>
                            @endif

                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.apercu') }}
                                </span>
                                <div>
                                  <ul class="filter-check-listing">
                                        <li>
                                            <input @if(isAlert() && $filters['with_image'] == 1) checked="" @endif class="custom-checkbox" id="image-chkbox" type="checkbox" value="1" name="prop_image[]">
                                            <label for="image-chkbox">
                                            @if($scenario_id == 1 ||$scenario_id==2)
                                            {{ __('searchlisting.with_image') }}
                                            @else
                                            {{ __('filters.profil_with_image') }}
                                            @endif
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.quel_property') }}
                                </span>
                                @if(!empty($propertyTypes) && count($propertyTypes) > 0)
                                <div>
                                    <div class="form-group">
                                        <div class="custom-selectbx">
                                            <select  class="sport-sumo-select sumo-select" placeholder="{{__('filters.no_selected')}}" name="prop_type[]" id="prop_type" multiple="">
                                                @foreach($propertyTypes as $propertyType)
                                                <option @if(isAlert() && isListElement($propertyType->id, $filters['property_types'])) selected="" @endif value="{{$propertyType->id}}">{{traduct_info_bdd($propertyType->property_type)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div id="div-map-search" class="filter-content">

                        <div id="ou-chercher"> {{ __('searchlisting.ou_cherchez_vous') }}
                        </div>
                        <div class="cache-shadow"></div>
                            @if(!isUserSubscribed())
                            <div class="row content-left">
                                <div class="div-cadenas">
                                    <img class="img-cadenas" src="/img/cadenas.png"/>
                                    <p> {{ __('searchlisting.benefice_filtre') }}</p>
                                    <a class="btn-more-detail" style="width : 150px;" href="/subscription_plan?type=filtre">Passer en Premium</a>
                                </div>
                            </div>
                            @endif
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.ou_cherchez_vous') }}
                                </span>
                                <div id="champ-recherche" style="height: 37px;">
                                @if(!empty($itemLat))
                                    @foreach($itemLat as $key=>$itemLa)
                                        <div class="XaYpq _1GwGp oydxI" id="{{ $key }}">
                                            <div class="_1DJWB">
                                                <div class="_1f4ib">

                                                 <span class="_137P- P4PEa _3j0OU">{{ $itemLa["add"] }}</span>

                                                </div>
                                            </div>
                                            <div class="_3hxfS _3Cn9F">
                                            <svg viewBox="0 0 24 24" class="sc-bdVaJa src___StyledBox-fochin-0 cSECIV">
                                            <use xlink:href="#SvgMore">
                                            <svg id="SvgMore"><path d="M12 0a12 12 0 1012 12A12 12 0 0012 0zm4.8 13.2h-3.6v3.6a1.2 1.2 0 01-2.4 0v-3.6H7.2a1.2 1.2 0 110-2.4h3.6V7.2a1.2 1.2 0 112.4 0v3.6h3.6a1.2 1.2 0 010 2.4z"></path></svg>

                                            </use>
                                            </svg>

                                            </div>
                                        </div>
                                    @endforeach
                                 @endif

                                </div>
                                <div>
                                   <div class="form-group div-search-map-input">
                                        <button type="button" id="btn-recherche-lieux" class="btn btn-recherche-lieux">
                                                <i class="fi fi-search"></i>
                                        </button>
                                        <div class="input-group" id="search-container">
                                            <input id="address" name="address" class="form-control" type="text" placeholder="{{ __('filters.enter_location') }}" @if(isset($address)) value="{{$address}}" @endif autocomplete="off">
                                            <input type="hidden" id="latitude" name="latitude" @if(isset($lat)) value="{{$lat}}" @endif>
                                            <input type="hidden" id="longitude" name="longitude" @if(isset($long)) value="{{$long}}" @endif>
                                            <input id="actual_address" name="actual_address" type="hidden" @if(isset($address)) value="{{$address}}" @endif>
                                        </div>
                                    </div>
                                    <label class="error-address">{{__('filters.error_adress_valide')}}</label>
                                </div>
                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('property.point_proximity') }}
                                </span>
                                <div>
                                   <div class="form-group div-search-map-input">
                                        <div class="input-group">
                                            <select id="proximity" placeholder="{{__('filters.no_selected')}}" name="proximity[]" class="sumo-select" multiple="">
                                                @if(count($proximities)>0)
                                                    @foreach($proximities as $proximity)
                                                        <option @if(!isUserSubscribed()) disabled="" @endif value="{{$proximity->id}}">{{ $proximity->title }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="premium_only"><span class="span_premium_only">* Premium Only</span></div>
                                    <label class="error-address">{{__('filters.error_adress_valide')}}</label>
                                </div>
                            </div>
                            @if($scenario_id==1 || $scenario_id==2)
                            <?php $lines=getAllMetroLines($address);?>
                            @if(count($lines) > 0)
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('filters.metro_lines') }}
                                </span>
                                <div>
                                    <div class="custom-selectbx">
                                        <select class="selectpicker" title="{{__('filters.no_selected')}}" name="metro_line" id="metro_line">
                                            <option value=""></option>
                                            @foreach($lines as $key => $value)
                                            <option @if(isAlert() && $filters['metro_line'] == $value) selected="" @endif value="{{$value}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    Rayon
                                    <!-- {{ __('searchlisting.zone_recherche') }} -->
                                </span>
                                @if(isset($map))
                                <!-- <div class="div-btn-search-area">
                                    <a class="menu-serach-area active-menu" href="javascript:" data-type="0" data-id="radius">
                                        <span class="glyphicon glyphicon-time"></span>
                                        {{ __('searchlisting.radius') }}
                                    </a>
                                    <a class="menu-serach-area" id="draw-zone-button" data-type="1" data-id="draw" href="javascript:">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                        {{ __('searchlisting.dessiner') }}
                                    </a>
                                    <a class="menu-serach-area" id="travel-time-button" data-type="2" data-id="travel-time" href="javascript:">
                                        <span class="glyphicon glyphicon-dashboard"></span>
                                        {{ __('searchlisting.travel_time') }}
                                    </a>
                                </div> -->
                                @endif

                                <div class="div-menu-search-area" id="div-radius">
                                   <div class="custom-range-slider">
                                       {{-- ici --}}
                                    <div class="range-slider-value" id="ex6CurrentSliderValLabel"><h6 id="ex6SliderVal">{{ $radius??'40' }}</h6>{{ __('searchlisting.km') }}</div>
                                        <input id="ex6" type="text" data-slider-min="1" data-slider-max="40" data-slider-step="1"
                                         @if(isAlert()) data-slider-value="{{$filters['radius']}}" @else data-slider-value="{{ $radius??'40' }}" @endif />
                                    </div>
                                </div>
                                @if(isset($map))
                                <div class="div-menu-search-area div-menu-search-area-hidden" id="div-travel-time">

                                    <div>
                                        {{__('searchlisting.travel_message', array("scenario" => __("searchlisting.travel_scenario_" . $scenario_id)))}}
                                    </div>
                                    <div>
                                        <div class="div-select div-padd-other">
                                            <select title="{{__('filters.no_selected')}}" class="selectpicker travel-input" name="travel_time" id="travel_time">
                                                <option value="600">10 {{__('searchlisting.min')}}</option>
                                                <option value="1200">20 {{__('searchlisting.min')}}</option>
                                                <option value="1800">30 {{__('searchlisting.min')}}</option>
                                                <option value="2200">45 {{__('searchlisting.min')}}</option>
                                                <option value="3600">1 {{__('searchlisting.hour')}}</option>
                                                <option value="5400">1 {{__('searchlisting.hour')}} 30 {{__('searchlisting.min')}}</option>
                                                <option value="7200">2 {{__('searchlisting.hour')}}</option>
                                            </select>
                                        </div>
                                        <div class="div-select div-padd-other">
                                            <select title="{{__('filters.no_selected')}}" class="selectpicker travel-input" name="travel_mode" id="travel_mode">
                                                <option value="walking">{{__("searchlisting.a_pied")}}</option>
                                                <option value="cycling">{{__("searchlisting.a_velo")}}</option>
                                                <option selected value="driving">{{__("searchlisting.en_voiture")}}</option>
                                                <option value="public_transport">{{__("searchlisting.public_transport")}}</option>

                                            </select>
                                        </div>
                                        <div class="div-select div-padd">
                                            <span>{{ __('searchlisting.de') }}</span>
                                            <select title="{{__('filters.no_selected')}}" class="selectpicker travel-input" name="travel_type_place" id="travel_type_place">
                                                <option selected value="0">{{__("searchlisting.my_work")}}</option>
                                                <option value="1">{{__("searchlisting.my_school")}}</option>
                                                <option value="2">{{__("searchlisting.other_place")}}</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="div-select div-select-address">
                                        <span>{{ __('searchlisting.situe') }} : </span>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                            <input id="address-travel" name="address_travel" class="form-control" type="text" placeholder="{{ __('searchlisting.adress_exact') }}" >
                                            <input type="hidden" id="latitude-travel" name="latitude_travel">
                                            <input type="hidden" id="longitude-travel" name="longitude_travel">
                                        </div>
                                        <span class="exemple-address">{{__("searchlisting.travel_address_ex")}}</span>
                                    </div>
                                </div>
                                @endif
                            </div>

                        </div>
                        <div id="div-budget-search" class="filter-content">
                            <div class="cache-shadow cache-shadow-budget"></div>
                            <div class="row content-left first-element-filter">
                                <i class="filter-icon-mobile fi fi-euro filter-icon-blue filter-icon-blue-modal"></i>
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.votre_budget') }}
                                </span>
                                <div class="filter-child-content">
                                    <input @if(isAlert()) value="{{$filters['min_rent']}}" @endif type="text" class="form-control input-rent" name="min_rent" id="min_rent" placeholder="{{__('searchlisting.min_loyer')}}">
                                    <input @if(isAlert()) value="{{$filters['max_rent']}}" @endif type="text" class="form-control input-rent" name="max_rent" id="max_rent" placeholder="{{__('searchlisting.max_loyer')}}">
                                </div>
                                <span id="error-budget" class=" filter-child filter-error">
                                </span>
                            </div>
                        </div>
                        <div id="div-pieces-search" class="filter-content">
                            <div class="cache-shadow cache-shadow-budget cache-shadow-pieces cache-shadow-right"></div>
                            <div class="row content-left first-element-filter">

                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.surface_habitable') }}
                                </span>
                                <div class="filter-child-content">
                                    <input  @if(isAlert()) value="{{$filters['min_area']}}" @endif type="text" class="form-control input-rent" name="min_area" id="min_area" placeholder="{{__('searchlisting.min_surface')}}">
                                    <input @if(isAlert()) value="{{$filters['max_area']}}" @endif type="text" class="form-control input-rent" name="max_area" id="max_area" placeholder="{{__('searchlisting.max_surface')}}">
                                </div>
                                <span id="error-surface" class="filter-child-title filter-child filter-error">
                                </span>
                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.is_meuble') }}
                                </span>
                                <div class="filter-radio-listing">
                                    <div class="custom-radio">
                                        <input type="radio" id="f-furnished-op1" @if(!is_null(getSearchFilters('furnished')) &&  getSearchFilters('furnished') == 0) checked @endif @if(isAlert() && $filters['furnished'] == 0) checked="" @endif name="furnished" value="0" />
                                        <label for="f-furnished-op1">{{ __('searchlisting.yes') }}</label>
                                    </div>
                                    <div class="custom-radio">
                                        <input @if(isAlert() && $filters['furnished'] == 1) checked="" @endif type="radio" id="f-furnished-op2" @if(!is_null(getSearchFilters('furnished')) &&  getSearchFilters('furnished') == 1) checked @endif name="furnished" value="1" />
                                        <label for="f-furnished-op2">{{ __('searchlisting.no') }}</label>
                                    </div>
                                    <div class="custom-radio custom-radio-big">
                                        <input type="radio" id="f-furnished-op3" name="furnished" @if(!is_null(getSearchFilters('furnished')) &&  getSearchFilters('furnished') != 0 && getSearchFilters('furnished') != 1) checked @endif value="" />
                                        <label for="f-furnished-op3">{{ __('searchlisting.dont_matter') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.combien_bedroom') }}
                                </span>
                                <div class="filter-radio-listing">
                                    <div class="custom-radio">
                                        <input @if(isAlert() && $filters['bedrooms'] == 0) checked="" @endif type="radio" id="bedroom-op1" name="bedrooms" value="0" />
                                        <label for="bedroom-op1">{{ __('searchlisting.any') }}</label>
                                    </div>
                                    <div class="custom-radio">
                                        <input @if(isAlert() && $filters['bedrooms'] == 1) checked="" @endif type="radio" id="bedroom-op2" name="bedrooms" value="1"/>
                                        <label for="bedroom-op2">1+</label>
                                    </div>
                                    <div class="custom-radio">
                                        <input @if(isAlert() && $filters['bedrooms'] == 2) checked="" @endif type="radio" id="bedroom-op3" name="bedrooms" value="2"/>
                                        <label for="bedroom-op3">2+</label>
                                    </div>
                                    <div class="custom-radio">
                                        <input @if(isAlert() && $filters['bedrooms'] == 3) checked="" @endif type="radio" id="bedroom-op4" name="bedrooms" value="3"/>
                                        <label for="bedroom-op4">3+</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.combien_bathroom') }}
                                </span>
                                <div class="filter-child-content radio-box-filter">
                                    <div class="filter-radio-listing">
                                        <div class="custom-radio">
                                            <input @if(isAlert() && $filters['bathrooms'] == 0) checked="" @endif type="radio" id="bathroom-op1" name="bathrooms" value="0"/>
                                            <label for="bathroom-op1">{{ __('searchlisting.any') }}</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input @if(isAlert() && $filters['bathrooms'] == 0) checked="" @endif type="radio" id="bathroom-op2" name="bathrooms" value="1" />
                                            <label for="bathroom-op2">1+</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input @if(isAlert() && $filters['bathrooms'] == 0) checked="" @endif type="radio" id="bathroom-op3" name="bathrooms" value="2" />
                                            <label for="bathroom-op3">2+</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input @if(isAlert() && $filters['bathrooms'] == 3) checked="" @endif type="radio" id="bathroom-op4" name="bathrooms" value="3" />
                                            <label for="bathroom-op4">3+</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.is_separate_kitchen') }}
                                </span>
                                <div class="filter-radio-listing">
                                    <div class="custom-radio">
                                        <input @if(isAlert() && $filters['kitchen'] == 1) checked="" @endif type="radio" id="kitchen-op1" name="kitchen" value="1" />
                                        <label for="kitchen-op1">{{ __('searchlisting.yes') }}</label>
                                    </div>
                                    <div class="custom-radio">
                                        <input type="radio" id="kitchen-op2" name="kitchen" @if(isAlert() && $filters['kitchen'] == 0) checked="" @endif value="0" />
                                        <label for="kitchen-op2">{{ __('searchlisting.no') }}</label>
                                    </div>
                                    <div class="custom-radio custom-radio-big">
                                        <input type="radio" id="kitchen-op3" name="kitchen" checked="" value="" />
                                        <label for="kitchen-op3">{{ __('searchlisting.dont_matter') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="div-ad-info-search" class="filter-content ">
                            <div class="cache-shadow cache-shadow-right"></div>

                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.trier_par') }}
                                </span>
                                <div>
                                    <div class="form-group">
                                        <div class="custom-selectbx">
                                            <select  class="selectpicker" title="{{__('filters.no_selected')}}" name="stat_filter" id="stat_filter">
                                                <option value="1">Vue</option>
                                                <option value="2">Clic</option>
                                                <option value="3">Message</option>
                                                <option value="4">Toc Toc</option>
                                                <option value="5">Phone</option>
                                                <option value="6">Contact Fb</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="row content-left">
                                <div>
                                    <div class="form-group">
                                        <div class="custom-selectbx">
                                            <select  class="selectpicker" name="type_stat_filter" id="type_stat_filter">
                                                <option value="desc">{{ __('searchlisting.desc') }}</option>
                                                <option value="asc">{{ __('searchlisting.asc') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="div-colocataire-search" class="filter-content @if($scenario_id==1) div-scenario-1 @endif">
                            <div class="cache-shadow cache-shadow-right"></div>
                            @if(!isUserSubscribed())
                            <div class="row content-left">
                                <div class="div-cadenas">
                                    <img class="img-cadenas" src="/img/cadenas.png"/>
                                    <p>{{ __('searchlisting.benefice_filtre') }}</p>
                                    <a class="btn-more-detail" style="width : 150px;" href="/subscription_plan?type=filtre">{{ __('searchlisting.passe_premium') }}</a>
                                </div>
                            </div>
                            @endif
                            @if($scenario_id!=1)
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.quel_genre') }}
                                </span>
                                <div>
                                    <div class="form-group">
                                        <div class="custom-selectbx">
                                            <select @if(!isUserSubscribed()) disabled="" @endif  class="sport-sumo-select sumo-select" placeholder="{{__('filters.no_selected')}}" name="gender[]" id="gender" multiple="">
                                                <option @if(isUserSubscribed() && isAlert() && $filters['gender'] == 0) selected="" @endif value="0">{{ __('searchlisting.men') }}</option>
                                                <option @if(isUserSubscribed() && isAlert() && $filters['gender'] == 1) selected="" @endif value="1">{{ __('searchlisting.women') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('filters.son_age') }}
                                </span>
                                <div>
                                    <div class="form-group">
                                        <div class="custom-selectbx">
                                            <select @if(!isUserSubscribed()) disabled="" @endif  class="selectpicker" title="{{__('filters.no_selected')}}" name="age" id="age">
                                                <option value=""></option>
                                                @for($i=18;$i<100;$i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($scenario_id==1 || $scenario_id == 3)
                            <div class="row content-left">
                                @if($scenario_id==1)
                                <span class="filter-child-title filter-child">
                                    {{ __('filters.garantie_demande') }}
                                </span>
                                @endif
                                @if($scenario_id==3)
                                <span class="filter-child-title filter-child">
                                    {{ __('filters.garantie') }}
                                </span>
                                @endif
                                <div>
                                    <div class="custom-selectbx">
                                        <select @if(!isUserSubscribed()) disabled="" @endif  class="sport-sumo-select sumo-select" placeholder="{{__('filters.no_selected')}}" name="garanty[]" id="garanty" multiple="">
                                            <?php $guarAsked=getUsersGaranties(); ?>
                                            @foreach($guarAsked as $data)
                                            <option @if(isUserSubscribed() && isAlert() && isListElement($data->id, $filters['garanty'])) selected="" @endif value="{{$data->id}}">{{traduct_info_bdd($data->guarantee)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($scenario_id==4 || $scenario_id == 2 || $scenario_id == 5)
                            @if(Auth::check() && Auth::user()->provider=="facebook")
                            <div class="row content-left">
                                <i style="font-size : 1.5em;color:rgb(66,103,178);;" class="fa fa-facebook-official"></i>
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.avec_amis_en_commun') }}
                                    <a data-toggle="tooltip" data-placement="top" title="{{ __('searchlisting.affiche_unique') }}" href="javascript:" class="info-sign-fb">
                                    <span class="glyphicon glyphicon-question-sign"></span>
                                    </a>
                                </span>
                                <ul class="filter-check-listing">
                                    <li>
                                        <input @if(!isUserSubscribed()) disabled="" @endif @if(isUserSubscribed() && isAlert() && $filters['common_friend'] == "true") checked="" @endif class="custom-checkbox" id="fb-checkbox-1" type="checkbox" value="value2">
                                        <label for="fb-checkbox-1">{{ __('filters.with_common_friend') }}
                                        </label>
                                    </li>

                                </ul>
                            </div>
                             @endif
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.quel_occupation') }}
                                </span>
                                <div>
                                    <div class="form-group">
                                        <div class="custom-selectbx">
                                            <select @if(!isUserSubscribed()) disabled="" @endif  class="sport-sumo-select sumo-select" placeholder="{{__('filters.no_selected')}}" name="occupation[]" id="occupation" multiple="">
                                                <option @if(isUserSubscribed() && isAlert() && isListElement2(0, $filters['occupation'])) selected="" @endif value="0">{{ __('profile.student') }}</option>
                                                    <option @if(isUserSubscribed() && isAlert() && isListElement2(1, $filters['occupation'])) selected="" @endif value="1">{{ __('profile.freelancer') }}</option>
                                                    <option @if(isUserSubscribed() && isAlert() && isListElement2(0, $filters['occupation'])) selected="" @endif value="2">{{ __('profile.salaried') }}</option>
                                                    <option @if(isUserSubscribed() && isAlert() && isListElement2(3, $filters['occupation'])) selected="" @endif value="3">{{ __('profile.cadre') }}</option>
                                                    <option @if(isUserSubscribed() && isAlert() && isListElement2(4, $filters['occupation'])) selected="" @endif value="4">{{ __('profile.retraite') }}</option>
                                                    <option @if(isUserSubscribed() && isAlert() && isListElement2(5, $filters['occupation'])) selected="" @endif value="5">{{ __('profile.chomage') }}</option>
                                                    <option @if(isUserSubscribed() && isAlert() && isListElement2(6, $filters['occupation'])) selected="" @endif value="6">{{ __('profile.rentier') }}</option>
                                                    <option @if(isUserSubscribed() && isAlert() && isListElement2(7, $filters['occupation'])) selected="" @endif value="7">{{ __('profile.situationPro7') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('filters.profession') }}
                                </span>
                                <div>
                                    <div class="custom-selectbx">
                                        <select @if(!isUserSubscribed()) disabled="" @endif class="selectpicker" title="{{__('filters.no_selected')}}" name="profession" id="profession">
                                            <?php $profs=getUsersProfessions(); ?>
                                            <option value=""></option>
                                            @foreach($profs as $key => $value)
                                            <option @if(isUserSubscribed() && isAlert() && $filters['profession'] == $value->profession) selected="" @endif value="{{$value->profession}}">{{$value->profession}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('filters.quel_school') }}
                                </span>
                                <div>
                                    <div class="custom-selectbx">
                                        <select @if(!isUserSubscribed()) disabled="" @endif class="selectpicker" title="{{__('filters.no_selected')}}" name="school_name" id="school_name">
                                            <?php $schools=getUsersSchools(); ?>
                                            <option value=""></option>
                                            @foreach($schools as $key => $value)
                                            <option @if(isUserSubscribed() && isAlert() && $filters['school'] == $value->school) selected="" @endif value="{{$value->school}}">{{$value->school}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.est_il_fumeur') }}
                                </span>
                                <div>
                                <div class="custom-selectbx">
                                    <select @if(!isUserSubscribed()) disabled="" @endif class="selectpicker" title="{{__('filters.no_selected')}}" name="smoker" id="smoker">
                                        <option @if(isUserSubscribed() && isAlert() && $filters['smoker'] == 0) selected="" @endif value="0">{{ __('filters.smoker_no') }}</option>
                                        <option @if(isUserSubscribed() && isAlert() && $filters['smoker'] == 1) selected="" @endif value="1">{{ __('filters.smoker_yes') }}</option>
                                        <option @if(isUserSubscribed() && isAlert() && $filters['smoker'] == 0 && $filters['smoker'] == 1) selected="" @endif value="2">{{ __('filters.smoker_indifferent') }}</option>
                                    </select>
                                </div>
                                </div>
                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('filters.est_il_alcool') }}
                                </span>
                                <div>
                                <div class="custom-selectbx">
                                    <select class="selectpicker" @if(!isUserSubscribed()) disabled="" @endif title="{{__('filters.no_selected')}}" name="alcool" id="alcool">
                                        <option @if(isUserSubscribed() && isAlert() && $filters['alcool'] == 0) selected="" @endif value="0">{{ __('filters.smoker_no') }}</option>
                                        <option @if(isUserSubscribed() && isAlert() && $filters['alcool'] == 1) selected="" @endif value="1">{{ __('filters.smoker_yes') }}</option>
                                        <option @if(isUserSubscribed() && isAlert() && $filters['alcool'] != 0 && $filters['alcool'] != 1) selected="" @endif value="2">{{ __('filters.smoker_indifferent') }}</option>
                                    </select>
                                </div>
                                </div>
                            </div>
                            <!-- <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.quel_ville') }}
                                </span>
                                <div>
                                    <div class="custom-selectbx">
                                        <select class="selectpicker" title="{{__('filters.no_selected')}}" name="city" id="city">
                                            <?php $villes=getUsersVille(); ?>
                                            <option value=""></option>
                                            @foreach($villes as $key => $value)
                                            <option value="{{$value->city}}">{{$value->city}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.quel_interet') }}
                                </span>
                                @if(!empty($socialInterests) && count($socialInterests) > 0)
                                <div>
                                    <div class="form-group">
                                        <div class="custom-selectbx">
                                            <select  @if(!isUserSubscribed()) disabled="" @endif class="sport-sumo-select sumo-select" placeholder="{{__('filters.no_selected')}}" name="social_interest[]" id="social_interest" multiple="">
                                                @foreach($socialInterests as $i => $data)
                                                <option @if(isUserSubscribed() && isAlert() && isListElement2($data->id, $filters['social_interest'])) selected="" @endif value="{{$data->id}}">{{$data->interest_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('filters.sport_preferes') }}
                                </span>
                                @if(!empty($socialInterests) && count($socialInterests) > 0)
                                <div>
                                    <div class="form-group">
                                        <div class="custom-selectbx">
                                            <select   disabled="" class="sport-sumo-select sumo-select" placeholder="{{__('filters.no_selected')}}" name="social_interest[]" id="social_interest" multiple="">
                                                @foreach($socialInterests as $i => $data)
                                                <option value="{{$data->id}}">{{$data->interest_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                            <div class="row content-left">
                                <span class="filter-child-title filter-child">
                                    {{ __('searchlisting.quel_music') }}
                                </span>
                                @if(!empty($typeMusics) && count($typeMusics) > 0)
                                <div>
                                    <div class="form-group">
                                        <div class="custom-selectbx">
                                            <select @if(!isUserSubscribed()) disabled="" @endif class="sport-sumo-select sumo-select" placeholder="{{__('filters.no_selected')}}" name="musics[]" id="musics" multiple="">
                                                @foreach($typeMusics as $i => $data)
                                                <option  @if(isUserSubscribed() && isAlert() && isListElement2($data->id, $filters['musics'])) selected="" @endif value="{{$data->id}}">{{$data->label}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
            <div class="filter-div web-filter" id="filter-div-map">
                <a href="javascript:" left-position="204" id="map-search" class="filter-parent filter-top-button web-filter">
                    <i class="fi fi-map-marker-alt filter-icon"></i>
                    <span class="filter-title filter-title-map">
                        {{-- adresse sur le 2ème nav __ADDRESS__ --}}
                        {{-- ici --}}
                        {{$address}} (r <span id="radius-filter">{{ $radius??'40' }}</span>{{ __('searchlisting.km') }})
                    </span>

                </a>
            </div>
            <div class="filter-div web-filter" id="filter-div-budget">
                <a href="javascript:" id="budget-search" left-position="407" class="filter-parent  filter-parent-budget filter-top-button">
                    <i class="fi fi-euro filter-icon"></i><span class="filter-title">{{ __('searchlisting.budget') }}</span>
                </a>
            </div>
            @if($scenario_id!=4)
            <div class="filter-div web-filter" id="filter-div-pieces">
                <a href="javascript:" id="pieces-search" left-position="330" class="filter-parent filter-top-button">
                    <i class="fi fi-room filter-icon"></i><span class="filter-title">{{__('searchlisting.pieces')}}</span>
                </a>
            </div>
            @endif

            @if($scenario_id!=1)
            <div class="filter-div web-filter">
                <a href="javascript:" id="colocataire-search" left-position-medium="115" top-position-medium="140" @if($scenario_id==4) left-position="420" @else left-position="544" @endif class="filter-parent filter-top-button last-filter-div">
                    @if($scenario_id!=3)
                    <i class="fi fi-persons filter-icon-retour allign2"></i>
                    <span class="filter-title filter-title-1 allign">{{__('searchlisting.colocataire')}} {{__('searchlisting.ideal')}}</span>
                    <span class="filter-title filter-title-2"></span>

                    @endif
                    @if($scenario_id==3)
                    <i class="fi fi-persons filter-icon-retour allign2"></i>
                    <span class="filter-title filter-title-1 allign">{{__('searchlisting.locataire')}} {{__('searchlisting.ideal')}}</span>
                    <span class="filter-title filter-title-2"></span>

                    @endif
                </a>
            </div>
            @endif
            @if($scenario_id==1)
            <div class="filter-div web-filter plus-filter">
                <a href="javascript:" id="colocataire-search" left-position-medium="90" top-position-medium="140" left-position="494" class="filter-parent filter-top-button last-filter-div">
                    @if($scenario_id==1)
                    <i class="fi fi-heart filter-icon-retour"></i>
                    <span class="filter-title">{{__('filters.plus')}}</span>
                    @endif
                </a>
            </div>
            @endif

            @if(isStatUser())
            <div class="filter-div web-filter plus-filter">
                <a href="javascript:" id="ad-info-search" left-position-medium="352" top-position-medium="120" left-position="858" class="filter-parent filter-top-button last-filter-div">
                    <i class="fi fi-prescription filter-icon-retour"></i>
                    <span class="filter-title">Ad Stat</span>
                </a>
            </div>
            @endif

        </form>
        <form class="form-inline no-gutter col-lg-12 mobile-filter" id="design">

            <button type="button"   id="toutFr" class="btn btn-recherche-mobile">
            <i class="glyphicon glyphicon-map-marker"></i>
               {{ __('searchlisting.tout_france') }}
             </button>

            <button type="button" id="btn-recherche-mobile" class="btn filtre btn-recherche-mobile">
             {{ __('searchlisting.filters') }}
             <span id="count-glyph-filtre" class="filtre-ico count-glyph-all-notif count-glyph-user-pic count-glyph-all-notif-user-pic glyph-button" >0</span>
            </button>




                    <!-- Button trigger modal -->
            <button type="button" class="btn btn-recherche-mobile" id="btn-test" data-toggle="modal" data-target="#exampleModal">
            {{ __('searchlisting.tri_recent') }}
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="height: 158px;">

                <div class="modal-body rad">

                        <div class="form-check">
                                <label class="form-check-label" for="radio1">
                                    <input type="radio" class="form-check-input" id="radio1" name="sortR" value="1" checked>  {{ __('searchlisting.recent') }}
                                </label>
                                </div>
                                <div class="form-check">
                                <label class="form-check-label" for="radio2">
                                    <input type="radio" class="form-check-input" id="radio2" name="sortR" value="4">  {{ __('searchlisting.ancienne') }}
                                </label>
                                </div>
                                <div class="form-check">
                                <label class="form-check-label" for="radio3">
                                    <input type="radio" class="form-check-input" id="radio3" name="sortR" value="2">  {{ __('searchlisting.increase_rent') }}
                                </label>
                                </div>
                                <div class="form-check">
                                <label class="form-check-label" for="radio4">
                                    <input type="radio" class="form-check-input" id="radio4" name="sortR" value="3">  {{ __('searchlisting.decrease_rent') }}
                                </label>
                                </div>




                </div>

                    <button type="button"  id="okok" class="btn btn-secondary" style="display: none;" data-dismiss="modal">OK</button>

                </div>
            </div>
            </div>

     </form>
 </div>
<script>
    $("#ex6").on("slide", function(slideEvt) {
        $("#radius-filter").text(slideEvt.value);
    });
</script>
<style>


    div.filter-child-content.radio-box-filter {
    overflow-x: none;
    padding-left: 3px;
    }
    body{
    padding: 0px !important;
    }
  .occup-none
    {
        display: none;
    }

    .error-address
    {
        color: red;
        display: none;
    }
    div#div-map-search.filter-content.active
    {
        position: relative;
         top: -32px;
  }


@media only screen and (min-width: 820px) { /*1023px*/
    #ou-chercher{
 display:none;
}
}

@media only screen and (max-width: 820px) { /*1023px*/
    #ou-chercher{
    position: fixed;
    top: 0;
    width: auto;
    left: 0px;
    right: 0;
    background-color: ;
    font-family: 'Varela';
    padding: 14px;
    font-size: 22px;
    color: #e65611;
    height: 61px;
    box-shadow: 29px -12px 22px #000;


   }

   div.filter-child-content.radio-box-filter{
            display: block;
    width: 100%;
    overflow-x: scroll;
    overflow-y: hidden;
    padding-left: 3px;

          }

}
    button#btn-test.btn.btn-recherche-mobile:focus, button#btn-test.btn.btn-recherche-mobile:hover{
		-webkit-appearance: none;
  background: -webkit-gradient(to right,$green 0%,$sand 50%,$peach 100%);
  background: linear-gradient(to right,$green 0%,$sand 50%,$peach 100%);
  background-size: 500%;
  border: none;
  border-radius: 5rem;
  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
  color: rgb(228 106 47);
  cursor: pointer;


  outline: none;
  -webkit-tap-highlight-color: transparent;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;


          }

          button.btn.dropdown-toggle.bs-placeholder.btn-default, button.btn.dropdown-toggle.bs-placeholder.btn-default:active:focus, button.btn.dropdown-toggle.bs-placeholder.btn-default:active:hover{
            border-radius: 6px !important;
            display: block ;
            margin-left: 0px !important;
            margin-right: 0px !important;
            padding: 9px !important;
            right: 4px !important;

          }
          .btn-default {
            border-radius: 6px !important;
            display: block;
            margin-left: -30px !important;
            margin-right: 0px !important;
            padding: 10px!important;
            right: -27px !important
}
          #count-glyph-filtre{
            display: none;
            position: relative;
            width: 20px;
            top: 0px;
            left: 0px;
            background-color: #ff5f12;
          }

          div.filter-child-content.radio-box-filter{
            display: block;
    width: 100%;
    overflow-y: hidden;
          }
          #champ-recherche{
            background-color: #f4f6f7;
    border-radius: 4px;
    width: 100%;
    cursor: pointer;
    font-size: 0;
    width: 95%;
    margin-bottom: 11px;
    padding: 2px;
    height: 100% !important;
    padding: 7px;
    min-height: 51px;
          }

          .XaYpq {
    border: 1px solid #cad1d9;
    border-radius: 16px;
    display: inline-block;
    white-space: nowrap;
    height: 3.2rem;
    transition: background-color .2s ease-in-out,border .2s ease-in-out;
    cursor: default;
    background: #fff;
    position: relative;
    margin: 3px 2px;
    max-width: 132px;

}


.XaYpq ._1DJWB {
    position: relative;
    width: 100%;
    color: #1a1a1a;
    font-size: 0;
    line-height: 0;
    padding: 0 1.6rem;
    white-space: normal;
}
.XaYpq {
    min-width: 137px;
}
.XaYpq.oydxI ._1DJWB {
    width: calc(100% - 2.3rem + 1px);
}
.XaYpq.oydxI ._1DJWB {
    padding-right: .6rem;
}
.XaYpq ._1DJWB ._1f4ib {
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.XaYpq ._1DJWB ._1f4ib, .XaYpq ._1DJWB ._3qFDa {
    position: relative;
    display: inline-block;
    line-height: calc(3.2rem - 2px);
    height: calc(3.2rem - 2px);
    font-size: 1.4rem;
    font-weight: 400;
    vertical-align: top;
}
.P4PEa {
    line-height: 1.9rem;
}

.XaYpq ._3hxfS._3Cn9F {
    text-align: left;
}
.XaYpq ._3hxfS {
    width: 2.3rem;
    padding-top: .6rem;
    text-align: right;
    overflow: hidden;
}
.XaYpq>:not(._3hxfS), .XaYpq>:not(._30xoP) {
    white-space: normal;
}
.XaYpq ._1DJWB, .XaYpq ._3hxfS, .XaYpq ._30xoP {
    display: inline-block;
    vertical-align: top;
    height: calc(3.2rem - 2px);
    line-height: calc(3.2rem - 2px);
    font-size: 0;
}
.XaYpq ._3hxfS._3Cn9F svg {
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
}
svg:not(:root) {
    overflow: hidden;
}
.cSECIV {
    width: 1.6rem;
    height: 1.6rem;
    min-width: 1.6rem;
    fill: rgb(168, 180, 192);
    color: rgb(168, 180, 192);
}
.cSECIV {
    width: 1.6rem;
    height: 1.6rem;
    min-width: 1.6rem;
    fill: rgb(168, 180, 192);
    color: rgb(168, 180, 192);
}
.XaYpq ._3hxfS._3Cn9F {
    text-align: left;
    cursor: pointer;
}

.filter-div:hover{
    background-color: #b5b2b2a8;
}
</style>



@include("profile.profile-recherche")
