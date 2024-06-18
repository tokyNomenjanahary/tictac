<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if (!empty(Route::currentRouteName()) && Route::currentRouteName() == 'searchad.map') class="search-map-page-html" @endif>

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
    <link rel='shortcut icon' href="/img/favicon.png" type='image/x-icon' />
    <!-- Styles -->
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
            href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649146128/css/slick.min_mj9xcd.css" />
        {{-- <link rel="stylesheet" type="text/css" href="/css/slick-theme.min.css"/> --}}
        <link rel="stylesheet" type="text/css"
            href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145851/css/slick-theme.min_jqpyzg.css" />
        {{-- <link rel="stylesheet" href="/css/sumoselect.min.css"> --}}
        <link rel="stylesheet"
            href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145765/css/sumoselect.min_v3ds78.css">
        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" /> -->
        {{-- <link href="{{ asset('css/custom_seek.min.css') }}" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145614/css/custom_seek.min_r8qfte.css"
            rel="stylesheet">
        {{-- <link href="/css/developer.min.css" rel="stylesheet"> --}}
        <!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145544/css/developer.min_rgnsi8.css"
            rel="stylesheet"> -->
    @elseif(!empty(Route::currentRouteName()) && Route::currentRouteName() == 'view.ad')
        <!--link href="/css/bootstrap.min.css" rel="stylesheet"-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        {{-- <link href="/css/footer.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650352790/Bailti/css/footer.min_jjbegh.css"
            rel="stylesheet">
            {{-- style.min.css --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651133852/Bailti/css/style.min_vfztxr.css" rel="stylesheet">
        <link href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648814275/bailti/css/media.min_ercnpd.css"
            rel="stylesheet">
        {{-- <link href="/css/custom-media.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649164306/css/custom-media.min_mddc7h.css"
            rel="stylesheet">
        <link href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648803262/bailti/css/developer.min_qgebqr.css"
            rel="stylesheet">
        {{-- <link href="/css/flexslider.min.css" rel="stylesheet"> --}}
        <!-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134192/Bailti/css/flexslider.min_dpbucp.css" rel="stylesheet"> -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
            rel="stylesheet">
        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" /> -->
        {{-- <link href="{{ asset('css/custom_seek.min.css') }}" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649408323/Bailti/css/custom_seek.min_mpr9vb.css"
            rel="stylesheet">
        {{-- <link href="/css/bootstrap-select.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134788/Bailti/css/bootstrap-select.min_cec8is.css" rel="stylesheet">
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
        {{-- <link href="{{ asset('css/include.css') }}" rel="stylesheet"> --}}
        <!-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134861/Bailti/css/include_m6j05r.css" rel="stylesheet"> -->


<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
<link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626427/css/bootstrap.min_eopyjk.css" rel="stylesheet">
<!-- 'footer.css; -->
<link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649420731/Bailti/css/footer_azat5k.css" rel="stylesheet">
<link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626500/css/bootstrap-select.min_j8fxck.csshttps://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626538/css/bootstrap-slider.min_u4ykj1.css"rel="stylesheet">
<!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626560/css/owl.carousel.min_u7al5h.css" rel="stylesheet"> -->
<!-- font-awesome.min.css; -->
<link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649316836/css/font-awesome.min_ymfswg.css" rel="stylesheet">
<!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626638/css/animate.min_widem5.css" rel="stylesheet"> -->
<!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626662/css/developer.min_ecylri.css" rel="stylesheet"> -->
<link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649487498/Bailti/css/style_jmohza.css" rel="stylesheet">
<link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626745/css/media_to8har.css" rel="stylesheet">
<!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626818/css/custom-media.min_hrncxm.css" rel="stylesheet"> -->
<!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626856/css/flexslider.min_fadqkb.css" rel="stylesheet"> -->
<!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626878/css/jquery.timepicker_k9isxi.css" rel="stylesheet"> -->
<!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626903/css/bootstrap-datepicker.min_febycr.css" rel="stylesheet"> -->
<!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626940/css/fileinput.min_yqpoj8.css" rel="stylesheet"> -->
<link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648627003/css/theme.min_ik0bag.css" rel="stylesheet">





        {{-- <link href="/css/owl.carousel.min.css" rel="stylesheet"> --}}
        <!-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649409521/Bailti/css/owl.carousel.min_uuplvd.css"
            rel="stylesheet"> -->
        {{-- <link rel="stylesheet" type="text/css" href="/css/slick.min.css"/> --}}
        <!-- <link rel="stylesheet" type="text/css"
            href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649146128/css/slick.min_mj9xcd.css" /> -->
        {{-- <link rel="stylesheet" type="text/css" href="/css/slick-theme.min.css"/> --}}
        <!-- <link rel="stylesheet" type="text/css"
            href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145851/css/slick-theme.min_jqpyzg.css" /> -->
        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" /> -->
        {{-- <link rel="stylesheet" href="/css/sumoselect.min.css"> --}}
        <!-- <link rel="stylesheet"
            href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145765/css/sumoselect.min_v3ds78.css"> -->
        {{-- <link href="{{ asset('css/custom_seek.min.css') }}" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649408323/Bailti/css/custom_seek.min_mpr9vb.css"
            rel="stylesheet">
    @endif

    @stack('styles')
    {{-- <link rel="stylesheet" href="/css/h_menu.min.css"> --}}
    <link rel="stylesheet"
        href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650350691/Bailti/css/h_menu.min_bbtpz5.css">
    {{-- <link rel="stylesheet" href="/css/anc-style.min.css"> --}}
    <link rel="stylesheet"
        href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650350529/Bailti/css/anc-style.min_p0wlaz.css">
    {{-- <link href="/css/intlTelInput/intlTelInput.min.css" rel="stylesheet"> --}}
    <!-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649072790/css/intlTelInput.min_uktg0j.css"
        rel="stylesheet"> -->

    {{-- <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649144688/js/jquery-3.2.1.min_lrzmxq.js"></script>
    {{-- <script src="{{ asset('js/bootstrap.min.js') }}"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649144628/js/bootstrap.min_vasx3d.js"></script>


    <!-- Dump all dynamic scripts into template -->

    @if (!empty(Route::currentRouteName()) && Route::currentRouteName() == 'search.ad')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
        {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.0/bootstrap-slider.min.js">
            </script> --}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649402901/Bailti/js/bootstrap-slider.min_vlv61y.js">
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
        {{-- <script src="{{ asset('js/bootstrap-select.min.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649072402/js/bootstrap-select.min_wg34fc.js"></script>
        <!--script src="/js/jquery.sumoselect.min.js"><script-->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.4/jquery.sumoselect.min.js"></script>
        {{-- <script src="/js/sumoSelectInclude.min.js"></script> --}}
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625174/sumoSelectInclude.min_xu2pg5.js"></script>
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
        <script src="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648819009/bailti/js/theme.min_o28nk5.js"></script>
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
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649072402/js/bootstrap-select.min_wg34fc.js"></script>
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
        {{-- <script src="{{ asset('js/jquery.timepicker.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649403362/Bailti/js/jquery.timepicker_x4ab0x.js">
        </script>
        <script type="text/javascript">
            $('.navbar-toggle').on('click', function() {
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
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649320248/js/jquery.easy-autocomplete.min_wzadpl.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.4/jquery.sumoselect.min.js"></script>
         {{-- <script src="/js/sumoSelectInclude.min.js"></script> --}}
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625174/sumoSelectInclude.min_xu2pg5.js"></script>
        <script type="text/javascript"
                src="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648815235/bailti/js/slick.min_emja5l.js"></script>
         {{-- <script src="{{ asset('js/bootstrap-select.min.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649072402/js/bootstrap-select.min_wg34fc.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
        {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.0/bootstrap-slider.min.js"></script> --}}
        <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649402901/Bailti/js/bootstrap-slider.min_vlv61y.js">
        </script>
        @push('scripts')
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
                    <script src="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648819009/bailti/js/theme.min_o28nk5.js"></script>
                     {{-- <script src="{{ asset('bootstrap-fileinput/js/locales/fr.js') }}"></script> --}}
                     @if(App::getLocale()=="fr")
                     <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649071933/js/fr_bfbjvn.js"></script>
                     @endif
@endpush
@endif
        <meta name="httpcs-site-verification" content="HTTPCS9083HTTPCS" />

            <style>
                @media (max-width: 768px){
                    .none-display {
                        display: none;
                    }

                    .langue {
                        float: left;
                    }
                }
                .link-back:hover{
                    text-decoration: underline;
                }


            </style>

        {{-- Google Adsense --}}
        @if (Auth::check())
@if (getConfig('google_adsense') && !isUserSubscribed(Auth::id()))
{{ google_adsense_code() }}
@endif
@endif


        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" rel="stylesheet" type="text/css"> -->

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

    <body @if (!empty(Route::currentRouteName()) && Route::currentRouteName() == 'payment') class="payBG" @else class="body-list" @endif>
 {{ google_tag_manager_body() }}


        {{ serverInfos() }}
        <input type="hidden" id="changeLang" value="fr" name="">
        <div class='loader-icon' style="display:none;"><img src='/images/rolling-loader.apng'></div>
        <div class='loader-icon-search' style="display:none;"><img src='/images/rolling-loader.apng'></div>
        @include('common.header2')


        <section class="inner-page-wrap">
        <div class="container">
            @if ($message = Session::get('status'))
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close"
                        title="{{ __('close') }}">×</a>
                    {{ $message }}
                </div>
            @endif
            
            @if ($message = Session::get('error'))
                <div class="alert alert-danger fade in alert-dismissable" style="margin-top:20px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close"
                        title="{{ __('close') }}">×</a>
                    {{ $message }}
                </div>
            @endif
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer">
                    <div class="user-visit-main-hdr" style="float: left; width: 100%">
                        <h4 style="width: 75%; float: left;">{{ __('dashboard.my_ads') }} </h4>
                        <div class="div-post-ad">
                            <a class="add_button_ad post_an_ad" id="post_ad_button"
                                href="/publiez-annonce">{{ __('dashboard.post_ad') }}</a>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0">
                        <ul class="nav second-tab-menu m-t-2 col-md-8 col-xs-12" style="padding: 0">
                            <li @if ($type == 'tous' || $type == null) class="active" @endif><a
                                    @if ($search_query) href="{{ url('/tableau-de-bord/tous?titre_recherche=' . $search_query) }}" @else href="{{ url('/tableau-de-bord/tous') }}" @endif>{{ __('dashboard.all_ads') }}({{ $active_ads_count }})</a>
                            </li>

                            <li @if ($type == 'desactive') class="active" @endif><a
                                    @if ($search_query) href="{{ url('/tableau-de-bord/desactive?titre_recherche=' . $search_query) }}" @else href="{{ url('/tableau-de-bord/desactive') }}" @endif>{{ __('dashboard.inactive') }}({{ $inactive_ads_count }})</a>
                            </li>
                        </ul>
                        <div class="col-md-4 col-xs-12 text-right m-t-2" style="padding: 0">
                            <div class="ad-search-bx">
                                <form id="searchForm" method="GET">
                                    <div class="form-group" style="margin: 0;">
                                        <input type="text" class="form-control"
                                            placeholder="{{ __('dashboard.search_placeholder') }}" name='titre_recherche'
                                            @if ($search_query) value="{{ $search_query }}" @endif
                                            style="height: 41px;" />
                                        <div class="ad-srch-btn" style="height: 41px;">
                                            <input type="submit" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade in active">
                            <div class="visitTab-cont visitTab-cont1">
                                <div class="adTab-cont-hdr m-t-2">
                                    <div class="row">
                                    </div>
                                </div>
                                <div class="myad-cont-bx white-bg m-t-2">
                                    @if ($ad_details_array)
                                        @foreach ($ad_details_array as $ad_details)
                                            <div class="myad-bx @if (!$ad_details['complete']) flou-ad @endif">
                                                <a
                                                    @if ($ad_details['complete']) href="{{ adUrl($ad_details['id']) }}" @endif>
                                                    <div class="myad-bx-left">
                                                        <div class="myad-bx-pic">
                                                            @if ($ad_details['scenario_id'] == 1 || $ad_details['scenario_id'] == 2)
                                                                <figure class="brdr-rect">
                                                                    <img
                                                                        @if ($ad_details['ad_file']) class="pic_available" src="{{ '/uploads/images_annonces/' . $ad_details['ad_file'] }}" alt="{{ $ad_details['user_file_name'] }}" @else class="no_pic_available" src="{{ URL::asset('images/room_no_pic.png') }}" alt="{{ __('no pic') }}" @endif />
                                                                </figure>
                                                            @else
                                                                <figure class="brdr-radi">
                                                                    <img
                                                                        @if (!empty(Auth::user()->user_profiles) && !empty(Auth::user()->user_profiles->profile_pic)) class="pic_available" src="{{ URL::asset('/uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic) }}" alt="{{ Auth::user()->user_profiles->profile_pic }}" @else class="no_pic_available" src="{{ URL::asset('images/room_no_pic.png') }}" alt="{{ __('no pic') }}" @endif />
                                                                </figure>
                                                            @endif
                                                        </div>
                                                        <div class="myad-bx-pic-txt custum-myad-bx-pic-txt">
                                                            <h5 class="dash-ad-title">{{ $ad_details['ad_title'] }}</h5>

                                                            <h6>
                                                                <div>{{ $ad_details['address'] }}</div>
                                                                @if (!empty($ad_details['min_rent']) && $ad_details['min_rent'] != 0)
                                                                    <span>{{ Conversion_devise($ad_details['min_rent']) }} {{get_current_symbol()}} @if ($ad_details['max_rent'])
                                                                            {{ '- ' . $ad_details['max_rent'] }} {{get_current_symbol()}}
                                                                        @endif
                                                                    </span>
                                                                @endif
                                                            </h6>
                                                            <!--
                                                    <h6>
                                                    <div>{{ $ad_details['address'] }}</div>
                                                    @if (!empty($ad_details['budget']) && $ad_details['budget'] != 0)
    <span>{{ $ad_details['budget'] }} {{get_current_symbol()}}</span>
    @endif
                                                        </h6>
                                                        -->
                                                                @if ($ad_details['is_logo_urgent'] == 1 && !isOldDate($ad_details['date_logo_urgent']))
                                                                    <a href="javascript:" class="link-logo-urgent">
                                                                        <span class="glyphicon glyphicon-star"></span>
                                                                        {{ __('searchlisting.urgent') }}
                                                                    </a>
                                                                @endif

                                                                <p>
                                                                    @if ($ad_details['scenario_id'] == 1 || $ad_details['scenario_id'] == 2)
                                                                        {{ traduct_info_bdd($ad_details['property_type']) }}
                                                                    @else
                                                                        {{ __('dashboard.seek_for') . ' - ' . traduct_info_bdd($ad_details['property_type']) }}
                                                                    @endif
                                                                </p>
                                                                @if ($ad_details['admin_approve'] == 1)
                                                                    <div><span
                                                                            class="approved">{{ __('dashboard.approved') }}</span>
                                                                    </div>
                                                                @else
                                                                    <div><span
                                                                            class="approved">{{ __('dashboard.not_approved') }}</span>
                                                                    </div>
                                                                @endif
                                                                <small>{{ __('dashboard.last_update') }} -
                                                                    {{ $ad_details['last_updated'] }}</small>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="myad-bx-right custum-myad-bx-right">
                                                    @if ($type == 'desactive')
                                                        <ul class='right-aligned-list'>
                                                            <li><a
                                                                    href="{{ route('delete.ad', [$ad_details['ad_url_slug']]) }}"><img
                                                                        width="35" height="35" alt="boost"
                                                                        src="/img/supprimer-annonce.png" /><span>{{ __('dashboard.delete') }}</span></a>
                                                            </li>
                                                            <li><a
                                                                    href="{{ route('activer.ad', [$ad_details['ad_url_slug']]) }}"><img
                                                                        width="35" height="35" alt="boost"
                                                                        src="/img/activer-annonce.png" /><span>{{ __('dashboard.active') }}</span></a>
                                                            </li>
                                                        </ul>
                                                    @else
                                                            <ul>
                                                                @if ($ad_details['scenario_id'] == 1 || $ad_details['scenario_id'] == 2)
                                                                    <li><a
                                                                            href="{{ url('/demandes/envoyes/' . $ad_details['ad_url_slug']) }}"><img
                                                                                width="35" height="35" alt="boost"
                                                                                src="/img/liste-demande-annonce.png" /><span>{{ __('dashboard.request_list') }}</span></a>
                                                                    </li>

                                                                    <li><a
                                                                            href="{{ searchUrl($ad_details['latitude'],$ad_details['longitude'],$ad_details['address'],permuteScenId($ad_details['scenario_id'])) }}"><img
                                                                                width="80" height="35" alt="boost"
                                                                                src="/img/chercher-demandeur-annonce.png" /><span>
                                                                                @if ($ad_details['scenario_id'] == 1)
                                                                                    {{ __('dashboard.proper_seekers') }}
                                                                                @else
                                                                                    {{ __('dashboard.room_seekers') }}
                                                                                @endif
                                                                            </span></a></li>
                                                                    <li><a
                                                                            href="{{ url('/booster-annonce/' . $ad_details['ad_url_slug']) }}"><img
                                                                                width="35" height="35" alt="boost"
                                                                                src="/img/booster_annonce.png" /><span>{{ __('dashboard.boost') }}</span></a>
                                                                    </li>
                                                                    <li><a
                                                                            href="{{ url('/messages-boite-reception/' . $ad_details['ad_url_slug']) }}"><img
                                                                                width="35" height="35" alt="boost"
                                                                                src="/img/message-dashboard.png" /><span>{{ __('dashboard.message') }}</span></a>
                                                                    </li>
                                                                    <li><a
                                                                            href="{{ url('/modifier-annonce/' . $ad_details['ad_url_slug']) }}"><img
                                                                                width="35" height="35" alt="boost"
                                                                                src="/img/modifier-annonce.png" /><span>{{ __('dashboard.edit') }}</span></a>
                                                                    </li>
                                                                    <li><a
                                                                            href="{{ url('/demandes/visite/' . $ad_details['ad_url_slug']) }}"><img
                                                                                width="35" height="35" alt="boost"
                                                                                src="/img/list-demande-visite-annonce.png" /><span>{{ __('dashboard.visit_request') }}</span></a>
                                                                    </li>
                                                                    <li><a
                                                                            href="{{ url('/candidatures-annonce/en-attente/' . $ad_details['ad_url_slug']) }}"><img
                                                                                width="35" height="35" alt="boost"
                                                                                src="/img/list-candidature-annonce.png" /><span>{{ __('dashboard.application_list') }}</span></a>
                                                                    </li>
                                                                    <li><a
                                                                            href="{{ url('/desactiver-annonce/' . $ad_details['ad_url_slug']) }}"><img
                                                                                width="35" height="35" alt="boost"
                                                                                src="/img/desactiver-annonce.png" /><span>{{ __('dashboard.deactive') }}</span></a>
                                                                    </li>
                                                                @else
                                                                    <li><a
                                                                            href="{{ url('/booster-annonce/' . $ad_details['ad_url_slug']) }}"><img
                                                                                width="35" height="35" alt="boost"
                                                                                src="/img/booster_annonce.png" /><span>{{ __('dashboard.boost') }}</span></a>
                                                                    </li>
                                                                    <li><a
                                                                            href="{{ url('/demande/recu/' . $ad_details['ad_url_slug']) }}"><img
                                                                                width="35" height="35" alt="boost"
                                                                                src="/img/liste-demande-annonce.png" /><span>
                                                                                @if ($ad_details['scenario_id'] == 5)
                                                                                    {{ __('dashboard.team_request') }}
                                                                                @else
                                                                                    {{ __('dashboard.request_recu') }}
                                                                                @endif
                                                                            </span></a></li>
                                                                    @if ($ad_details['scenario_id'] == 5)
                                                                        <li><a
                                                                                href="{{ url('/demandes/envoyes/' . $ad_details['ad_url_slug']) }}"><img
                                                                                    width="35" height="35" alt="boost"
                                                                                    src="/img/liste-demande-annonce.png" /><span>{{ __('dashboard.request') }}</span></a>
                                                                        </li>
                                                                        <li><a
                                                                                href="{{ searchUrl($ad_details['latitude'],$ad_details['longitude'],$ad_details['address'],permuteScenId($ad_details['scenario_id'])) }}"><img
                                                                                    width="80" height="35" alt="boost"
                                                                                    src="/img/chercher-demandeur-annonce.png" /><span>{{ __('dashboard.partner') }}</span></a>
                                                                        </li>
                                                                        <li><a
                                                                                href="{{ url('/messages-boite-reception/' . $ad_details['ad_url_slug']) }}"><img
                                                                                    width="35" height="35" alt="boost"
                                                                                    src="/img/message-dashboard.png" /><span>{{ __('dashboard.message') }}</span></a>
                                                                        </li>
                                                                        <li><a
                                                                                href="{{ url('/modifier-annonce/' . $ad_details['ad_url_slug']) }}"><img
                                                                                    width="35" height="35" alt="boost"
                                                                                    src="/img/modifier-annonce.png" /><span>{{ __('dashboard.edit') }}</span></a>
                                                                        </li>
                                                                        @if ($ad_details['scenario_id'] != 3 && $ad_details['scenario_id'] != 4)
                                                                            <li><a
                                                                                    href="{{ url('/candidatures-annonce/en-attente/' . $ad_details['ad_url_slug']) }}"><img
                                                                                        width="35" height="35" alt="boost"
                                                                                        src="/img/list-candidature-annonce.png" /><span>{{ __('dashboard.application_list') }}</span></a>
                                                                            </li>
                                                                        @endif
                                                                        <li><a
                                                                                href="{{ url('/desactiver-annonce/' . $ad_details['ad_url_slug']) }}"><img
                                                                                    width="35" height="35" alt="boost"
                                                                                    src="/img/desactiver-annonce.png" /><span><span>{{ __('dashboard.deactive') }}</span></a>
                                                                        </li>
                                                                    @else
                                                                        <li><a
                                                                                href="{{ searchUrl($ad_details['latitude'],$ad_details['longitude'],$ad_details['address'],permuteScenId($ad_details['scenario_id'])) }}"><img
                                                                                    width="80" height="35" alt="boost"
                                                                                    src="/img/chercher-demandeur-annonce.png" /><span>
                                                                                    @if ($ad_details['scenario_id'] == 3)
                                                                                        {{ __('dashboard.property') }}
                                                                                    @else
                                                                                        {{ __('dashboard.room') }}
                                                                                    @endif
                                                                                </span></a></li>
                                                                        <li><a
                                                                                href="{{ url('/messages-boite-reception/' . $ad_details['ad_url_slug']) }}"><img
                                                                                    width="35" height="35" alt="boost"
                                                                                    src="/img/message-dashboard.png" /><span>{{ __('dashboard.message') }}</span></a>
                                                                        </li>
                                                                        <li><a
                                                                                href="{{ url('/modifier-annonce/' . $ad_details['ad_url_slug']) }}"><img
                                                                                    width="35" height="35" alt="boost"
                                                                                    src="/img/modifier-annonce.png" /><span>{{ __('dashboard.edit') }}</span></a>
                                                                        </li>
                                                                        <li><a
                                                                                href="{{ url('/demandes/envoyes/' . $ad_details['ad_url_slug']) }}"><img
                                                                                    width="35" height="35" alt="boost"
                                                                                    src="/img/list-demande-visite-annonce.png" /><span>{{ __('dashboard.request_list') }}</span></a>
                                                                        </li>
                                                                        @if ($ad_details['scenario_id'] != 3 && $ad_details['scenario_id'] != 4)
                                                                            <li><a
                                                                                    href="{{ url('/candidatures-annonce/en-attente/' . $ad_details['ad_url_slug']) }}"><img
                                                                                        width="35" height="35" alt="boost"
                                                                                        src="/img/list-candidature-annonce.png" /><span>{{ __('dashboard.application_list') }}</span></a>
                                                                            </li>
                                                                        @endif
                                                                        <li><a
                                                                                href="{{ url('/desactiver-annonce/' . $ad_details['ad_url_slug']) }}"><img
                                                                                    width="35" height="35" alt="boost"
                                                                                    src="/img/desactiver-annonce.png" /><span>{{ __('dashboard.deactive') }}</span></a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                        @endif
                                    </div>
                                    @if ($ads)
                                        @if ($search_query)
                                            {{ $ads->appends(['search_title' => $search_query])->links('vendor.pagination.default') }}
                                        @else
                                            {{ $ads->links('vendor.pagination.default') }}
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @if (!$escrocPopup)
            @include('modal.escrocModal')
        @endif
        <script>
            history.replaceState ?
                history.replaceState(null, null, window.location.href.split("#")[0]) :
                window.location.hash = "";
        </script>




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

    @if ($registration)
    @push('scripts')
        <script type="text/javascript">
            function animatePostAdButton() {
                $("#post_ad_button").fadeOut(900).delay(300).fadeIn(600);
            }
            $(document).ready(function() {
                var anim = setInterval('animatePostAdButton()', 1300);
                $("#post_ad_button").hover(function() {
                    clearInterval(anim);
                });
            });
        </script>
    @endpush
@endif

    </body>

</html>
