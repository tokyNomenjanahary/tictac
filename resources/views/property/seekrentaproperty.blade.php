@extends( ($layout == 'inner') ? 'layouts.appinner' : 'layouts.app' )

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customfileupload/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customfileupload/component.css') }}" rel="stylesheet">
    <link href="{{ asset('css/intlTelInput/intlTelInput.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Autocomplete-style.css') }}">
@endpush
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

<script>
    var appSettings = {};
    @if(!empty($ad_details))
        @if(!empty($ad_details->latitude) && !empty($ad_details->longitude))
            appSettings['long'] = {{$ad_details->latitude}};
            appSettings['lat'] = {{$ad_details->longitude}};
            appSettings['address'] = '{{$ad_details->address}}';
        @endif
        @if(!empty($ad_details->nearby_facilities) && count($ad_details->nearby_facilities) > 0)
            appSettings['nearby'] = [];
            @foreach($ad_details->nearby_facilities as $nearby)
                appSettings.nearby.push("{{$nearby->latitude.'#'.$nearby->longitude.'#'.$nearby->name.'#'.$nearby->nearbyfacility_type}}");
            @endforeach
        @endif
        @if(!empty($ad_details->ad_uploaded_guarantees) && count($ad_details->ad_uploaded_guarantees) > 0)
            appSettings['ad_uploaded_gaurantees'] = [];
            @foreach($ad_details->ad_uploaded_guarantees as $key => $ad_uploaded_guarantee)
                appSettings.ad_uploaded_gaurantees.push([{{$ad_uploaded_guarantee->id}}, {{$ad_uploaded_guarantee->ad_id}}]);
            @endforeach
        @endif
    @endif
    @if(!empty($AdInfo))
        @if(!empty($AdInfo->latitude) && !empty($AdInfo->longitude))
            appSettings['long'] = {{$AdInfo->latitude}};
            appSettings['lat'] = {{$AdInfo->longitude}};
            appSettings['address'] = '{{$AdInfo->address}}';
        @endif
    @endif
    @if(!empty($address) && !empty($latitude) && !empty($longitude))
        appSettings['long'] = {{$latitude}};
        appSettings['lat'] = {{$longitude}};
        appSettings['address'] = '{{$address}}';
    @endif
</script>
 <script>
 var messages = {"rectify" : "{{__('property.rectify')}}",  "add_saved_approb" : "{{__('backend_messages.ad_success_posted')}}" , "data_saved" : "{{__('property.data_saved')}}", "error_address" : "{{__('property.error_address')}}", "login_success" : "{{__('property.login_success')}}", "phone_error" : "{{__('validator.phone_error')}}"}
</script>

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="/js/ad_url.js"></script>
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/intlTelInput.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.16.1"></script>
    <script src="{{ asset('js/seekrentaproperty.js') }}"></script>
    <script src="{{ asset('js/customfileupload/simpleUpload.min.js') }}"></script>
    <script src="/js/manage-step.js"></script>
@endpush


@section('content')
@if(!$standard)
@include('common.bandeau-message')
@endif
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 rent-property-form-outer rent-property-step-1">

                <div class="step-content step-1-content rent-property-form-content project-form rp-step-1-content white-bg m-t-20">
                    <div class="rent-property-form-hdr">

                        <div class="rent-property-form-heading">
                            <h6>{{__('property.title-chercher-logement-entier')}}</h6>
                        </div>
                        @if($standard)
                            <br />
                            <div id="wizard" class="form_wizard wizard_horizontal">
                                <ul class="wizard_steps">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <span class="step_no" style="background-color: #32ca7d;">1</span>
                                            <span class="step_descr">
            {{__('property.ad_info')}}<br />
            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <span class="step_no">2</span>
                                            <span class="step_descr">
        {{__('property.rent_property_info')}}<br />
        </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <span class="step_no">3</span>
                                            <span class="step_descr">
        {{__('property.rental_application')}}<br />
        </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- <div class="rent-property-form-step-listing share-accom-form-step-listing">
    <ul>
        <li class="step-1-menu menu seek-share-an-accc-st1 active"><a href="javascript:void(0);"><span>1</span><h6>{{__('property.ad_info')}}</h6></a></li>
        <li class="step-2-menu menu seek-share-an-accc-st2"><a href="javascript:void(0);"><span>2</span><h6>{{__('property.room_info')}}</h6></a></li>
        <li class="step-3-menu menu seek-share-an-accc-st3"><a href="javascript:void(0);"><span>3</span><h6>{{__('property.rent_property_info')}}</h6></a></li>
        <li class="step-4-menu menu seek-share-an-accc-st4"><a href="javascript:void(0);"><span>4</span><h6>{{__('property.rental_application')}}</h6></a></li>
    </ul>
