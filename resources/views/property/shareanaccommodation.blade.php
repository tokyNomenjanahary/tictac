@extends( ($layout == 'inner') ? 'layouts.appinner' : 'layouts.app' )

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="{{ asset('css/intlTelInput/intlTelInput.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Autocomplete-style.css') }}">

@endpush

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
        @if(!empty($ad_details) && !empty($ad_details->ad_visiting_details) && count($ad_details->ad_visiting_details) > 0)
            appSettings['visit_details'] = {{count($ad_details->ad_visiting_details)}};
        @endif
        @if(!empty($ad_details) && !empty($ad_details->ad_files) && count($ad_details->ad_files) > 0)
            appSettings['ad_files'] = [];
            appSettings['ad_videos'] = [];
            @foreach($ad_details->ad_files as $ad_file)
                @if(file_exists(storage_path('uploads/images_annonces/' . $ad_file->filename)))
                @if($ad_file->media_type == '0')
                    filesize = {{filesize(storage_path('uploads/images_annonces/' . $ad_file->filename))}}
                    appSettings.ad_files.push([{{$ad_file->id}}, "{{$ad_file->filename}}", "{{$ad_file->user_filename}}", filesize]);
                @elseif($ad_file->media_type == '1')
                    filesize = {{filesize(storage_path('uploads/videos_annonces/' . $ad_file->filename))}}
                    appSettings.ad_videos.push([{{$ad_file->id}}, "{{$ad_file->filename}}", "{{$ad_file->user_filename}}", filesize]);
                @endif
                @endif
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
 var messages = {"rectify" : "{{__('property.rectify')}}",  "add_saved_approb" : "{{__('backend_messages.ad_success_posted')}}" ,"data_saved" : "{{__('property.data_saved')}}", "error_address" : "{{__('property.error_address')}}", "login_success" : "{{__('property.login_success')}}", "phone_error" : "{{__('validator.phone_error')}}"}
</script>

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="/js/ad_url.js"></script>
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/intlTelInput.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.16.1"></script>
    <script src="{{ asset('js/shareanacco.js') }}"></script>
    <script src="/js/manage-step.js"></script>
    <script src="/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script>
    <script src="/js/metro_autocomplete.js"></script>
    <script src="/js/rotate-image.js"></script>
@endpush
@include("common.fileInputMessages")
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
    <h6>{{ __('property.share_an_accomodation') }}</h6>
</div>
@if($standard)
<br />
<div id="wizard" class="form_wizard wizard_horizontal">
  <ul class="wizard_steps">
    <li>
      <a href="javascript:void(0);">
        <span class="step_no" style="background-color: #32ca7d;">1</span>
            <span class="step_descr">
            {{ __('property.property_info') }}<br />
            </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no">2</span>
        <span class="step_descr">
        {{ __('property.room_info') }}<br />
        </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no">3</span>
        <span class="step_descr">
        {{ __('property.property_features') }}<br />
        </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no">4</span>
        <span class="step_descr">
        {{ __('property.visit_details') }}<br />
        </span>
      </a>
    </li>
  </ul>
</div>
<!-- <div class="rent-property-form-step-listing share-accom-form-step-listing">
    <ul>
        <li class="step-1-menu menu share-an-accc-st1 active"><a href="javascript:void(0);"><span>1</span><h6>{{ __('property.property_info') }}</h6></a></li>
        <li class="step-2-menu menu share-an-accc-st2"><a href="javascript:void(0);"><span>2</span><h6>{{ __('property.room_info') }}</h6></a></li>
        <li class="step-3-menu menu share-an-accc-st3"><a href="javascript:void(0);"><span>3</span><h6>{{ __('property.property_features') }}</h6></a></li>
        <li class="step-4-menu menu share-an-accc-st4"><a href="javascript:void(0);"><span>4</span><h6>{{ __('property.visit_details') }}</h6></a></li>
    </ul>
