@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/intlTelInput/intlTelInput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profil.css') }}" rel="stylesheet">

    <style>
        .no-display {
            display: none;
        }
    </style>
@endpush

<script>
    var messages = {
        "phone_error": "{{__('validator.phone_error')}}",
        "error_form": "{{__('profile.error_form')}}",
        "error_contact": "{{__('backend_messages.error_contact')}}"
    }
    var appSettings = {};

    @if(!empty($user->user_profiles) && !empty($user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $user->user_profiles->profile_pic)))
        filesize = {{filesize(storage_path('uploads/profile_pics/' . $user->user_profiles->profile_pic))}}
        appSettings['profile_pic'] = ["{{$user->user_profiles->profile_pic}}", filesize];
    @endif
</script>

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/intlTelInput.js') }}"></script>
    <script src="{{ asset('js/edit_profile.js') }}"></script>
    <script src="/js/return_handler.js"></script>
    <script src="/js/date-naissance.js"></script>
    <script src="/js/metier_autocompletion.js"></script>
    <script src="/js/school_autocomplete.js"></script>
    <script src="/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script>
@endpush

@section('content')

    <section class="inner-page-wrap section-center">
        <div class="container">
            <div class="row">
                <div
                    class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 rent-property-form-outer rent-property-step-1">


                    @if(Session::has('status'))
                        <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                            {!! session('status') !!}
                        </div>
                    @endif

                    <div class="alert alert-success alert-nrh fade in alert-dismissable no-display">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close"
                           title="{{ __('close') }}">×</a>
                        <p id="alert-nrh-p"></p>
                    </div>

                    <div class="edit-pro-hdr text-center">
                        <h4>{{ __('profile.title') }}</h4>
                        <p>{{ __('profile.keep_personal_detail') }}</p>
                    </div>
                    <div class="rent-property-form-hdr">
                        <div class="profil-percent">
                            @if($profilPercent == 100)
                                <div id="completer-profil">
                                    {{__("profile.profil_completed")}}
                                </div>
                            @else
                                <div id="completer-profil">
                                    {{__("profile.completer_profile")}}
                                </div>
                            @endif

                            <div class="pourcentage-profil-mesure">
                                <div class="pourcentage-profil" style="width: {{$profilPercent}}%">

                                </div>
                            </div>
                            <div class="number-precent-profil">
                                {{$profilPercent}}%
                            </div>
                        </div>
                        <div class="rent-property-form-heading">
                            <h6>{{ __('profile.edit_profile') }}</h6>
                        </div>
                        <div class="rent-property-form-step-listing edit-pro-step-listing">
                            <ul>
                                <li class="rent-property-menu active"><a href="javascript:void(0);"><span><i
                                                class="fa fa-user" aria-hidden="true"></i></span>
                                        <h6>{{ __('profile.personal_info') }}</h6></a></li>
                                <!--                            <li class="property-feature-menu"><a href=""><span><i class="fa fa-envelope" aria-hidden="true"></i></span><h6>{{ __('Change Email') }}</h6></a></li>-->
                                <li class="visiting-menu"><a href="{{ route('edit.changepwd') }}"><span><i
                                                class="fa fa-eye" aria-hidden="true"></i></span>
                                        <h6>{{ __('profile.modif_pass') }}</h6></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
                        <div class="row edit-pro-list-row">
                            <div class="rent-property-form-step-listing">
                                <ul>
                                    <li class="step-1-menu menu rent-property-menu active"><a
                                            href="javascript:void(0);"><span>1</span>
                                            <h6>{{ __('profile.a_propos') }}</h6></a></li>
                                    <li class="step-2-menu property-feature-menu"><a
                                            href="javascript:void(0);"><span>2</span>
                                            <h6>{{ __('profile.social_info') }}</h6></a></li>
                                    <li class="step-3-menu menu visiting-menu"><a
                                            href="javascript:void(0);"><span>3</span>
                                            <h6>{{ __('profile.social_info_plus') }}</h6></a></li>
                                </ul>
                            </div>
                        </div>


                        <div class="register-second-content step-content step-1-content" id="edit_step_1">
                            <form id="editProfile1" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.registered_mail') }}</label>
                                            <p class="form-control">{{$user->email}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.first_name') }} <sup>*</sup></label>
                                            <input type="text" class="form-control"
                                                   placeholder="{{ __('profile.first_name') }}" name="first_name"
                                                   id="first_name"
                                                   @if(!empty($user->first_name)) value="{{$user->first_name}}" @endif />
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.date_birth') }} <sup>*</sup></label>


                                            <div class="datepicker-outer">
                                                <select id="date-jour" data-class="date" data-id="birth_date"
                                                        class="date-jour date-select">
                                                    <?php echo getJourOption(getDateElement($user->user_profiles->birth_date, "d"));?>
                                                </select>
                                                <select id="date-mois" data-class="date" data-id="birth_date"
                                                        class="date-mois date-select">
                                                    <?php echo getMoisOption(getDateElement($user->user_profiles->birth_date, "m"));?>
                                                </select>
                                                <select id="date-annee" data-class="date" data-id="birth_date"
                                                        class="date-annee date-select">
                                                    <?php echo getAnneeOption(getDateElement($user->user_profiles->birth_date, "Y"));?>
                                                </select>
                                                <input class="form-control datepicker-new" type="hidden"
                                                       name="birth_date" id="birth_date"
                                                       @if(!empty($user->user_profiles) && !empty($user->user_profiles->birth_date)) value="{{date("d/m/Y", strtotime($user->user_profiles->birth_date))}}"
                                                       @else 01/01/1970 @endif/>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('profile.last_name') }} <sup>*</sup></label>
                                        <input type="text" class="form-control" placeholder="{{ __('profile.last_name') }}" name="last_name" id="last_name" @if(!empty($user->last_name))
                                        value="{{$user->last_name}}"
                                    @endif />
                                    </div>
                                </div> -->
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.sex') }} <sup>*</sup></label>
                                            <div class="custom-selectbx">

                                                <select class="selectpicker" title="{{__('filters.no_selected')}}"
                                                        name="sex" id="sex">
                                                    <option
                                                        @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->sex == '0') selected
                                                        @endif value="0">{{ __('profile.male') }}</option>
                                                    <option
                                                        @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->sex == '1') selected
                                                        @endif value="1">{{ __('profile.female') }}</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.phone') }} <sup>*</sup></label>
                                            <input type="tel" class="form-control"
                                                   placeholder="{{ __('profile.phone_placeholder') }}" name="mobile_no"
                                                   id="mobile_no"
                                                   @if(!empty($user->user_profiles) && !empty($user->user_profiles->mobile_no)) value="{{$user->user_profiles->mobile_no}}" @endif />
                                            <input type="hidden" name="valid_number" id="valid_number"
                                                   @if(!empty($user->user_profiles) && !empty($user->user_profiles->mobile_no)) value="{{$user->user_profiles->mobile_no}}" @endif />
                                            <input type="hidden" name="iso_code" id="iso_code"
                                                   @if(!empty($user->user_profiles) && !empty($user->user_profiles->iso_code)) value="{{$user->user_profiles->iso_code}}" @endif />
                                            <input type="hidden" name="dial_code" id="dial_code"
                                                   @if(!empty($user->user_profiles) && !empty($user->user_profiles->dial_code)) value="{{$user->user_profiles->dial_code}}" @endif />
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="submit-btn-1 save-nxt-btn"><a href="javascript:void(0);"
                                                                              id="edit-profile-step-1">{{ __('profile.next') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="register-second-content step-content step-2-content" id="edit_step_2">
                            <form id="editProfile2" method="POST" enctype="multipart/form-data">

                                <label><b>*{{ __("profile.better_to_fill") }}</b></label>
                                <div class="form-group">
                                    <label class="control-label">{{ __('profile.upload_photo') }}</label>
                                    <div class="upload-photo-outer">
                                        <div class="file-loading">
                                            <input id="file-profile-photo" type="file"
                                                   class="file" data-overwrite-initial="true" data-min-file-count="1"
                                                   name="file_profile_photos" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="upload-photo-listing">
                                        <p>{{ __('profile.upload_photo_message') }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="about_me">{{ __('profile.about_me') }}</label>
                                        <textarea id="about_me" name="about_me" class="form-control"
                                                  placeholder="{{ __('profile.about_me_placeholder') }}" rows="4">@if(!empty($user->user_profiles) && !empty($user->user_profiles->about_me))
                                                {{$user->user_profiles->about_me}}
                                            @endif</textarea>
                                    </div>
                                    <div class="ad-detail-ftr"><p>{{ __('profile.max_caracter') }}</p></div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('profile.school') }}</label>
                                                <input type="text" class="form-control"
                                                       placeholder="{{ __('profile.school_placeholder') }}"
                                                       name="school_name" id="school"
                                                       @if(!empty($user->user_profiles) && !empty($user->user_profiles->school)) value="{{$user->user_profiles->school}}" @endif />
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('profile.professional_category') }}</label>
                                                <div class="custom-selectbx">
                                                    <select class="selectpicker" title="{{__('filters.no_selected')}}"
                                                            name="professional_category" id="professional_category">
                                                        <option
                                                            @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->professional_category == '0') selected
                                                            @endif value="0">{{ __('profile.student') }}</option>
                                                        <option
                                                            @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->professional_category == '1') selected
                                                            @endif value="1">{{ __('profile.freelancer') }}</option>
                                                        <option
                                                            @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->professional_category == '2') selected
                                                            @endif value="2">{{ __('profile.salaried') }}</option>
                                                        <option
                                                            @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->professional_category == '3') selected
                                                            @endif value="3">{{ __('profile.cadre') }}</option>
                                                        <option
                                                            @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->professional_category == '4') selected
                                                            @endif value="4">{{ __('profile.retraite') }}</option>
                                                        <option
                                                            @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->professional_category == '5') selected
                                                            @endif value="5">{{ __('profile.chomage') }}</option>
                                                        <option
                                                            @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->professional_category == '6') selected
                                                            @endif value="6">{{ __('profile.rentier') }}</option>
                                                        <option
                                                            @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->professional_category == '7') selected
                                                            @endif value="7">{{ __('profile.situationPro7') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('profile.study_level') }}</label>
                                                <div class="custom-selectbx">
                                                    <select id="lvl_of_study" name="lvl_of_study" class="selectpicker"
                                                            title="{{__('filters.no_selected')}}">
                                                        @foreach($studyLevels as $data)
                                                            @if(!empty($user->user_profiles) && count((array)$user->user_profiles) > 0 && $user->user_profiles->study_level_id == $data->id)
                                                                <option selected
                                                                        value="{{$data->id}}">{{$data->study_level}}</option>
                                                            @else
                                                                <option
                                                                    value="{{$data->id}}">{{$data->study_level}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('profile.profession') }}</label>
                                                <input type="text" class="form-control"
                                                       placeholder="{{ __('profile.profession_placeholder') }}"
                                                       name="profession" id="profession"
                                                       @if(!empty($user->user_profiles) && !empty($user->user_profiles->profession)) value="{{$user->user_profiles->profession}}" @endif />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="submit-btn-1 save-nxt-btn">
                                        <a href="javascript:void(0);"
                                           id="edit-profile-step-2">{{ __('profile.next') }}</a></div>
                                </div>
                            </form>
                        </div>

                        <div class="register-second-content step-content step-3-content" id="edit_step_3">
                            <form id="editProfile3" method="POST" enctype="multipart/form-data">
                                <label><b>*{{ __("profile.better_to_fill") }}</b></label>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('profile.interest') }}</label>
                                            <div class="custom-selectbx">
                                                <select class="sumo-select" sumo-required="true"
                                                        placeholder="{{__('filters.no_selected')}}"
                                                        name="social_interests[]" id="social_interests" multiple="">
                                                    @foreach($socialInterests as $data)
                                                        @if(!empty($social_interests_array) && in_array($data->id, $social_interests_array))
                                                            <option selected=""
                                                                    value="{{$data->id}}">{{traduct_info_bdd($data->interest_name)}}</option>
                                                        @else
                                                            <option
                                                                value="{{$data->id}}">{{traduct_info_bdd($data->interest_name)}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('profile.type_music') }}</label>
                                            <div class="custom-selectbx">
                                                <select class="sumo-select" sumo-required="true"
                                                        placeholder="{{__('filters.no_selected')}}" name="type_musics[]"
                                                        id="type_musics" multiple="">
                                                    @foreach($typeMusics as $data)
                                                        @if(!empty($user_musics_array) && in_array($data->id, $user_musics_array))
                                                            <option selected=""
                                                                    value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                                        @else
                                                            <option
                                                                value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @if(!empty($social_interests_array) && in_array(4, $social_interests_array))
                                        <div class="sport-select col-xs-12 col-sm-6 col-md-6">
                                            @else
                                                <div class="sport-select hide-sport-select col-xs-12 col-sm-6 col-md-6">
                                                    @endif

                                                    <div class="form-group">
                                                        <label class="control-label">{{ i18n('user_sport') }}
                                                            <sup>*</sup></label>
                                                        <div class="custom-selectbx">
                                                            <select class="sport-sumo-select sumo-select"
                                                                    sumo-required="false"
                                                                    placeholder="{{__('filters.no_selected')}}"
                                                                    name="sports[]" id="sports" multiple="">
                                                                @foreach($sports as $data)
                                                                    @if(!empty($user_sport_array) && in_array($data->id, $user_sport_array))
                                                                        <option selected=""
                                                                                value="{{$data->id}}">{{$data->label}}</option>
                                                                    @else
                                                                        <option
                                                                            value="{{$data->id}}">{{$data->label}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('profile.smoker') }}</label>
                                                    <div class="custom-selectbx">
                                                        <select class="selectpicker"
                                                                title="{{__('filters.no_selected')}}" name="smoker"
                                                                id="smoker">
                                                            <option
                                                                @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->smoker == '0') selected
                                                                @endif value="0">{{ __('profile.yes') }}</option>
                                                            <option
                                                                @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->smoker == '1') selected
                                                                @endif value="1">{{ __('profile.no') }}</option>
                                                            <option
                                                                @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->smoker == '2') selected
                                                                @endif value="2">{{ __('profile.indifferent') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('profile.alcool') }}</label>
                                                    <div class="custom-selectbx">
                                                        <select class="selectpicker"
                                                                title="{{__('filters.no_selected')}}" name="alcool"
                                                                id="alcool">
                                                            <option
                                                                @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->alcool == '0') selected
                                                                @endif value="0">{{ __('profile.yes') }}</option>
                                                            <option
                                                                @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->alcool == '1') selected
                                                                @endif value="1">{{ __('profile.no') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('profile.gay') }}</label>
                                                    <div class="custom-selectbx">
                                                        <select class="selectpicker" name="gay" id="gay">
                                                            <option
                                                                @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->gay == '0') selected
                                                                @endif value="0">{{ __('profile.yes') }}</option>
                                                            <option
                                                                @if(!empty($user) && count((array)$user->user_profiles) > 0 && $user->user_profiles->gay == '1') selected
                                                                @endif value="1">{{ __('profile.no') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('profile.origin_country') }}</label>
                                        <div class="custom-selectbx">
                                            <select class="selectpicker" title="{{__('filters.no_selected')}}" name="country" id="country">
                                                @foreach($countries as $data)
                                                @if(!empty($user->user_profiles) && $user->user_profiles->country_id == $data->id)
                                                    <option selected value="{{$data->id}}">{{$data->country_name}}</option>

                                                @else
                                                    <option value="{{$data->id}}">{{$data->country_name}}</option>

                                                @endif
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('profile.actual_city') }}</label>
                                                    <!-- <div class="custom-selectbx">
                                            <select class="selectpicker" title="{{__('filters.no_selected')}}" name="city" id="city">
                                                @foreach($cities as $data)
                                                        @if(!empty($user->user_profiles) && $user->user_profiles->city_id == $data->id)
                                                            <option selected value="{{$data->id}}">{{$data->city_name}}</option>

                                                        @else
                                                            <option value="{{$data->id}}">{{$data->city_name}}</option>

                                                        @endif
                                                    @endforeach
                                                    </select>
                                                </div> -->
                                                    <input type="text" class="form-control"
                                                           placeholder="{{ __('profile.actual_city') }}" name="city"
                                                           id="city"
                                                           @if(!empty($user->user_profiles)) value="{{$user->user_profiles->city}}" @endif />

                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('profile.origin_city') }}</label>
                                                    <input type="text" class="form-control"
                                                           placeholder="{{ __('profile.origin_city') }}" name="hometown"
                                                           id="chometown"
                                                           @if(!empty($user->user_profiles)) value="{{$user->user_profiles->hometown}}" @endif />

                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                <label class="control-label">{{ __('profile.lifestyle') }}</label>
                                <ul class="property-feature-check-listing">
                                    @foreach($userLifestyles as $data)
                                            <li>
