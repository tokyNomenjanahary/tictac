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
    @push('styles')
<!-- <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet"> -->
<link href="/css/subscription.css" rel="stylesheet" type="text/css">
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
        <!-- <link rel="stylesheet" type="text/css"
            href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649146128/css/slick.min_mj9xcd.css" /> -->
        {{-- <link rel="stylesheet" type="text/css" href="/css/slick-theme.min.css"/> --}}
        <!-- <link rel="stylesheet" type="text/css"
            href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145851/css/slick-theme.min_jqpyzg.css" /> -->
        {{-- <link rel="stylesheet" href="/css/sumoselect.min.css"> --}}
        <link rel="stylesheet"
            href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649145765/css/sumoselect.min_v3ds78.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
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
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651133852/Bailti/css/style.min_vfztxr.css" rel="stylesheet">
        <link href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648814275/bailti/css/media.min_ercnpd.css"
            rel="stylesheet">
        {{-- <link href="/css/custom-media.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649164306/css/custom-media.min_mddc7h.css"
            rel="stylesheet">
        <link href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648803262/bailti/css/developer.min_qgebqr.css"
            rel="stylesheet">
        {{-- <link href="/css/flexslider.min.css" rel="stylesheet"> --}}
        <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134192/Bailti/css/flexslider.min_dpbucp.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
            rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
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
        <link href="{{ asset('html/css/style-annonce.css') }}" rel="stylesheet">
        <!-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1651134861/Bailti/css/include_m6j05r.css" rel="stylesheet"> -->
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
        <!-- <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649408323/Bailti/css/custom_seek.min_mpr9vb.css"
            rel="stylesheet"> -->
    @endif

    @stack('styles')
    {{-- <link rel="stylesheet" href="/css/h_menu.min.css"> --}}
    <link rel="stylesheet"
        href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650350691/Bailti/css/h_menu.min_bbtpz5.css">
    {{-- <link rel="stylesheet" href="/css/anc-style.min.css"> --}}
    <!-- <link rel="stylesheet"
        href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650350529/Bailti/css/anc-style.min_p0wlaz.css"> -->
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

        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script> -->
        {{-- <script src="{{ asset('js/bootstrap-select.min.js') }}"></script> --}}
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649072402/js/bootstrap-select.min_wg34fc.js"></script>
        <!--script src="/js/jquery.sumoselect.min.js"><script-->
            <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.4/jquery.sumoselect.min.js"></script> -->
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

        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script> -->
        {{-- <script src="{{ asset('js/jquery.timepicker.js') }}"></script> --}}
        <!-- <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649403362/Bailti/js/jquery.timepicker_x4ab0x.js"> -->
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
        <!-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649320248/js/jquery.easy-autocomplete.min_wzadpl.js"> -->
        </script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script> -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.4/jquery.sumoselect.min.js"></script> -->
         {{-- <script src="/js/sumoSelectInclude.min.js"></script> --}}
        <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648625174/sumoSelectInclude.min_xu2pg5.js"></script>
        <!-- <script type="text/javascript"
                src="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1648815235/bailti/js/slick.min_emja5l.js"></script> -->
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
    <script>
    $(document).ready(function(){
        $('#btn-badi-link').on('click', function(){
            insertUserBadiInfos($(this).attr("data-id"));
        });
    });
    function insertUserBadiInfos(ad_id,callback = null)
    {
        $.ajax({
            type: "POST",
            data : {"ad_id" : ad_id},
            url: "/insert_user_badi",
            success: function (data) {
            }
        });
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

        <section class="inner-page-wrap section-subscription">
    <!-- <div class="subscription-header-badi">
        <img src="/img/badi-app.jpg" class="img-badi">
        <div class="page-header-container-badi">
            <h2 class="payment__pgsubtitle badi-subtitle">Badi a levé 45 millions d'euro pour accélérer la location de votre logement ! Postez votre logement chez Badi et recevez dans la journée des candidatures de qualité</h2>
            <div class="button-entete badi-button"><a href="https://badi.com/fr/rent-room?utm_source=partnerships&utm_medium=facebookgroup&utm_campaign=paris_nasser&utm_term=post_moderator&utm_content=link_1" target="_blank" class="btn-acceuil" data-id="{{$ad->id}}" id="btn-badi-link">{{__('subscription.rent_here')}}</a></div>
        </div>
    </div> -->
    <div class="subscription-header">
        <div class="payment__header--filter"></div>
        <div class="page-header-container">
            <h1 class="payment__pgtitle">{{ __('subscription.pgtitle') }}</h1>
            <h2 class="payment__pgsubtitle">{{ __('subscription.pgsubtitle') }}</h2>
            @if(!isUserSubscribed())
            <div class="button-entete"><a href="{{route('subscription_plan') . '?tag=new_ad_confirmation'}}" class="btn-acceuil">{{__('subscription.en_savoir_plus')}}</a></div>
            @endif
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 user-visit-main-outer">
                <div class="user-visit-main-hdr">
                    <h4>{{ __('property.confirmation') }}</h4>
                </div>

                <div class="subscription-listing white-bg m-t-2">
                    <div class="row">
                        <div class="alert alert-success alert-confirmation fade in alert-dismissable"><span class="icon-ok">{{__("property.confirmation_text")}}</span></div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="ad_id" value="{{$ad->id}}" name="">
                        <input type="hidden" id="ad_title" value="{{$ad->title}}" name="">

                        <div class="detail-paiement"><strong>{{__('property.detail_ad')}} : </strong></div>
                        <div class="detail-paiement">{{__('property.title_ad')}}  : {{$ad->title}}</div>
                        <div class="detail-paiement">{{__('property.lieu_recherche')}}  : {{$ad->address}}
                        </div>
                        @if($ad->scenario_id <= 2)
                        <div class="detail-paiement">{{__('property.prix')}} : {{Conversion_devise($ad->min_rent)}}{{get_current_symbol()}}
                        </div>
                        @else
                        @if(!empty($ad->min_rent))
                        <div class="detail-paiement">{{__('property.budget')}} : {{Conversion_devise($ad->min_rent)}}{{get_current_symbol()}}  @if(!is_null($ad->max_rent)) - {{Conversion_devise($ad->max_rent)}}{{get_current_symbol()}}
                        @endif
                        </div>
                        @endif
                        @endif
                    </div>
                    <div class="row">
                        <div class="detail-paiement">
                        <a href="{{route('user.dashboard')}}" class="btn-acceuil">{{__('subscription.dashboard')}}</a>
                        @if($ad->scenario_id==1)
                        <a class="btn-acceuil" href="{{ searchUrl($ad->latitude, $ad->longitude, $ad->address, permuteScenId($ad->scenario_id)) }}">{{__('dashboard.proper_seekers')}}</a>
                        @endif
                        @if($ad->scenario_id==2)
                        <a class="btn-acceuil" href="{{ searchUrl($ad->latitude, $ad->longitude, $ad->address, permuteScenId($ad->scenario_id)) }}">{{__('dashboard.room_seekers')}}</a>
                        @endif
                        @if($ad->scenario_id==3)
                        <a class="btn-acceuil" href="{{ searchUrl($ad->latitude, $ad->longitude, $ad->address, permuteScenId($ad->scenario_id)) }}">{{__('dashboard.property')}}</a>
                        @endif
                        @if($ad->scenario_id==4)
                        <a class="btn-acceuil" href="{{ searchUrl($ad->latitude, $ad->longitude, $ad->address, permuteScenId($ad->scenario_id)) }}">{{__('dashboard.room')}}</a>
                        @endif
                        @if($ad->scenario_id==5)
                        <a class="btn-acceuil" href="{{ searchUrl($ad->latitude, $ad->longitude, $ad->address, permuteScenId($ad->scenario_id)) }}">{{__('dashboard.partner')}}</a>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
