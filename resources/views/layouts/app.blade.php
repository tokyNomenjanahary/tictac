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
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134077/Bailti/css/developer.min_dvdavv.css" rel="stylesheet">

        {{-- <link href="/css/flexslider.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134192/Bailti/css/flexslider.min_dpbucp.css" rel="stylesheet">
        <link href="/css/font-awesome.min.css" rel="stylesheet">
        @else
        {{-- <link href="{{ asset('css/include.css') }}" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134861/Bailti/css/include_m6j05r.css" rel="stylesheet">
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648629049/js/owl.carousel.min_lppcif.css" rel="stylesheet">
                {{-- bailtidev --}}
{{--        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648628985/js/style_pkcfhn.css" rel="stylesheet">--}}
                {{-- bailti3 --}}
                <link href="https://res.cloudinary.com/avaim/raw/upload/v1651747908/bailti3/css/style_c7dtxe.css" rel="stylesheet">
        @endif
        <!-- Styles -->
        @stack('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648722483/css/intlTelInput.min_dpc7md.css" rel="stylesheet">

        <link rel="stylesheet" href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648629206/css/sumoselect.min_sof1hb.css">
        <link rel="stylesheet" type="text/css" href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648627947/css/slick_optghr.css"/>
        <link rel="stylesheet" type="text/css" href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648627888/css/slick-theme_sjgzgm.css"/>
        {{deactiveElements()}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649487192/Bailti/js/jquery-3.2.1.min_gdx1kd.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623734/bootstrap.min_lpirue.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623652/common_nanhue.js"></script>
        @if(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'view.ad')
        <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625951/slick.min_gfbzgh.js"></script>
        {{-- <script src="{{ asset('js/message_flash.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651217270/Bailti/js/message_flash_vyvsdf.js"></script>
        @else
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623564/bootstrap-select_cio7qn.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623486/wow.min_zyw8vv.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623363/owl.carousel_xjecmj.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
        {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.0/bootstrap-slider.min.js"></script> --}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649402901/Bailti/js/bootstrap-slider.min_vlv61y.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623300/bootstrap-datepicker.min_r9iu0u.js"></script>
        <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648623205/jquery.validate.min_j1mjcb.js"></script>
        {{deleteDeactiveElements()}}
        <!-- Dump all dynamic scripts into template -->
        @push('scripts')

        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648628890/js/jquery.sumoselect.min_yny39b.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624801/jquery.timepicker_wqeuw6.js"></script>
        <script>
        var messagess = {"browse" : "{{__('profile.browse')}}","cancel" : "{{__('profile.cancel')}}","remove" : "{{__('profile.remove')}}","upload" : "{{__('profile.upload')}}"}
        </script>
                    {{-- bailti --}}
{{--        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624955/fileinput.min_cxlrg9.js"></script>--}}
                    {{-- bailti3 --}}
                    <script src="https://res.cloudinary.com/avaim/raw/upload/v1651747495/bailti3/js/fileinput.min_ujunga.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624149/explorer-fa_theme.min_jsekze.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624176/theme.min_kqmhud.js"></script>
        @if(App::getLocale()=="fr")
         <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648624008/fr_wbdvfu.js"></script>
        @endif
        <script type="text/javascript">

            var REQUIRED_TEXT ="{{ __("Is this documents required?") }}";
            var DELETE_IMG = "{{ URL::asset("images/delete.png") }}";
        </script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625174/sumoSelectInclude.min_xu2pg5.js"></script>
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625788/js/sumoSelectInclude_eva7v2.js"></script>
        <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625951/slick.min_gfbzgh.js"></script>
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


            <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" rel="stylesheet" type="text/css">

        <style>
            .dropdown-login-nrh{
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

                .dropdown-header-1-nrh.open .dropdown-menu-header-1-nrh{
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

            @media only screen and (max-width: 990px){
                .home-custom-navbar ul li a {
                    max-width: 200px;
                    /* display: inline; */
                    width: 100%;
                }

                .home-custom-navbar-menuone ul li{
                    display: block !important;
                }

                .home-custom-navbar {
                    height: auto;
                }
            }



        </style>

    </head>

    <body  class="body-list">

        {{google_tag_manager_body()}}

        {{serverInfos()}}
        <input type="hidden" id="changeLang" value="fr" name="">
        <div class='loader-icon-search' style="display:none;"><img src='/images/rolling-loader.apng'></div>
        <div class='loader-icon' style="display:none;"><img src='/images/rolling-loader.apng' alt="rolling-loader.apng"></div>

        @if(!empty(Route::currentRouteName()) && (Route::currentRouteName() == 'login_popup' || Route::currentRouteName() == 'register' || Route::currentRouteName() == 'registerStep2' || Route::currentRouteName() == 'fb_register'))
            @include('common.header_login')
        @else
            @include('common.header1')
        @endif


        @yield('content')
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


    </body>
</html>