@if(!empty($user_lifestyles_array) && in_array($data->id, $user_lifestyles_array))
                                                <input class="custom-checkbox" id="ul-checkbox-{{$data->id}}" type="checkbox" name="user_lifestyles[]" checked="checked" value="{{$data->id}}">

                                            @else
                                                <input class="custom-checkbox" id="ul-checkbox-{{$data->id}}" type="checkbox" name="user_lifestyles[]" value="{{$data->id}}">

                                            @endif
                                            <label for="ul-checkbox-{{$data->id}}">{{traduct_info_bdd($data->lifestyle_name)}}</label>
                                    </li>

                                        @endforeach
                                        </ul>
                                    </div> -->
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label><i class="fa fa-facebook-square" aria-hidden="true"
                                                              style="color:#3B5998;"></i>&nbsp;{{ __('profile.link_fb') }}
                                                    </label>
                                                    <input type="text" class="form-control"
                                                           placeholder="{{ __('profile.link_fb_placeholder') }}"
                                                           name="fb_profile_link" id="fb_profile_link"
                                                           @if(!empty($user->user_profiles) && !empty($user->user_profiles->fb_profile_link)) value="{{$user->user_profiles->fb_profile_link}}" @endif />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label><i class="fa fa-linkedin-square" aria-hidden="true" style="color:#0077B5;"></i>&nbsp;{{ __('profile.link_linkedin') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('profile.link_linkedin_placeholder') }}" name="in_profile_link" id="in_profile_link" @if(!empty($user->user_profiles) && !empty($user->user_profiles->in_profile_link))
                                            value="{{$user->user_profiles->in_profile_link}}"
                                        @endif />
                                    </div>
                                </div>
                            </div> -->
                                        <div class="text-right">
                                            <div class="submit-btn-1 save-nxt-btn"><a href="javascript:void(0);"
                                                                                      id="edit-profile-step-3">{{ __('profile.update') }}</a>
                                            </div>
                                        </div>
                            </form>
                        </div>
                        <p class='text-center'>
                            {{ __('profile.want_to_delete')}}
                            <a class='btn-link' href="javascript:void(0)" id="openDeleteAccountBtn">
                                {{ __('profile.click_here') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="alertModalContact" class="modal fade alert-modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="alrt-modal-body">
                    <p>{{ __('profile.delete_question') }}</p>
                </div>
                <div class="form-group">
                    <textarea id="raison-delete" name="message" class="form-control"
                              placeholder="{{ __('profile.raison') }}" rows="6"></textarea>
                    <label id="error-raison" class="control-label" for="note">{{ __('profile.error_raison') }}</label>
                </div>
                <div class="text-right" style="padding: 15px 0 0;">
                    <a class="btn btn-primary" href="{{route('delete.profile')}}"
                       id="modal-btn-yes">{{ __('profile.ok') }}</a>
                    <a href="javascript:void(0);" class="btn btn-default" id="modal-btn-cancel"
                       style="margin-left: 10px;">{{ __('profile.cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
    @include('property.step-management')
    <script>
        history.replaceState
            ? history.replaceState(null, null, window.location.href.split("#")[0])
            : window.location.hash = "";

    </script>
@endsection

