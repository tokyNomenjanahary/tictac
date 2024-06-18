<!-- Modal -->
<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content-notif">
            <div class="modal-header modal-header-search">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="modalTitleRecherche"> {{__('searchlisting.votre_recherche')}}
                 </h4>

            </div>
            <div class="modal-body">
                <div class="content-body">
                    <div class="row content-left">
                        <i class="fi fi-home filter-icon-blue"></i>
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.je_cherche') }}
                        </span>
                        <div class="filter-child-content radio-box-filter">
                            <a href="javascript:" class="@if(isset($scenario_id) && $scenario_id == 1) active @endif scenario_id_ajax type-scenario-filter type-scenario-filter-first new-filtre-scen" value="1" >{{ __('searchlisting.locataire') }}</a>
                            <a href="javascript:"  class="scenario_id_ajax type-scenario-filter new-filtre-scen @if(isset($scenario_id) && $scenario_id == 3) active @endif" value="3">{{ __('searchlisting.rent_property') }}</a>
                            <a href="javascript:" class="scenario_id_ajax type-scenario-filter new-filtre-scen @if(isset($scenario_id) && $scenario_id == 4) active @endif" value="4">{{ __('searchlisting.colocation') }}</a>
                            <a href="javascript:" class="scenario_id_ajax type-scenario-filter-last new-filtre-scen @if(isset($scenario_id) && $scenario_id == 2) active @endif" value="2">{{ __('searchlisting.colocataire') }}</a>
                        </div>
                    </div>
                    <div class="row content-left">
                        <span class="filter-child-title filter-child">
                            {{ __('Quel type de propriété cherchez-vous?') }}
                        </span>
                        <ul class="filter-check-listing">
                            @if(!empty($propertyTypes) && count($propertyTypes) > 0)
                            @foreach($propertyTypes as $propertyType)
                            <li>
                                <input class="custom-checkbox" id="proptype-checkbox-{{$propertyType->id}}" type="checkbox" value="{{$propertyType->id}}" name="prop_type[]">
                                <label for="proptype-checkbox-{{$propertyType->id}}">{{traduct_info_bdd($propertyType->property_type)}}</label>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="row content-left">
                        <i class="fi fi-euro filter-icon-blue filter-icon-blue-modal"></i>
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.votre_budget') }}
                        </span>
                        <div class="filter-child-content">
                            <input type="text" class="form-control input-rent" name="min_rent" id="min_rent" placeholder="{{__('searchlisting.min_loyer')}}">
                            <input type="text" class="form-control input-rent" name="max_rent" id="max_rent" placeholder="{{__('searchlisting.max_loyer')}}">
                        </div>
                    </div>
                    <div class="row content-left">
                        <i class="fi fi-room filter-icon-blue filter-icon-blue-modal"></i>
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.surface_habitable') }}
                        </span>
                        <div class="filter-child-content">
                            <input type="text" class="form-control input-rent" name="min_area" id="min_area" placeholder="{{__('searchlisting.min_surface')}}">
                            <input type="text" class="form-control input-rent" name="max_area" id="max_area" placeholder="{{__('searchlisting.max_surface')}}">
                        </div>
                    </div>
                    <div class="row content-left">
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.is_meuble') }}
                        </span>
                        <div class="filter-radio-listing">
                            <div class="custom-radio">
                                <input type="radio" id="f-furnished-op1" name="furnished" value="0" />
                                <label for="f-furnished-op1">{{ __('searchlisting.yes') }}</label>
                            </div>
                            <div class="custom-radio">
                                <input type="radio" id="f-furnished-op2" name="furnished" value="1" />
                                <label for="f-furnished-op2">{{ __('searchlisting.no') }}</label>
                            </div>
                            <div class="custom-radio custom-radio-big">
                                <input type="radio" id="f-furnished-op3" name="furnished" checked="" value="" />
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
                                <input type="radio" id="bedroom-op1" name="bedrooms" checked="checked" value="0" />
                                <label for="bedroom-op1">{{ __('searchlisting.any') }}</label>
                            </div>
                            <div class="custom-radio">
                                <input type="radio" id="bedroom-op2" name="bedrooms" value="1"/>
                                <label for="bedroom-op2">1+</label>
                            </div>
                            <div class="custom-radio">
                                <input type="radio" id="bedroom-op3" name="bedrooms" value="2"/>
                                <label for="bedroom-op3">2+</label>
                            </div>
                            <div class="custom-radio">
                                <input type="radio" id="bedroom-op4" name="bedrooms" value="3"/>
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
                                    <input type="radio" id="bathroom-op1" name="bathrooms" checked="checked" value="0" />
                                    <label for="bathroom-op1">{{ __('searchlisting.any') }}</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="bathroom-op2" name="bathrooms" value="1" />
                                    <label for="bathroom-op2">1+</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="bathroom-op3" name="bathrooms" value="2" />
                                    <label for="bathroom-op3">2+</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="bathroom-op4" name="bathrooms" value="3" />
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
                                <input type="radio" id="kitchen-op1" name="kitchen" value="1" />
                                <label for="kitchen-op1">{{ __('searchlisting.yes') }}</label>
                            </div>
                            <div class="custom-radio">
                                <input type="radio" id="kitchen-op2" name="kitchen" value="0" />
                                <label for="kitchen-op2">{{ __('searchlisting.no') }}</label>
                            </div>
                            <div class="custom-radio custom-radio-big">
                                <input type="radio" id="kitchen-op3" name="kitchen" checked="" value="" />
                                <label for="kitchen-op3">{{ __('searchlisting.dont_matter') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row content-left">
                        <i class="fi fi-persons filter-icon-blue filter-icon-blue-modal"></i>
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.quel_genre') }}
                        </span>
                        <ul class="filter-check-listing">
                            <li>
                                <input class="custom-checkbox" id="gender-checkbox-1" type="checkbox" value="0">
                                <label for="gender-checkbox-1">{{ __('searchlisting.men') }}</label>
                            </li>
                            <li>
                                <input class="custom-checkbox" id="gender-checkbox-2" type="checkbox" value="1" >
                                <label for="gender-checkbox-2">{{ __('searchlisting.women') }}</label>
                            </li>
                        </ul>
                    </div>
                    @if(Auth::check() && Auth::user()->provider=="facebook")
                    <div class="row content-left">
                        <i style="font-size : 1.5em;color:rgb(66,103,178);;" class="fa fa-facebook-official"></i>
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.avec_amis_en_commun') }}
                        </span>
                        <ul class="filter-check-listing">
                            <li>
                                <input class="custom-checkbox" id="fb-checkbox-1" type="checkbox" value="value2">
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
                        <ul class="filter-check-listing">
                            <li>
                                <input class="custom-checkbox" id="occup-checkbox-1" type="checkbox" value="0">
                                <label for="occup-checkbox-1">{{ __('searchlisting.student') }}</label>
                            </li>
                            <li>
                                <input class="custom-checkbox" id="occup-checkbox-2" type="checkbox" value="1" >
                                <label for="occup-checkbox-2">{{ __('filters.freelancer') }}</label>
                            </li>
                            <li>
                                <input class="custom-checkbox" id="occup-checkbox-3" type="checkbox" value="2" >
                                <label for="occup-checkbox-3">{{ __('searchlisting.salaried') }}</label>
                            </li>
                        </ul>
                    </div>
                    <div class="row content-left">
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.quel_ecole') }}
                        </span>
                        <div>
                        <input type="text" class="form-control" placeholder="{{ __('filters.prefered_school') }}" name="school_name" id="school_name"/>
                        </div>
                    </div>
                    <div class="row content-left">
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.quel_pays') }}
                        </span>
                        <div>
                            <select class="selectpicker" name="country" id="country">
                                <option value="0"></option>
                                @foreach($countries as $data)
                                <option value="{{$data->id}}">{{$data->country_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row content-left">
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.quel_ville') }}
                        </span>
                        <div>
                            <select class="selectpicker" name="city" id="city">
                                <option value="0"></option>
                            </select>
                        </div>
                    </div>
                    <div class="row content-left">
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.quel_mode_de_vie') }}
                        </span>
                        <ul class="filter-check-listing">
                                @foreach($lifestyles as $i => $data)
                                    <li>
                                        <input class="custom-checkbox lifestyle_checkbox" id="lifestyle-checkbox-{{$data->id}}" value="{{$data->id}}"  type="checkbox">
                                        <label for="lifestyle-checkbox-{{$data->id}}">{{$data->lifestyle_name}}</label>
                                    </li>
                                @endforeach
                            </ul>
                    </div>
                    <div class="row content-left">
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.quel_interet') }}
                        </span>
                        <ul class="filter-check-listing">
                            @foreach($socialInterests as $i => $data)
                                <li>
                                    <input class="custom-checkbox interest_checkbox"  id="interest-checkbox-{{$data->id}}" value="{{$data->id}}"  type="checkbox">
                                    <label for="interest-checkbox-{{$data->id}}">{{$data->interest_name}}</label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="row content-left">
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.est_il_fumeur') }}
                        </span>
                        <div>
                        <div class="custom-selectbx">
                            <select class="selectpicker" name="smoker" id="smoker">
                                <option value="0">{{ __('filters.smoker_no') }}</option>
                                <option value="1">{{ __('filters.smoker_yes') }}</option>
                                <option selected value="2">{{ __('filters.smoker_indifferent') }}</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="row content-left">
                        <i class="fi fi-heart filter-icon-blue filter-icon-blue-modal"></i>
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.apercu') }}
                        </span>
                        <div>
                          <ul class="filter-check-listing">
                                <li>
                                    <input class="custom-checkbox" id="image-chkbox" type="checkbox" value="1" name="prop_type[]">
                                    <label for="image-chkbox">{{ __('searchlisting.with_image') }}</label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row content-left">
                        <span class="filter-child-title filter-child">
                            {{ __('searchlisting.radius') }}
                        </span>
                        <div>
                           <div class="custom-range-slider">
                                <div class="range-slider-value" id="ex6CurrentSliderValLabel"><h6 id="ex6SliderVal">40</h6>{{ __('searchlisting.km') }}</div>
                                <input id="ex6" type="text" data-slider-min="1" data-slider-max="40" data-slider-step="1" data-slider-value="40"/>
                            </div>
                        </div>
                    </div>
                    <div class="row content-left">
                        <span class="filter-child-title filter-child">
                            {{ __('filters.near_by_message1') }}
                        </span>
                        <div>
                           <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                <input id="address" name="address" class="form-control" type="text" placeholder="{{ __('filters.enter_location') }}" @if(!empty($ad_details)) value="{{$ad_details->address}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->address}}" @endif>
                                <input type="hidden" id="latitude" name="latitude" @if(!empty($ad_details)) value="{{$ad_details->latitude}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->latitude}}" @endif>
                                <input type="hidden" id="longitude" name="longitude" @if(!empty($ad_details)) value="{{$ad_details->longitude}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->longitude}}" @endif>
                                <input id="actual_address" name="actual_address" type="hidden" @if(!empty($ad_details)) value="{{$ad_details->address}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->address}}" @endif>
                            </div>
                        </div>
                        <div class="near-by-field">
                            <span class="left-oriented ">{{ __('filters.near_by_message2') }}</span>
                            <div>
                            <select name="near_by_facilities[]" class="from-control js-example-basic-multiple" id="nearByFacilities" multiple="multiple" style="width: 80%">
                            </select>
                            </div>
                        </div>
                        <div class="map-search-bx" id="near-by-map">
                            <div id="gmap" class="google_map"></div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
                
        </div>
    </div>
</div>