</div> -->
@endif
</div>
                    <form id="firstStep" method="POST" enctype="multipart/form-data" action="{{ route('save.step1.sc2') }}">
                        {{ csrf_field() }}

                        <input type="hidden" id="scenario_id" name="scenario_id" value="2">
                        <input type="hidden" name="edite" id="button_edite" value="{{ (isset($edite)&&$edite==1)?'edite':'' }}">
                        @if(!empty($ad_details))
                            <input type="hidden" name="ad_id" value="{{$ad_details->id}}">
                        @endif
                        @if(!empty($AdInfo))
                            <input type="hidden" name="redirected_ad_id" value="{{$AdInfo->id}}">
                        @endif
                        <input type="hidden" id="contact-continue" name="contact_continue" value="0">
                        <div class="heading-underline">
                            <h6>{{ __('property.basic_info') }}</h6>
                        </div>
                        <div class="form-group" >
                            <label class="control-label" for="address">{{ __('property.quel_adresse_louer') }} *</label>
                            <div class="input-group" id="autocomplete-container">
                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                <input id="address" name="address" class="form-control" type="text" placeholder="{{ __('property.placeholder_adress') }}" @if(!empty($ad_details)) value="{{$ad_details->address}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->address}}" @endif @if(!empty($address)) value="{{$address}}" @endif>
                                <input type="hidden" id="latitude" name="latitude" @if(!empty($ad_details)) value="{{$ad_details->latitude}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->latitude}}" @endif @if(!empty($latitude)) value="{{$latitude}}" @endif>
                                <input type="hidden" id="longitude" name="longitude" @if(!empty($ad_details)) value="{{$ad_details->longitude}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->longitude}}" @endif @if(!empty($longitude)) value="{{$longitude}}" @endif>
                                <input id="actual_address" name="actual_address" type="hidden" @if(!empty($ad_details)) value="{{$ad_details->address}}" @endif @if(!empty($AdInfo)) value="{{$AdInfo->address}}" @endif @if(!empty($address)) value="{{$address}}" @endif>
                            </div>
                        </div>
                        <!-- <div class="heading-underline m-t-0">
                            <h6>{{ __('property.basic_info') }}</h6>
                        </div> -->
                        <div class="form-group">
                            <label class="control-label" for="title">{{ __('property.title') }} *</label>
                            <input type="text" class="form-control" id="title" placeholder="{{ __('property.title_placeholder') }}" name="title" @if(!empty($ad_details)) value="{{$ad_details->title}}" @endif autofocus>
                        </div>
                        @if(isSuperUser())
                        <div class="form-group">
                            <label class="control-label" for="title">Ad Url</label>
                            <input type="text" class="form-control" id="ad_url" placeholder="Url de l'annonce" name="ad_url" @if(!empty($ad_details)) value="{{$ad_details->custom_url}}" @endif autofocus>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label" for="description">{{ __('property.description') }} *</label>
                            <textarea id="description" name="description" class="form-control" placeholder="{{ __('property.description_placeholder') }}" rows="6">@if(!empty($ad_details)){{$ad_details->description}}@endif</textarea>
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
                        <div class="form-group">
                            <label class="control-label" for="rent_per_month">{{ __('property.metro_lines') }}</label>
                            <div class="div-metro">
                                <input type="text" class="form-control metro_lines" id="metro_line" placeholder="{{ __('property.metro_lines_placeholder') }}" name="metro_line">
                                <button type="button" id="btn-add-metro" class="btn btn-add-metro">
                                        <i class="glyphicon glyphicon-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div id="metro-data" class="metro-data">
                             @if(!empty($ad_details->nearby_facilities) && count($ad_details->nearby_facilities) > 0)
                             @foreach($ad_details->nearby_facilities as $key => $nearby)
                             @if($nearby->nearbyfacility_type == "subway_station")
                             <div class="metro-elem">{{$nearby->name}}<a href="javascript:" class="close-metro">x</a><input type="hidden" name="metro_lines[]" value="{{$nearby->name}}"/></div>
                             @endif
                             @endforeach
                             @endif
                        </div>
                        @if(!$standard)
                        <div class="form-group">
                            <label class="control-label" for="rent_per_month">{{ __('property.rent') }}</label>
                            <input type="text" class="form-control" id="rent_per_month" placeholder="{{get_current_symbol()}}"
                                   name="rent_per_month" @if(!empty($ad_details)) value="{{$ad_details->min_rent}}" @endif>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="property_type">{{ __('property.property_type') }} *</label>
                            <div class="custom-selectbx">
                                <select id="property_type" name="property_type" class="selectpicker">
                                    @foreach($propertyTypes as $data)
                                    @if(!empty($ad_details) && $ad_details->ad_details->property_type_id == $data->id)
                                    <option selected value="{{$data->id}}">{{$data->property_type}}</option>
                                    @else
                                    <option value="{{$data->id}}">{{traduct_info_bdd($data->property_type)}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group radio2-width">
                            <label class="control-label">{{ __('property.furnished_or_unfurnished') }}? *</label>
                            <div class="filter-radio-listing">

                                <div class="custom-radio">
                                    <input type="radio" id="fr-un-1" name="furnished" autocomplete="off" value="0" />
                                    <label for="fr-un-1">{{ __('property.furnished') }}</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="fr-un-2" autocomplete="off" name="furnished" checked="checked" value="1" />
                                    <label for="fr-un-2">{{ __('property.unfurnished') }}</label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="control-label" for="sous_loc_type">{{ __('property.type_location') }} *</label>
                            <div class="custom-selectbx">
                                <?php $sous_type_loc=getTypeLocation(); ?>
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


                        <!-- <div id="results"></div>
                        <div class="form-group">
                            <label class="control-label" style="width: 100%" for="nearByFacilities">{{ __('property.near_by_message') }}</label>
                            <span class="left-oriented">{{ __('property.near_by_placeholder') }}</span>
                            <select name="near_by_facilities[]" class="from-control js-example-basic-multiple" id="nearByFacilities" multiple="multiple" style="width: 100%">
                            </select>
                        </div> -->
                        @if(!$standard)
                        <div class="form-group">
                            <label class="control-label">{{ __('property.upload_photos') }}</label>
                            <div class="upload-photo-outer">
                                <div class="file-loading">
                                    <input id="file-photos" type="file" multiple class="file" data-overwrite-initial="false" name="file_photos[]" accept="image/*">
                                </div>
                            </div>
                            <div class="upload-photo-listing">
                                <p>**{{ __('property.upload_photos_message') }}.</p>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="control-label">{{ __('property.upload_video') }}</label>
                            <div class="upload-photo-outer">
                                <div class="file-loading">
                                    <input id="file-video" type="file" class="file" data-overwrite-initial="false" name="file_video[]" accept="video/*">
                                </div>
                            </div>
                            <div class="upload-photo-listing">
                                <p>**{{ __('property.upload_video_message') }}.</p>
                            </div>
                        </div> -->
                        @endif
                        @include("property.register_login")
                        <input type="hidden" name="button_clicked" id="button_clicked" value="">
                        <div class="pr-form-ftr">
                             @if($standard)
                            <!-- <div class="submit-btn-1 save-btn"><input type="submit" name="Save" value="{{ __('property.save') }}" id="save_button_st_1" /></div> -->
                            <div class="submit-btn-1 save-nxt-btn"><input type="submit" name="Save & Next" value="{{ __('property.save_next') }}" id="savenext_button_st_1" /></div>
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
    <h6>{{ __('property.share_an_accomodation') }}</h6>
</div>
@if($standard)
<br />
<div id="wizard" class="form_wizard wizard_horizontal">
  <ul class="wizard_steps">
    <li>
      <a href="javascript:void(0);">
        <span class="step_no" style="background-color: #32ca7d;">1</span>
            <span class="step_descr">
            {{ __('property.property_info') }}<br />
            </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no" style="background-color: #32ca7d;">2</span>
        <span class="step_descr">
        {{ __('property.room_info') }}<br />
        </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no">3</span>
        <span class="step_descr">
        {{ __('property.property_features') }}<br />
        </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no">4</span>
        <span class="step_descr">
        {{ __('property.visit_details') }}<br />
        </span>
      </a>
    </li>
  </ul>
</div>
<!-- <div class="rent-property-form-step-listing share-accom-form-step-listing">
    <ul>
        <li class="step-1-menu menu share-an-accc-st1 active"><a href="javascript:void(0);"><span>1</span><h6>{{ __('property.property_info') }}</h6></a></li>
        <li class="step-2-menu menu share-an-accc-st2"><a href="javascript:void(0);"><span>2</span><h6>{{ __('property.room_info') }}</h6></a></li>
        <li class="step-3-menu menu share-an-accc-st3"><a href="javascript:void(0);"><span>3</span><h6>{{ __('property.property_features') }}</h6></a></li>
        <li class="step-4-menu menu share-an-accc-st4"><a href="javascript:void(0);"><span>4</span><h6>{{ __('property.visit_details') }}</h6></a></li>
    </ul>
</div> -->
@endif
</div>
                    <form id="secondStep" method="POST" enctype="multipart/form-data" action="{{ route('save.step2.sc2') }}">
                        {{ csrf_field() }}

                        <div class="heading-underline">
                            <h6>{{ __('property.room_info') }}.</h6>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{ __('property.date_availability_room') }} *&nbsp;<a href="javascript://;" data-toggle="tooltip" data-placement="top" title="{{ __('Date of availablity of the room') }}"><i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></a></label>
                                    <div class="datepicker-outer">
                                        <div id="datepicker-1" class="custom-datepicker">
                                            <input class="form-control" type="text" id="date_of_availablity" @if(!empty($ad_details) && !empty($ad_details->available_date)) value="{{date("d/m/Y", strtotime($ad_details->available_date))}}" @endif name="date_of_availablity" placeholder="dd/mm/yyyy">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="min_stay_months">{{ __('property.minim_staying') }}</label>
                                    <div class="custom-selectbx">
                                        <select id="min_stay_months" name="min_stay_months" class="selectpicker">
                                            @for ($i = 0; $i <= 12; $i++)
