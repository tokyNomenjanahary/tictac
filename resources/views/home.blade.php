<!DOCTYPE html>
{{-- {{ App::getLocale()  }} --}}

<html lang="fr-FR" prefix="og: http://ogp.me/ns#">
<!--<![endif]-->

<head>
    {{ google_tag_manager_head() }}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-*********" crossorigin="anonymous" />

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, width=device-width">
    @if (is_null($infosAddress))
        <meta name="title" content='{{ __('seo.title_page', ["date"=>date("Y")]) }}'>
    @else
        <meta name="title" content='{{ __('acceuil.title_ville', ['ville' => $infosAddress['ville']]) }}'>
    @endif
    @if (is_null($infosAddress))
        <meta name="description" content="{{ __('seo.description_site', ["date"=>date("Y")]) }}">
    @else
        <meta name="description" content="{{ __('seo.description_site_ville', ['ville' => $infosAddress['ville'], "date"=>date("Y")]) }}">
    @endif
    @if (is_null($infosAddress))
        <meta id="e_metaKeywords" name="KEYWORDS" content="{{ __('seo.description_site', ["date"=>date("Y")]) }}">
    @else
        <meta id="e_metaKeywords" name="KEYWORDS"
            content="{{ __('seo.description_site_ville', ['ville' => $infosAddress['ville'], "date"=>date("Y")]) }}">
    @endif
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="vWRsdZ3-d863Hdr7ZQKc1ysH0dEkYpB8ovZk99oA9wE" />
    <title>
        @if (is_null($infosAddress))
            {{ __('seo.title_page', ["date"=>date("Y")]) }}
        @else
            {{ __('acceuil.title_ville', ['ville' => $infosAddress['ville']]) }}
        @endif
    </title>

    @if (is_null($infosAddress))
        <meta property="og:title" content='{{ __('seo.title_page', ["date"=>date("Y")]) }}' />
    @else
        <meta property="og:title" content="{{ __('acceuil.title_ville', ['ville' => $infosAddress['ville']]) }}" />
    @endif
    @if (is_null($infosAddress))
        <meta property="og:description" content="{{ __('seo.description_site', ["date"=>date("Y")]) }}" />
    @else
        <meta property="og:description"
            content="{{ __('seo.description_site_ville', ['ville' => $infosAddress['ville'], "date"=>date("Y")]) }}" />
    @endif

    <meta property="og:image" content="https://www.bailti.fr/img/lefond.jpeg" />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:url" content="https://www.bailti.fr/" />
    <meta property="og:site_name" content="bailti" />
    <meta property="og:type" content="website" />
    <link rel="icon" href="/img/favicon.png">
    <!-- <meta name="robots" content="noindex"> -->

    {{-- <link href="/css/cssHome/bootstrap.min.css" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649315670/css/bootstrap.min_h6gjp3.css" rel="stylesheet" type="text/css"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    {{-- <link href="/css/styleHome.min.css" rel="stylesheet" type="text/css"> --}}
    <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649317613/css/styleHome.min_lt0vq9.css"
        rel="stylesheet" type="text/css">
    {{-- <link href="/css/styleAnymate.css" rel="stylesheet" type="text/css"> --}}
    <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649314958/css/styleAnymate_sddajv.css"
        rel="stylesheet" type="text/css">
    {{-- <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css"> --}}
    <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649316836/css/font-awesome.min_ymfswg.css"
        rel="stylesheet" type="text/css">
    {{-- <link href="/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648626500/css/bootstrap-select.min_j8fxck.css" rel="stylesheet" type="text/css"> --}}
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,500i,700,900" rel="stylesheet">
    <link rel="canonical" href="https://www.bailti.fr" />
    <!-- <link href="/css/popup-home.min.css" rel="stylesheet" type="text/css"> -->

    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" rel="stylesheet" type="text/css"> --}}

    <meta name="httpcs-site-verification" content="HTTPCS9083HTTPCS" />

    <style>

        .autocomplete-items {
          position: absolute;
          border: 1px solid rgba(0, 0, 0, 0.1);
          box-shadow: 0px 2px 10px 2px rgba(0, 0, 0, 0.1);
          border-top: none;
          background-color: #fff;

          z-index: 99;
          top: calc(100% + 2px);
          left: 0;
          right: 0;
          overflow-x: scroll;
        }

        .autocomplete-items div {
          padding: 10px;
          cursor: pointer;
        }

        .autocomplete-items div:hover {
          /*when hovering an item:*/
          background-color: rgba(0, 0, 0, 0.1);
        }

        .autocomplete-items .autocomplete-active {
          /*when navigating through the items using the arrow keys:*/
          background-color: rgba(0, 0, 0, 0.1);
        }

        </style>


    <style>
        .dropdown-nrh {
            background: white;
            width: 60px;
            z-index: 3;
            position: absolute;
            top: 18px;
            border-radius: 6px;
            margin-left: 7px;
        }

        a#navbarDropdownMenuLink78.nav-link.dropdown-toggle {
            padding: 1px;
            margin: 0px;
            border-radius: -3px !important;
            padding-left: 11px;
        }

        div.dropdown-menu.dropdown-menu-nrh {
            padding: 0px;
            margin: 0px;
            border-radius: 6px;
            /*border: 1px solid #979797;*/
        }

        a.dropdown-item.dropdown-item-nrh {
            padding: 1px 0px;
            margin-left: 10px;
        }

        .dropdown-menu-nrh {
            min-width: 60px;
        }

        .dropdown-item-nrh {
            width: 46px;
            padding-left: 9px;
        }

        .icon-home {
            font-size: 5rem;
            color: white;
        }

        .ligne-home {
            border: 5px solid white;
        }

    </style>

    {{-- Google Adsense --}}
    @if (Auth::check())
        @if (getConfig('google_adsense') && !isUserSubscribed(Auth::id()))
            {{ google_adsense_code() }}
        @endif
    @else
        @if (getConfig('google_adsense'))
            {{ google_adsense_code() }}
        @endif
    @endif
</head>

<body>
    @if (is_null($infosAddress))
        {{ fil_ariane() }}
    @else
        {{ fil_ariane($infosAddress['ville']) }}
    @endif

    {{ google_tag_manager_body() }}

    <!--star header-->
    <section class="header clearfix">
        <div class=" head header">
            <div class="h2-ville">
                <h2>
                    @if (is_null($infosAddress))
                        {{ __('acceuil.h2', ['ville' => '']) }}
                    @else
                        {{ __('acceuil.h2', ['ville' => $infosAddress['ville']]) }}
                    @endif
                </h2>
            </div>
            @if (!is_null($infosAddress) && $infosAddress['ville'] != 'Paris')
                <div class="div-image-fond {{ $infosAddress['ville'] }}">

                </div>
            @else
                <div class="div-image-fond" style="background-image:url(/uploads/cover_pics/{{{ getConfig('photo_couverture') }}});"></div>
                {{-- cdn bailtidev
                {{-- <div class="div-image-fond" style="background-image:url(https://res.cloudinary.com/dl7aa4kjj/image/upload/v1649486873/Bailti/img/89389_60084efa7cbcf_gj56cd.jpg);"> --}}
                {{-- cdn bailtidev2 --}}
                {{-- <div class="div-image-fond"
                    style="background-image:url(https://res.cloudinary.com/bailti2/image/upload/v1651745599/bailti/img/89389_60084efa7cbcf_gj56cd_mdkia0.jpg);">
                </div> --}}
            @endif
            <div class="div-content-acceuil">
                <div class="vert_line"></div>
                <div class="pub_log">
                    <span class="text-white">
                        <a rel='nofollow' class="post_an_ad"
                            href="/publiez-annonce">{{ __('acceuil.post_ad') }}</a>
                    </span>

                    <span @guest class="span-not-connected" @else class="user-profile-content" @endguest>
                        @guest
                            <a rel='nofollow' id="btnRegister" href="javascript:"
                                class="buttonSearchButton">{{ __('acceuil.register_acceuil') }}</a> <span
                                style="margin:0px;">|</span> <a rel='nofollow'
                                href="{{ route('login') }}">{{ __('acceuil.login_acceuil') }}</a>
                        @else
                            <div class="dropdown user">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <table>
                                        <tr>
                                            @if (!empty(Auth::user()->user_profiles) && !empty(Auth::user()->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic)))
                                                <td><img src="{{ URL::asset('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic) }}"
                                                        loading="lazy" alt="" style="transform: rotate({{Auth::user()->user_profiles->pdp_rotate}}deg);" /></td>
                                            @else
                                                <td><img src="{{ URL::asset('images/profile_avatar.jpeg') }}"
                                                        loading="lazy" alt="" /></td>
                                            @endif
                                            <td>{{ __('acceuil.hi') }},&nbsp{{ Auth::user()->first_name }}</td>
                                        </tr>
                                    </table>

                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                     <form action="{{ route('user.toggle') }}" method="post">
                        {{ csrf_field() }}
                                         @method('put')
                        <input type="text" value="1" name="role_id" hidden>
                        <a href="#" class="dropdown-item"
                           onclick="event.preventDefault(); this.closest('form').submit();"><i class="fas fa-home" style="margin-right: 10px!important;"></i>Espace propriétaire</a>
                    </form>
                    <form action="{{ route('user.toggle') }}" method="post">
                        {{ csrf_field() }}
                        @method('put')
                        <input type="text" value="2" name="role_id" hidden>
                        <a href="" class="dropdown-item"
                           onclick="event.preventDefault(); this.closest('form').submit();"><i class="fas fa-key" style="margin-right: 10px!important;"></i> Espace locataire</a>
                    </form>
                                    <a class="dropdown-item"
                                        href="{{ route('edit.profile') }}"><i class="fas fa-user" style="margin-right: 10px!important;"></i>{{ __('header.profile') }}</a>
                                    <a class="dropdown-item"
                                        href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt" style="margin-right: 5px!important;"></i>{{ __('header.dashboard') }}</a>
                                    <a class="dropdown-item"
                                        href="{{ url('/mes-candidatures/envoyes/tous') }}"><i class="fas fa-file-alt" style="margin-right: 10px!important;"></i>{{ __('header.applications') }}</a>
                                    <a class="dropdown-item" id="mes_reception"
                                    href="{{ url("messages-boite-reception") }}" ><i class="fas fa-bell" style="margin-right: 10px!important;"></i>{{ __('notification.modal_notif_title') }} <strong
                                            id="nbNotifs"></strong></a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();">
                                      <i class="fas fa-sign-out-alt" style="margin-right: 10px!important;"></i>  {{ __('header.logout') }}
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                            {{ csrf_field() }}
                                        </form>
                                    </a>
                                </div>
                            </div>
                        @endguest
                    </span>
                </div>

                <div class="d-lg-block dropdown dropdown-nrh">
                    @if (getConfig('langue') == 1)
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink78"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display:fixe;">
                            @if (App::getLocale() == 'fr')
                                <i class="flag-icon flag-icon-fr"></i>
                            @else
                                <i class="flag-icon flag-icon-us"></i>
                            @endif

                        </a>
                    @endif
                    <div class="dropdown-menu dropdown-menu-nrh" aria-labelledby="navbarDropdownMenuLink78">
                        @if (App::getLocale() == 'fr')
                            <a class="dropdown-item dropdown-item-nrh"
                                href="{{ route('changement-langue', ['langue' => 'en']) }}"><i
                                    class="flag-icon flag-icon-us"></i></a>
                        @else
                            <a class="dropdown-item dropdown-item-nrh"
                                href="{{ route('changement-langue', ['langue' => 'fr']) }}"><i
                                    class="flag-icon flag-icon-fr"></i> </a>
                        @endif
                    </div>
                </div>





                <div class="logo_section">
                    <div class="logo"><a rel='follow' href="/"><img src="/img/blue-logo.png" loading="lazy"
                                width="1600" height="720" alt="logo"></a></div>
                    <div class="title">
                        <h1>
                            @if (is_null($infosAddress))
                                <?php echo __('acceuil.reseau_social'); ?>
                            @else
                                {{ __('acceuil.h1_title_ville', ['ville' => $infosAddress['ville'], "date"=>date("Y")]) }}
                            @endif
                        </h1>
                        <p class="many_users">{{ __('acceuil.many_users') }}</p>

                    </div>

                </div>
                <form id="home-search-sc2" method="POST" action="{{ route('searchadScenId') }}">
                    {{ csrf_field() }}
                    <div class="h_search" id="search-container">
                        <img src="/img/home.png" loading="lazy" alt="">
                        <input id="address" class="text_search text-white pb-1 pl-2" name="address"
                            placeholder="{{ __('acceuil.searchPlaceHolder') }}"
                            @if (isset($dataRegister)) value="{{ $dataRegister['address'] }}" @else @if (is_null($infosAddress)) value="Paris, Île-de-France, France" @else value="{{ $infosAddress['ville'] . ', ' . $infosAddress['region'] . ', ' . 'France' }}" @endif
                            @endif autocomplete="off">
                        <a href="javascript:" class="buttonSearchButton"><span class="buttonRecherche"></span></a>
                        <div class="error-address">{{ __('acceuil.error_address') }}</div>
                    </div>
                    <input type="hidden" id="first_latitude" name="latitude"
                        @if (isset($dataRegister)) value="{{ $dataRegister['latitude'] }}" @else @if (is_null($infosAddress)) value="48.8546" @else value="{{ $infosAddress['latitude'] }}" @endif
                        @endif>
                    <input type="hidden" id="first_longitude" name="longitude"
                        @if (isset($dataRegister)) value="{{ $dataRegister['longitude'] }}" @else @if (is_null($infosAddress)) value="2.34771" @else value="{{ $infosAddress['longitude'] }}" @endif
                        @endif>
                    <input type="hidden" id="search_scenario_id" name="scenario_id"
                        @if (isset($dataRegister)) value="{{ $dataRegister['scenario'] }}" @else value="2" @endif>
                    @if (isset($dataRegister))
                        <input type="hidden" id="isRegistration" value="1" name="">
                    @endif
                    <input type="hidden" name="Search" value="Search">
                    <div class="vert_line2"></div>
                    <div class="search_filter">
                        <div class="custom-control custom-checkbox">
                            <span data-id="1" class="type_scenario last-type-scenario type_scenario_1">
                                <label class="container-checkbox">
                                    {{ __('acceuil.logement') }}
                                    <input type="checkbox" id="customCheck1" value="1" class="check-upsel"
                                        name="customCheck1">
                                    <span class="checkmark"></span>
                                </label>
                            </span>
                            <span data-id="2" class="type_scenario last-type-scenario type_scenario_2">
                                <label class="container-checkbox">
                                    {{ __('acceuil.colocation') }}
                                    <input type="checkbox" id="customCheck2" checked value="2" class="check-upsel"
                                        name="customCheck2">
                                    <span class="checkmark"></span>
                                </label>
                            </span>
                            <span data-id="3" class="type_scenario last-type-scenario type_scenario_3">
                                <label class="container-checkbox">
                                    {{ __('acceuil.locataire') }}
                                    <input type="checkbox" id="customCheck3" value="3" class="check-upsel"
                                        name="customCheck3">
                                    <span class="checkmark"></span>
                                </label>
                            </span>
                            <br class="retour-scenario">
                            <span data-id="4" class="type_scenario last-type-scenario type_scenario_4">
                                <label class="container-checkbox">
                                    {{ __('acceuil.colocataire') }}
                                    <input type="checkbox" id="customCheck4" value="4" class="check-upsel"
                                        name="customCheck4">
                                    <span class="checkmark"></span>
                                </label>
                            </span>
                            <span data-id="5" class="type_scenario last-type-scenario type_scenario_5">
                                <label class="container-checkbox">
                                    {{ __('acceuil.monter_colocation') }}
                                    <input type="checkbox" id="customCheck5" value="5" class="check-upsel"
                                        name="customCheck5">
                                    <span class="checkmark"></span>
                                </label>
                            </span>
                        </div>
                        <div class="post_ad_div">
                            <div class="text-rechercher">{{ __('acceuil.recherche_locatiare')}}</div><a rel='follow'
                                class="annonce post-job-btn post_an_ad"
                                href="/publiez-annonce/logement">{{ __('acceuil.post_ad_logement') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!--star content-->

    <div id="trouver" class="loc-sol">
        <div class="inner">
            <div class="container">
                <div class="section-heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="44.576" height="45.838" viewBox="0 0 44.576 45.838">
                        <path
                            d="M213.465,863.064,187.73,878.1h6.329l-.4,2.007L187.73,908.9l11.476-9.97h22.482l3.464-17.481.664-3.35h6.491Zm-11.638,23.178h-5.811l1.117-5.422h5.827Zm9.5,0h-5.811l1.117-5.422h5.811Zm9.5,0h-5.811l1.117-5.422h5.811Z"
                            transform="translate(-187.73 -863.064)" fill="#e65611" />
                    </svg>
                    <h2>{{ __('acceuil.futur_colocataire') }}</h2>
                    <p>

                        {{ __('acceuil.find_logement') }}
                    </p>
                </div>

                <div class="card-content">
                    <div class="card">
                        <a rel='follow' href="/solution-pour-les-locataires">
                            <div class="card-body">
                                <svg xmlns="http://www.w3.org/2000/svg" width="37.251" height="37.251"
                                    viewBox="0 0 37.251 37.251">
                                    <g transform="translate(-533 -1417.433)">
                                        <g transform="translate(538.497 1423.789)">
                                            <circle cx="4.173" cy="4.173" r="4.173" transform="translate(4.173)"
                                                fill="#ffd97d" />
                                            <path
                                                d="M-23.485,15.054V17.14H-6.793V15.054c0-2.775-5.561-4.172-8.345-4.172S-23.485,12.279-23.485,15.054Z"
                                                transform="translate(23.485 -0.45)" fill="#ffd97d" />
                                        </g>
                                        <path
                                            d="M-21.295,13.61c.469-.1,7.685-.547,7.685-.547s2.238-.234,1.614,1.095a14.186,14.186,0,0,1-1.9,2.76l-4.324.26a18.691,18.691,0,0,1-1.823-1.016c-.026-.1-.624-1.2-.624-1.2Z"
                                            transform="translate(563.457 1425.785)" fill="#ffd97d" />
                                        <path
                                            d="M.557,26.428H-1.125l-.6-.575a13.783,13.783,0,0,0,3.344-9.009A13.844,13.844,0,0,0-12.222,3,13.844,13.844,0,0,0-26.066,16.844,13.844,13.844,0,0,0-12.222,30.688a13.783,13.783,0,0,0,9.009-3.344l.575.6v1.683L8.012,40.251l3.173-3.173Zm-12.779,0a9.572,9.572,0,0,1-9.584-9.584A9.572,9.572,0,0,1-12.222,7.26a9.572,9.572,0,0,1,9.584,9.584A9.572,9.572,0,0,1-12.222,26.428Z"
                                            transform="translate(559.066 1414.433)" fill="#4c8dcb" />
                                    </g>
                                </svg>
                                <h4 class="card-title">
                                    {{ __('acceuil.find_coloc') }}
                                </h4>
                                <p class="card-text">
                                    {{ __('acceuil.school_coloc_text') }}
                                </p>
                            </div>
                        </a>
                    </div>
                    <div class="card">
                        <a rel='follow' href="/solution-pour-les-locataires">
                            <div class="card-body">
                                <svg xmlns="http://www.w3.org/2000/svg" width="42.29" height="36.3"
                                    viewBox="0 0 42.29 36.3">
                                    <path
                                        d="M25.8,8.078c0-.02,0-.039,0-.057Q25.6,5.28,25.338,2.546A2.958,2.958,0,0,0,24.689.753,2.53,2.53,0,0,0,22.208.063C19.118.571,16.031,1.1,12.942,1.6Q7.52,2.492,2.1,3.363A2.47,2.47,0,0,0,0,5.83,5.267,5.267,0,0,0,.245,7.074,71.732,71.732,0,0,0,2.281,14.6a36.011,36.011,0,0,0,5,9.807,15.6,15.6,0,0,0,4.689,4.439,7.562,7.562,0,0,0,4.022,1.138,8.828,8.828,0,0,0,2.125-.424c.078-.022.146-.041.207-.057h0a1.471,1.471,0,0,1,.237-.048h0a5.388,5.388,0,0,0,2.382-1.517.92.92,0,0,1,.16-.481c.255-.41.549-.8.824-1.194a12.07,12.07,0,0,0,1.082-1.751,3.889,3.889,0,0,1,.553-.875c.206-.526.405-1.1.594-1.721A42.746,42.746,0,0,0,25.8,8.078M6.567,13.593c-.942.391-1.444.122-1.643-.885-.033-.17-.042-.345-.056-.469A3.358,3.358,0,0,1,6.6,9.257a3.686,3.686,0,0,1,3.569-.326A3.19,3.19,0,0,1,11.6,10.242a.924.924,0,0,1-.476,1.407c-1,.443-2,.864-3.009,1.293q-.773.329-1.549.651M19.6,23.077a5.009,5.009,0,0,1-3.383,2.24,5.593,5.593,0,0,1-.924.078,5.328,5.328,0,0,1-4.183-2.042,9.877,9.877,0,0,1-1.948-3.976.905.905,0,0,1,.68-1.21,17.611,17.611,0,0,1,2.134-.4c2.106-.352,4.213-.691,6.32-1.034.458-.074.915-.161,1.376-.2a.945.945,0,0,1,1.177.934A8.821,8.821,0,0,1,19.6,23.077m2.126-12.053h-1.22c-1.284-.016-2.568-.069-3.853-.06-.879.006-1.276-.488-1.108-1.352A2.867,2.867,0,0,1,16.82,7.677a3.9,3.9,0,0,1,4.153-.251,3.224,3.224,0,0,1,1.714,2.422.964.964,0,0,1-.96,1.176"
                                        transform="translate(0 0)" fill="#4c8dca" />
                                    <path
                                        d="M262.525,109.061a3.294,3.294,0,0,0-1.367-.605l-13.814-3.429c-.366-.091-.473-.209-.5-.575-.005-.074-.01-.148-.016-.223a42.741,42.741,0,0,1-1.635,13.833c-.188.624-.388,1.2-.594,1.721a3.948,3.948,0,0,1,2.644-1.323,5.044,5.044,0,0,1,5.576,4.185,11.54,11.54,0,0,1,.239,2.262,7.422,7.422,0,0,1-.137,1.427.967.967,0,0,1-1.331.812q-2.955-.727-5.907-1.468c-.938-.234-1.873-.477-2.81-.712a1.414,1.414,0,0,1-.471-.214.818.818,0,0,1-.421-.667A5.388,5.388,0,0,1,239.6,125.6c.236-.021.282.115.443.557a11.791,11.791,0,0,0,2.32,4.052,6.252,6.252,0,0,0,4.77,2.239,5.745,5.745,0,0,0,1.117-.092,9.11,9.11,0,0,0,3.879-1.745,21.17,21.17,0,0,0,5-5.369,43.166,43.166,0,0,0,4.522-8.936,42.156,42.156,0,0,0,1.584-4.938,2.161,2.161,0,0,0-.715-2.309m-16.107,5.1c-.15.046-.23.073-.2-.145q.229-1.575.435-3.154a.448.448,0,0,1,.481-.432c.826-.052,1.273.317,1.272,1.05a3.088,3.088,0,0,1-1.989,2.681m12.008,2.185a3.234,3.234,0,0,1-3.681.643,3.536,3.536,0,0,1-2.225-3.274,2.5,2.5,0,0,1,.142-.89.95.95,0,0,1,1.446-.614c.65.319,1.282.678,1.918,1.025.7.381,1.388.767,2.082,1.152.043.023.086.045.128.069a1.056,1.056,0,0,1,.19,1.888"
                                        transform="translate(-221.032 -96.151)" fill="#fd556f" />
                                    <rect width="42.29" height="36.3" fill="none" />
                                </svg>
                                <h4 class="card-title">{{ __('acceuil.regroupement') }}</h4>
                                <p class="card-text">
                                    {{ __('acceuil.regroupement_text') }}
                                </p>
                            </div>
                        </a>
                    </div>
                    <div class="card">
                        <a rel='follow' href="/solution-pour-les-locataires">
                            <div class="card-body">
                                <svg xmlns="http://www.w3.org/2000/svg" width="38.203" height="38.274"
                                    viewBox="0 0 38.203 38.274">
                                    <g transform="translate(266.96 164.872)">
                                        <path
                                            d="M-239.893-164.872h-24.079a2.988,2.988,0,0,0-2.989,2.984v16.69a2.992,2.992,0,0,0,2.989,2.989h3.14v6.435a1.137,1.137,0,0,0,1.94.8l7.238-7.238h11.761A2.992,2.992,0,0,0-236.9-145.2v-16.69A2.988,2.988,0,0,0-239.893-164.872Zm-17.61,13.7a1.787,1.787,0,0,1-1.789-1.785,1.791,1.791,0,0,1,1.789-1.789,1.79,1.79,0,0,1,1.785,1.789A1.786,1.786,0,0,1-257.5-151.171Zm5.571,0a1.783,1.783,0,0,1-1.785-1.785,1.787,1.787,0,0,1,1.785-1.789,1.787,1.787,0,0,1,1.785,1.789A1.783,1.783,0,0,1-251.932-151.171Zm5.571,0a1.783,1.783,0,0,1-1.785-1.785,1.787,1.787,0,0,1,1.785-1.789,1.791,1.791,0,0,1,1.789,1.789A1.787,1.787,0,0,1-246.361-151.171Z"
                                            transform="translate(0 0)" fill="#4c8dcb" />
                                        <path
                                            d="M-224.043-119.72a2.074,2.074,0,0,1-1.468-.61l-6.961-6.961h-9.062a.944.944,0,0,1-.944-.944.944.944,0,0,1,.944-.944h9.453a.945.945,0,0,1,.668.277l7.238,7.237a.174.174,0,0,0,.208.042.176.176,0,0,0,.118-.177v-6.435a.944.944,0,0,1,.944-.944h3.14a2.045,2.045,0,0,0,2.043-2.042v-16.689a2.045,2.045,0,0,0-2.043-2.042h-1.385a.944.944,0,0,1-.944-.944.944.944,0,0,1,.944-.944h1.385a3.935,3.935,0,0,1,3.931,3.931v16.689a3.935,3.935,0,0,1-3.931,3.931h-2.2v5.49a2.073,2.073,0,0,1-1.284,1.921A2.075,2.075,0,0,1-224.043-119.72Z"
                                            transform="translate(-12.923 -6.878)" fill="#00b16a" />
                                    </g>
                                </svg>
                                <h4 class="card-title">
                                    {{ __('acceuil.echange') }}
                                </h4>
                                <p class="card-text">
                                    {{ __('acceuil.echange_text') }}
                                </p>
                            </div>
                        </a>
                    </div>
                    <div class="card">
                        <a rel='follow' href="/solution-pour-les-locataires">
                            <div class="card-body">
                                <svg xmlns="http://www.w3.org/2000/svg" width="53.711" height="24.524"
                                    viewBox="0 0 53.711 24.524">
                                    <g transform="translate(-44.131 14.088)">
                                        <path
                                            d="M91.815,10.436H65.538A4.377,4.377,0,0,1,61.788,8.38a4.133,4.133,0,0,1-.2-4.034L69.479-11.67a4.415,4.415,0,0,1,3.952-2.418H99.707a4.376,4.376,0,0,1,3.75,2.055,4.133,4.133,0,0,1,.2,4.034L95.766,8.018A4.417,4.417,0,0,1,91.815,10.436ZM73.43-12.189a2.5,2.5,0,0,0-2.248,1.358L63.29,5.186a2.218,2.218,0,0,0,.11,2.191,2.491,2.491,0,0,0,2.138,1.16H91.815a2.5,2.5,0,0,0,2.248-1.358l7.892-16.017a2.216,2.216,0,0,0-.11-2.192,2.489,2.489,0,0,0-2.138-1.159Z"
                                            transform="translate(-6.247)" fill="#4c8dcb" />
                                        <path
                                            d="M87.814,4.657a.948.948,0,0,1-.685-.291l-8.3-8.629a.95.95,0,0,1,.026-1.343A.949.949,0,0,1,80.2-5.58L88,2.528l14.31-7.748a.95.95,0,0,1,1.288.382.95.95,0,0,1-.384,1.288L88.266,4.542A.945.945,0,0,1,87.814,4.657Z"
                                            transform="translate(-12.635 -3.015)" fill="#4c8dcb" />
                                        <path
                                            d="M70.711,15.135a.95.95,0,0,1-.55-1.724l6.977-4.955A.949.949,0,1,1,78.237,10L71.26,14.959A.941.941,0,0,1,70.711,15.135Z"
                                            transform="translate(-9.405 -8.208)" fill="#4c8dcb" />
                                        <path
                                            d="M106.5,14.846a.948.948,0,0,1-.786-.415l-3.179-4.665A.949.949,0,1,1,104.1,8.7l3.18,4.666a.95.95,0,0,1-.784,1.485Z"
                                            transform="translate(-21.37 -8.208)" fill="#4c8dcb" />
                                        <path
                                            d="M57.747-5.635h-11.4a.95.95,0,0,1-.95-.95.95.95,0,0,1,.95-.95h11.4a.95.95,0,0,1,.95.95A.95.95,0,0,1,57.747-5.635Z"
                                            transform="translate(-0.466 -2.405)" fill="#e65611" />
                                        <path d="M52.285,3.4h-7.2a.95.95,0,1,1,0-1.9h7.2a.95.95,0,1,1,0,1.9Z"
                                            transform="translate(0 -5.72)" fill="#e65611" />
                                        <path d="M50.158,12.144H45.081a.95.95,0,1,1,0-1.9h5.078a.95.95,0,1,1,0,1.9Z"
                                            transform="translate(0 -8.929)" fill="#e65611" />
                                    </g>
                                </svg>
                                <h4 class="card-title"> {{ __('acceuil.send_apply') }}</h4>
                                <p class="card-text">
                                    {{ __('acceuil.send_apply_text') }}
                                </p>
                            </div>
                        </a>
                    </div>
                    <div class="card">
                        <a rel='follow' href="/solution-pour-les-locataires">
                            <div class="card-body">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32.584" height="33.477"
                                    viewBox="0 0 32.584 33.477">
                                    <g transform="translate(959.648 431.43)">
                                        <path
                                            d="M-927.339-420.755q-6.614-5.278-13.226-10.56a.372.372,0,0,0-.5-.034q-9.113,5.333-18.233,10.655c-.068.039-.131.085-.267.174h4.558l-4.641,22.567c.15-.126.241-.2.329-.278q3.89-3.379,7.777-6.762a.905.905,0,0,1,.644-.241q7.869.011,15.738.005h.375c.127-.651.244-1.256.364-1.861q1.3-6.558,2.593-13.116c.047-.239.137-.33.4-.327,1.339.014,2.678.006,4.017.006h.352C-927.2-420.638-927.268-420.7-927.339-420.755Zm-21.553,4.218c-.1.5-.8,4.06-1.118,5.648a3.582,3.582,0,0,0-2.133-.692,3.4,3.4,0,0,0-2.2.761c.3-1.529.887-4.542.947-4.832.182-.883.368-1.766.544-2.65.03-.15.077-.23.244-.23,1.311,0,2.621,0,3.932,0a1,1,0,0,1,.181.039C-948.628-417.83-948.759-417.183-948.892-416.536Zm7.2-1.163c-.17.837-1,5.034-1.35,6.81a3.584,3.584,0,0,0-2.132-.691,3.4,3.4,0,0,0-2.2.761c.306-1.569.911-4.684.981-5.027.159-.784.331-1.566.477-2.352.048-.257.147-.342.415-.339,1.2.016,2.4.007,3.6.007h.358C-941.591-418.228-941.634-417.963-941.688-417.7Zm7.092-.591c-.207,1.019-1.1,5.52-1.468,7.4a3.574,3.574,0,0,0-2.136-.7,3.409,3.409,0,0,0-2.194.759c.319-1.634.954-4.9,1.041-5.327.143-.7.29-1.406.434-2.11.031-.149.056-.276.268-.275,1.3.009,2.6,0,3.906.006a1.152,1.152,0,0,1,.151.022A1.147,1.147,0,0,1-934.6-418.29Z"
                                            transform="translate(0 0)" fill="#4b8ccb" />
                                        <path
                                            d="M-874.28-201.918a1.288,1.288,0,0,0-1.289,1.289,1.29,1.29,0,0,0,1.289,1.289,1.291,1.291,0,0,0,1.293-1.289A1.289,1.289,0,0,0-874.28-201.918Z"
                                            transform="translate(-77.864 -212.548)" fill="#00b16a" />
                                        <path
                                            d="M-779.935-201.918a1.288,1.288,0,0,0-1.289,1.289,1.29,1.29,0,0,0,1.289,1.289,1.291,1.291,0,0,0,1.293-1.289A1.288,1.288,0,0,0-779.935-201.918Z"
                                            transform="translate(-165.236 -212.548)" fill="#00b16a" />
                                        <path
                                            d="M-685.635-201.918a1.288,1.288,0,0,0-1.289,1.289,1.29,1.29,0,0,0,1.289,1.289,1.291,1.291,0,0,0,1.293-1.289A1.288,1.288,0,0,0-685.635-201.918Z"
                                            transform="translate(-252.566 -212.548)" fill="#00b16a" />
                                    </g>
                                </svg>
                                <h4 class="card-title">
                                    {{ __('acceuil.team') }}
                                </h4>
                                <p class="card-text">
                                    {{ __('acceuil.team_text') }}
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="solution" class="solution loc-sol">
        <div class="inner">
            <div class="container">
                <div class="section-heading">
                    <h2>{{ __('acceuil.solution') }}</h2>
                    <p>{{ __('acceuil.gagner_temp') }}</p>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="solution-item">
                            <a rel='follow' href="/solution-pour-les-bailleurs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="42.585" height="48.464"
                                    viewBox="0 0 42.585 48.464">
                                    <g transform="translate(-679.492 -2348.308)">
                                        <g transform="translate(677.106 2343.088)">
                                            <path
                                                d="M19.247,36.954A15.867,15.867,0,1,0,3.38,21.087,15.9,15.9,0,0,0,19.247,36.954Z"
                                                transform="translate(4.407 0)" fill="#fd556f" />
                                            <path
                                                d="M23.676,11.2c-7.1,0-21.29,3.565-21.29,10.645V27.17H44.971V21.845C44.971,14.765,30.783,11.2,23.676,11.2Z"
                                                transform="translate(0 26.514)" fill="#fd556f" />
                                            <path
                                                d="M24.523,11.987a3.453,3.453,0,0,0,1.69-.44c.3-.109.44-.1.576.2.239.505.44,1.022.652,1.538.163.375.31.761.478,1.13.206.451.1.69-.37.853a2.98,2.98,0,0,0-1.956,1.761A2.713,2.713,0,0,0,26.6,20.046a11.632,11.632,0,0,0,1.087.511c.288.125.386.261.288.571-.114.375-.272.734-.418,1.092-.212.527-.446,1.043-.647,1.57-.185.473-.44.56-.913.359a4.38,4.38,0,0,0-1.3-.408,2.509,2.509,0,0,0-2.081.907,2.574,2.574,0,0,0-.581,2.44,8.279,8.279,0,0,0,.353.924c.125.342.065.484-.261.625-.924.4-1.853.788-2.777,1.179-.277.114-.494-.016-.619-.315a6.375,6.375,0,0,0-.516-1.081,2.471,2.471,0,0,0-2.516-1.1,2.5,2.5,0,0,0-2.13,1.777c-.071.174-.136.348-.217.516a.406.406,0,0,1-.571.2c-.951-.391-1.891-.793-2.836-1.2a.375.375,0,0,1-.234-.522,10.652,10.652,0,0,0,.424-1.277,2.44,2.44,0,0,0-1.1-2.592,2.273,2.273,0,0,0-1.625-.484,7.245,7.245,0,0,0-1.255.386,3.4,3.4,0,0,1-.331.114.4.4,0,0,1-.505-.266c-.255-.657-.543-1.3-.821-1.945-.1-.234-.19-.467-.293-.7-.179-.4-.1-.609.293-.782a3.741,3.741,0,0,0,1.56-.967,2.591,2.591,0,0,0,.571-1.532,2.437,2.437,0,0,0-1.1-2.32,5.15,5.15,0,0,0-1.005-.473c-.38-.152-.505-.348-.353-.723.31-.772.641-1.532.962-2.3a4.29,4.29,0,0,1,.239-.533.364.364,0,0,1,.522-.168,3.547,3.547,0,0,0,2.119.451A2.678,2.678,0,0,0,9.922,10.52a2.336,2.336,0,0,0,.185-1.771c-.092-.337-.25-.657-.364-.989-.125-.353-.065-.516.272-.657.782-.326,1.57-.636,2.358-.956.147-.054.288-.13.435-.168.321-.087.467.016.6.31A6.874,6.874,0,0,0,13.9,7.314,2.374,2.374,0,0,0,15.9,8.461a2.386,2.386,0,0,0,2.157-.9,5.371,5.371,0,0,0,.63-1.185c.19-.435.37-.538.815-.353.859.348,1.712.706,2.57,1.06.418.174.473.429.212.973a2.756,2.756,0,0,0-.3,1.788,2.635,2.635,0,0,0,1.521,1.929,5.09,5.09,0,0,0,1.005.283Zm-8.58,11.791a5.935,5.935,0,0,0,6.075-6,5.814,5.814,0,0,0-5.8-5.8,5.906,5.906,0,1,0-.277,11.808Z"
                                                transform="translate(7.619 3.187)" fill="#fd556f" />
                                        </g>
                                        <g transform="translate(689.614 2357.323)">
                                            <path
                                                d="M14.586,27.633A11.206,11.206,0,1,0,3.38,16.426,11.228,11.228,0,0,0,14.586,27.633Z"
                                                transform="translate(2.821 0)" />
                                            <path
                                                d="M17.423,11.2c-5.016,0-15.037,2.518-15.037,7.518v3.761H32.463V18.718C32.463,13.718,22.443,11.2,17.423,11.2Z"
                                                transform="translate(0 16.97)" />
                                            <path
                                                d="M18.525,10.211A2.439,2.439,0,0,0,19.719,9.9c.211-.077.311-.069.407.138.169.357.311.722.461,1.086.115.265.219.537.338.8.146.319.073.487-.261.6a2.1,2.1,0,0,0-1.382,1.243A1.916,1.916,0,0,0,20,15.9a8.215,8.215,0,0,0,.768.361c.2.088.272.184.2.4-.081.265-.192.518-.3.771-.15.372-.315.737-.457,1.109-.13.334-.311.4-.645.253a3.093,3.093,0,0,0-.921-.288,1.772,1.772,0,0,0-1.47.641,1.818,1.818,0,0,0-.411,1.723,5.847,5.847,0,0,0,.249.652c.088.242.046.342-.184.441-.652.28-1.309.556-1.961.833-.2.081-.349-.012-.438-.223a4.5,4.5,0,0,0-.365-.764,1.852,1.852,0,0,0-3.281.476c-.05.123-.1.246-.154.365a.287.287,0,0,1-.4.142c-.672-.276-1.336-.56-2-.844a.265.265,0,0,1-.165-.368,7.523,7.523,0,0,0,.3-.9,1.723,1.723,0,0,0-.779-1.831,1.605,1.605,0,0,0-1.148-.342,5.117,5.117,0,0,0-.887.272,2.4,2.4,0,0,1-.234.081.281.281,0,0,1-.357-.188c-.18-.464-.384-.917-.58-1.374-.069-.165-.134-.33-.207-.5-.127-.28-.069-.43.207-.553a2.642,2.642,0,0,0,1.1-.683,1.83,1.83,0,0,0,.4-1.082,1.722,1.722,0,0,0-.775-1.639,3.637,3.637,0,0,0-.71-.334c-.269-.107-.357-.246-.249-.51.219-.545.453-1.082.679-1.623A3.03,3.03,0,0,1,5,10.007a.257.257,0,0,1,.368-.119,2.505,2.505,0,0,0,1.5.319A1.891,1.891,0,0,0,8.213,9.175a1.65,1.65,0,0,0,.13-1.251c-.065-.238-.177-.464-.257-.7-.088-.249-.046-.365.192-.464.553-.23,1.109-.449,1.666-.675.1-.038.2-.092.307-.119.226-.061.33.012.422.219a4.855,4.855,0,0,0,.349.725,1.677,1.677,0,0,0,1.416.81,1.685,1.685,0,0,0,1.524-.633,3.793,3.793,0,0,0,.445-.837c.134-.307.261-.38.576-.249.606.246,1.209.5,1.815.748.3.123.334.3.15.687A1.946,1.946,0,0,0,16.733,8.7a1.861,1.861,0,0,0,1.075,1.362,3.6,3.6,0,0,0,.71.2Zm-6.06,8.328A4.191,4.191,0,0,0,16.756,14.3a4.106,4.106,0,0,0-4.095-4.1,4.171,4.171,0,1,0-.2,8.34Z"
                                                transform="translate(4.876 2.04)" fill="#fff" />
                                        </g>
                                    </g>
                                </svg>
                                <h4>{{ __('acceuil.manage_apply') }}</h4>
                            </a>
                        </div>
                        <div class="solution-item">
                            <a rel='follow' href="/solution-pour-les-bailleurs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="42.714" height="48.611"
                                    viewBox="0 0 42.714 48.611">
                                    <g transform="translate(-679.492 -2508.308)">
                                        <g transform="translate(677.106 2503.088)">
                                            <path
                                                d="M19.247,36.954A15.867,15.867,0,1,0,3.38,21.087,15.9,15.9,0,0,0,19.247,36.954Z"
                                                transform="translate(4.407 0)" fill="#ffd97d" />
                                            <path
                                                d="M23.676,11.2c-7.1,0-21.29,3.565-21.29,10.645V27.17H44.971V21.845C44.971,14.765,30.783,11.2,23.676,11.2Z"
                                                transform="translate(0 26.514)" fill="#ffd97d" />
                                            <path
                                                d="M24.523,11.987a3.453,3.453,0,0,0,1.69-.44c.3-.109.44-.1.576.2.239.505.44,1.022.652,1.538.163.375.31.761.478,1.13.206.451.1.69-.37.853a2.98,2.98,0,0,0-1.956,1.761A2.713,2.713,0,0,0,26.6,20.046a11.632,11.632,0,0,0,1.087.511c.288.125.386.261.288.571-.114.375-.272.734-.418,1.092-.212.527-.446,1.043-.647,1.57-.185.473-.44.56-.913.359a4.38,4.38,0,0,0-1.3-.408,2.509,2.509,0,0,0-2.081.907,2.574,2.574,0,0,0-.581,2.44,8.279,8.279,0,0,0,.353.924c.125.342.065.484-.261.625-.924.4-1.853.788-2.777,1.179-.277.114-.494-.016-.619-.315a6.375,6.375,0,0,0-.516-1.081,2.471,2.471,0,0,0-2.516-1.1,2.5,2.5,0,0,0-2.13,1.777c-.071.174-.136.348-.217.516a.406.406,0,0,1-.571.2c-.951-.391-1.891-.793-2.836-1.2a.375.375,0,0,1-.234-.522,10.652,10.652,0,0,0,.424-1.277,2.44,2.44,0,0,0-1.1-2.592,2.273,2.273,0,0,0-1.625-.484,7.245,7.245,0,0,0-1.255.386,3.4,3.4,0,0,1-.331.114.4.4,0,0,1-.505-.266c-.255-.657-.543-1.3-.821-1.945-.1-.234-.19-.467-.293-.7-.179-.4-.1-.609.293-.782a3.741,3.741,0,0,0,1.56-.967,2.591,2.591,0,0,0,.571-1.532,2.437,2.437,0,0,0-1.1-2.32,5.15,5.15,0,0,0-1.005-.473c-.38-.152-.505-.348-.353-.723.31-.772.641-1.532.962-2.3a4.29,4.29,0,0,1,.239-.533.364.364,0,0,1,.522-.168,3.547,3.547,0,0,0,2.119.451A2.678,2.678,0,0,0,9.922,10.52a2.336,2.336,0,0,0,.185-1.771c-.092-.337-.25-.657-.364-.989-.125-.353-.065-.516.272-.657.782-.326,1.57-.636,2.358-.956.147-.054.288-.13.435-.168.321-.087.467.016.6.31A6.874,6.874,0,0,0,13.9,7.314,2.374,2.374,0,0,0,15.9,8.461a2.386,2.386,0,0,0,2.157-.9,5.371,5.371,0,0,0,.63-1.185c.19-.435.37-.538.815-.353.859.348,1.712.706,2.57,1.06.418.174.473.429.212.973a2.756,2.756,0,0,0-.3,1.788,2.635,2.635,0,0,0,1.521,1.929,5.09,5.09,0,0,0,1.005.283Zm-8.58,11.791a5.935,5.935,0,0,0,6.075-6,5.814,5.814,0,0,0-5.8-5.8,5.906,5.906,0,1,0-.277,11.808Z"
                                                transform="translate(7.619 3.187)" fill="#ffd97d" />
                                        </g>
                                        <g transform="translate(689.614 2517.323)">
                                            <path
                                                d="M14.634,5.22A11.255,11.255,0,1,0,25.928,16.475,11.243,11.243,0,0,0,14.634,5.22Zm7.323,9.366L12.592,20.56a1.39,1.39,0,0,1-.771.231,1.442,1.442,0,0,1-1.118-.5L7.774,16.86a1.424,1.424,0,0,1,.154-2.043,1.459,1.459,0,0,1,2.043.154l2.12,2.505,8.325-5.319a1.438,1.438,0,1,1,1.542,2.428Z"
                                                transform="translate(2.837 0)" />
                                            <path
                                                d="M2.386,18.751v3.777H32.592V18.751c0-5.022-10.064-7.551-15.1-7.551S2.386,13.728,2.386,18.751Z"
                                                transform="translate(0 17.069)" />
                                        </g>
                                    </g>
                                </svg>
                                <h4>
                                    {{ __('acceuil.louez') }}
                                </h4>
                            </a>
                        </div>
                        <div class="solution-item">
                            <a rel='follow' href="/solution-pour-les-bailleurs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="44.021" height="44.302"
                                    viewBox="0 0 44.021 44.302">
                                    <g transform="translate(-681.5 -2673.808)">
                                        <path
                                            d="M-262.682-164.872h34.466a4.277,4.277,0,0,1,4.278,4.271v23.889a4.283,4.283,0,0,1-4.278,4.278h-4.494v9.211a1.628,1.628,0,0,1-2.778,1.149l-10.36-10.36h-16.834a4.283,4.283,0,0,1-4.278-4.278V-160.6A4.277,4.277,0,0,1-262.682-164.872Z"
                                            transform="translate(948.96 2839.18)" fill="#e65611" stroke="rgba(0,0,0,0)"
                                            stroke-width="1" />
                                        <g transform="translate(953.96 2847.872)">
                                            <path
                                                d="M-242.265-164.872h-21.968a2.726,2.726,0,0,0-2.727,2.722v15.227a2.73,2.73,0,0,0,2.727,2.727h2.865v5.871a1.038,1.038,0,0,0,1.77.732l6.6-6.6h10.73a2.73,2.73,0,0,0,2.727-2.727V-162.15A2.726,2.726,0,0,0-242.265-164.872Zm-16.067,12.5A1.631,1.631,0,0,1-259.965-154a1.634,1.634,0,0,1,1.633-1.633A1.633,1.633,0,0,1-256.7-154,1.63,1.63,0,0,1-258.332-152.372Zm5.083,0A1.627,1.627,0,0,1-254.877-154a1.631,1.631,0,0,1,1.628-1.633A1.631,1.631,0,0,1-251.621-154,1.627,1.627,0,0,1-253.249-152.372Zm5.083,0A1.627,1.627,0,0,1-249.795-154a1.631,1.631,0,0,1,1.628-1.633A1.634,1.634,0,0,1-246.534-154,1.631,1.631,0,0,1-248.166-152.372Z"
                                                transform="translate(0 0)" />
                                            <path
                                                d="M-225.659-122.536a1.892,1.892,0,0,1-1.34-.557l-6.351-6.351h-8.267a.862.862,0,0,1-.862-.861.861.861,0,0,1,.862-.861h8.624a.862.862,0,0,1,.61.252l6.6,6.6a.159.159,0,0,0,.19.038.16.16,0,0,0,.108-.161V-130.3a.861.861,0,0,1,.861-.861h2.865a1.866,1.866,0,0,0,1.864-1.863v-15.226a1.866,1.866,0,0,0-1.864-1.863h-1.264a.862.862,0,0,1-.862-.862.861.861,0,0,1,.862-.861h1.264a3.591,3.591,0,0,1,3.587,3.586v15.226a3.591,3.591,0,0,1-3.587,3.586h-2v5.009a1.892,1.892,0,0,1-1.172,1.753A1.893,1.893,0,0,1-225.659-122.536Z"
                                                transform="translate(-13.936 -7.417)" />
                                        </g>
                                    </g>
                                </svg>
                                <h4>{{ __('acceuil.chat') }}</h4>
                            </a>
                        </div>
                    </div>
                    <div class="col-4">
                        {{-- <img src="/images/homme.png" class="fnd-bailleur" alt="fille" /> --}}
                    </div>
                    <div class="col-4">
                        <div class="solution-item">
                            <a rel='follow' href="/solution-pour-les-bailleurs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="39.839" height="48.692"
                                    viewBox="0 0 39.839 48.692">
                                    <g transform="translate(-1198 -2629.308)">
                                        <g>
                                            <path
                                                d="M22.919,1,3,9.853v13.28C3,35.416,11.5,46.9,22.919,49.692,34.34,46.9,42.839,35.416,42.839,23.133V9.853Z"
                                                transform="translate(1195 2628.308)" fill="#ffd97d" />
                                            <path
                                                d="M17.1,1,3,7.265v9.4c0,8.693,6.014,16.821,14.1,18.795,8.082-1.973,14.1-10.1,14.1-18.795v-9.4ZM13.964,26.06,7.7,19.795l2.208-2.208,4.057,4.041L24.285,11.306l2.208,2.224Z"
                                                transform="translate(1195 2642.543)" />
                                        </g>
                                    </g>
                                </svg>
                                <h4>
                                    {{ __('acceuil.securiser') }}
                                </h4>
                            </a>
                        </div>
                        <div class="solution-item">
                            <a rel='follow' href="/solution-pour-les-bailleurs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="67.396" height="49.822"
                                    viewBox="0 0 67.396 49.822">
                                    <g transform="translate(-1200 -2507.308)">
                                        <g transform="translate(0 39)">
                                            <g transform="translate(1210.106 2463.088)">
                                                <path
                                                    d="M19.247,36.954A15.867,15.867,0,1,0,3.38,21.087,15.9,15.9,0,0,0,19.247,36.954Z"
                                                    transform="translate(4.407 0)" fill="#00b16a" />
                                                <path
                                                    d="M23.676,11.2c-7.1,0-21.29,3.565-21.29,10.645V27.17H44.971V21.845C44.971,14.765,30.783,11.2,23.676,11.2Z"
                                                    transform="translate(0 26.514)" fill="#00b16a" />
                                                <path
                                                    d="M24.523,11.987a3.453,3.453,0,0,0,1.69-.44c.3-.109.44-.1.576.2.239.505.44,1.022.652,1.538.163.375.31.761.478,1.13.206.451.1.69-.37.853a2.98,2.98,0,0,0-1.956,1.761A2.713,2.713,0,0,0,26.6,20.046a11.632,11.632,0,0,0,1.087.511c.288.125.386.261.288.571-.114.375-.272.734-.418,1.092-.212.527-.446,1.043-.647,1.57-.185.473-.44.56-.913.359a4.38,4.38,0,0,0-1.3-.408,2.509,2.509,0,0,0-2.081.907,2.574,2.574,0,0,0-.581,2.44,8.279,8.279,0,0,0,.353.924c.125.342.065.484-.261.625-.924.4-1.853.788-2.777,1.179-.277.114-.494-.016-.619-.315a6.375,6.375,0,0,0-.516-1.081,2.471,2.471,0,0,0-2.516-1.1,2.5,2.5,0,0,0-2.13,1.777c-.071.174-.136.348-.217.516a.406.406,0,0,1-.571.2c-.951-.391-1.891-.793-2.836-1.2a.375.375,0,0,1-.234-.522,10.652,10.652,0,0,0,.424-1.277,2.44,2.44,0,0,0-1.1-2.592,2.273,2.273,0,0,0-1.625-.484,7.245,7.245,0,0,0-1.255.386,3.4,3.4,0,0,1-.331.114.4.4,0,0,1-.505-.266c-.255-.657-.543-1.3-.821-1.945-.1-.234-.19-.467-.293-.7-.179-.4-.1-.609.293-.782a3.741,3.741,0,0,0,1.56-.967,2.591,2.591,0,0,0,.571-1.532,2.437,2.437,0,0,0-1.1-2.32,5.15,5.15,0,0,0-1.005-.473c-.38-.152-.505-.348-.353-.723.31-.772.641-1.532.962-2.3a4.29,4.29,0,0,1,.239-.533.364.364,0,0,1,.522-.168,3.547,3.547,0,0,0,2.119.451A2.678,2.678,0,0,0,9.922,10.52a2.336,2.336,0,0,0,.185-1.771c-.092-.337-.25-.657-.364-.989-.125-.353-.065-.516.272-.657.782-.326,1.57-.636,2.358-.956.147-.054.288-.13.435-.168.321-.087.467.016.6.31A6.874,6.874,0,0,0,13.9,7.314,2.374,2.374,0,0,0,15.9,8.461a2.386,2.386,0,0,0,2.157-.9,5.371,5.371,0,0,0,.63-1.185c.19-.435.37-.538.815-.353.859.348,1.712.706,2.57,1.06.418.174.473.429.212.973a2.756,2.756,0,0,0-.3,1.788,2.635,2.635,0,0,0,1.521,1.929,5.09,5.09,0,0,0,1.005.283Zm-8.58,11.791a5.935,5.935,0,0,0,6.075-6,5.814,5.814,0,0,0-5.8-5.8,5.906,5.906,0,1,0-.277,11.808Z"
                                                    transform="translate(7.619 3.187)" fill="#00b16a" />
                                            </g>
                                            <g transform="translate(-29 168)">
                                                <g transform="translate(1226.614 2311.534)">
                                                    <path
                                                        d="M11.644,21.609a7.556,7.556,0,1,0-7.556-7.556A7.569,7.569,0,0,0,11.644,21.609Z"
                                                        transform="translate(4.109 6.007)" />
                                                    <path
                                                        d="M23.546,21.609a7.556,7.556,0,1,0-7.556-7.556A7.569,7.569,0,0,0,23.546,21.609Z"
                                                        transform="translate(32.845 6.007)" />
                                                    <path
                                                        d="M56.4,13.511a23.987,23.987,0,0,0-5.8.84c-3.909-2.731-10.649-4.107-14.671-4.107-3.995,0-10.666,1.359-14.586,4.053a23.806,23.806,0,0,0-5.582-.785c-4.463,0-13.377,2.24-13.377,6.689v3.346h67.4V20.2C69.782,15.75,60.867,13.511,56.4,13.511Z"
                                                        transform="translate(0 15.051)" />
                                                    <path
                                                        d="M19.287,24.041A10.016,10.016,0,1,0,9.273,14.027,10.035,10.035,0,0,0,19.287,24.041Z"
                                                        transform="translate(16.628)" />
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <h4>{{ __('acceuil.regroupez_coloc') }}</h4>
                            </a>
                        </div>
                        <div class="solution-item">
                            <a rel='follow' href="/solution-pour-les-bailleurs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="45.219" height="50.243"
                                    viewBox="0 0 45.219 50.243">
                                    <g transform="translate(-1199.776 -2331.308)">
                                        <path
                                            d="M18.073,24.609H13.049v5.024h5.024Zm10.049,0H23.1v5.024h5.024Zm10.049,0H33.146v5.024H38.17ZM43.194,7.024H40.682V2H35.658V7.024h-20.1V2H10.536V7.024H8.024a5,5,0,0,0-5,5.024L3,47.219a5.023,5.023,0,0,0,5.024,5.024h35.17a5.039,5.039,0,0,0,5.024-5.024V12.049A5.039,5.039,0,0,0,43.194,7.024Zm0,40.194H8.024V19.585h35.17Z"
                                            transform="translate(1196.776 2329.308)" fill="#e65611" />
                                        <path
                                            d="M12.551,16.327H9.368v3.184h3.184Zm6.368,0H15.735v3.184h3.184Zm6.368,0H22.1v3.184h3.184ZM28.47,5.184H26.878V2H23.694V5.184H10.959V2H7.776V5.184H6.184A3.169,3.169,0,0,0,3.016,8.368L3,30.654a3.183,3.183,0,0,0,3.184,3.184H28.47a3.193,3.193,0,0,0,3.184-3.184V8.368A3.193,3.193,0,0,0,28.47,5.184Zm0,25.47H6.184V13.143H28.47Z"
                                            transform="translate(1196.776 2347.714)" />
                                    </g>
                                </svg>
                                <h4>{{ __('acceuil.proposer') }}</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input id="input_featured_data" type="hidden" value="0" />
    <input type="hidden" id="current_ville"
        @if (!is_null($infosAddress)) value="{{ $infosAddress['ville'] }}" @endif name="">
    <div id="featured_data">
        @if (!is_null($infosAddress))
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Paris')
                <div class="collocataire recherchant logements clearfix">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Paris') !!}
                    </div>

                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Lille')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Lille') !!}
                    </div>

                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Nantes')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Nantes') !!}
                    </div>

                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Lyon')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Lyon') !!}
                    </div>

                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Toulouse')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Toulouse') !!}
                    </div>

                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Bordeaux')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Bordeaux') !!}
                    </div>

                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Rennes')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Rennes') !!}
                    </div>

                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Nice')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Nice') !!}
                    </div>
                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Montpellier')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Montpellier') !!}
                    </div>

                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Marseille')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Marseille') !!}
                    </div>

                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Strasbourg')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Strasbourg') !!}
                    </div>

                </div>
            @endif
            @if (!is_null($infosAddress) && $infosAddress['ville'] == 'Grenoble')
                <div class="collocataire recherchant logements clearfix text-ville">
                    <div class="inner">
                        {!! getDescriptionStaticPages('Colocation Grenoble') !!}
                    </div>

                </div>
            @endif
            <div id="adsVille">

            </div>
            <div id="featured_room_mates_div">
                @include('home.featured_room_mates')
            </div>
            <!--h  -->
            @if (!empty($featured_rooms) && count($featured_rooms) > 0)
                <div class="collocataire recherchant logements clearfix">
                    <div class="inner">
                        <h3>
                            {{ __('acceuil.logement_list') }} @if (isset($ville))
                                {{ __('acceuil.a_ville', ['ville' => $ville]) }} @endif
                        </h3>
                        <!-- <div class="custom-pagination">
                    <div class="pagination-button pagination-button-prev">
                    <a href="javascript:"><</a>
                    </div>
                    <div class="pagination-button pagination-button-next">
                    <a href="javascript:">></a>
                    </div>
                </div> -->
                        <div class="publication">
                            @foreach ($featured_rooms as $featured_room)
                                <a rel='follow' href="{{ adUrl($featured_room->id) }}" class="publ">
                                    <span class="prix">
                                        @if (!empty($featured_room) && !empty($featured_room->min_rent))
                                            {!! '&euro;' . $featured_room->min_rent !!}
                                        @endif/ {{ __('acceuil.mois') }}
                                    </span>

                                    @if (!empty($featured_room->ad_files) && count($featured_room->ad_files) > 0)
                                        <img width="1024" height="683" class="ad-image active-custom-carousel"
                                            src="{{ '/uploads/images_annonces/' . $featured_room->ad_files[0]->filename }}"
                                            loading="lazy">
                                    @else
                                        <img width="1024" height="683" class="ad-image active-custom-carousel"
                                            @if (!empty($featured_room->ad_files) && count($featured_room->ad_files) > 0) src="{{ '/uploads/images_annonces/' . $featured_room->ad_files[0]->filename }}" loading="lazy"
                                 @else  src="/images/room_no_pic.png" loading="lazy" @endif>
                                    @endif

                                    <div class="items-publ">
                                        <img width="80" height="80" class="profile-image" loading="lazy"
                                            @if (File::exists(storage_path('/uploads/profile_pics/' . $featured_room->user->user_profiles->profile_pic)) && !empty($featured_room->user->user_profiles->profile_pic)) src="{{ '/uploads/profile_pics/' . $featured_room->user->user_profiles->profile_pic }}"  @else  src="/images/profile_avatar. jpeg" @endif>
                                        <h5>
                                            @if (!empty($featured_room) && !empty($featured_room->address))
                                                {{ $featured_room->address }}
                                            @endif
                                        </h5>
                                        <span class="logement">
                                            @if (!empty($featured_room) && !empty($featured_room->title))
                                                {{ $featured_room->title }}
                                            @endif
                                        </span>
                                        <p>
                                            @if (!empty($featured_room) && !empty($featured_room->description))
                                                {{ substr($featured_room->description, 0, 130) }} @if (strlen($featured_room->description) > 130)
                                                    ...
                                                @endif
                                            @endif
                                        </p>
                                        <span class="date">
                                            {{ translateDuration($featured_room->updated_at) }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif
        <!-- h -->


    </div>
    <div>
        <input type="hidden" @if (isset($lat)) value="{{ $lat }}" @endif id="latAnnonce" />
        <input type="hidden" @if (isset($long)) value="{{ $long }}" @endif id="longAnnonce" />
        <input type="hidden" @if (isset($ville)) value="{{ $ville }}" @endif
            id="villeAnnonce" />

    </div>
    <div id="featured_room_mates_div">
        @include('home.featured_room_mates')
    </div>
    <!--h  -->
    @if (!empty($featured_rooms) && count($featured_rooms) > 0)
        <div class="collocataire recherchant logements clearfix">
            <div class="inner">
                <h3>
                    {{ __('acceuil.logement_list') }} @if (isset($ville)) {{ __('acceuil.a_ville', ['ville' => $ville]) }}
                    @endif
                </h3>
                <!-- <div class="custom-pagination">
                    <div class="pagination-button pagination-button-prev">
                    <a href="javascript:"><</a>
                    </div>
                    <div class="pagination-button pagination-button-next">
                    <a href="javascript:">></a>
                    </div>
                </div> -->
                <div class="publication">
                    @foreach ($featured_rooms as $featured_room)
                        <a href="{{ adUrl($featured_room->id) }}" class="publ">
                            <span class="prix">
                                @if (!empty($featured_room) && !empty($featured_room->min_rent))
                                    {!! '&euro;' . $featured_room->min_rent !!}
                                @endif/ {{ __('acceuil.mois') }}
                            </span>
                            @if (!empty($featured_room->ad_files) && count($featured_room->ad_files) > 1)
                                <div class="slick-slider" id="slick-slider-room-{{ $featured_room->id }}">
                                    @foreach ($featured_room->ad_files as $ad_file)
                                        <div>
                                            <img class="ad-image active-custom-carousel"
                                                src="{{ '/uploads/images_annonces/' . $ad_file->filename }}"
                                                loading="lazy">
                                        </div>
                                    @endforeach
                                </div>
                                <div class='custom-carousel-prev'
                                    data-id="slick-slider-room-{{ $featured_room->id }}"><img src="/img/small-img.png"
                                        loading="lazy" width="40" height="40" /></div>
                                <div class='custom-carousel-next'
                                    data-id="slick-slider-room-{{ $featured_room->id }}"><img src="/img/small-img.png"
                                        width="40" loading="lazy" height="40" /></div>
                            @else
                                <img width="1024" height="683" class="ad-image active-custom-carousel"
                                    @if (!empty($featured_room->ad_files) && count($featured_room->ad_files) > 0) src="{{ '/uploads/images_annonces/' . $featured_room->ad_files[0]->filename }}" @else  src="/images/room_no_pic.png" @endif>
                            @endif
                            <div class="items-publ">
                                <img width="80" height="80" class="profile-image" loading="lazy"
                                    @if (File::exists(storage_path('/uploads/profile_pics/' . $featured_room->profile_pic)) && !empty($featured_room->profile_pic)) src="{{ '/uploads/profile_pics/' . $featured_room->profile_pic }}" @else  src="/images/profile_avatar.jpeg" @endif>
                                <h8>
                                    @if (!empty($featured_room) && !empty($featured_room->address))
                                        {{ $featured_room->address }}
                                    @endif
                                </h8>
                                <span class="logement">
                                    @if (!empty($featured_room) && !empty($featured_room->title))
                                        {{ $featured_room->title }}
                                    @endif
                                </span>
                                <p>
                                    @if (!empty($featured_room) && !empty($featured_room->description))
                                        {{ substr($featured_room->description, 0, 130) }} @if (strlen($featured_room->description) > 130)
                                            ...
                                        @endif
                                    @endif
                                </p>
                                <span class="date">
                                    {{ translateDuration($featured_room->updated_at) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if (is_null($infosAddress))
        <div class="collocataire recherchant logements clearfix">
            <div id="div-ville" class="inner">
                <h2 id="titleVille">{{ __('acceuil.search_ad_france') }}</h2>
                <div class="publication">
                    <div class="publication">
                        <div class="ville-annonce-container">
                            <a rel='follow' class="annonces acceuil-annonce-ville paris"
                                href="/colocation/ile-de-france/paris">
                                {{-- <img alt="Colocation Paris" class="img-top" src="/img/villes/paris.jpg"/> --}}
                                <img alt="Colocation Paris" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671388/bailti/img/paris_rx9k2k.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Paris']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Paris']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Paris']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Paris']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Paris']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class="acceuil-annonce-ville annonces lille"
                                href="/colocation/hauts-de-france/lille">
                                {{-- <img alt="Colocation Lille" class="img-top" src="/img/villes/lille.jpg"/> --}}
                                <img alt="Colocation Lille" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671388/bailti/img/lille_z1v8ih.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Lille']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Lille']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Lille']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Lille']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Lille']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class=" acceuil-annonce-ville annonces nantes"
                                href="/colocation/pays-de-la-loire/nantes">
                                {{-- <img alt="Colocation Nantes" class="img-top" src="/img/villes/nantes.jpg"/> --}}
                                <img alt="Colocation Nantes" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671389/bailti/img/nantes_gttq9b.jpg"
                                    loading="lazy" />
{{--                                lazy--}}
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Nantes']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Nantes']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Nantes']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Nantes']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Nantes']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class="acceuil-annonce-ville annonces lyon"
                                href="/colocation/auvergne-rhone-alpes/lyon">
                                {{-- <img alt="Colocation Lyon" class="img-top" src="/img/villes/lyon.jpg"/> --}}
                                <img alt="Colocation Lyon" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671387/bailti/img/lyon_zzeuim.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Lyon']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Lyon']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Lyon']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Lyon']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Lyon']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class="acceuil-annonce-ville annonces toulouse"
                                href="/colocation/occitanie/toulouse">
                                {{-- <img alt="Colocation Toulouse" class="img-top" src="/img/villes/toulouse.jpg"/> --}}
                                <img alt="Colocation Toulouse" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671389/bailti/img/toulouse_kwxxlo.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Toulouse']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Toulouse']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Toulouse']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Toulouse']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Toulouse']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class="acceuil-annonce-ville annonces saint-etienne"
                                href="/colocation/nouvelle-aquitaine/bordeaux">
                                {{-- <img alt="Colocation Bordeaux" class="img-top" src="/img/villes/bordeaux.jpg"/> --}}
                                <img alt="Colocation Bordeaux" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671387/bailti/img/bordeaux_rystjv.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Bordeaux']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Bordeaux']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Bordeaux']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Bordeaux']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Bordeaux']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class="acceuil-annonce-ville annonces rennes"
                                href="/colocation/bretagne/rennes">
                                {{-- <img alt="Colocation Rennes" class="img-top" src="/img/villes/Rennes.jpg"/> --}}
                                <img alt="Colocation Rennes" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671388/bailti/img/Rennes_qrgzqy.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Rennes']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Rennes']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Rennes']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Rennes']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Rennes']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class="acceuil-annonce-ville annonces angers"
                                href="/colocation/provence-alpes-cote-dazur/nice">
                                {{-- <img alt="Colocation Nice" class="img-top" src="/img/villes/nice.jpg"/> --}}
                                <img alt="Colocation Nice" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671388/bailti/img/nice_aq6sar.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Nice']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Nice']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Nice']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Nice']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Nice']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class="acceuil-annonce-ville annonces montpellier"
                                href="/colocation/occitanie/montpellier">
                                {{-- <img alt="Colocation MontPellier" class="img-top" src="/img/villes/montpellier.jpg"/> --}}
                                <img alt="Colocation MontPellier" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671387/bailti/img/montpellier_fzhmaf.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'MontPellier']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'MontPellier']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'MontPellier']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'MontPellier']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'MontPellier']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class="acceuil-annonce-ville annonces marseille"
                                href="/colocation/provence-alpes-cote-dazur/marseille">
                                {{-- <img alt="Colocation Marseille" class="img-top" src="/img/villes/marseille.jpg"/> --}}
                                <img alt="Colocation Marseille" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671387/bailti/img/marseille_gxvfbr.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Marseille']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Marseille']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Marseille']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Marseille']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Marseille']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class="acceuil-annonce-ville annonces strasbourg"
                                href="/colocation/grand-est/strasbourg">
                                {{-- <img alt="Colocation Strasbourg" class="img-top" src="/img/villes/strasbourg.jpg"/> --}}
                                <img alt="Colocation Strasbourg" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671388/bailti/img/strasbourg_gxtw74.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Strasbourg']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Strasbourg']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Strasbourg']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Strasbourg']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Strasbourg']) }}</div>
                            </div>
                        </div>
                        <div class="ville-annonce-container">
                            <a rel='follow' class="acceuil-annonce-ville annonces grenoble"
                                href="/colocation/auvergne-rhone-alpes/grenoble">
                                {{-- <img alt="Colocation Grenoble" class="img-top" src="/img/villes/grenoble.jpg"/> --}}
                                <img alt="Colocation Grenoble" class="img-top"
                                    src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651671387/bailti/img/grenoble_wwjaip.jpg"
                                    loading="lazy" />
                            </a>
                            <div class="ville-text">
                                <div>{{ __('acceuil.ou_vivre', ['ville' => 'Grenoble']) }}</div>
                                <div>{{ __('acceuil.appart_a_louer', ['ville' => 'Grenoble']) }}</div>
                                <div>{{ __('acceuil.studios_a_louer', ['ville' => 'Grenoble']) }}</div>
                                <div>{{ __('acceuil.chambres_a_louer', ['ville' => 'Grenoble']) }}</div>
                                <div>{{ __('acceuil.residences_a_louer', ['ville' => 'Grenoble']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div id="other-div-ajax">
        <div class="all-utli clearfix">
            {{-- <img class="all-uti-img" src="/img/utilisateur.jpeg"> --}}
            <img class="all-uti-img"
                src="https://res.cloudinary.com/dwajoyl2c/image/upload/v1651673119/bailti/img/utilisateur_bvi9lq.jpg"
                loading="lazy">
            <div class="inner">
                <div class="utilisateur-items">
                    <div class="counter-div">
                        {{-- <img src="/img/icon-maison.png" width="125" height="94" alt="maison.png"> --}}
                        <i class="fa fa-home icon-home" aria-hidden="true"></i>
                        <hr class="ligne-home">
                        <span>{{ $nbAdsCount }}</span>
                    </div>
                    <div>
                        <span>{{ __('acceuil.ads') }}</span>
                    </div>

                </div>
                <div class="utilisateur-items">
                    <div class="counter-div">
                        {{-- <img src="/img/icon-homme-two.png" alt="home.png" width="125" height="100"> --}}
                        <i class="fa fa-user-o icon-home" aria-hidden="true"></i>
                        <hr class="ligne-home">
                        <span>{{ $nbUsersCount }}</span>
                    </div>

                    <div>
                        <span>{{ __('acceuil.users') }}</span>
                    </div>
                </div>
            </div>
        </div>
        {{-- <!-- <div class="collocataire recherchant logements moyenne clearfix">
            <div class="inner">
                <p class="avis-user">{{ __('acceuil.avis_moyenne') }}</p>
                <div class="moyenne">
                    @if (getTotalAvis() != 0)
                    <div id="div_avis" class="avis_stars">

                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i < calculMoyenneAvis())
                                <a  href="javascript:">
                                    <img data-id="{{ $i }}" class="filled-star-home stars_avis" alt="stars.png" width="60" height="60" src="/img/small-img.png">
                                </a>
                            @endif
                            @if ($i == calculMoyenneAvis())
                                <a  href="javascript:">
                                    <img data-id="{{ $i }}" class="filled-star-home stars_avis" alt="stars.png" width="60" height="60" src="/img/small-img.png" >
                                </a>
                            @endif

                            @if ($i > calculMoyenneAvis())
                                @if ($i == intval(calculMoyenneAvis()) + 1 && getPartieDecimale(calculMoyenneAvis()) != 0)
                                <a  href="javascript:" >
                                    <img data-id="{{ $i }}" class="stars_avis {{ 'filled-star-home-' . getPartieDecimale(calculMoyenneAvis()) }}" alt="stars.png" width="60" height="60"
                                    src="/img/small-img.png">
                                </a>
                                @else
                                <a  href="javascript:">
                                    <img data-id="{{ $i }}" class="stars_avis empty-star" alt="stars.png" width="60" height="60"  src="/img/small-img.png">
                                </a>
                                @endif
                            @endif
                        @endfor

                    </div>
                    <span>{{ calculMoyenneAvis() }}/5</span>
                    @endif
                    <a class="avis" rel='nofollow' href="{{ url('/tous-les-avis') }}">{{ __('acceuil.voir_avis') }}</a>
                </div>
            </div>
        </div> --> --}}
    </div>
    @include('common.footer')
    <div id="common-page">

    </div>
        @include('common.notification')
        @include('common.all-notification')

    @if (Auth::check())
        <input type="hidden" id="user-authentified" value="true">
    @endif
    <div class='loader-icon'><img src='/images/rolling-loader.apng' loading="lazy" alt="rolling-loader.apng"></div>

    <div id="information-modal-register" class="modal ">
        <div class="modal-dialog ">
            <div class="modal-content ">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                    <div id="modal-information-text" class="modal-title text-center">{{ i18n('error_register') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $var = 'Megadeth';
    @endphp

    @stack('custom-scripts')
    <script type="text/javascript">
        var jsvar = '<?=$var?>';
        // console.log(jsvar);
        var messages = {
            "placeholder_search": "{{ __('acceuil.enter_ville_search') }}"
        };
    </script>
    {{-- <script src="/js/jquery-3.2.1.min.js"></script> --}}
    {{-- bailtidev --}}
    {{-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649313503/js/jquery-3.2.1.min_m8cegm.js"></script> --}}
    {{-- bailti3 --}}
    <script src="https://res.cloudinary.com/avaim/raw/upload/v1651747679/bailti3/js/jquery-3.2.1.min_ilofsr.js"></script>
    {{-- <script src="/js/bootstrap.min.js"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649313657/js/bootstrap.min_tipe0p.js"></script>
    {{-- <script src="/js/jquery.validate.min.js"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649313718/js/jquery.validate.min_zfypnh.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/places.js@1.16.1"></script> --}}


    <script src="/js/home.js"></script>
    {{-- <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649314278/js/home_jqluje.js"></script> --}}
    {{-- <script src="/js/bootstrap-select.min.js"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649313786/js/bootstrap-select.min_dbaoox.js"></script>
    {{-- <script src="/js/popper.min.js"></script> --}}
    <script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649313854/js/popper.min_rqa7pg.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    @if (isTelegramAcceuil())
        @include('common.pub')
    @endif



    @if (!is_null($infosAddress))
        <script>
            $(document).ready(function() {
                $('html,body').animate({
                    scrollTop: 0
                }, 'slow');
            });
        </script>
    @endif
    <!--Start of Tawk.to Script-->
    <!-- <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/5d0aaefa36eab972111846a4/default';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script> -->
    <!--End of Tawk.to Script-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js" integrity="sha512-jNDtFf7qgU0eH/+Z42FG4fw3w7DM/9zbgNPe3wfJlCylVDTT3IgKW5r92Vy9IHa6U50vyMz5gRByIu4YIXFtaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
