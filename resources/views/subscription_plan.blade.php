<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
      @if (!empty(Route::currentRouteName()) && Route::currentRouteName() == 'searchad.map') class="search-map-page-html" @endif>

<head>
    @if (isset($ad) && $ad->id == 42694)
        <meta name="robots" content="noindex">
    @endif

    {{-- @yield('google_tag_manager_head') --}}
    {{ google_tag_manager_head() }}

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, width=device-width">
    <!-- CSRF Token -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($page_description))
        <meta name="description" content='{{ $page_description }}'>
    @else
        <meta name="description" content="{{ generatePageDescription() }}">
    @endif

    <meta name="robots" content="index, follow">
    @if (!in_array(Request::segment(1), config('customConfig.meta_blacklist_link')))
        @if (isset($page_meta_keyword))
            <meta id="e_metaKeywords" name="KEYWORDS" content="{{ $page_meta_keyword }}">
        @else
            <meta id="e_metaKeywords" name="KEYWORDS" content="{{ __('seo.keywords') }}">
        @endif
    @endif

    @if (isset($page_title))
        <title>{{ $page_title }}</title>
    @else
        @if (!empty(Route::currentRouteName()))
            @switch(Route::currentRouteName())
                @case('staticpage')
                    <title>{{ $page_detail->meta_title }}</title>
                    @break

                @default
                    <title>{{ generatePageTitle() }}</title>
            @endswitch
        @else
            <title>{{ generatePageTitle() }}</title>
        @endif

    @endif
    <link rel='shortcut icon' href="/img/favicon.png" type='image/x-icon'/>
    <!-- Styles -->
    @push('styles')
        <link href="https://fonts.googleapis.com/css?family=Sirin+Stencil&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Sahitya&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Sahitya|Sawarabi+Gothic&display=swap" rel="stylesheet">
        {{-- <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet"> --}}
        <!-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649408091/Bailti/css/custom_seek_cgov4c.css"
        rel="stylesheet"> -->
        {{-- <link href="/css/subscription.css" rel="stylesheet" type="text/css"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649407812/Bailti/css/subscription_zjacpe.css"
              rel="stylesheet" type="text/css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
              rel="stylesheet">
        <style>
            .stars-5 {
                color: white;
                font-size: 34px;
            }

            .star5 {
                margin-right: -12px;
                margin-left: 26px;
            }

            .stars5-pro {
                top: -35px;
                position: absolute;
            }


            .btn_urgent:hover {
                background-color: #0b8c00;
                color: white;
            }

            .btn_confort:hover {
                background-color: #007893;
                color: white;
            }

            .btn_zen:hover {
                background-color: #8400b3;
                color: white;
            }

            .btn_pro:hover {
                background-color: #ff3d00;
                color: white;
            }

            .btn-choix-pass{
                width: 80%;
                background: #fff;
                color: #7b7b7a;
            }

            @media only screen and (min-width: 900px) {
                .flag_cont {
                    margin-bottom: 1rem;
                }

                .flag_cont.bg_flag.green:hover {
                    box-shadow: 0px 0px 40px 0px #0b8c00;
                    background-color: #d1cece52;
                    border-radius: 2rem;
                }

                .flag_cont.bg_flag.blue:hover {
                    box-shadow: 0px 0px 40px 0px #007893;
                    background-color: #d1cece52;
                    border-radius: 2rem;
                }

                .flag_cont.bg_flag.pink:hover {
                    box-shadow: 0px 0px 40px 0px #8400b3;
                    background-color: #d1cece52;
                    border-radius: 2rem;
                }

                .flag_cont.bg_flag.red:hover {
                    box-shadow: 0px 0px 40px 0px #ff3d00;
                    background-color: #d1cece52;
                    border-radius: 2rem;
                }
            }

        </style>
    @endpush
    @if (!empty(Route::currentRouteName()) && (Route::currentRouteName() == 'search.ad' || Route::currentRouteName() == 'searchdemande.ad'))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fontisto@v3.0.4/css/fontisto/fontisto.min.css"></i>
        {{-- <link href="/css/bootstrap.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649162024/css/bootstrap.min_ir0j9h.css"
              rel="stylesheet">
        {{-- <link href="/css/footer.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650352790/Bailti/css/footer.min_jjbegh.css"
              rel="stylesheet">

        {{-- <link href="/css/bootstrap-select.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649147026/css/bootstrap-select.min_vkrdgs.css"
              rel="stylesheet">
        {{-- <link href="/css/bootstrap-slider.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649146563/css/bootstrap-slider.min_mpqida.css"
              rel="stylesheet">
        {{-- <link href="/css/style.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650352163/Bailti/css/style_loh2wo.css"
              rel="stylesheet">
        <link href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648814275/bailti/css/media.min_ercnpd.css"
              rel="stylesheet">
        {{-- <link href="/css/custom-media.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649164306/css/custom-media.min_mddc7h.css"
              rel="stylesheet">
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648887381/css/fileinput.min_symvud.css"
              rel="stylesheet">
        {{-- <link href="/bootstrap-fileinput/themes/explorer-fa/theme.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649163944/css/theme.min_s4vs40.css"
              rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
              rel="stylesheet">
        {{-- <link rel="stylesheet" type="text/css" href="/css/slick.min.css"/> --}}
        <link rel="stylesheet" type="text/css"
              href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649146128/css/slick.min_mj9xcd.css"/>
        {{-- <link rel="stylesheet" type="text/css" href="/css/slick-theme.min.css"/> --}}
        <!-- <link rel="stylesheet" type="text/css"
            href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145851/css/slick-theme.min_jqpyzg.css" /> -->
        {{-- <link rel="stylesheet" href="/css/sumoselect.min.css"> --}}
        <link rel="stylesheet"
              href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145765/css/sumoselect.min_v3ds78.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet"/>
        {{-- <link href="{{ asset('css/custom_seek.min.css') }}" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145614/css/custom_seek.min_r8qfte.css"
              rel="stylesheet">
        {{-- <link href="/css/developer.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145544/css/developer.min_rgnsi8.css"
              rel="stylesheet">
    @elseif(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'view.ad')
        <!--link href="/css/bootstrap.min.css" rel="stylesheet"-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        {{-- <link href="/css/footer.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650352790/Bailti/css/footer.min_jjbegh.css"
              rel="stylesheet">
        {{-- style.min.css --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651133852/Bailti/css/style.min_vfztxr.css"
              rel="stylesheet">
        <link href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648814275/bailti/css/media.min_ercnpd.css"
              rel="stylesheet">
        {{-- <link href="/css/custom-media.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649164306/css/custom-media.min_mddc7h.css"
              rel="stylesheet">
        <link href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648803262/bailti/css/developer.min_qgebqr.css"
              rel="stylesheet">
        {{-- <link href="/css/flexslider.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134192/Bailti/css/flexslider.min_dpbucp.css"
              rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
              rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet"/>
        {{-- <link href="{{ asset('css/custom_seek.min.css') }}" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649408323/Bailti/css/custom_seek.min_mpr9vb.css"
              rel="stylesheet">
        {{-- <link href="/css/bootstrap-select.min.css" rel="stylesheet"> --}}
        <link
            href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134788/Bailti/css/bootstrap-select.min_cec8is.css"
            rel="stylesheet">
    @elseif(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'payment')
        <!--link href="/css/bootstrap.min.css" rel="stylesheet"-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        {{-- <link href="/css/footer.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650352790/Bailti/css/footer.min_jjbegh.css"
              rel="stylesheet">
        {{-- <link href="/css/style.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650352163/Bailti/css/style_loh2wo.css"
              rel="stylesheet">
        <link href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648814275/bailti/css/media.min_ercnpd.css"
              rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
              crossorigin>
    @else
        <!-- inclusion des styles -->
        {{-- <link href="{{ asset('css/include.css') }}" rel="stylesheet"> --}}



        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900">
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626427/css/bootstrap.min_eopyjk.css"
              rel="stylesheet">
        <!-- footer.css  -->
        <link rel="stylesheet"
              href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649420731/Bailti/css/footer_azat5k.css">
        <link rel="stylesheet" href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626500/css/bootstrap-select.min_j8fxck.css
">
        <link rel="stylesheet" href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626662/css/developer.min_ecylri.css
">
        <link rel="stylesheet" href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649487498/Bailti/css/style_jmohza.css
">
        <style>

            .loader-icon {
                background: rgba(0, 0, 0, .1) none repeat scroll 0 0;
                height: 100%;
                left: 0;
                margin: 0 auto;
                position: fixed;
                right: 0;
                text-align: center;
                top: 0;
                width: 100%;
                z-index: 20000000202
            }

            .loader-icon img {
                left: 0;
                margin: -70px auto 0;
                position: absolute;
                right: 0;
                top: 50%
            }
        </style>

    @endif

    @stack('styles')
    {{-- <link rel="stylesheet" href="/css/h_menu.min.css"> --}}
    <link rel="stylesheet"
          href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650350691/Bailti/css/h_menu.min_bbtpz5.css">
    {{-- <link rel="stylesheet" href="/css/anc-style.min.css"> --}}
    <link rel="stylesheet"
          href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650350529/Bailti/css/anc-style.min_p0wlaz.css">
    {{-- <link href="/css/intlTelInput/intlTelInput.min.css" rel="stylesheet"> --}}
    {{-- <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649144688/js/jquery-3.2.1.min_lrzmxq.js"></script>
    {{-- <script src="{{ asset('js/bootstrap.min.js') }}"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649144628/js/bootstrap.min_vasx3d.js"></script>


    <!-- Dump all dynamic scripts into template -->

    @if (!empty(Route::currentRouteName()) && Route::currentRouteName() == 'search.ad')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
        {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.0/bootstrap-slider.min.js">
            </script> --}}
        <script
            src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649402901/Bailti/js/bootstrap-slider.min_vlv61y.js">
        </script>

        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
        {{-- <script src="{{ asset('js/bootstrap-select.min.js') }}"></script> --}}
        <script
            src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649072402/js/bootstrap-select.min_wg34fc.js"></script>
        <!--script src="/js/jquery.sumoselect.min.js"><script-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.4/jquery.sumoselect.min.js"></script>
        {{-- <script src="/js/sumoSelectInclude.min.js"></script> --}}
        <script
            src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625174/sumoSelectInclude.min_xu2pg5.js"></script>
        <script type="text/javascript"
                src="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648815235/bailti/js/slick.min_emja5l.js"></script>
        <script>
            var messagess = {
                "browse": "{{ __('profile.browse') }}",
                "cancel": "{{ __('profile.cancel') }}",
                "remove": "{{ __('profile.remove') }}",
                "upload": "{{ __('profile.upload') }}"
            }
        </script>
        {{-- <script src="{{ asset('bootstrap-fileinput/js/fileinput.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649072051/js/fileinput.min_uyyft6.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649071849/js/theme.min_f52op0.js"></script>
        <script
            src="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648819009/bailti/js/theme.min_o28nk5.js"></script>
        {{-- <script src="{{ asset('bootstrap-fileinput/js/locales/fr.js') }}"></script> --}}
        {{-- <script src="{{ asset('js/commoninner.js') }}"></script> --}}
        @if(App::getLocale()=="fr")
            <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649071933/js/fr_bfbjvn.js"></script>
        @endif
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649071723/js/commoninner_asnndq.js"></script>
        <script type="text/javascript"
                src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648887940/js/jquery.validate.min_bvlpwl.js"></script>
    @elseif(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'view.ad')
        <script type="text/javascript"
                src="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648815235/bailti/js/slick.min_emja5l.js"></script>
        {{-- <script src="{{ asset('js/bootstrap-select.min.js') }}"></script> --}}
        <script
            src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649072402/js/bootstrap-select.min_wg34fc.js"></script>
        <script type="text/javascript"
                src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648887940/js/jquery.validate.min_bvlpwl.js"></script>
    @elseif(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'payment')
        <script type="text/javascript"
                src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648887940/js/jquery.validate.min_bvlpwl.js"></script>
    @else
        <script type="text/javascript"
                src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648887940/js/jquery.validate.min_bvlpwl.js"></script>
        {{-- <script src="{{ asset('js/wow.min.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649403824/Bailti/js/wow.min_upw6yq.js"></script>
        {{-- <script src="{{ asset('js/common.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649403632/Bailti/js/common_jlh1nb.js"></script>
        {{-- <script src="{{ asset('js/commoninner.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649071723/js/commoninner_asnndq.js"></script>

        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
        {{-- <script src="{{ asset('js/jquery.timepicker.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649403362/Bailti/js/jquery.timepicker_x4ab0x.js">
        </script>
        <script type="text/javascript">
            $('.navbar-toggle').on('click', function () {
                if ($('.navbar-collapse').attr('aria-expanded') == 'false' || $('.navbar-collapse').attr(
                    'aria-expanded') == null) {
                    $('.upgrade-btn-outside').hide();
                } else {
                    $('.upgrade-btn-outside').show();
                }

            });
            var REQUIRED_TEXT = "{{ __('Is this documents required?') }}";
            var DELETE_IMG = "{{ URL::asset('images/delete.png') }}";
        </script>
        {{-- <script src="/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script> --}}
        <script
            src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649320248/js/jquery.easy-autocomplete.min_wzadpl.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.4/jquery.sumoselect.min.js"></script>
        {{-- <script src="/js/sumoSelectInclude.min.js"></script> --}}
        <script
            src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625174/sumoSelectInclude.min_xu2pg5.js"></script>
        <script type="text/javascript"
                src="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648815235/bailti/js/slick.min_emja5l.js"></script>
        {{-- <script src="{{ asset('js/bootstrap-select.min.js') }}"></script> --}}
        <script
            src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649072402/js/bootstrap-select.min_wg34fc.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
        {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.0/bootstrap-slider.min.js"></script> --}}
        <script
            src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649402901/Bailti/js/bootstrap-slider.min_vlv61y.js">
        </script>
        @push('scripts')
            <script>
                var messagess = {
                    "browse": "{{ __('profile.browse') }}",
                    "cancel": "{{ __('profile.cancel') }}",
                    "remove": "{{ __('profile.remove') }}",
                    "upload": "{{ __('profile.upload') }}"
                }
                <script>
                    /*$(document).ready(function(){
                    $('.button-show-plan').on('click', function () {
                        location.href = $(this).attr('data-href');
                    });
                });*/
            </script>
            </script>
            <script src="https://js.stripe.com/v3/"></script>
            {{-- <script src="{{ asset('bootstrap-fileinput/js/fileinput.min.js') }}"></script> --}}
            {{-- <script src="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.js') }}"></script> --}}
            <script
                src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649072051/js/fileinput.min_uyyft6.js"></script>
            <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649071849/js/theme.min_f52op0.js"></script>
            <script
                src="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648819009/bailti/js/theme.min_o28nk5.js"></script>
            {{-- <script src="{{ asset('bootstrap-fileinput/js/locales/fr.js') }}"></script> --}}
            @if(App::getLocale()=="fr")
                <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649071933/js/fr_bfbjvn.js"></script>
            @endif
        @endpush
    @endif
    <meta name="httpcs-site-verification" content="HTTPCS9083HTTPCS"/>

    <style>
        @media (max-width: 768px) {
            .none-display {
                display: none;
            }

            .langue {
                float: left;
            }
        }

        .link-back:hover {
            text-decoration: underline;
        }
    </style>

    {{-- Google Adsense --}}
    @if (Auth::check())
        @if (getConfig('google_adsense') && !isUserSubscribed(Auth::id()))
            {{ google_adsense_code() }}
        @endif
    @endif
    <style>
        .dropdown-item-header-2-nrh {
            padding: 20px;
        }

        @media (max-width: 767px) {
            .dropdown-item-header-2-nrh {
                left: -30px;
            }
        }

        .btn-default-nrh {
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }
    </style>

</head>

<body @if (!empty(Route::currentRouteName()) && Route::currentRouteName() == 'payment') class="payBG"
      @else class="body-list" @endif>

{{ google_tag_manager_body() }}


{{ serverInfos() }}
<input type="hidden" id="changeLang" value="fr" name="">
<div class='loader-icon' style="display:none;"><img src='/images/rolling-loader.apng'></div>
<div class='loader-icon-search' style="display:none;"><img src='/images/rolling-loader.apng'></div>
@include('common.header2')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 ">
            <div class="panel heading_page bg_adh">
                <div class="panel-body ">
                    <h1 class="pan_title">
                        @if (is_null($typeUser))
                            {{ __('subscription.envie_louer_rapidement') }}
                        @else
                            {{ __('subscription.envie_louer_rapidement') }}
                            {{-- {{ i18n('envie_louer_rapidement' . $typeUser) }} --}}
                        @endif
                    </h1>
                    <p class="text-center">
                        @if (is_null($typeUser))
                            {{ __('subscription.le_pass_premium') }}
                        @else
                            {{ __('subscription.le_pass_premium') }}
                            {{-- {{ i18n('le_pass_premium' . $typeUser) }} --}}
                        @endif
                        <br>
                        <span class="font-weight-bold">
                                @if (is_null($typeUser))
                                {!! __('subscription.les_annonces_premium') !!}
                            @else
                                {!! __('subscription.les_annonces_premium') !!}
                                {{-- {{ i18n('les_annonces_premium' . $typeUser) }}  --}}
                            @endif

                            </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <span class="row separator position-relative">
            <span class="text-center">
                @if (is_null($typeUser))
                    {{ __("subscription.selectionnez_plan") }}
                @else
                    {{ __("subscription.selectionnez_plan") }}
                    {{-- {{ i18n('selectionnez_plan' . $typeUser) }} --}}
                @endif
            </span>
                </span>
    @php
        $description=App::getLocale()=="fr"?"description":"description_en";
    @endphp
    <div class="container-fluid">
        <div class="row">
            @if (!empty($packages) && count($packages) > 0)
                @foreach ($packages as $i => $package)
                    @if ($i == 0)
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            @if ($package->popular == 1)
                                <div class="T_flag_cont position-relative">
                                    <div class="T_flag text-center">
                                        {{ __("subscription.le_plus_populaire") }}
                                    </div>
                                </div>
                            @endif
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}">
                                <div class="flag_cont bg_flag green">
                                    <div class="H_flag">{{ strtoupper($package->title) }}</div>
                                    <div class="M_flag text-center">
                                        <span>{{ $package->duration }}</span><br>
                                        <span>{{ traduct_info_bdd($package->unite) }}</span>
                                    </div>
                                    <div class="B_flag text-center">
                                            <span class="inline-block">
                                                <span>{{$current}}</span>
                                                @if (getConfig('tva') == 1)
                                                    @php
                                                        $moinTva = amountmoinTVA(Conversion_devise($package->amount));
                                                    @endphp
                                                    @if ($moinTva == null)
                                                        <span>{{ number_format($package->amount, 2, ',', '') }} </span>
                                                    @else
                                                        <span>{{ $moinTva }}  {{ __('payment.HT') }}</span>
                                                    @endif
                                                @else
                                                    <span>{{ number_format($package->amount, 2, ',', '') }} </span>
                                                @endif
                                            </span>
                                    </div>
                                    <div class="F_flag">
                                        <p class="flag_desc text-center">
                                                <?php echo $package->$description; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <div class="btn_block text-center">
                                <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                   sub-plan-amount="{{ $package->amount }}">
                                    <button class="btn btn-lg btn_urgent">{{ __('payment.choix') }}</button>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($i == 1)
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            @if ($package->popular == 1)
                                <div class="T_flag_cont position-relative">
                                    <div class="T_flag text-center">
                                        {{ __("subscription.le_plus_populaire") }}
                                    </div>
                                </div>
                            @endif
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}">
                                <div class="flag_cont bg_flag blue">
                                    <div class="H_flag blue">{{ strtoupper($package->title) }}</div>
                                    <div class="M_flag text-center blue">
                                        <span>{{ $package->duration }}</span><br>
                                        <span>{{ traduct_info_bdd($package->unite) }}</span>
                                    </div>
                                    <div class="B_flag text-center">
                                            <span class="inline-block">
                                                <span class="text-white">{{$current}}</span>
                                                @if (getConfig('tva') == 1)
                                                    @php

                                                        $moinTva = amountmoinTVA(Conversion_devise($package->amount));
                                                    @endphp

                                                    @if ($moinTva == null)
                                                        <span
                                                            class="text-white">{{ number_format($package->amount, 2) }}</span>
                                                    @else
                                                        <span>{{ $moinTva }} {{ __('payment.HT') }}</span>
                                                    @endif
                                                @else
                                                    <span
                                                        class="text-white">{{ number_format($package->amount, 2) }}</span>
                                                @endif

                                            </span>
                                    </div>

                                    <div class="F_flag">
                                        <p class="flag_desc text-center text-white">
                                                <?php echo $package->$description; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <div class="btn_block text-center">

                                <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                    sub-plan-amount="{{ $package->amount }}">
                                    <button class="btn btn-lg btn_confort">{{ __('payment.choix') }}</button>
                                </a>

                            </div>
                        </div>
                    @endif
                    @if ($i == 2)
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            @if ($package->popular == 1)
                                <div class="T_flag_cont position-relative">
                                    <div class="T_flag text-center">
                                        {{ __("subscription.le_plus_populaire") }}
                                    </div>
                                </div>
                            @endif
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}">
                                <div class="flag_cont bg_flag pink">
                                    <div class="H_flag pink">{{ strtoupper($package->title) }}</div>
                                    <div class="M_flag text-center pink">
                                        <span>{{ $package->duration }}</span><br>
                                        <span>{{ traduct_info_bdd($package->unite) }}</span>
                                    </div>
                                    <div class="B_flag text-center pink">
                                            <span class="inline-block">
                                                                                            <span>{{$current}}</span>

                                                @if (getConfig('tva') == 1)
                                                    @php

                                                        $moinTva = amountmoinTVA(Conversion_devise($package->amount));

                                                    @endphp
                                                    @if ($moinTva == null)
                                                        <span>{{ number_format($package->amount, 2) }}</span>
                                                    @else
                                                        <span>{{ $moinTva }} {{ __('payment.HT') }}</span>
                                                    @endif
                                                @else
                                                    <span>{{ number_format($package->amount, 2) }}</span>
                                                @endif


                                            </span>
                                    </div>
                                    <div class="F_flag pink">
                                        <p class="flag_desc text-center">
                                                <?php echo $package->$description; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <div class="btn_block text-center">
                                <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                   sub-plan-amount="{{ $package->amount }}">
                                    <button class="btn btn-lg btn_zen">{{ __('payment.choix') }}</button>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($i == 3)
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            @if ($package->popular == 1)
                                <div class="T_flag_cont position-relative">
                                    <div class="T_flag text-center">
                                        {{ __("subscription.le_plus_populaire") }}
                                    </div>
                                </div>
                            @endif
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}">
                                <div class="flag_cont bg_flag red">
                                    <div class="stars5-pro">
                                            <span class="stars-5">
                                                <i class="fa fa-star star5" aria-hidden="true"></i>
                                                <i class="fa fa-star star5" aria-hidden="true"></i>
                                                <i class="fa fa-star star5" aria-hidden="true"></i>
                                                <i class="fa fa-star star5" aria-hidden="true"></i>
                                                <i class="fa fa-star star5" aria-hidden="true"></i>
                                            </span>
                                    </div>


                                    <div class="H_flag red">{{ strtoupper($package->title) }}</div>
                                    <div class="M_flag text-center red">
                                        <span>{{ $package->duration }}</span><br>
                                        <span>{{ traduct_info_bdd($package->unite) }}</span>
                                    </div>

                                    <div class="B_flag text-center">
                                            <span class="inline-block">
                                                <span class="text-white">{{$current}}</span>
                                                @if (getConfig('tva') == 1)
                                                    @php

                                                        $moinTva = amountmoinTVA(Conversion_devise($package->amount));
                                                    @endphp
                                                    @if ($moinTva == null)
                                                        <span
                                                            class="text-white">{{ number_format($package->amount, 2) }}</span>
                                                    @else
                                                        <span>{{ $moinTva }} {{ __('payment.HT') }}</span>
                                                    @endif
                                                @else
                                                    <span
                                                        class="text-white">{{ number_format($package->amount, 2) }}</span>
                                                @endif
                                            </span>
                                    </div>
                                    <div class="F_flag">
                                        <p class="flag_desc text-center text-white">

                                            {{ $package->$description }}
                                        </p>
                                    </div>

                                </div>
                            </a>

                            <div class="btn_block text-center">
                                <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                   sub-plan-amount="{{ $package->amount }}">
                                    <button class="btn btn-lg btn_pro">{{ __('payment.choix') }}</button>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    <div class="container-fluid mobile d-none">
        <div class="row">
            <div class="col-sm-12">
                @if (!empty($packages) && count($packages) > 0)
                    @foreach ($packages as $i => $package)
                        @if ($i == 0)
                            @if ($package->popular == 1)
                                <div class="container-popular-mobile">
                                    <div class="plan-popular-mobile">{{ __("subscription.le_plus_populaire") }}</div>
                                </div>
                            @endif
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}" sub-plan-amount="{{ $package->amount }}">

                                <div class="h_flag button-show-plan"
                                    data-href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                    sub-plan-amount="{{ $package->amount }}">

                                    <span class="inline-block">{{ strtoupper($package->title) }}</span>
                                    <span class="inline-block">
                                                                                        <span>{{$current}}</span>
                                            @if (getConfig('tva') == 1)
                                            @php
                                                $moinTva = amountmoinTVA(Conversion_devise($package->amount));
                                            @endphp
                                            @if ($moinTva == null)
                                                <span>{{ number_format($package->amount, 2) }}</span>
                                            @else
                                                <span>{{ $moinTva }} {{ __('payment.HT') }}</span>
                                            @endif
                                        @else
                                            <span>{{ number_format($package->amount, 2) }}</span>
                                        @endif

                                        </span>
                                </div>
                                <div class="flag_bg inline-block button-show-plan"
                                    data-href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                    sub-plan-amount="{{ $package->amount }}">
                                    <div class="M_flag inline-block">
                                        <span class="inline-block">{{ $package->duration }}</span>
                                        <span class="inline-block">{{ traduct_info_bdd($package->unite) }}</span>
                                    </div>
                                    <div class="F_flag inline-block">
                                        <p class="flag_desc text-center">
                                                <?php echo $package->$description; ?>
                                        </p>
                                    </div>
                                </div>

                            </a>
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                               sub-plan-amount="{{ $package->amount }}">
                                <button class="btn btn-lg btn-choix-pass">{{ __('payment.choix') }}</button>
                            </a>
                            <div class="line"></div>
                        @endif
                        @if ($i == 1)
                            @if ($package->popular == 1)
                                <div class="container-popular-mobile">
                                    <div class="plan-popular-mobile">{{ __("subscription.le_plus_populaire") }}</div>
                                </div>
                            @endif
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}">
                                <div class="h_flag blue button-show-plan"
                                    data-href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                    sub-plan-amount="{{ $package->amount }}">
                                    <span class="inline-block">{{ strtoupper($package->title) }}</span>
                                    <span class="inline-block">
                                                                                        <span>{{$current}}</span>

                                            @if (getConfig('tva') == 1)
                                            @php
                                                $moinTva = amountmoinTVA(Conversion_devise($package->amount));
                                            @endphp
                                            @if ($moinTva == null)
                                                <span>{{ number_format($package->amount, 2) }}</span>
                                            @else
                                                <span>{{ $moinTva }} {{ __('payment.HT') }}</span>
                                            @endif
                                        @else
                                            <span>{{ number_format($package->amount, 2) }}</span>
                                        @endif
                                        </span>
                                </div>
                                <div class="flag_bg inline-block blue button-show-plan"
                                    data-href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                    sub-plan-amount="{{ $package->amount }}">
                                    <div class="M_flag inline-block">
                                        <span class="inline-block">{{ $package->duration }}</span>
                                        <span class="inline-block">{{ traduct_info_bdd($package->unite) }}</span>
                                    </div>
                                    <div class="F_flag inline-block">
                                        <p class="flag_desc text-center text-white">
                                                <?php echo $package->$description; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                               sub-plan-amount="{{ $package->amount }}">
                                <button class="btn btn-lg btn-choix-pass">{{ __('payment.choix') }}</button>
                            </a>
                            <div class="line"></div>
                        @endif
                        @if ($i == 2)
                            @if ($package->popular == 1)
                                <div class="container-popular-mobile">
                                    <div class="plan-popular-mobile">{{ __("subscription.le_plus_populaire") }}</div>
                                </div>
                            @endif
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}">
                                <div class="h_flag pink button-show-plan"
                                    data-href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                    sub-plan-amount="{{ $package->amount }}">
                                    <span class="inline-block">{{ strtoupper($package->title) }}</span>
                                    <span class="inline-block">
                                                                                        <span>{{$current}}</span>
                                            @if (getConfig('tva') == 1)
                                            @php

                                                $moinTva = amountmoinTVA(Conversion_devise($package->amount));

                                            @endphp
                                            @if ($moinTva == null)
                                                <span>{{ number_format($package->amount, 2) }}</span>
                                            @else
                                                <span>{{ $moinTva }} {{ __('payment.HT') }}</span>
                                            @endif
                                        @else
                                            <span>{{ number_format($package->amount, 2) }}</span>
                                        @endif


                                        </span>
                                </div>
                                <div class="flag_bg inline-block pink button-show-plan"
                                    data-href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                    sub-plan-amount="{{ $package->amount }}">
                                    <div class="M_flag inline-block">
                                        <span class="inline-block">{{ $package->duration }}</span>
                                        <span class="inline-block">{{ traduct_info_bdd($package->unite) }}</span>
                                    </div>
                                    <div class="F_flag inline-block">
                                        <p class="flag_desc text-center">
                                                <?php echo $package->$description; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                               sub-plan-amount="{{ $package->amount }}">
                                <button class="btn btn-lg btn-choix-pass">{{ __('payment.choix') }}</button>
                            </a>
                            <div class="line">
                                <img src="img/plan/white-stars_5.png" alt="" class="star-5">
                            </div>
                        @endif
                        @if ($i == 3)
                            @if ($package->popular == 1)
                                <div class="container-popular-mobile">
                                    <div class="plan-popular-mobile">{{ __("subscription.le_plus_populaire") }}</div>
                                </div>
                            @endif
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}">
                                <div class="h_flag red button-show-plan"
                                    data-href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                    sub-plan-amount="{{ $package->amount }}">

                                    <span class="inline-block">{{ strtoupper($package->title) }}</span>
                                    <span class="inline-block">
                                                                                        <span>{{$current}}</span>

                                            @if (getConfig('tva') == 1)
                                            @php
                                                $moinTva = amountmoinTVA(Conversion_devise($package->amount));
                                            @endphp
                                            @if ($moinTva == null)
                                                <span>{{ number_format($package->amount, 2) }}</span>
                                            @else
                                                <span>{{ $moinTva }} {{ __('payment.HT') }}</span>
                                            @endif
                                        @else
                                            <span>{{ number_format($package->amount, 2) }}</span>
                                        @endif

                                        </span>

                                </div>
                                <div class="flag_bg inline-block red button-show-plan"
                                    data-href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                                    sub-plan-amount="{{ $package->amount }}">
                                    <div class="M_flag inline-block">
                                        <span class="inline-block">{{ $package->duration }}</span>
                                        <span class="inline-block">{{ traduct_info_bdd($package->unite) }}</span>
                                    </div>
                                    <div class="F_flag inline-block">
                                        <p class="flag_desc text-center text-white">
                                                <?php echo $package->$description; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <a href="/payment/{{ $package->id }}?email={{ Auth::user()->email }}&EncryptedKey={{ generateKey() }}"
                               sub-plan-amount="{{ $package->amount }}">
                                <button class="btn btn-lg btn-choix-pass">{{ __('payment.choix') }}</button>
                            </a>
                        @endif
                    @endforeach
                @endif


            </div>
        </div>
    </div>
    <!-- <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="btn-parainage">
                        <a href="/parrainage"><span>{{ i18n('abonnement_parainnage') }}</span></a>
                        <div class="layer_2">
                            <img src="/images/layer_2.png">
                        </div>
                        <div class="layer_3">
                            <img src="/images/layer_3.png">
                        </div>
                    </div>
                </div>
            </div> -->


    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="panel content_panel">
                <div class="panel-body">
                    <h1 class="text-center cardTitle">
                        @if (is_null($typeUser))
                            {{ __('subscription.pourquoi_allez_premium') }}
                        @else
                            {{ __('subscription.pourquoi_allez_premium') }}
                            {{-- {{ i18n('pourquoi_allez_premium' . $typeUser) }} --}}
                        @endif
                    </h1>
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th width="20%" scope="col">{{ __("subscription.basic") }}</th>
                            <th width="20%" scope="col">
                                <button class="btn btn-lg">{{ __("subscription.premium") }}</button>
                            </th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $phrases = getPremiumPhrases(); ?>
                        @foreach ($phrases as $phrase)
                            <tr>
                                <td width="20%">
                                    @if ($phrase->type_membre == 0 || $phrase->type_membre == 3)
                                        <span class="td_img">
                                                    {{-- <img src="/img/plan/checked-grey.png"
                                            alt=""> --}}
                                                    <img
                                                        src="https://res.cloudinary.com/dl7aa4kjj/image/upload/v1649421073/Bailti/img/checked-grey_sksftb.png"
                                                        alt="">
                                                </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td width="20%">
                                    @if ($phrase->type_membre == 1 || $phrase->type_membre == 3)
                                        {{-- <span class="td_img"><img src="/img/plan/checked-orange.png"
                                    alt=""></span> --}}
                                        <span class="td_img"><img
                                                src="https://res.cloudinary.com/dl7aa4kjj/image/upload/v1649421136/Bailti/img/checked-orange_lmyrml.png"
                                                alt=""></span>
                                    @else
                                        -
                                    @endif
                                </td>
                                @php
                                    $phrase_lang=App::getLocale()=="fr"?"phrase_fr":"phrase_en"
                                @endphp
                                <td>{{ $phrase->$phrase_lang }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        <h1 class="text-center cardTitle">
                            {{ __("subscription.infos_supp") }}
                        </h1>
                        <div class="div-infos">
                            <div>
                                <div>
                                    <div class="signal-info div-icon">
                                            <span
                                                class="
                                            span-icon span-signal-info span-signal-info-loue"><i
                                                    class="fa fa-home fb-nb"></i><span
                                                    class="icon-x icon-x-info icon-x-loue">{{ __('addetails.loue') }}</span>
                                            </span>
                                    </div>
                                    <div class="acces-right-content">
                                        <div class="txt-infos-vente">{{ __('subscription.subs_annonce_loue') }}</div>
                                        <div class="text-explication td">
                                            {{ __('subscription.subs_annonce_loue_desc') }}
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="signal-info div-icon">
                                            <span class="span-icon span-signal-info">
                                                <i class="fa fa-phone fb-nb"></i><span class="icon-x icon-x-info"
                                                                                       style="margin-top: -3px;margin-left: 6px;">X</span>
                                            </span>
                                    </div>
                                    <div class="acces-right-content">
                                        <div class="txt-infos-vente">{{ __('subscription.subs_no_phone') }}</div>
                                        <div class="text-explication td">
                                            {{ __('subscription.subs_no_phone_desc') }}
                                        </div>
                                    </div>

                                </div>
                                <div>
                                    <div class="signal-info div-icon">
                                            <span class="span-icon span-signal-info">
                                                <i class="fa fa-facebook-f fb-nb"></i><span
                                                    class="icon-x icon-x-info">X</span>
                                            </span>
                                    </div>

                                    <div class="acces-right-content">
                                        <div class="txt-infos-vente">{{ __('subscription.subs_no_fb') }}</div>
                                        <div class="text-explication td">
                                            {{ __('subscription.subs_no_fb_desc') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-center cardTitle">
                            {{ __('payment.dossier_accept') }}
                        </h1>
                        <div class="div-infos">
                            <div class="text-explication">
                                {{ __('payment.Bailti_signale_fb') }}

                            </div>
                        </div>
                        <div class="row">
                            <div class="img-fb-vente">
                                <div class="panel panel-default">
                                    {{-- <img src="/img/fb_vente.png"> --}}
                                    {{-- bailtidev --}}
                                    {{--<img src="https://res.cloudinary.com/dl7aa4kjj/image/upload/v1649421185/Bailti/img/fb_vente_crhmef.png">--}}
                                    {{-- bailtidev2 --}}
                                    <img
                                        src="https://res.cloudinary.com/bailti2/image/upload/v1651746249/bailti/img/fb_vente_pfohek.png">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-center cardTitle">
                            {{ __('subscription.design_premium') }}
                        </h1>
                        <div class="row">
                            <div class="col-sm col-md-6 col-lg-6 no-bg">
                                <div class="panel panel-default">
                                    <img class="img-desk" src="/images/last-premium-list-2.jpg">
                                    <img class="img-mobile" src="/images/last-premium-list-mobile-2.jpg">
                                </div>
                            </div>
                            <div class="col-sm col-md-6 col-lg-6 no-bg">
                                <div class="panel panel-default">
                                    <img class="img-desk" src="/images/detail-ad-premium.jpg">
                                    <img class="img-mobile" src="/images/detail-premium-mobile.jpg">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-center cardTitle">
                            @if (is_null($typeUser))
                                {{ __('subscription.millier_user_week') }}
                            @else
                                {{ __('subscription.millier_user_week') }}
                                {{-- {{ __('millier_user_week' . $typeUser) }} --}}
                            @endif

                        </h1>
                        <div class="row">
                            <div class="col-sm col-md-6 col-lg-6 no-bg">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title p_title">
                                            @if (is_null($typeUser))
                                                {{ __('subscription.temoin_parfait') }}
                                            @else
                                                {{ __('subscription.temoin_parfait') }}
                                                {{-- {{ i18n('temoin_parfait' . $typeUser) }} --}}
                                            @endif
                                        </h3>
                                    </div>
                                    <div class="panel-body position-relative">
                                        <p class="panel_desc">
                                            @if (is_null($typeUser))
                                                {{ __('subscription.temoin_1_parole') }}
                                            @else
                                                {{ __('subscription.temoin_1_parole') }}
                                                {{-- {{ i18n('temoin_1_parole' . $typeUser) }} --}}
                                            @endif

                                        </p>
                                        <span class="aut"><span class="fav"><img
                                                    src="img/plan/yellow-stars-5.png" alt=""></span>
                                                @if (is_null($typeUser))
                                                {{ __('subscription.nom_temoin_1') }}
                                            @else
                                                {{ __('subscription.nom_temoin_1') }}
                                                {{-- {{ i18n('nom_temoin_1' . $typeUser) }} --}}
                                            @endif
                                            </span>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title p_title">
                                            @if (is_null($typeUser))
                                                {{ __('subscription.temoin_2_resume') }}
                                            @else
                                                {{ __('subscription.temoin_2_resume') }}
                                                {{-- {{ i18n('temoin_2_resume' . $typeUser) }} --}}
                                            @endif
                                        </h3>
                                    </div>
                                    <div class="panel-body position-relative">
                                        <p class="panel_desc">
                                            @if (is_null($typeUser))
                                                {{ __('subscription.temoin_2_parole') }}
                                            @else
                                                {{ __('subscription.temoin_2_parole') }}
                                                {{-- {{ i18n('temoin_2_parole' . $typeUser) }} --}}
                                            @endif
                                        </p>
                                        <span class="aut"><span class="fav"><img
                                                    src="img/plan/yellow-stars-5.png" alt=""></span>
                                                @if (is_null($typeUser))
                                                {{ __('subscription.temoin_2_nom') }}
                                            @else
                                                {{ __('subscription.temoin_2_nom') }}
                                                {{-- {{ i18n('temoin_2_nom' . $typeUser) }} --}}
                                            @endif
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm col-md-6 col-lg-6 no-bg">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title p_title">
                                            @if (is_null($typeUser))
                                                {{ __('subscription.temoin_3_resume') }}
                                            @else
                                                {{ __('subscription.temoin_3_resume') }}
                                                {{-- {{ i18n('temoin_3_resume' . $typeUser) }} --}}
                                            @endif
                                        </h3>
                                    </div>
                                    <div class="panel-body position-relative">
                                        <p class="panel_desc">
                                            @if (is_null($typeUser))
                                                {{ __('subscription.temoin_3_parole') }}
                                            @else
                                                {{ __('subscription.temoin_3_parole') }}
                                                {{-- {{ i18n('temoin_3_parole' . $typeUser) }} --}}
                                            @endif
                                        </p>
                                        <span class="aut"><span class="fav"><img
                                                    src="img/plan/yellow-stars-5.png" alt=""></span>
                                                @if (is_null($typeUser))
                                                {{ __('subscription.temoin_3_nom') }}
                                            @else
                                                {{ __('subscription.temoin_3_nom') }}
                                                {{-- {{ i18n('temoin_3_nom' . $typeUser) }} --}}
                                            @endif
                                            </span>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title p_title">
                                            @if (is_null($typeUser))
                                                {{ __('subscription.temoin_4_resume') }}
                                            @else
                                                {{ __('subscription.temoin_4_resume') }}
                                                {{-- {{ i18n('temoin_4_resume' . $typeUser) }} --}}
                                            @endif
                                        </h3>
                                    </div>
                                    <div class="panel-body position-relative">
                                        <p class="panel_desc">
                                            @if (is_null($typeUser))
                                                {{ __('subscription.temoin_4_parole') }}
                                            @else
                                                {{ __('subscription.temoin_4_parole') }}
                                                {{-- {{ i18n('temoin_4_parole' . $typeUser) }} --}}
                                            @endif
                                        </p>
                                        <span class="aut"><span class="fav"><img
                                                    src="img/plan/yellow-stars-5.png" alt=""></span>
                                                @if (is_null($typeUser))
                                                {{ __('subscription.temoin_4_nom') }}
                                            @else
                                                {{ __('subscription.temoin_4_nom') }}
                                                {{-- {{ i18n('temoin_4_nom' . $typeUser) }} --}}
                                            @endif
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>
@include('common.code_promo')
@if (!empty(Route::currentRouteName()) && Route::currentRouteName() == 'searchad.map')
    <!--No Footer -->
@else
    @include('common.footer')
@endif
@include('common.notification')
@include('common.all-notification')
@stack('scripts')
<style>
    .count-glyph {
        background: #f72626 none repeat scroll 0 0;
        border-radius: 25px;
        color: #fff;
        display: none;
        font-family: Lato, Arial;
        font-size: 1.3rem;
        font-weight: 400;
        line-height: normal;
        padding: 1px 5px 2px;
        position: absolute;
        left: 3%;
        top: 12px;
    }

</style>
</body>

</html>
