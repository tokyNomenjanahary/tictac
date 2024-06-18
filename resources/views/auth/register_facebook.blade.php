
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'searchadlocation.map') class="search-map-page-html" @endif>
    <head>
        @if((isset($ad) && $ad->user_id != 435) || isNoIndexPage())
        <meta name="robots" content="noindex">
        @else
        <meta name="robots" content="index,follow">
        @endif

        {{google_tag_manager_head()}}

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if(isset($page_description))
        <meta name="description" content='{{$page_description}}'>
        @else
        <meta name="description" content="{{generatePageDescription()}}">
        @endif

        @if(isset($page_meta_keyword))
         <meta id="e_metaKeywords" name="KEYWORDS" content="{{$page_meta_keyword}}">
        @else
         <meta id="e_metaKeywords" name="KEYWORDS" content="{{__('seo.keywords')}}">
        @endif

        <!--titre du page -->
        @if(isset($page_title))
        <title>{{ $page_title }}</title>
        @else
            @if(!empty(Route::currentRouteName()))
                @switch(Route::currentRouteName())
                    @case("staticpage")
                        <title>{{ $page_detail->meta_title }}</title>
                        @break
                    @default
                       <title>{{generatePageTitle()}}</title>
                @endswitch
            @else
                <title>{{generatePageTitle()}}</title>
            @endif

        @endif

        <link rel='shortcut icon' href="/img/favicon.png" type='image/x-icon' />
         @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'view.ad')
        {{-- <link href="/css/bootstrap.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651133741/Bailti/css/bootstrap.min_dg3mga.css" rel="stylesheet">
        {{-- <link href="/css/footer.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650352790/Bailti/css/footer.min_jjbegh.css" rel="stylesheet">
        {{-- <link href="/css/style.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651133852/Bailti/css/style.min_vfztxr.css" rel="stylesheet">
        {{-- <link href="/css/media.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651133942/Bailti/css/media.min_iz0yxk.css" rel="stylesheet">
        {{-- <link href="/css/custom-media.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134031/Bailti/css/custom-media.min_u94dnd.css" rel="stylesheet">
        {{-- <link href="/css/developer.min.css" rel="stylesheet"> --}}
        <!-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134077/Bailti/css/developer.min_dvdavv.css" rel="stylesheet"> -->

        {{-- <link href="/css/flexslider.min.css" rel="stylesheet"> --}}
        <!-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134192/Bailti/css/flexslider.min_dpbucp.css" rel="stylesheet"> -->
        <!-- <link href="/css/font-awesome.min.css" rel="stylesheet"> -->
        @else
        {{-- <link href="{{ asset('css/include.css') }}" rel="stylesheet"> --}}
        <!-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134861/Bailti/css/include_m6j05r.css" rel="stylesheet"> -->
        <!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648629049/js/owl.carousel.min_lppcif.css" rel="stylesheet"> -->
                {{-- bailtidev --}}
{{--        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648628985/js/style_pkcfhn.css" rel="stylesheet">--}}
                {{-- bailti3 --}}
                <link href="https://res.cloudinary.com/avaim/raw/upload/v1651747908/bailti3/css/style_c7dtxe.css" rel="stylesheet">

                <link href="/html/css/style_fb.css" rel="stylesheet">
                @endif
        <!-- Styles -->
        {{--    <link href="/css/register.css" rel="stylesheet">--}}
    <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650613771/Bailti/css/register_hpg2l4.css" rel="stylesheet">
{{--    <link rel="stylesheet" href="/css/sumoselect.min.css">--}}
    <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650613845/Bailti/css/sumoselect.min_crfnat.css" rel="stylesheet">
{{--    <link href="/css/intlTelInput/intlTelInput.min.css" rel="stylesheet">--}}
<link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650614649/Bailti/css/intlTelInput.min_y46djr.css" rel="stylesheet">
{{--    <link href="/css/country/countrySelect.css" rel="stylesheet">--}}
<link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650614683/Bailti/css/countrySelect_tgam12.css" rel="stylesheet">
        <style>
    .hide
    {
        display: none;
    }

    .form-check-condition
    {
        margin-top: 30px;
    }

</style>

        @stack('styles')
        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" /> -->
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648722483/css/intlTelInput.min_dpc7md.css" rel="stylesheet">

        <link rel="stylesheet" href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648629206/css/sumoselect.min_sof1hb.css">
        <!-- <link rel="stylesheet" type="text/css" href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648627947/css/slick_optghr.css"/> -->
        <link rel="stylesheet" type="text/css" href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648627888/css/slick-theme_sjgzgm.css"/>
        {{deactiveElements()}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649487192/Bailti/js/jquery-3.2.1.min_gdx1kd.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623734/bootstrap.min_lpirue.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623652/common_nanhue.js"></script>
        @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'view.ad')
        <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625951/slick.min_gfbzgh.js"></script>
        {{-- <script src="{{ asset('js/message_flash.js') }}"></script> --}}
        <!-- <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651217270/Bailti/js/message_flash_vyvsdf.js"></script> -->
        @else
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623564/bootstrap-select_cio7qn.js"></script>
<!-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623363/owl.carousel_xjecmj.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
        {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.0/bootstrap-slider.min.js"></script> --}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649402901/Bailti/js/bootstrap-slider.min_vlv61y.js"></script>
        <!-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623300/bootstrap-datepicker.min_r9iu0u.js"></script> -->
        <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623205/jquery.validate.min_j1mjcb.js"></script>
        {{deleteDeactiveElements()}}

        @push('scripts')
{{--       <script src="/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script>--}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650613232/Bailti/js/jquery.easy-autocomplete.min_fyly1b.js"></script>
        <script src="/js/register_fb.js"></script>
{{--        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650613160/Bailti/js/register_fb_srfid7.js"></script>--}}
{{--        <script src="/js/intlTelInput/intlTelInput.min.js"></script>--}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650611137/Bailti/js/intlTelInput.min_ie9opc.js"></script>
{{--        <script src="/js/metier_autocompletion.js"></script>--}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650610944/Bailti/js/metier_autocompletion_z89f1a.js"></script>
{{--        <script src="/js/school_autocomplete.js"></script>--}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650610802/Bailti/js/school_autocomplete_xuzseb.js"></script>
{{--        <script src="/js/jquery.sumoselect.min.js"></script>--}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650610753/Bailti/js/jquery.sumoselect.min_wnkane.js"></script>
{{--        <script src="/js/country/countrySelect.js"></script>--}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650610706/Bailti/js/countrySelect_qmrjhm.js"></script>
        <script>

            jQuery.extend(jQuery.validator.messages, {
                required: "{{__('validator.required')}}",
                email: "{{__('validator.email')}}"
            });
            jQuery.validator.addMethod("greaterThanZero", function(value, element) {
                return this.optional(element) || (parseFloat(value) > 0);
            }, "{{__('validator.required')}}");
            jQuery("#registerStepFb").validate({
                rules: {
                    /*"revenus": {
                        "required": true,
                        "number": true,
                        "greaterThanZero" : true
                    },*/
                    /*"budget" : {
                        "required" : true,
                        "number" : true
                    },*/
                    "school" : {
                        required: {
                            depends: function(element) {
                                return ($('#professional_category').val() == 0);
                            }
                        }
                    },
                    "professional_category" : {
                        "required": true
                    },
                    "profession" : {
                        required: {
                            depends: function(element) {
                                return ($('#professional_category').val() == 1 || $('#professional_category').val() == 2 || $('#professional_category').val() == 3);
                            }
                        }
                    }
                }
            });
            $("#newsletter-form").validate();
        </script>

    @endpush
        <!-- Dump all dynamic scripts into template -->
        @push('scripts')

        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648628890/js/jquery.sumoselect.min_yny39b.js"></script>
        <!-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624801/jquery.timepicker_wqeuw6.js"></script> -->
        <script>
        var messagess = {"browse" : "{{__('profile.browse')}}","cancel" : "{{__('profile.cancel')}}","remove" : "{{__('profile.remove')}}","upload" : "{{__('profile.upload')}}"}
        </script>
                    {{-- bailti --}}
{{--        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624955/fileinput.min_cxlrg9.js"></script>--}}
                    {{-- bailti3 --}}
                    <script src="https://res.cloudinary.com/avaim/raw/upload/v1651747495/bailti3/js/fileinput.min_ujunga.js"></script>
        <!-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624149/explorer-fa_theme.min_jsekze.js"></script> -->
        <!-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624176/theme.min_kqmhud.js"></script> -->
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624008/fr_wbdvfu.js"></script>
        <script type="text/javascript">

            var REQUIRED_TEXT ="{{ __("Is this documents required?") }}";
            var DELETE_IMG = "{{ URL::asset("images/delete.png") }}";
        </script>
        // <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625174/sumoSelectInclude.min_xu2pg5.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625788/js/sumoSelectInclude_eva7v2.js"></script>
        // <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625951/slick.min_gfbzgh.js"></script>
        @endpush
        @endif

        <meta name="httpcs-site-verification" content="HTTPCS9083HTTPCS" />

            <style>
                @media (max-width: 768px){
                    .none-display {
                        display: none;
                    }

                    .mb {
                        margin-bottom: 10px;
                    }

                    .mt {
                        margin-top: 20px;
                    }
                }
                .link-back:hover{
                    text-decoration: underline;
                }
            </style>

            {{-- Google Adsense --}}
            @if(Auth::check())
                @if(getConfig('google_adsense') && !isUserSubscribed(Auth::id()))
                    {{ google_adsense_code() }}
                @endif
            @endif


    </head>

    <body  class="body-list">

        {{google_tag_manager_body()}}

        {{serverInfos()}}
        <input type="hidden" id="changeLang" value="fr" name="">
        <div class='loader-icon' style="display:none;"><img src='/images/rolling-loader.apng' alt="rolling-loader.apng"></div>

        @if(!empty(Route::currentRouteName()) && (Route::currentRouteName() == 'login_popup' || Route::currentRouteName() == 'register' || Route::currentRouteName() == 'registerStep2' || Route::currentRouteName() == 'fb_register'))
            @include('common.header_login')
        @else
            @include('common.header1')
        @endif
        <section class="inner-page-wrap section-center section-login">
        <div id="popup-modal-body-login" class="register-form-x rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
            <a class="change_place" href="#" data-dismiss="modal">< {{__('register.change_place')}}</a>
            <div class="modal-body">
                <div class="pop-hdr">
                    <h6 class="fb-registration-message register-to-search">{{__('register.bonjour')}} <span id="ville-register">{{getNomUserFb()}}</span>!</h6>
                </div>
                <div class="pop-hdr">
                    <span class="find-colocation">{{__('register.presque')}}</span>
                </div>

                <div class="tab-content">
                    <div id="login-tab" class="tab-pane fade in active">

                        <form id="registerStepFb" action="{{route('save_fb_loyer')}}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="register-first-step">
                                <div class="row">
                                    @if(getInfoRegister('scenario_register') != 3)
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('register.situation_pro')}} <sup>*</sup></label>
                                                <div class="custom-selectbx">
                                                    <select class="selectpicker" title="{{ __('profile.choisir_professional') }}" name="professional_category" id="professional_category">
                                                        <option value="0">{{ __('register.student') }}</option>
                                                        <option value="1">{{ __('register.freelancer') }}</option>
                                                        <option value="2">{{ __('register.salaried') }}</option>
                                                        <option value="3">{{ __('register.cadre') }}</option>
                                                        <option value="4">{{ __('register.retraite') }}</option>
                                                        <option value="5">{{ __('register.chomage') }}</option>
                                                        <option value="6">{{ __('register.rentier') }}</option>
                                                        <option value="7">{{ __('register.situationPro7') }}</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('register.phone')}} <sup>*</sup></label>
                                            @if(Session::has('tab'))
                                                <input type="tel" class="form-control" placeholder="{{ __('register.enter_phone') }}" name="mobile_no" id="mobile_no" value="{{Session::get('tab')['mobile_no']}}" />
                                                <input type="hidden" name="valid_number" id="valid_number" value="{{Session::get('tab')['valid_number']}}" />
                                                <input type="hidden" name="iso_code" id="iso_code" value="{{Session::get('tab')['iso_code']}}" />
                                                <input type="hidden" name="dial_code" id="dial_code" value="{{Session::get('tab')['dial_code']}}" />
                                                <label id="phone_custom_error" class="custom-error">{{__('validator.required')}}</label>
                                                <label id="profession-error" class="error" for="profession">{{Session::get('tab')['num_duplication']}}</label>
                                            @else
                                                <input type="tel" class="form-control" placeholder="{{ __('register.enter_phone') }}" name="mobile_no" id="mobile_no"  />
                                                <input type="hidden" name="valid_number" id="valid_number"  />
                                                <input type="hidden" name="iso_code" id="iso_code"  />
                                                <input type="hidden" name="dial_code" id="dial_code"  />
                                                <label id="phone_custom_error" class="custom-error">{{__('validator.required')}}</label>
                                            @endif
                                        </div>
                                    </div>
                                <!-- <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('profile.revenu') }} <sup>*</sup></label>
                                        <input type="text" class="form-control" placeholder="€" name="revenus" id="revenus" />
                                    </div>
                                </div> -->
                                <!-- <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>{{__('login.budget')}}</label>
                                        <input type="text" class="form-control" placeholder="{{__('login.budget')}}" name="budget" id="budget" />
                                    </div>
                                </div> -->
                                </div>
                            <!-- <div class="row">
                                <div id="register-school" class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>{{__('login.school')}}</label>
                                        <input type="text" class="form-control" placeholder="{{__('login.school')}}" name="school" id="school" />
                                    </div>
                                </div>
                                <div id="register-profession" class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('profile.profession') }} <sup>*</sup></label>
                                        <input type="text" class="form-control" placeholder="{{ __('profile.profession_placeholder') }}" name="profession" id="profession"/>
                                    </div>
                                </div>
                            </div> -->
                                <div class="row">
                                    <div class="row" style="margin :-6px">
                                        <div id="register-school" class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('register.school')}}</label>
                                                <input type="text" class="form-control" placeholder="{{__('register.school')}}" name="school" id="school" />
                                            </div>
                                        </div>
                                        <div id="register-profession" class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('register.profession') }} <sup>*</sup></label>
                                                <input type="text" class="form-control" placeholder="{{ __('register.profession_placeholder') }}" name="profession" id="profession"/>
                                            </div>
                                        </div>
                                    </div>
                                    @if(getInfoRegister('scenario_register') == 3)
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('register.location_par')}} <sup>*</sup></label>
                                                <div class="custom-selectbx">
                                                    <select class="selectpicker" name="accept_as" id="accept_as">
                                                        <option value="1">{{__('register.landlord')}}</option>
                                                        <option value="2">{__('register.agent')}}</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                    @endif


                                </div>
                                @if(!is_null(getInfoRegister('scenario_register')) && getInfoRegister('scenario_register') != 1 && getInfoRegister('scenario_register') != 3)
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">{{ __('register.interest') }} <sup>*</sup></label>
                                                <div class="custom-selectbx">
                                                    <select class="sumo-select" sumo-required="true" placeholder="{{__('filters.no_selected')}}" name="social_interests[]" id="social_interests" multiple="">
                                                        @foreach($socialInterests as $data)
                                                            <option value="{{$data->id}}">{{traduct_info_bdd($data->interest_name)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <label id="social_interests-custom_error" class="custom-error" for="social_interests" style="">{{__('validator.required')}}</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">{{ __('register.type_music') }} <sup>*</sup></label>
                                                <div class="msc-desc">{{__('register.same_music_desc')}}</div>
                                                <div class="custom-selectbx">
                                                    <select class="sumo-select" sumo-required="true" placeholder="{{__('filters.no_selected')}}" name="type_musics[]" id="type_musics" multiple="">
                                                        @foreach($typeMusics as $data)
                                                            <option value="{{$data->id}}">{{traduct_info_bdd($data->label)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <label id="type_musics-custom_error" class="custom-error" for="social_interests" style="">{{__('validator.required')}}</label>
                                            </div>
                                        </div>
                                    </div>

                                @endif
                                @if(getInfoRegister('scenario_register') != 1 && getInfoRegister('scenario_register') != 3)
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('register.origin_country_label')}} <sup>*</sup></label>
                                                <input type="text" class="form-control" placeholder="{{ __('register.origin_country') }}" name="origin_country" id="origin_country" />
                                                <input type="hidden" name="origin_country_code" id="origin_country_code" />
                                                <label id="origin_country_error" class="custom-error">{{__('validator.required')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-check form-check-condition">
                                    <input type="checkbox" class="term_check" name="term_check" class="form-check-input"/>
                                    <label class="form-check-label" for="term_check">{!! __('register.term_condition') !!}</label>
                                </div>


                                <div class="pr-poup-ftr">
                                    <div class="submit-btn-2"><a data-dismiss="modal" href="/">{{__('register.cancel')}}</a></div>
                                    <div class="submit-btn-2 reg-next-btn button_condition"><a href="javascript:void(0);" id="submit-rent">{{__('register.register_button')}}</a></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="information-modal" class="modal ">
        <div class="modal-dialog ">
            <div class="modal-content ">
                <div class="modal-body" >
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5 style="font-size : 1.2em;" id="modal-information-text" class="modal-title text-center"> {{__('register.error_link_term_condition')}} </h5>
                </div>
            </div>
        </div>
    </div>


        @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'searchadlocation.map')
        <!--No Footer -->
        @else
        @include('common.footer')
        <!-- modal login -->
        <!--Start of Tawk.to Script-->
        <!-- <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5d0aaefa36eab972111846a4/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script> -->
        <!--End of Tawk.to Script-->
        @endif
        @stack('scripts')
        <script>
            $(document).ready(function() {
                $('#btn-reset-popup').on('click', function(){
                    $('#LoginModal').modal('hide');
                    $('#ReinitialisePasswordModal').modal('show');
                });
                $('#btn-login-popup').on('click', function(){
                    $('#LoginModal').modal('show');
                });
            });
        </script>
        <script type="text/javascript">
    $(document).ready(function(){
        $('.sumo-select').SumoSelect();
    });
</script>

    </body>
</html>
