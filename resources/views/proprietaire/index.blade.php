<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template-free">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Bailti</title>
    @yield('file-input')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="description" content="" />
    <link rel="icon" href="img/favicon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    {{--        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" --}}
    {{--            integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" --}}
    {{--            crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/Autocomplete-style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous"> --}}

    {{--    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" /> --}}
    @yield('css')
    @stack('css')


    <style>
        body{
                background-color: #e7f2ff!important;
        }
        .img {
            width: 300px;
            height: 350px;
            margin-left: 35%;
            border: 1px solid rgb(235, 229, 229);

        }

        #icone_notif {
            width: 20px;
            height: 20px;
            float: right;
            font-size: 11px;
            border-radius: 10px;
            color: white;
            text-align: center;
            background-color: red;
            padding-top: 4px;
        }

        #example_filter {
            display: none !important;
        }

        .dataTables_filter {
            display: none !important;

        }

        .menu-finance:hover {
            background: none !important;
        }

        #pas_payé {
            display: block;
            width: 20px;
            height: 20px;
            float: right;
            font-size: 11px;
            border-radius: 10px;
            margin-top: 1px;
            margin-right: 2px;
            color: white;
            text-align: center;
            /* background-color: #b94a48; */
            background-color: red;
        }

        #notif {
            width: 20px;
            height: 20px;
            float: left;
            font-size: 11px;
            border-radius: 10px;
            color: white;
            text-align: center;
            background-color: red;
            padding-top: 2px;
        }

        #en_attente {
            display: block;
            width: 20px;
            height: 20px;
            float: right;
            font-size: 11px;
            border-radius: 10px;
            margin-top: 1px;
            color: white;
            text-align: center;
            /* background-color: #b94a48; */
            background-color: #f89406;
        }

        .list-bien {
            border-left-style: solid;
            margin-left: 2rem;
            list-style: none;
        }

        .left-bien-n {
            margin-left: -20px;
        }

        .left-bien {
            margin-left: 10px;
        }

        .menu-link {
            margin: 0rem 0rem !important;
        }
    </style>
    @stack('styles')

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <?php
    use Carbon\Carbon;
    $userId = auth()->user()->id; // Obtenez l'ID de l'utilisateur connecté
    $count = \App\Document_caf::where('etat', 1)
        ->where('cree_par', 2)
        ->whereHas('location', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->count();

    $ticket_non_lus = \App\Ticket::instance()->getNouveauTicketLocataire();
    $loyer_en_retard = \App\Finance::instance()->getLoyerEnRetard();
    $revenue_en_attente = \App\Finance::instance()->getRevenuByStatus(2);
    $revenue_pas_paye = \App\Finance::instance()->getRevenuByStatus(1);

    $compt_notif = 0;
    if ($ticket_non_lus > 0) {
        $compt_notif++;
    }
    if (count($loyer_en_retard) > 0) {
        $compt_notif++;
    }
    if (count($revenue_en_attente) > 0) {
        $compt_notif++;
    }
    if (count($revenue_pas_paye) > 0) {
        $compt_notif++;
    }

    $message_non_lus = [];

    //notification de qui se passe rdv aujourd'hui
    $today = \Carbon\Carbon::today()->toDateString();
    $rdv_ajourdhui = \App\Agenda::whereDate('start_time',$today)->where('status',1)->where('cree_par',1)->count();
    if( $rdv_ajourdhui>0){
        $compt_notif++;
    }
    ?>

    @php
    if (Auth::user()->owner_step == 1) {
        $step_per = 3;
    }
    elseif (Auth::user()->owner_step == 2) {
        $step_per = 34;
    } else {
        $step_per = 100;
    }
    @endphp
    <div id="myLoader" class="p-4 position-fixed d-none"
        style="z-index: 9999; background: #00000040; top: 0; bottom: 0; left: 0; right: 0;">
        <div class="row">
            <div class="spinner-border text-primary position-absolute" role="status"
                style="z-index: 9999; top: 50%; left: 49%;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo mb-2">
                    {{-- <a href="index.html" class="app-brand-link">
                                <span class="app-brand-logo demo">
                                    <svg
                                    width="25"
                                    viewBox="0 0 25 42"
                                    version="1.1"
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    >
                                    <defs>
                                        <path
                                        d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                        id="path-1"
                                        ></path>
                                        <path
                                        d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                                        id="path-3"
                                        ></path>
                                        <path
                                        d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                                        id="path-4"
                                        ></path>
                                        <path
                                        d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                                        id="path-5"
                                        ></path>
                                    </defs>
                                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                                            <g id="Mask" transform="translate(0.000000, 8.000000)">
                                            <mask id="mask-2" fill="white">
                                                <use xlink:href="#path-1"></use>
                                            </mask>
                                            <use fill="#4C8DCB" xlink:href="#path-1"></use>
                                            <g id="Path-3" mask="url(#mask-2)">
                                                <use fill="#4C8DCB" xlink:href="#path-3"></use>
                                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                            </g>
                                            <g id="Path-4" mask="url(#mask-2)">
                                                <use fill="#4C8DCB" xlink:href="#path-4"></use>
                                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                            </g>
                                            </g>
                                            <g
                                            id="Triangle"
                                            transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) "
                                            >
                                            <use fill="#4C8DCB" xlink:href="#path-5"></use>
                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                            </g>
                                        </g>
                                        </g>
                                    </g>
                                    </svg>
                                </span>
                                <span class="app-brand-text demo menu-text fw-bolder ms-2">Sneat</span>
                            </a> --}}
                    <div class="log">
                        <a href="/"> <img src="{{ asset('assets/img/lotie/blue-logo.png') }}" srcset=""
                                          style="width: 150px"></a>
                    </div>
                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item">
                        <a href="{{ route('proprietaire.bureau') }}" class="menu-link">
                            <i class="menu-icon fa fa fa-desktop"></i>
                            <div data-i18n="Analytics">Bureau</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is(['logement*']) ? 'active' : '' }}">
                        <a class="menu-link" data-bs-toggle="collapse" href="#collapseExample" role="button"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="menu-icon fa-solid fa-house"></i>
                            <div data-i18n="Analytics">Biens</div>
                            @if (Auth::user()->logements->isEmpty() && Auth::user()->owner_step == 1)
                            <div class="spinner-grow text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            @endif
                        </a>
                        <ul class="collapse list-group list-group-flush list-bien {{ Request::is(['logement*']) ? 'show' : '' }}"
                            id="collapseExample">
                            @foreach (getAllPropertyType() as $property)
                                <li>
                                    {{-- <a href="{{ route('proprietaire.listLogements',$property->property_type, encrypt($property->id)) }}"  class="menu-link" style="{{ Request::is(['logement/list/'.$property->property_type.'/*']) ? 'background-color:rgb(232,227,255)' : '' }}"> --}}
                                    <a href='/logement/list/{{ $property->property_type }}/{{ encrypt($property->id) }}'
                                        class="menu-link"
                                        style="{{ Request::is(['logement/list/' . $property->property_type . '/*']) ? 'background-color:rgb(232,227,255)' : '' }}">
                                        @switch($property->id)
                                            @case(2)
                                                {{-- Chambre --}}
                                                <i class="fa-sharp fa-solid fa-door-closed"></i>
                                            @break

                                            @case(3)
                                                {{-- maison-de-ville --}}
                                                <i class='bx bxs-institution'></i>
                                            @break

                                            @case(4)
                                                {{-- appartement --}}
                                                <i class='bx bxs-building-house'></i>
                                            @break

                                            @case(7)
                                                {{-- studio-individuel --}}
                                                <i class="fa-solid fa-house-chimney-user"></i>
                                            @break

                                            @case(9)
                                                {{-- loft --}}
                                                <i class='bx bxs-door-open'></i>
                                            @break

                                            @case(10)
                                                {{-- bureau --}}
                                                <i class="fa-solid fa-house-laptop"></i>
                                            @break

                                            @default
                                        @endswitch

                                        <div class="left-bien">{{ $property->property_type }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="menu-item ">
                        <a href="{{ route('locataire.locataire') }}" class="menu-link">
                            <i class="menu-icon fa fa-users"></i>
                            <div>Locataires</div>
                            @if (!Auth::user()->logements->isEmpty() && Auth::user()->locataires->isEmpty() && Auth::user()->owner_step == 2)
                            <div class="spinner-grow text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>2
                            @endif
                        </a>
                    </li>

                    <li
                        class="menu-item {{ Request::is('edition*', 'location*', 'ficheLocation*', 'importLocation*', 'regularisationCharge*', 'aprecu*', 'revisionLoyer*', 'TerminerLocation*', 'Ajouter_commentaire*', 'document_CAF*') ? 'active' : '' }}">
                        <a href="{{ route('location.index') }}" class="menu-link justify-content-between">
                            <div><i class="menu-icon fa fa-key"></i>Locations</div>
                            @if ($count > 0)
                                <span id="pas_payé">{{ $count }}</span>
                            @endif
                            @if (!Auth::user()->logements->isEmpty() && !Auth::user()->locataires->isEmpty() && Auth::user()->locations->isEmpty() && Auth::user()->owner_step == 3)
                            <div class="spinner-grow text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a href="{{ route('note.index') }}" class="menu-link">
                            <i class="menu-icon fa fa-star"></i>
                            <div>Notes</div>
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a href="{{ route('proprietaire.etat-des-lieux') }}" class="menu-link">
                            <i class="menu-icon fa-solid fa-file-circle-check"></i>
                            <div>État des lieux</div>
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a href="{{ route('carnet.index') }}" class="menu-link">
                            <i class="menu-icon fa fa-address-card" aria-hidden="true"></i>
                            <div>Contact</div>
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a href="{{route('proprietaire.agenda')}}" class="menu-link">
                            <i class="menu-icon  far fa-calendar"></i>
                            <div>Agenda</div>
                        </a>
                    </li>
                    <li class="menu-item ">
                        <a href="{{ route('ticket.index') }}" class="menu-link">
                            <i class="menu-icon fa-solid fa-clipboard-check"></i>
                            <div>Ticket</div>
                        </a>
                    </li>

                    <li class="menu-item ">
                        <a href="{{ route('message.index') }}" class="menu-link">
                            <i class="menu-icon fa-solid fa-message"></i>
                            <div>Message</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a class="menu-link" data-bs-toggle="collapse" href="#collapseDocument" role="button"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="menu-icon fa fa-briefcase"></i>
                            <div data-i18n="Analytics">Document</div>
                        </a>
                        <ul class="collapse list-group list-group-flush list-bien" id="collapseDocument">
                            <li>
                                <a href="{{ route('documents.index') }}" class="menu-link">
                                    <i class="fa-solid fa-briefcase left-bien-n" style="margin-left:2px;"></i>
                                    <div class="left-bien">{{ __('documents.mes_documents') }}</div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('documents.modeles') }}" class="menu-link">
                                    <i class="fa-solid fa-copy left-bien-n" style="margin-left:2px;"></i>
                                    <div class="left-bien">{{ __('documents.modele') }}</div>
                                </a>
                            </li>
                            @if (!isTenant())
                                <li>
                                    <a href="{{ route('documents.gestionDossier') }}" class="menu-link">
                                        <i class='bx bxs-folder left-bien-n' style="margin-left:2px;"></i>
                                        <div class="left-bien">{{ __('documents.dossier') }}</div>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('documents.gestionSignature') }}" class="menu-link">
                                    <i class="fa-sharp fa-regular fa-folder-open left-bien-n"
                                        style="margin-left:2px;"></i>
                                    <div class="left-bien">{{ 'Ma Signature' }}</div>
                                </a>
                            </li>
                        </ul>
                    </li>



                    <li class="menu-item ">
                        <a href="{{ route('inventaire.index') }}" class="menu-link">
                            <i class="menu-icon fas fa-couch"></i>
                            <div>{{ __('inventaire.Inventaires') }}</div>
                        </a>
                    </li>

                    <li class="menu-item ">
                        <a href="{{ route('corbeille.index') }}" class="menu-link">
                            <i class="menu-icon fa fa-trash" aria-hidden="true"></i>
                            <div>Corbeille</div>
                        </a>
                    </li>

                    <li class="menu-item ">
                        <?php

                        $currentMonth = Carbon::now()->month;
                        ?>
                        <!-- Ajouter un ID à votre lien d'origine pour le sélectionner avec JavaScript -->
                        <a href="#" id="mon-lien" class="menu-link"><i
                                class="menu-icon fa-solid fa-coins"></i>Gestion financière @if (\App\Finance::where('user_id', Auth::id())->whereMonth('debut', '<=', $currentMonth)->where('etat', '1')->count() != 0)
                                <span id="pas_payé"
                                    style="margin-left: 8px;">{{ \App\Finance::where('user_id', Auth::id())->whereMonth('debut', '<=', $currentMonth)->where('etat', '1')->count() }}</span>
                            @endif
                            @if (\App\Finance::where('user_id', Auth::id())->whereMonth('debut', '<=', $currentMonth)->where('etat', '2')->count() != 0)
                                <span id="en_attente"
                                    style="margin-left: 8px;">{{ \App\Finance::where('user_id', Auth::id())->whereMonth('debut', '<=', $currentMonth)->where('etat', '2')->count() }}</span>
                            @endif
                        </a>
                        <!-- Ajouter des éléments vides pour les nouveaux liens -->
                        <a href="#" id="lien-1" class="menu-link menu-finance"></a>
                        <a href="#" id="lien-2" class="menu-link menu-finance"></a>
                    </li>
                    <!-- Misc -->
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="no-print layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar" style="z-index: 1 !important">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            @php
                                $hideSearch = request()->is('proprietaire*');
                            @endphp

                            @if (!$hideSearch)
                                <div class="nav-item d-flex align-items-center">
                                    <i class="bx bx-search fs-4 lh-0"></i>
                                    <input type="text" class="form-control border-0 shadow-none"
                                        placeholder="Search..." aria-label="Search..." aria-controls="example"
                                        id="rec" />
                                </div>
                            @endif

                        </div>


                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li>
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-bell p-2" style="font-size: 25px;">
                                        @if ($compt_notif > 0)
                                            <span id="icone_notif">
                                                {{ $compt_notif }}
                                            </span>
                                        @endif
                                    </i>
                                </a>

                                @if ($compt_notif > 0)
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if ($ticket_non_lus > 0)
                                            <li><a class="dropdown-item" href="{{ route('ticket.index') }}"><span
                                                        style="margin-left: 5px;"> <i
                                                            class="menu-icon fa-solid fa-info-circle "
                                                            style="color: blue"></i>
                                                        {{ str_replace('x_tickets',$ticket_non_lus,__('texte_global.ticket_notif')) }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($rdv_ajourdhui > 0)
                                            <li><a class="dropdown-item" href="{{route('proprietaire.agenda')}}"><span
                                                        style="margin-left: 5px;"> <i
                                                            class="menu-icon fa-solid fa-info-circle "
                                                            style="color: blue"></i>
                                                         Vous-avez  {{ $rdv_ajourdhui }} rendez-vous aujour'hui
                                                    </span>
                                                </a>
                                            </li>
                                        @endif
                                        @if (count($loyer_en_retard) > 0)
                                            <li><a class="dropdown-item" href="{{ route('proprietaire.finance') }}">
                                                    <span style="margin-left: 5px;"><i
                                                            class="fas fa-exclamation-triangle text-warning"
                                                            style="font-size: 20px;"></i>
                                                            {{ str_replace('x_location',count($loyer_en_retard->unique('identifiant')),__('texte_global.location_loye')) }}
                                                          </span></a></li>
                                        @endif
                                        @if (count($revenue_en_attente) > 0)
                                            <li><a class="dropdown-item"
                                                    href="{{ route('proprietaire.finance') }}"><span
                                                        style="margin-left: 5px;"><i
                                                            class="fas fa-exclamation-triangle text-warning"
                                                            style="font-size: 20px;"></i>
                                                        {{ str_replace('x_revenu',count($revenue_en_attente),__('texte_global.revenu_attente')) }}
                                                </span></a>
                                            </li>
                                        @endif
                                        @if (count($loyer_en_retard) > 0 || count($revenue_pas_paye) > 0 || count($revenue_en_attente) > 0)
                                            <li><a class="dropdown-item"
                                                    href="{{ route('proprietaire.notification') }}"><span
                                                        style="margin-left: 5px;"> <i
                                                            class="fa-solid fa-list text-info"></i> {{ __('texte_global.voir_plus')}}...</span></a></li>
                                        @endif
                                    </ul>
                                @endif


                            </li>

                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        {{-- <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" /> --}}
                                        <img class="w-px-40 h-auto rounded-circle"
                                            @if (
                                                !empty(Auth::user()->user_profiles) &&
                                                    !empty(Auth::user()->user_profiles->profile_pic) &&
                                                    File::exists(storage_path('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic))) src="{{ URL::asset('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic) }}" style="transform: rotate({{ Auth::user()->user_profiles->pdp_rotate }}deg);"
                                        @else src="{{ URL::asset('images/profile_avatar.jpeg') }}" @endif
                                            alt="">
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <form action="{{ route('user.toggle') }}" method="post">
                                            {{ csrf_field() }}
                                            @method('put')
                                            <input type="text" value="2" name="role_id" hidden>
                                            <a class="dropdown-item" href=""
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Espace Locataire</span>
                                            </a>
                                        </form>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('edit.profile') }}">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">{{ __('header.profile') }}</span>
                                        </a>
                                    </li>
                                    {{-- <li>
                                <div class="dropdown-divider"></div>
                            </li> --}}
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                            <i class="bx bxs-dashboard me-2"></i>
                                            <span class="align-middle">{{ __('header.dashboard') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/mes-candidatures/envoyes/tous') }}">
                                            <i class="bx bx-edit me-2"></i>
                                            <span class="align-middle">{{ __('header.applications') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            {{ csrf_field() }}
                                            <a class="dropdown-item" href=""
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Déconexion</span>
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                    <div class="content ">
                        @yield('contenue')
                    </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
        $(document).ready(function() {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
                keyboard: false
            })
            myModal.show()
        })
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js"></script>


    <script>
        var lien = document.querySelector('#mon-lien');

        // Ajouter un écouteur d'événement de clic
        lien.addEventListener('click', function(e) {
            // Empêcher le comportement de lien par défaut
            e.preventDefault();

            // Sélectionner les nouveaux liens
            var lien1 = document.querySelector('#lien-1');
            var lien2 = document.querySelector('#lien-2');

            // Vérifier si les liens sont visibles ou cachés
            if (lien1.innerHTML === '') {
                // Ajouter le contenu pour les nouveaux liens
                lien1.innerHTML = '<i class="menu-icon fa-solid fa-coins"></i>Finance';
                lien1.href = "{{ route('proprietaire.finance') }}";
                lien2.innerHTML = '<i class="fas fa-chart-line" style="margin-right:5px;"></i></i> Bilan';
                lien2.href = "{{ route('proprietaire.bilan') }}";
            } else {
                lien1.innerHTML = '';
                lien2.innerHTML = '';
            }
        });
    </script>
    @stack('plugin')
    @stack('script')
    @stack('js')
</body>

</html>