</div> -->
                        @endif
                    </div>

                    <form id="firstStep" method="POST" enctype="multipart/form-data" action="{{ route('save.step1.sc3') }}">
                        {{ csrf_field() }}

                        <input type="hidden" id="scenario_id" name="scenario_id" value="3">
                        <input type="hidden" name="edite" id="button_edite" value="{{ (isset($edite)&&$edite==1)?'edite':'' }}">

                        @if(!empty($ad_details))
                            <input type="hidden" name="ad_id" value="{{$ad_details->id}}">
                        @endif
                        @if(!empty($AdInfo))
                            <input type="hidden" name="redirected_ad_id" value="{{$AdInfo->id}}">
                        @endif
                        <input type="hidden" id="contact-continue" name="contact_continue" value="0">
                        <div class="heading-underline">
                            <h6>{{__('property.basic_info')}}</h6>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="address">{{__('property.search_for_adress')}} *</label>
                            <div class="input-group" id="autocomplete-container">
                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                <input id="address" name="address" class="form-control" type="text" placeholder="{{ __('property.placeholder_adress') }}" @if(!empty($ad_details)) value="{{$ad_details->address}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->address}}" @endif @if(!empty($address)) value="{{$address}}" @endif>
                                <input type="hidden" id="latitude" name="latitude" @if(!empty($ad_details)) value="{{$ad_details->latitude}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->latitude}}" @endif @if(!empty($latitude)) value="{{$latitude}}" @endif>
                                <input type="hidden" id="longitude" name="longitude" @if(!empty($ad_details)) value="{{$ad_details->longitude}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->longitude}}" @endif @if(!empty($longitude)) value="{{$longitude}}" @endif>
                                <input id="actual_address" name="actual_address" type="hidden" @if(!empty($ad_details)) value="{{$ad_details->address}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->address}}" @endif @if(!empty($address)) value="{{$address}}" @endif>
                            </div>
                        </div>
                        <!-- <div class="heading-underline m-t-0">
                            <h6>{{__('property.basic_info')}}</h6>
                        </div> -->
                        <div class="form-group">
                            <label class="control-label" for="title">{{__('property.title')}} *</label>
                            <input type="text"  class="form-control" id="title" placeholder="{{ __('Enter Title') }}" name="title" @if(!empty($ad_details)) value="{{$ad_details->title}}" @endif autofocus>
                        </div>
                        @if(isSuperUser())
                        <div class="form-group">
                            <label class="control-label" for="title">Ad Url</label>
                            <input type="text" class="form-control" id="ad_url" placeholder="Url de l'annonce" name="ad_url" @if(!empty($ad_details)) value="{{$ad_details->custom_url}}" @endif autofocus>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label" for="description">{{__('property.description')}} *</label>
                            <textarea id="description"  name="description" class="form-control" placeholder="{{__('property.description_placeholder')}}" rows="6">@if(!empty($ad_details)){{$ad_details->description}}@endif</textarea>
                        </div>
                        <!-- point of proximity -->
                        <div class="form-group">
                            <label class="control-label" for="proximity">{{ __('property.point_proximity') }}</label>
                            <div class="custom-selectbx">
                                <select id="proximity" placeholder="{{__('filters.no_selected')}}" name="proximity[]" class="sumo-select" multiple="">
                                    @if(count($proximities)>0)
                                        @foreach($proximities as $proximity)
                                            <option value="{{$proximity->id}}" {{ in_array($proximity->id, $proximities_array) ? 'selected' : '' }}>{{ $proximity->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        @if(!$standard)
                        <div class="form-group">
                            <label class="control-label" for="rent_per_month">{{ __('property.rent') }} *</label>
                            <input type="number" min="1" class="form-control" id="rent_per_month_standard"
                                   placeholder="{{get_current_symbol()}}" name="rent_per_month"
                                   @if(!empty($ad_details)) value="{{$ad_details->min_rent}}" @endif>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="sous_loc_type">{{ __('property.type_location') }} *</label>
                            <div class="custom-selectbx">
                                <select id="sous_loc_type" name="sous_loc_type" class="selectpicker">
                                    @foreach($sous_type_loc as $data)
                                    @if(!empty($ad_details) && $ad_details->sous_type_loc == $data->id)
                                    <option selected value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                    @else
                                    <option value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="property_type">{{ __('property.property_type') }} *</label>
                            <div class="custom-selectbx">
                                <select id="property_type" name="property_type" class="selectpicker">
                                    @foreach($propertyTypes as $data)
                                    @if(!empty($ad_details) && $ad_details->ad_details->property_type_id == $data->id)
                                    <option selected value="{{$data->id}}">{{traduct_info_bdd($data->property_type)}}</option>
                                    @else
                                    <option value="{{$data->id}}">{{traduct_info_bdd($data->property_type)}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group radio2-width">
                            <label class="control-label">{{ __('property.furnished_or_unfurnished') }}? *</label>
                            <div class="custom-selectbx">
                                <select id="furnished"  placeholder="{{__('filters.no_selected')}}" name="furnished[]" class="sumo-select" multiple="">
                                    <option value="0">{{ __('property.furnished') }}</option>
                                    <option value="1">{{ __('property.unfurnished') }}</option>
                                </select>
                            </div>
                            <label id="furnished-custom_error" hidden class="custom-error">{{__('validator.required')}}</label>
                        </div>
                        @endif

                        <!-- <div id="results"></div>
                        <div class="form-group">
                            <span class="left-oriented">{{__('property.near_by_message')}}</span>
                            <select name="near_by_facilities[]" class="from-control js-example-basic-multiple" id="nearByFacilities" multiple="multiple" style="width: 100%">
                            </select>
                        </div> -->
                        <div class="form-group radio2-width">
                            <label class="control-label">{{__('property.if_you_accept')}} *</label>
                            <div class="filter-radio-listing">
                                @if(!empty($ad_details) && $ad_details->ad_details->accept_as == '0')

                                <div class="custom-radio">
                                    <input type="radio" id="accept-radio-2" autocomplete="off" name="accept_as" value="1" />
                                    <label for="accept-radio-2">{{__('property.landlord')}}</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="accept-radio-3" autocomplete="off" name="accept_as" value="2" />
                                    <label for="accept-radio-3">{{__('property.agent')}}</label>
                                </div>
                                @elseif(!empty($ad_details) && $ad_details->ad_details->accept_as == '2')

                                <div class="custom-radio">
                                    <input type="radio" id="accept-radio-2" autocomplete="off" name="accept_as" value="1" />
                                    <label for="accept-radio-2">{{__('property.landlord')}}</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="accept-radio-3" autocomplete="off" name="accept_as" checked="checked" value="2" />
                                    <label for="accept-radio-3">{{__('property.agent')}}</label>
                                </div>
                                @else

                                <div class="custom-radio">
                                    <input type="radio" id="accept-radio-2" autocomplete="off" name="accept_as" checked="checked" value="1" />
                                    <label for="accept-radio-2">{{__('property.landlord')}}</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="accept-radio-3" autocomplete="off" name="accept_as" value="2" />
                                    <label for="accept-radio-3">{{__('property.agent')}}</label>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if(!$standard)
                        <div class="form-group">
                            <label class="control-label" for="guarantee_type_1">{{ __('property.type_garantie') }}</label>
                            <div class="custom-selectbx">
                                <select id="guarantee_type_1"  placeholder="{{__('filters.no_selected')}}" name="guarantee_type[]" class="sumo-select" multiple="">
                                    @foreach($guarAsked as $data)
                                    <option value="{{$data->id}}">{{traduct_info_bdd($data->guarantee)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label id="guarantee_type_1-custom_error" hidden class="custom-error" for="social_interests">{{__('validator.required')}}</label>
                        </div>
                        @endif
                        @include("property.register_login")
                        <input type="hidden" name="button_clicked" id="button_clicked" value="">
                        <div class="pr-form-ftr">
                            @if($standard)
                            {{-- <div class="submit-btn-1 save-btn"><input type="submit" name="Save" value="{{__('property.save')}}" id="save_button_st_1" /></div> --}}
                            <div class="submit-btn-1 save-nxt-btn"><input type="submit" name="Save & Next" value="{{__('property.save_next')}}" id="savenext_button_st_1" /></div>
                            @else
                            <div class="submit-btn-1 save-nxt-btn"><input type="submit" name="Save" value="{{ __('property.save') }}" id="save_button_standard" /></div>
                            @endif
                        </div>
                    </form>
                </div>

                @if($standard)
                <div class="step-content step-2-content rent-property-form-content project-form rp-step-2-content white-bg m-t-20">
                    <div class="rent-property-form-hdr">

                        <div class="rent-property-form-heading">
                            <h6>{{__('property.seek_share_accomodation')}}</h6>
                        </div>
                        @if($standard)
                        <br />
                        <div id="wizard" class="form_wizard wizard_horizontal">
                            <ul class="wizard_steps">
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="step_no"style="background-color: #32ca7d;">1</span>
                                        <span class="step_descr">
            {{__('property.ad_info')}}<br />
            </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="step_no"  style="background-color: #32ca7d;">2</span>
                                        <span class="step_descr">
        {{__('property.rent_property_info')}}<br />
        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="step_no">3</span>
                                        <span class="step_descr">
        {{__('property.rental_application')}}<br />
        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </div>

                    <form id="secondtStep" method="POST" enctype="multipart/form-data" action="{{ route('save.step2.sc3') }}">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="property_type">{{__('property.property_type')}} *</label>
                                    <div class="custom-selectbx">
                                        <select id="property_type" name="property_type" class="selectpicker">
                                            @foreach($propertyTypes as $data)
                                            @if(!empty($ad_details) && $ad_details->ad_details->property_type_id == $data->id)
                                            <option selected value="{{$data->id}}">{{traduct_info_bdd($data->property_type)}}</option>
                                            @else
                                            <option value="{{$data->id}}">{{traduct_info_bdd($data->property_type)}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="rent_per_month">{{__('property.min_rent')}}</label>
                                    <input type="text" class="form-control" id="rent_per_month" placeholder="{{get_current_symbol()}}" name="rent_per_month" @if(!empty($ad_details)) value="{{$ad_details->min_rent}}" @endif>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="rent_per_month_max">{{__('property.max_rent')}}</label>
                                    <input type="text" class="form-control" id="rent_per_month_max" placeholder="{{get_current_symbol()}}" name="rent_per_month_max" @if(!empty($ad_details)) value="{{$ad_details->max_rent}}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="sous_loc_type">{{ __('property.type_location') }} *</label>
                                <div class="custom-selectbx">
                                    <select id="sous_loc_type" name="sous_loc_type" class="selectpicker">
                                        @foreach($sous_type_loc as $data)
                                        @if(!empty($ad_details) && $ad_details->sous_type_loc == $data->id)
                                        <option selected value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                        @else
                                        <option value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="prop_square_meters">{{__('property.minim_surface_area')}} *</label>
                                    <input id="prop_square_meters" type="text" class="form-control" placeholder="{{__('property.minim_square_meters')}}" name="prop_square_meters" @if(!empty($ad_details)) value="{{$ad_details->ad_details->min_surface_area}}" @endif>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="prop_square_meters_max">{{__('property.maxim_surface_area')}}</label>
                                    <input id="prop_square_meters_max" type="text" class="form-control" placeholder="{{__('property.maxim_square_meters')}}" name="prop_square_meters_max" @if(!empty($ad_details)) value="{{$ad_details->ad_details->max_surface_area}}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('property.min_bedroom')}} *</label>
                            <div class="filter-radio-listing">
                                @for ($i = 1; $i <= 11; $i++)
                                <div class="custom-radio">
                                    @if(!empty($ad_details) && !is_null($ad_details->ad_details->bedrooms) && $ad_details->ad_details->bedrooms!=0)
                                    @if($ad_details->ad_details->bedrooms == $i)
                                    <input type="radio" id="rpt-bedroom-op{{$i}}" name="no_of_bedrooms" autocomplete="off" checked="checked" value="{{$i}}" />
                                    @else
                                    <input type="radio" id="rpt-bedroom-op{{$i}}" name="no_of_bedrooms" autocomplete="off" value="{{$i}}" />
                                    @endif
                                    @else
                                    <input type="radio" id="rpt-bedroom-op{{$i}}" name="no_of_bedrooms" autocomplete="off" @if ($i==1) checked="checked" @endif value="{{$i}}" />
                                    @endif
                                    <label for="rpt-bedroom-op{{$i}}">@if ($i==11)10+@else{{$i}}@endif @if ($i==1) {{ __('property.bed') }} @else {{ __('property.beds') }} @endif </label>
                                </div>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('property.min_bathroom')}}  *</label>
                            <div class="filter-radio-listing">
                                @for ($i = 1; $i <= 6; $i++)
                                <div class="custom-radio">
                                    @if(!empty($ad_details) && !is_null($ad_details->ad_details->bathrooms) && $ad_details->ad_details->bathrooms!=0)
                                    @if($ad_details->ad_details->bathrooms == $i)
                                    <input type="radio" name="no_of_bathrooms" autocomplete="off" id="rpt-bathroom-op{{$i}}" checked="checked" value="{{$i}}" />
                                    @else
                                    <input type="radio" name="no_of_bathrooms" autocomplete="off" id="rpt-bathroom-op{{$i}}" value="{{$i}}" />
                                    @endif
                                    @else
                                    <input type="radio" name="no_of_bathrooms" autocomplete="off" id="rpt-bathroom-op{{$i}}" @if ($i==1) checked="checked" @endif value="{{$i}}" />
                                    @endif
                                    <label for="rpt-bathroom-op{{$i}}">{{$i}}</label>
                                </div>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('property.pet_allowed')}}</label>
                            <ul class="property-filter-check-listing">
                                @foreach($petsAllowed as $data)
                                <li>
                                    @if(!empty($allowed_pets_array) && in_array($data->id, $allowed_pets_array))
                                    <input class="custom-checkbox" id="prpet-checkbox-{{$data->id}}" type="checkbox" name="allowed_pets[]" checked="checked" value="{{$data->id}}">
                                    @else
                                    <input class="custom-checkbox" id="prpet-checkbox-{{$data->id}}" type="checkbox" name="allowed_pets[]" value="{{$data->id}}">
                                    @endif
                                    <label for="prpet-checkbox-{{$data->id}}">{{traduct_info_bdd($data->petname)}}</label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('property.property_features')}}</label>
                            <ul class="property-feature-check-listing">
                                @foreach($propFeatures as $data)
                                <li>
                                    @if(!empty($property_features_array) && in_array($data->id, $property_features_array))
                                    <input class="custom-checkbox" id="pf-checkbox-{{$data->id}}" type="checkbox" checked="checked" name="prop_feature[]" value="{{$data->id}}">
                                    @else
                                    <input class="custom-checkbox" id="pf-checkbox-{{$data->id}}" type="checkbox" name="prop_feature[]" value="{{$data->id}}">
                                    @endif
                                    <label for="pf-checkbox-{{$data->id}}">@if(!empty($data->icon_image))<img src="{{URL::asset('images/icons/' . $data->icon_image )}}" alt="{{$data->feature}}">@endif{{traduct_info_bdd($data->feature)}}</label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('property.building_amneties')}}</label>
                            <ul class="property-feature-check-listing">
                                @foreach($buildAmenities as $data)
                                <li>
                                    @if(!empty($amenities_array) && in_array($data->id, $amenities_array))
                                    <input class="custom-checkbox" id="ba-checkbox-{{$data->id}}" type="checkbox" name="building_amenities[]" checked="checked" value="{{$data->id}}">
                                    @else
                                    <input class="custom-checkbox" id="ba-checkbox-{{$data->id}}" type="checkbox" name="building_amenities[]" value="{{$data->id}}">
                                    @endif
                                    <label for="ba-checkbox-{{$data->id}}">@if(!empty($data->icon_image))<img src="{{URL::asset('images/icons/' . $data->icon_image )}}" alt="{{$data->amenity}}">@endif{{traduct_info_bdd($data->amenity)}}</label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{__('property.do_you_need_separated_kitchen')}}?</label>
                                    <div class="ks-checkbox-bx">
                                        @if(!empty($ad_details) && $ad_details->ad_details->kitchen_separated == '1')
                                        <input class="custom-checkbox" id="ks-checkbox-1" type="checkbox" name="separate_kitchen" checked="checked" value="1">
                                        @else
                                        <input class="custom-checkbox" id="ks-checkbox-1" type="checkbox" name="separate_kitchen" value="1">
                                        @endif
                                        <label for="ks-checkbox-1">{{__('property.yes')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{__('property.date_availability')}} *&nbsp;<a href="javascript://;" data-toggle="tooltip" data-placement="top" title="{{ __('Date of availablity of the property') }}"><i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a></label>
                                    <div class="datepicker-outer">
                                        <div id="datepicker-1" class="custom-datepicker">
                                            <input class="form-control" type="text" id="date_of_availablity" @if(!empty($ad_details) && !empty($ad_details->available_date)) value="{{date("d/m/Y", strtotime($ad_details->available_date))}}" @endif name="date_of_availablity" placeholder="dd/mm/yyyy">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{__('property.property_rules')}}</label>
                            <ul class="property-feature-check-listing">
                                @foreach($propRules as $data)
                                <li>
                                    @if(!empty($property_rules_array) && in_array($data->id, $property_rules_array))
                                    <input class="custom-checkbox" id="pr-checkbox-{{$data->id}}" type="checkbox" name="property_rules[]" checked="checked" value="{{$data->id}}">
                                    @else
                                    <input class="custom-checkbox" id="pr-checkbox-{{$data->id}}" type="checkbox" name="property_rules[]" value="{{$data->id}}">
                                    @endif
                                    <label for="pr-checkbox-{{$data->id}}">@if(!empty($data->icon_image))<img src="{{URL::asset('images/icons/' . $data->icon_image )}}" alt="{{$data->rules}}">@endif{{traduct_info_bdd($data->rules)}}</label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="form-group radio2-width">
                            <label class="control-label">{{__('property.furnished_or_unfurnished')}}? *</label>
                            <div class="custom-selectbx">
                                <select id="furnished" sumo-required="true" placeholder="{{__('filters.no_selected')}}" name="furnished[]" class="sumo-select" multiple="">

                                    <option @if(!empty($ad_details) && ($ad_details->ad_details->furnished == '0' || $ad_details->ad_details->furnished == '2')) selected="" @endif value="0">{{ __('property.furnished') }}</option>
                                    <option @if(!empty($ad_details) && ($ad_details->ad_details->furnished == '1' || $ad_details->ad_details->furnished == '2')) selected="" @endif value="1">{{ __('property.unfurnished') }}</option>
                                </select>
                            </div>
                            <label id="furnished-custom_error" hidden class="custom-error">{{__('validator.required')}}</label>
                        </div>
                        <input type="hidden" name="button_clicked" id="button_clicked" value="">
                        <div class="pr-form-ftr">
                            <div class="submit-btn-1 save-btn"><input type="submit" name="Save" id="save_button_st_2" value="{{__('property.save')}}" /></div>
                            <div class="submit-btn-1 back-btn"><input type="button" name="Back" value="{{__('property.back')}}" id="back_button_st2" /></div>
                            <div class="submit-btn-1 save-nxt-btn"><input type="submit" name="Save & Next" id="savenext_button_st_2" value="{{__('property.save_next')}}" /></div>
                        </div>
                    </form>
                </div>
                <div class="step-content step-3-content rent-property-form-content project-form rp-step-3-content white-bg m-t-20">
                    <div class="rent-property-form-hdr">

                        <div class="rent-property-form-heading">
                            <h6>{{__('property.seek_share_accomodation')}}</h6>
                        </div>
                        @if($standard)
                        <br />
                        <div id="wizard" class="form_wizard wizard_horizontal">
                            <ul class="wizard_steps">
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="step_no"style="background-color: #32ca7d;">1</span>
                                        <span class="step_descr">
                                {{__('property.ad_info')}}<br />
                                </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="step_no" style="background-color: #32ca7d;">2</span>
                                        <span class="step_descr">
                            {{__('property.rent_property_info')}}<br />
                            </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="step_no" style="background-color: #32ca7d;">3</span>
                                        <span class="step_descr">
                            {{__('property.rental_application')}}<br />
                            </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </div>

                    <form id="thirdtStep" method="POST" enctype="multipart/form-data" action="{{ route('save.step3.sc3') }}">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="actual_renting_price">{{__('property.actual_rent_price')}} *</label>
                                    <input type="text" class="form-control" id="actual_renting_price" placeholder="{{get_current_symbol()}}" name="actual_renting_price" @if(!empty($ad_details) && count($ad_details->ad_rental_applications->get()) > 0) value="{{$ad_details->ad_rental_applications->actual_renting_price}}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="heading-underline m-t-0">
                            <h6>{{__('property.professional_details')}}</h6>
                        </div>
                        <div class="form-group radio2-width">
                            <div class="filter-radio-listing">
                                @if(!empty($ad_details) && count($ad_details->ad_rental_applications->get()) > 0 && $ad_details->ad_rental_applications->occupation == '1')
                                <div class="custom-radio">
                                    <input type="radio" id="occupation-1" name="occupation" autocomplete="off" class="check-occupation" checked="checked" value="1" />
                                    <label for="occupation-1">{{__('property.professional')}}</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="occupation-2" autocomplete="off" name="occupation" class="check-occupation" value="0" />
                                    <label for="occupation-2">{{__('property.student')}}</label>
                                </div>
                                @else
                                <div class="custom-radio">
                                    <input type="radio" id="occupation-1" name="occupation" autocomplete="off" class="check-occupation" value="1" />
                                    <label for="occupation-1">{{__('property.professional')}}</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="occupation-2" autocomplete="off" name="occupation" class="check-occupation" checked="checked" value="0" />
                                    <label for="occupation-2">{{__('property.student')}}</label>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row div-professional @if((!empty($ad_details) && count($ad_details->ad_rental_applications->get()) > 0 && $ad_details->ad_rental_applications->occupation == '0') ||  (!empty($ad_details) && count($ad_details->ad_rental_applications->get()) == 0) || empty($ad_details)) div-etudiant-hide @endif">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="job_title">{{__('property.job_title')}} *</label>
                                    <input type="text" class="form-control" id="job_title" placeholder="{{__('property.job_title_placeholder')}}" name="job_title" @if(!empty($ad_details) && count($ad_details->ad_rental_applications->get()) > 0) value="{{$ad_details->ad_rental_applications->job_title}}" @endif>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="monthly_salary">{{__('property.monthly_salary')}}</label>
                                    <input type="text" class="form-control" id="monthly_salary" placeholder="{{get_current_symbol()}}" name="monthly_salary" @if(!empty($ad_details) && count($ad_details->ad_rental_applications->get()) > 0 && $ad_details->ad_rental_applications->monthly_salary != null) value="{{$ad_details->ad_rental_applications->monthly_salary}}" @endif>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{__('property.start_date')}}</label>
                                    <div class="datepicker-outer">
                                        <div id="datepicker-1" class="custom-datepicker">
                                            <input class="form-control" type="text" id="start_date" @if(!empty($ad_details) && count($ad_details->ad_rental_applications->get()) > 0 && $ad_details->ad_rental_applications->start_date != null) value="{{date("d/m/Y", strtotime($ad_details->ad_rental_applications->start_date))}}" @endif name="start_date" placeholder="dd/mm/yyyy">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row div-professional @if((!empty($ad_details) && count($ad_details->ad_rental_applications->get()) > 0 && $ad_details->ad_rental_applications->occupation == '0') ||  (!empty($ad_details) && count($ad_details->ad_rental_applications->get()) == 0) || empty($ad_details)) div-etudiant-hide @endif">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="company_name">{{__('property.company_name')}}</label>
                                    <input type="text" class="form-control" id="company_name" placeholder="{{__('property.company_placeholder')}}" name="company_name" @if(!empty($ad_details) && count($ad_details->ad_rental_applications->get()) > 0 && $ad_details->ad_rental_applications->company_name != null) value="{{$ad_details->ad_rental_applications->company_name}}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="heading-underline m-t-0">
                            <h6>{{ __('property.type_garantie') }}</h6>
                        </div>
                        <div class="form-group">
                            <div class="custom-selectbx">
                                <select id="guarantee_type_1" placeholder="{{__('filters.no_selected')}}" name="guarantee_type[]" class="sumo-select" multiple="">
                                    @foreach($guarAsked as $data)
                                    @if(!empty($guarantees_array) && in_array($data->id, $guarantees_array))
                                    <option selected value="{{$data->id}}">{{traduct_info_bdd($data->guarantee)}}</option>
                                    @else
                                    <option value="{{$data->id}}">{{traduct_info_bdd($data->guarantee)}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <label id="guarantee_type_1-custom_error" hidden class="custom-error" for="social_interests" style="">{{__('validator.required')}}</label>
                        </div>
                        <input type="hidden" name="button_clicked" id="button_clicked" value="">
                        @guest
                        <input type="hidden" name="user" value="guest">
                        @else
                        <input type="hidden" name="user" value="logged_in">
                        @endguest
                        <div class="pr-form-ftr">
                            <div class="submit-btn-1 save-btn"><input type="submit" name="Save" value="{{__('property.save')}}" id="save_button_st_3" /></div>
                            <div class="submit-btn-1 back-btn"><input type="submit" name="Back" value="{{__('property.back')}}" id="back_button_st3" /></div>
                            <div class="submit-btn-1 save-nxt-btn"><input type="submit" name="Publish" @if(!empty($ad_details)) value="{{__('property.save_publish')}}" @else value="{{__('property.publish')}}" @endif id="savenext_button_st_3" /></div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
<script src="/js/Autocomplete-js.js"></script>
@include('property.information-modal')

@include('property.step-management')
@endsection

