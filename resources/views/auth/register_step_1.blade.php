<!DOCTYPE html>
<?php
$scenario = session::get('scenario');
$langues = session::get('langues');
?>

<html lang="{{ app()->getLocale() }}" @if(!empty(Route::currentRouteName()) && Route::currentRouteName()=='searchadlocation.map' ) class="search-map-page-html" @endif>

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
    {{-- <link href="/css/font-awesome.min.css" rel="stylesheet"> --}}
    {{-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651133741/Bailti/css/bootstrap.min_dg3mga.css" rel="stylesheet"> --}}

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
    <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134077/Bailti/css/developer.min_dvdavv.css" rel="stylesheet">

    {{-- <link href="/css/flexslider.min.css" rel="stylesheet"> --}}
    <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134192/Bailti/css/flexslider.min_dpbucp.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    @else
    {{-- <link href="{{ asset('css/include.css') }}" rel="stylesheet"> --}}
    {{-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134861/Bailti/css/include_m6j05r.css" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1652442655/bailti/css/style_etape1_slrikh.css"> --}}
    <link href="{{ asset('style_etape1.css') }}" rel="stylesheet">
    @endif

    <!-- Styles -->
    <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648628704/css/register.min_ufcquw.css" rel="stylesheet">
    <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650352790/Bailti/css/footer.min_jjbegh.css" rel="stylesheet">
    @stack('styles')

    {{deactiveElements()}}

    <meta name="httpcs-site-verification" content="HTTPCS9083HTTPCS" />

    <style>
        @media (max-width: 768px) {
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

        .link-back:hover {
            text-decoration: underline;
        }

        .hide_register {
            text-align: center;
            margin-top: 20px;
        }

        #userAsCat {
            display: none;
        }
    </style>

    {{-- Google Adsense --}}
    @if(Auth::check())
    @if(getConfig('google_adsense') && !isUserSubscribed(Auth::id()))
    {{ google_adsense_code() }}
    @endif
    @endif

    <style>
        .hide {
            display: none;
        }

        .dropdown-login-nrh {
            z-index: 100;
            position: absolute;
            top: 10px;
            right: 20px;

            background: white;
            width: 60px;
            border-radius: 10px 4px 4px 10px;
        }

        .dropdown-menu-login-nrh {
            min-width: 60px;
        }

        .dropdown-item-login-nrh {
            width: 46px;
            padding-left: 20px;
        }

        .dropdown-item-header-1-nrh {
            border: none !important;
            padding: 20px !important;
        }

        .dropdown-item-header-1-nrh:hover {
            background-color: transparent !important;
        }

        .dropdown-header-1-nrh {
            /*position: relative;
                    top: 15px;*/
        }

        @media (max-width: 990px) {
            .custum-navbar-nav-menuone {
                width: 100%;
            }

            .dropdown-header-1-nrh.open {
                /*width: 56px !important;*/
            }

            .dropdown-header-1-nrh.open .dropdown-menu-header-1-nrh {
                width: 56px !important;
                z-index: 100;
                float: right;
                position: relative;
                top: 20px;
                left: -60px;
            }

            .dropdown-menu {
                float: right;
            }
        }

        @media only screen and (max-width: 990px) {
            .home-custom-navbar ul li a {
                max-width: 200px;
                /* display: inline; */
                width: 100%;
            }

            .home-custom-navbar-menuone ul li {
                display: block !important;
            }

            .home-custom-navbar {
                height: auto;
            }
        }
    </style>

</head>

<body class="body-list">

    {{google_tag_manager_body()}}

    {{serverInfos()}}
    <input type="hidden" id="changeLang" value="fr" name="">
    <div class='loader-icon' style="display:none;"><img src='/images/rolling-loader.apng' alt="rolling-loader.apng"></div>

    @if(!empty(Route::currentRouteName()) && (Route::currentRouteName() == 'login_popup' || Route::currentRouteName() == 'register' || Route::currentRouteName() == 'registerStep2' || Route::currentRouteName() == 'fb_register'))
    @include('common.header_login')
    @else
    @include('common.header1')
    @endif

    @if ($message = Session::get('success'))

    <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
        {{ $message }}
    </div>

    @endif
    <section class="inner-page-wrap section-center section-login">
        <div id="popup-modal-body-login" class="register-form-x rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
            <a class="change_place" href="/" data-dismiss="modal">
                < {{__('register.change_place')}}</a>
                    <div class="modal-body">
                        <div class="pop-hdr">
                            <h6 class="fb-registration-message register-to-search">

                                {{__("register.entamons")}} <span id="ville-register">{{getVilleHome()}}</span>
                            </h6>
                        </div>
                        <div class="pop-hdr">
                            <span class="find-colocation">{{__('register.pour_acceder')}}</span>
                        </div>
                        <div class="pop-hdr">
                            <div class="find-colocation-text">
                                <ul>
                                    <li>
                                        {{__('register.consultez')}}
                                    </li>
                                    <li>
                                        {{__('register.faites_demandes')}}
                                    </li>
                                    <li>
                                        {{__('register.creez_alertes')}}
                                    </li>
                                    <li>
                                        {{__('register.filtrez_annonces')}}
                                    </li>
                                    <li>
                                        {{__('register.accedez_map')}}

                                    </li>
                                    <li>
                                        {{__('register.filtrez_annonces_affinites')}}

                                    </li>
                                    <li>
                                        {{__('register.plus_fonctionnalite')}}

                                    </li>
                                    @if ($scenario==3 || $scenario==4)
                                    @foreach ($textes as $text)
                                    @if ($langues=="fr")
                                    <li>{{$text->text_fr}}</li>

                                    @elseif ($langues=="en")
                                    <li>{{$text->text_en}}</li>
                                    @endif

                                    @endforeach

                                    @endif
                                </ul>
                            </div>
                        </div>
                        {{-- <!-- <div class="pop-hdr">
                <h6 class="fb-registration-message">{{__('login.register_fb_message')}}</h6>
                    </div> --> --}}


                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#login-tab"><i class="fa fa-address-card" aria-hidden="true"></i> {{__('register.new_user')}}?</a></li>
                        <li><a href="{{ route('login') }}"><i class="fa fa-user" aria-hidden="true"></i> {{__('register.existing_user')}}?</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="login-tab" class="tab-pane fade in active">

                            <form id="registerStep" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="register-first-step @if(isset($step)) hide @endif">

                                    @if(getConfig("icone_fb") == 1)
                                    <div class="popup-social-icons text-center">
                                        <h6>{{__('register.register_with')}}:</h6>
                                        <ul>
                                            <li class="fb-social"><a class="provider-social" href="{{ url('/login/facebook') }}">
                                                    <i class="fa fa-facebook" aria-hidden="true"></i>
                                                    <span>{{ __('register.facebook') }}</span></a>
                                            </li>
                                        </ul>
                                    </div>

                                    @endif


                                    <div class="form-group">
                                        <label>{{__('register.mail')}} <sup>*</sup></label>
                                        <input class="form-control" placeholder="{{__('register.mail_placeholder')}}" type="text" name="email" id="email_register" />
                                    </div>
                                    <div class="form-group">
                                        <label>{{__('register.pass')}} <sup>*</sup></label>
                                        <div class="input-with-icon"><i class="fa fa-eye show-hide-password" aria-hidden="true"></i><input class="form-control" placeholder="{{__('register.register_pass')}} " type="password" name="password" id="password_register" /></div>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="term_check" name="term_check" class="form-check-input" />
                                        <label class="form-check-label" for="term_check">{!! __('register.term_condition') !!}</label>
                                    </div>

                                    <div id="becomeCat" class="pr-poup-ftr">
                                        <div class="submit-btn-2"><a data-dismiss="modal" href="/">{{__('register.cancel')}}</a></div>
                                        <div class="submit-btn-2 button_condition"><button class="submit-btn-2 button_condition" id="catTimer" onClick="transformCat()"><a href="javascript:void(0);" id="submit-reg-step-1">{{__('register.next')}}</a></button></div>
                                    </div>
                                    <div id="userAsCat">
                                        <h2 class="hide_register">{{__('register.hide_send_mail')}}</h2>
                                    </div>

                                </div>
                                <div class="@if(!isset($step) || $step != 2) hide @endif">
                                    {{-- <!--add this class when only one step is there "single-step" -->
                           <!-- <div class="register-second-hdr text-center single-step">
                                <div class="step-back-arrow"><i class="fa fa-arrow-left" aria-hidden="true"></i></div>
                                <ul>
                                    <li class="current"><a href="javascript:void(0);"><span>1</span><h6>{{__('login.about')}}</h6>
            </a></li>
            </ul>
        </div> --> --}}


        <div class="popup-social-icons text-center">
            <h6>{{__('register.register_with')}}:</h6>
            <ul>
                <li class="fb-social"><a href="{{ url('/login/facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i><span>{{ __('register.facebook') }}</span></a></li>
            </ul>
        </div>
        <div class="or-divider"><span>{{__('register.or')}}</span></div>


        <div class="register-second-content">
            <input type="hidden" id="scenario_id" name="scenario_id" value="1">


            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label>{{__('register.first_name')}} <sup>*</sup></label>
                        <input type="text" class="form-control" placeholder="{{__('register.first_name')}}" name="first_name" id="first_name" />
                    </div>
                </div>
                {{-- <!--
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('login.last_name')}} <sup>*</sup></label>
                <input type="text" class="form-control" placeholder="{{__('login.last_name')}}" name="last_name" id="last_name" />
            </div>
        </div> --> --}}
        @if(isset($typeMusics) && getInfoRegister('scenario_register') != 3 )
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
        @endif
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>{{__('register.sex')}} <sup>*</sup></label>
                    <div class="custom-selectbx">
                        <select class="selectpicker" name="sex" id="sex">
                            <option value="0">{{__('register.male')}}</option>
                            <option value="1">{{__('register.female')}}</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>{{__('register.date_birth')}} <sup>*</sup></label>
                    <div class="datepicker-outer">
                        <div class="datepicker-outer">
                            <select id="date-jour" data-class="date" data-id="birth_date_registration" class="date-jour date-select">
                                <?php echo getJourOption(); ?>
                            </select>
                            <select id="date-mois" data-class="date" data-id="birth_date_registration" class="date-mois date-select">
                                <?php echo getMoisOption(); ?>
                            </select>
                            <select id="date-annee" data-class="date" data-id="birth_date_registration" class="date-annee date-select">
                                <?php echo getAnneeOption(); ?>
                            </select>
                            <input class="form-control datepicker" type="hidden" name="birth_date" value="01/01/{{date('Y')}}" id="birth_date_registration" />

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @if(getInfoRegister('scenario_register') != 3)
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>{{__('register.situation_pro')}} <sup>*</sup></label>
                    <div class="custom-selectbx">
                        <select class="selectpicker" title="{{ __('profile.choisir_professional') }}" name="professional_category" id="professional_category">
                            <option value="" selected></option>
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
            @if(getInfoRegister('scenario_register') == 3)
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>{{__('register.location_par')}} <sup>*</sup></label>
                    <div class="custom-selectbx">
                        <select class="selectpicker" name="accept_as" id="accept_as">
                            <option value="1">{{ucfirst(__('register.landlord'))}}</option>
                            <option value="2">{{ucfirst (__('register.agent'))}}</option>
                        </select>
                    </div>

                </div>
            </div>
            @endif
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>{{__('register.phone')}} <sup>*</sup></label>
                    <input type="tel" class="form-control only_num" placeholder="{{ __('register.enter_phone') }}" name="mobile_no" id="mobile_no" />
                    <input type="hidden" name="valid_number" id="valid_number" />
                    <input type="hidden" name="iso_code" id="iso_code" />
                    <input type="hidden" name="dial_code" id="dial_code" />
                    <label id="phone_custom_error" class="custom-error">{{__('validator.required')}}</label>
                </div>
            </div>

            {{-- <!--
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label> <sup>*</sup></label>
                                            <div class="custom-selectbx">
                                                <select class="selectpicker" name="accept_as" id="accept_as">
                                                    <option value="1">{{ucfirst(__('register.landlord'))}}</option>
            <option value="2">{{ucfirst (__('register.agent'))}}</option>
            </select>
        </div>

        </div>

        <div class="col-md-2">
            <label class="control-label" for="budget">Votre budget : *</label>
            <input type="text" class="form-control" id="rent_per_month_standard" placeholder="&euro;" name="rent_per_month" value="">
        </div>
        </div> -->

        <!-- <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.revenu') }} <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="€" name="revenus" id="revenus" />
                                        </div>
                                    </div> --> --}}


        @if(getInfoRegister('scenario_register') != 3)
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
                    <input type="text" class="form-control" placeholder="{{ __('register.profession_placeholder') }}" name="profession" id="profession" />
                </div>
            </div>
        </div>
        @endif

        @if(getInfoRegister('scenario_register') != 1 && getInfoRegister('scenario_register') != 3)
        <div style="padding: 10px;margin :-6px">
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
        @if(getInfoRegister('scenario_register') == 1 || getInfoRegister('scenario_register') == 2 || getInfoRegister('scenario_register') == 5 )

        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group" for="budget">
                <label style="margin-bottom:20px;">{{ __('register.budget') }} <sup> *</sup></label>
                <br>
                <input type="number" class="form-control" placeholder="&euro;" name="budget" id="budget" min="1" />
            </div>
        </div>
        @endif


        {{-- <!-- <div class="row">
                                    <div id="register-school" class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('login.school')}}</label>
        <input type="text" class="form-control" placeholder="{{__('login.school')}}" name="school" id="school" />
        </div>
        </div>
        <div id="register-profession" class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group">
                <label>{{ __('profile.profession') }} <sup>*</sup></label>
                <input type="text" class="form-control" placeholder="{{ __('profile.profession_placeholder') }}" name="profession" id="profession" />
            </div>
        </div>
        </div> --> --}}

        </div>
        <input type="hidden" id="address_register" name="address_register" @if(!is_null(getInfoRegister('address_register'))) value="{{getInfoRegister('address_register')}}" @else value="Paris, Île-de-France, France" @endif>
        <input type="hidden" id="latitude_register" @if(getInfoRegister('latitude_register')) value="{{getInfoRegister('latitude_register')}}" @else value="48.8546" @endif name="latitude_register">
        <input type="hidden" id="longitude_register" @if(getInfoRegister('longitude_register')) value="{{getInfoRegister('longitude_register')}}" @else value="2.34771" @endif name="longitude_register">
        <input type="hidden" id="scenario_register" @if(getInfoRegister('scenario_register')) value="{{getInfoRegister('scenario_register')}}" @else value="2" @endif name="scenario_register">



        <div class="pr-poup-ftr">
            <div class="submit-btn-2"><a data-dismiss="modal" href="/">{{__('register.cancel')}}</a></div>
            <div class="submit-btn-2 reg-next-btn"><a href="javascript:void(0);" id="submit-reg-step-2">{{__('register.register_button')}}</a></div>
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
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5 style="font-size : 1.2em;" id="modal-information-text" class="modal-title text-center"> {{__('register.error_link_term_condition')}} </h5>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var transformCat = function() {
            //	var startTimer = Date.now();
            //	var endTimer = startTimer+900000;
            //if(startTimer<endTimer){
            document.getElementById("userAsCat").style.display = "block";
            document.getElementById("becomeCat").style.display = "none"
            setTimeout(function() {
                document.getElementById("userAsCat").style.display = "none";
                document.getElementById("becomeCat").style.display = "block"
            }, 4000);
        };
        //}else{document.getElementById("userAsCat").style.display="none";}
    </script>

    <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649487192/Bailti/js/jquery-3.2.1.min_gdx1kd.js"></script>
    <!-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623734/bootstrap.min_lpirue.js"></script> -->

    @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'view.ad')
    {{-- <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625951/slick.min_gfbzgh.js"></script> --}}
    {{-- <script src="{{ asset('js/message_flash.js') }}"></script> --}}
    <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651217270/Bailti/js/message_flash_vyvsdf.js"></script>
    @else
    {{-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623564/bootstrap-select_cio7qn.js"></script> --}}
    <!-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623486/wow.min_zyw8vv.js"></script> -->
    {{-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623363/owl.carousel_xjecmj.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script> --}}
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.0/bootstrap-slider.min.js"></script> --}}
    {{-- <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649402901/Bailti/js/bootstrap-slider.min_vlv61y.js"></script> --}}
    {{-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623300/bootstrap-datepicker.min_r9iu0u.js"></script> --}}
    {{-- <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623205/jquery.validate.min_j1mjcb.js"></script> --}}
    {{deleteDeactiveElements()}}
    <!-- Dump all dynamic scripts into template -->
    @push('scripts')

    <!-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648628890/js/jquery.sumoselect.min_yny39b.js"></script> -->
    {{-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624801/jquery.timepicker_wqeuw6.js"></script> --}}
    <script>
        var messagess = {
            "browse": "{{__('profile.browse')}}",
            "cancel": "{{__('profile.cancel')}}",
            "remove": "{{__('profile.remove')}}",
            "upload": "{{__('profile.upload')}}"
        }
    </script>
    {{-- bailti --}}
    {{-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624955/fileinput.min_cxlrg9.js"></script>--}}
    {{-- bailti3 --}}
    {{-- <script src="https://res.cloudinary.com/avaim/raw/upload/v1651747495/bailti3/js/fileinput.min_ujunga.js"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624149/explorer-fa_theme.min_jsekze.js"></script>
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624176/theme.min_kqmhud.js"></script>
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624008/fr_wbdvfu.js"></script>
    <script type="text/javascript">
        var REQUIRED_TEXT = '{{ __("Is this documents required?") }}';
        var DELETE_IMG = '{{ URL::asset("images/delete.png") }}';
    </script>
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625174/sumoSelectInclude.min_xu2pg5.js"></script>
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625788/js/sumoSelectInclude_eva7v2.js"></script>
    {{-- <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625951/slick.min_gfbzgh.js"></script> --}}
    @endpush
    @endif

    {{-- <script src="/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649320248/js/jquery.easy-autocomplete.min_wzadpl.js"></script>
    {{-- <script src="/js/register.js"></script> --}}
    <script src="/js/script_step1.js"></script>
    {{-- <script src="/js/date-naissance.min.js"></script> --}}
    {{-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649320532/js/date-naissance.min_nkhsxo.js"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648629368/css/intlTelInput.min_gelir4.js"></script>
    <!-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648628890/js/jquery.sumoselect.min_yny39b.js"></script> -->
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626162/countrySelect_kxznsv.js"></script>
    {{-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624697/mask_od1c3d.js"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626056/jquery.validate_h7h0ud.js"></script>
    <script>
        jQuery.extend(jQuery.validator.messages, {
            required: "{{__('validator.required')}}",
            email: "{{__('validator.email')}}",
            number: "{{__('validator.number')}}"
        });
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "{{__('validator.required')}}");
    </script>
    <script>
        var messages_error = {
            "duplicat_num": "{{ __('backend_messages.duplicat_num') }}",
            "error_phone": "{{__('login.error_phone')}}",
            "error_occured": "{{__('backend_messages.error_occured')}}",
            "invalid_phone": "{{__('login.invalid_phone')}}",
            "rectify_message": "{{__('backend_messages.rectify_message')}}",
            "error_contact": "{{__('backend_messages.error_contact')}}",
            "email_confirmation": "{{__('backend_messages.email_confirmation')}}"
        };
        var messages = {
            "registration_successful": "{{__('login.registration_successful')}}"
        };
    </script>

    @if(Session::get('stepAccount') == 2)
    @push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            /*code here*/


            $('.sumo-select').SumoSelect();

            if ($("#registerStep").length >= 0) {
                $("#registerStep").validate({

                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                        },
                        budget: {
                            required: true,

                        }
                    },
                    messages: {
                        email: {
                            required: "{{__('validator.required')}}",
                            email: "{{__('validator.email')}}"
                        },
                        password: {
                            required: "{{__('validator.required')}}"
                        },
                        budget: {
                            required: "{{__('validator.required')}}"
                        }

                    }
                })
            }
        })
    </script>
    @endpush
    @endif

    @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'searchadlocation.map')
    <!--No Footer -->
    @else
    @include('common.footer')

    @endif
    @stack('scripts')
    <script>
        $(document).ready(function() {
            $('#btn-reset-popup').on('click', function() {
                $('#LoginModal').modal('hide');
                $('#ReinitialisePasswordModal').modal('show');
            });
            $('#btn-login-popup').on('click', function() {
                $('#LoginModal').modal('show');
            });
        });
    </script>


</body>

</html>