@if(!empty($ad_details) && $ad_details->ad_details->minimum_stay == $i)
                                            <option selected="selected" value="{{$i}}">@if($i==0){{ __('property.no_minim_staying') }} @else{{$i}} @endif</option>
                                            @else
                                            <option value="{{$i}}">@if($i==0){{ __('property.no_minim_staying') }} @else{{$i}} @endif</option>
                                            @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="prop_square_meters">{{ __('property.surface_area') }} *</label>
                                    <input id="prop_square_meters" type="text" class="form-control" placeholder="{{ __('property.room_square_meter') }}" name="prop_square_meters" @if(!empty($ad_details)) value="{{$ad_details->ad_details->min_surface_area}}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="preferred_gender">{{ __('property.prefered_gender') }} *</label>
                                    <div class="custom-selectbx">
                                        <select id="preferred_gender" name="preferred_gender" class="selectpicker">
                                            <option @if(!empty($ad_details) && $ad_details->ad_details->preferred_gender == '2') selected @endif value="2">{{ __('property.does_matter') }}</option>
                                            <option @if(!empty($ad_details) && $ad_details->ad_details->preferred_gender == '0') selected @endif value="0">{{ __('property.man') }}</option>
                                            <option @if(!empty($ad_details) && $ad_details->ad_details->preferred_gender == '1') selected @endif value="1">{{ __('property.woman') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="preferred_occupation">{{__('property.prefered_occupation')}} *</label>
                                    <div class="custom-selectbx">
                                        <select id="preferred_occupation" name="preferred_occupation" class="selectpicker">
                                            <option @if(!empty($ad_details) && $ad_details->ad_details->preferred_occupation == '2') selected @endif value="2">{{ __('property.does_matter') }}</option>
                                            <option @if(!empty($ad_details) && $ad_details->ad_details->preferred_occupation == '0') selected @endif value="0">{{ __('property.student') }}</option>
                                            <option @if(!empty($ad_details) && $ad_details->ad_details->preferred_occupation == '1') selected @endif value="1">{{ __('property.salaried') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="preferred_age_range_from">{{ __('property.preferred_age_from') }}</label>
                                    <div class="custom-selectbx">
                                        <select id="preferred_age_range_from" name="preferred_age_range_from" class="selectpicker">
                                            <option @if(!empty($ad_details) && $ad_details->ad_details->age_range_from <= 25) selected @endif value="18-25">18 - 25</option>
                                            <option @if(!empty($ad_details) && $ad_details->ad_details->age_range_from > 25 && $ad_details->ad_details->age_range_to <= 39) selected @endif  value="26-39">26 - 39</option>
                                            <option @if(!empty($ad_details) && $ad_details->ad_details->age_range_from > 39 && $ad_details->ad_details->age_range_to <= 45) selected @endif value="40-45">40 - 45</option>
                                            <option @if(!empty($ad_details) && $ad_details->ad_details->age_range_from > 45) selected @endif value="46-">> 45</option>
                                            <option @if(!empty($ad_details) && is_null($ad_details->ad_details->age_range_from)) selected @endif value="">Indifférent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="heading-underline">
                            <h6>{{ __('property.media_info') }}.</h6>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('property.upload_photos') }}</label>
                            <div class="upload-photo-outer">
                                <div class="file-loading">
                                    <input id="file-photos" type="file" multiple class="file" data-overwrite-initial="false" name="file_photos[]" accept="image/*">
                                </div>
                            </div>
                            <div class="upload-photo-listing">
                                <p>**{{ __('property.upload_photos_message') }}.</p>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="control-label">{{ __('property.upload_video') }}</label>
                            <div class="upload-photo-outer">
                                <div class="file-loading">
                                    <input id="file-video" type="file" class="file" data-overwrite-initial="false" name="file_video[]" accept="video/*">
                                </div>
                            </div>
                            <div class="upload-photo-listing">
                                <p>**{{ __('property.upload_video_message') }}.</p>
                            </div>
                        </div> -->
                        <div class="heading-underline m-t-0">
                            <h6>{{ __('Documents') }}</h6>
                        </div>
                        <div class="form-group">
                            {{ __('property.which_needed_document') }}
                            <ul id="test">
                                @foreach($adDocuments as $doc)
                                    <li>
                                        <input type="hidden" value="{{$doc->id}}">
                                        <input type="text" class="form-control" name="" placeholder="Document name" value="{{$doc->document_name}}">
                                        <div class="document-remove"><a href="javascript:" class="btn-delete-document" data-id="">
                                            <img src="{{ URL::asset('images/delete.png') }}">
                                        </a></div>
                                        <input type="checkbox" class="custom-checkbox" name="" @if($doc->document_required == 1)
                                        checked="checked"
                                        @endif><label>{{ __('property.is_document_required') }}</label>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="javascript:" class="btn-add-document" data-list="test">
                                {{ __('property.add_document_required') }}
                            </a>
                        </div>
                        <input type="hidden" name="button_clicked" id="button_clicked" value="">
                        <div class="pr-form-ftr">
                            <!-- <div class="submit-btn-1 save-btn"><input type="submit" name="Save" value="{{ __('property.save') }}" id="save_button_st_2" /></div> -->
                            <div class="submit-btn-1 back-btn"><input type="button" name="Back" value="{{ __('property.back') }}" id="back_button_st2" /></div>
                            <div class="submit-btn-1 save-nxt-btn"><input type="submit" name="Save & Next" value="{{ __('property.save_next') }}" id="savenext_button_st_2" /></div>
                        </div>

                    </form>
                </div>
                <div class="step-content step-3-content rent-property-form-content project-form rp-step-3-content white-bg m-t-20">
                <div class="rent-property-form-hdr">

<div class="rent-property-form-heading">
    <h6>{{ __('property.share_an_accomodation') }}</h6>
</div>
@if($standard)
<br />
<div id="wizard" class="form_wizard wizard_horizontal">
  <ul class="wizard_steps">
    <li>
      <a href="javascript:void(0);">
        <span class="step_no" style="background-color: #32ca7d;">1</span>
            <span class="step_descr">
            {{ __('property.property_info') }}<br />
            </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no" style="background-color: #32ca7d;">2</span>
        <span class="step_descr">
        {{ __('property.room_info') }}<br />
        </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no" style="background-color: #32ca7d;">3</span>
        <span class="step_descr">
        {{ __('property.property_features') }}<br />
        </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no">4</span>
        <span class="step_descr">
        {{ __('property.visit_details') }}<br />
        </span>
      </a>
    </li>
  </ul>
</div>
<!-- <div class="rent-property-form-step-listing share-accom-form-step-listing">
    <ul>
        <li class="step-1-menu menu share-an-accc-st1 active"><a href="javascript:void(0);"><span>1</span><h6>{{ __('property.property_info') }}</h6></a></li>
        <li class="step-2-menu menu share-an-accc-st2"><a href="javascript:void(0);"><span>2</span><h6>{{ __('property.room_info') }}</h6></a></li>
        <li class="step-3-menu menu share-an-accc-st3"><a href="javascript:void(0);"><span>3</span><h6>{{ __('property.property_features') }}</h6></a></li>
        <li class="step-4-menu menu share-an-accc-st4"><a href="javascript:void(0);"><span>4</span><h6>{{ __('property.visit_details') }}</h6></a></li>
    </ul>
</div> -->
@endif
</div>
                    <form id="thirdStep" method="POST" enctype="multipart/form-data" action="{{ route('save.step3.sc2') }}">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="rent_per_month">{{ __('property.rent') }} *</label>
                                    <input type="text" class="form-control" id="rent_per_month" placeholder="{{get_current_symbol()}}" name="rent_per_month" @if(!empty($ad_details)) value="{{$ad_details->min_rent}}" @endif>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
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
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('property.bedroom') }} *</label>
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
                            <label class="control-label">{{ __('property.bathroom') }} *</label>
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
                            <label class="control-label">{{ __('property.partial_bathroom') }} *</label>
                            <div class="filter-radio-listing">
                                @for ($i = 0; $i <= 6; $i++)
                                <div class="custom-radio">
                                    @if(!empty($ad_details) && !is_null($ad_details->ad_details->partial_bathrooms))
                                    @if($ad_details->ad_details->partial_bathrooms == $i)
                                    <input type="radio" name="no_of_part_bathrooms" autocomplete="off" id="prpt-bathroom-op{{$i}}" checked="checked" value="{{$i}}" />
                                    @else
                                    <input type="radio" name="no_of_part_bathrooms" autocomplete="off" id="prpt-bathroom-op{{$i}}" value="{{$i}}" />
                                    @endif
                                    @else
                                    <input type="radio" name="no_of_part_bathrooms" autocomplete="off" id="prpt-bathroom-op{{$i}}" @if ($i==0) checked="checked" @endif value="{{$i}}" />
                                    @endif

                                    <label for="prpt-bathroom-op{{$i}}">{{$i}}</label>
                                </div>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('property.pet_allowed') }}</label>
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
                            <label class="control-label">{{ __('property.property_features') }}</label>
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
                            <label class="control-label">{{ __('property.building_amneties') }}</label>
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
                        <div class="form-group">
                            <label class="control-label">{{ __('property.guarantee_ask') }}?</label>
                            <ul class="property-feature-check-listing">
                                @foreach($guarAsked as $data)
                                <li>
                                    @if(!empty($guarantees_array) && in_array($data->id, $guarantees_array))
                                    <input class="custom-checkbox" id="ga-checkbox-{{$data->id}}" type="checkbox" name="guarantee_asked[]" checked="checked" value="{{$data->id}}">
                                    @else
                                    <input class="custom-checkbox" id="ga-checkbox-{{$data->id}}" type="checkbox" name="guarantee_asked[]" value="{{$data->id}}">
                                    @endif
                                    <label for="ga-checkbox-{{$data->id}}">@if(!empty($data->icon_image))<img src="{{URL::asset('images/icons/' . $data->icon_image )}}" alt="{{$data->guarantee}}">@endif{{traduct_info_bdd($data->guarantee)}}</label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{ __('property.is_kitchen_separated') }}?</label>
                                    <div class="ks-checkbox-bx">
                                        @if(!empty($ad_details) && $ad_details->ad_details->kitchen_separated == '1')
                                        <input class="custom-checkbox" id="ks-checkbox-1" type="checkbox" name="separate_kitchen" checked="checked" value="1">
                                        @else
                                        <input class="custom-checkbox" id="ks-checkbox-1" type="checkbox" name="separate_kitchen" value="1">
                                        @endif
                                        <label for="ks-checkbox-1">{{ __('property.yes') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="deposit_price">{{ __('property.deposit_price') }}</label>
                                    <input type="text" class="form-control" id="deposit_price" placeholder="{{get_current_symbol()}}" name="deposit_price" @if(!empty($ad_details) && $ad_details->ad_details->deposit_price != null) value="{{$ad_details->ad_details->deposit_price }}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('property.property_rules') }}</label>
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
                        <div class="form-group">
                            <label class="control-label">{{ __('property.roommate_number') }} *</label>
                            <div class="filter-radio-listing">
                                @for ($i = 0; $i <= 8; $i++)
                                <div class="custom-radio">
                                    @if(!empty($ad_details))
                                    @if($ad_details->ad_details->no_of_roommates == $i)
                                    <input type="radio" id="rpt-roommates-op{{$i}}" name="no_of_roommates" autocomplete="off" checked="checked" value="{{$i}}" />
                                    @else
                                    <input type="radio" id="rpt-roommates-op{{$i}}" name="no_of_roommates" autocomplete="off" value="{{$i}}" />
                                    @endif
                                    @else
                                    <input type="radio" id="rpt-roommates-op{{$i}}" name="no_of_roommates" autocomplete="off" @if ($i==0) checked="checked" @endif value="{{$i}}" />
                                    @endif
                                    <label for="rpt-roommates-op{{$i}}">{{$i}}</label>
                                </div>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group radio2-width">
                            <label class="control-label">{{ __('property.furnished_or_unfurnished') }}? *</label>
                            <div class="filter-radio-listing">
                                @if(!empty($ad_details) && $ad_details->ad_details->furnished == '0')
                                <div class="custom-radio">
                                    <input type="radio" id="fr-un-1" name="furnished" autocomplete="off" checked="checked" value="0" />
                                    <label for="fr-un-1">{{ __('property.furnished') }}</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="fr-un-2" autocomplete="off" name="furnished" value="1" />
                                    <label for="fr-un-2">{{ __('property.unfurnished') }}</label>
                                </div>
                                @else
                                <div class="custom-radio">
                                    <input type="radio" id="fr-un-1" name="furnished" autocomplete="off" value="0" />
                                    <label for="fr-un-1">{{ __('property.furnished') }}</label>
                                </div>
                                <div class="custom-radio">
                                    <input type="radio" id="fr-un-2" autocomplete="off" name="furnished" checked="checked" value="1" />
                                    <label for="fr-un-2">{{ __('property.unfurnished') }}</label>
                                </div>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="button_clicked" id="button_clicked" value="">
                        <div class="pr-form-ftr">
                            <!-- <div class="submit-btn-1 save-btn"><input type="submit" name="Save" id="save_button_st_3" value="{{ __('property.save') }}" /></div> -->
                            <div class="submit-btn-1 back-btn"><input type="button" name="Back" value="{{ __('property.back') }}" id="back_button_st3" /></div>
                            <div class="submit-btn-1 save-nxt-btn"><input type="submit" name="Save & Next" id="savenext_button_st_3" value="{{ __('property.save_next') }}" /></div>
                        </div>
                    </form>
                </div>
                <div class="step-content step-4-content rent-property-form-content project-form rp-step-4-content white-bg m-t-20">
                <div class="rent-property-form-hdr">

<div class="rent-property-form-heading">
    <h6>{{ __('property.share_an_accomodation') }}</h6>
</div>
@if($standard)
<br />
<div id="wizard" class="form_wizard wizard_horizontal">
  <ul class="wizard_steps">
    <li>
      <a href="javascript:void(0);">
        <span class="step_no" style="background-color: #32ca7d;">1</span>
            <span class="step_descr">
            {{ __('property.property_info') }}<br />
            </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no" style="background-color: #32ca7d;">2</span>
        <span class="step_descr">
        {{ __('property.room_info') }}<br />
        </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no" style="background-color: #32ca7d;">3</span>
        <span class="step_descr">
        {{ __('property.property_features') }}<br />
        </span>
      </a>
    </li>
    <li>
      <a href="javascript:void(0);">
        <span class="step_no" style="background-color: #32ca7d;">4</span>
        <span class="step_descr">
        {{ __('property.visit_details') }}<br />
        </span>
      </a>
    </li>
  </ul>
</div>
<!-- <div class="rent-property-form-step-listing share-accom-form-step-listing">
    <ul>
        <li class="step-1-menu menu share-an-accc-st1 active"><a href="javascript:void(0);"><span>1</span><h6>{{ __('property.property_info') }}</h6></a></li>
        <li class="step-2-menu menu share-an-accc-st2"><a href="javascript:void(0);"><span>2</span><h6>{{ __('property.room_info') }}</h6></a></li>
        <li class="step-3-menu menu share-an-accc-st3"><a href="javascript:void(0);"><span>3</span><h6>{{ __('property.property_features') }}</h6></a></li>
        <li class="step-4-menu menu share-an-accc-st4"><a href="javascript:void(0);"><span>4</span><h6>{{ __('property.visit_details') }}</h6></a></li>
    </ul>
</div> -->
@endif
</div>
                    <form id="fourthStep" method="POST" enctype="multipart/form-data" action="{{ route('save.step4.sc2') }}">
                        {{ csrf_field() }}

                        <label>{{ __('property.scheduled_plan') }}.</label>

                        @if(!empty($ad_details) && count($ad_details->ad_visiting_details) > 0)
                        @foreach($ad_details->ad_visiting_details as $key=> $visiting_details)
                        <div class="visit-detail-bx-outer">
                            @if($key != 0)
                            <div class="remove-visit-btn"><a href="javascript://;" class="remove_this_visit" data-toggle="tooltip" data-placement="top" title="{{ __('Remove this visit detail') }}."><i class="fa fa-times fa-lg" aria-hidden="true"></i></a></div>
                            @endif
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                        <div class="form-group">
                                        <label class="control-label" for="date_of_visit_{{$key + 1}}">{{ __('property.date') }}</label>
                                        <div class="datepicker-outer">
                                            <div class="custom-datepicker">
                                                <input class="form-control" type="text" id="date_of_visit_{{$key + 1}}" name="date_of_visit[]" @if(!empty($visiting_details->visiting_date)) value="{{date("d/m/Y", strtotime($visiting_details->visiting_date))}}" @endif placeholder="dd/mm/yyyy">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="start_time_{{$key + 1}}">{{ __('property.start_time') }}</label>
                                        <input type="text" id="start_time_{{$key + 1}}" name="start_time[]" @if(!empty($visiting_details->start_time)) value="{{date("g:i a", strtotime($visiting_details->start_time))}}" @endif class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="end_time_{{$key + 1}}">{{ __('property.end_time') }}</label>
                                        <input type="text" id="end_time_{{$key + 1}}" name="end_time[]" @if(!empty($visiting_details->end_time)) value="{{date("g:i a", strtotime($visiting_details->end_time))}}" @endif class="form-control" readonly>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="control-label" for="note_{{$key + 1}}">{{ __('property.notes') }}</label>
                                <textarea id="note_{{$key + 1}}" name="note[]" class="form-control" placeholder="{{ __('Notes') }}" rows="4">@if(!empty($visiting_details->notes) && $visiting_details->notes != null){{$visiting_details->notes}}@endif</textarea>
                            </div>
                            <div class="ad-detail-ftr"><p>{{ __('property.notes_placeholder') }}</p></div>
                        </div>
                        @endforeach
                        @else
                        <div class="visit-detail-bx-outer">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                        <div class="form-group">
                                        <label class="control-label" for="date_of_visit_1">{{ __('property.date') }}</label>
                                        <div class="datepicker-outer">
                                            <div class="custom-datepicker">
                                                <input class="form-control" type="text" id="date_of_visit_1" name="date_of_visit[]" placeholder="dd/mm/yyyy">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="start_time_1">{{ __('property.start_time') }}</label>
                                        <input type="text" id="start_time_1" name="start_time[]" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="end_time_1">{{ __('property.end_time') }}</label>
                                        <input type="text" id="end_time_1" name="end_time[]" class="form-control" readonly>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="control-label" for="note_1">{{ __('property.notes') }}</label>
                                <textarea id="note_1" name="note[]" class="form-control" placeholder="{{ __('Notes') }}" rows="4"></textarea>
                            </div>
                            <div class="ad-detail-ftr"><p>{{ __('property.notes_placeholder') }}</p></div>
                        </div>
                        @endif
                        <div class="add-new-detail-btn">
                            <a id="add_another_visit_detail" href="javascript:void(0);">{{ __('property.another_detail_button') }}</a>
                        </div>
                        <input type="hidden" name="button_clicked" id="button_clicked" value="">
                        @guest
                        <input type="hidden" name="user" value="guest">
                        @else
                        <input type="hidden" name="user" value="logged_in">
                        @endguest
                        <div class="pr-form-ftr">

                            <div class="submit-btn-1 back-btn"><a href="{{route('user.dashboard')}}" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Ignorer</a></div>
                            <div class="submit-btn-1 save-nxt-btn"><input type="submit" name="Publish" @if(!empty($ad_details)) value="{{ __('property.save_publish') }}" @else value="{{ __('property.publish') }}" @endif id="savenext_button_st_4" /></div>
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

