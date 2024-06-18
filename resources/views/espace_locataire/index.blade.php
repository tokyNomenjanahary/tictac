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
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
            integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/Autocomplete-style.css') }}">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />


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
            .menu-finance:hover
            {
                background: none !important;
            }
            #notif{
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
            #quittance_notif{
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
            .list-bien{
                border-left-style: solid;
                margin-left: 2rem;
                list-style: none;
            }

            .left-bien-n{
                margin-left: -20px;
            }

            .left-bien{
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
    <body id="body-content">
        <div id="myLoader" class="p-4 position-fixed d-none" style="z-index: 9999; background: #00000040; top: 0; bottom: 0; left: 0; right: 0;">
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
                    <div class="app-brand demo">
                        <div class="log">
                            <a href="/">
                            <img src="{{ asset('assets/img/lotie/blue-logo.png') }}" alt="" srcset=""
                                style="width: 150px">
                            </a>
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
                            <a href="{{ route('espaceLocataire.dashboard') }}" class="menu-link">
                                <i class="menu-icon fa fa fa-desktop"></i>
                                <div data-i18n="Analytics">Bureau</div>
                            </a>
                        </li>
                        <li class="menu-item ">
                            <a href="{{ route('espaceLocataire.mesproprio') }}" class="menu-link">
                                <i class="menu-icon fas fa-user-tie"></i>
                                <div>Propriétaires</div>
                            </a>
                        </li>
                        <li class="menu-item ">
                            <a href="{{ route('espaceLocataire.mesinfo') }}" class="menu-link">
                                <i class="menu-icon fa fa-info-circle"></i>
                                <div> Mes informations</div>
                            </a>
                        </li>
                        <?php
                            $userId = auth()->user()->id; // Obtenez l'ID de l'utilisateur connecté
                            $count = \App\Document_caf::where('etat', 2)
                            ->where('cree_par', 2)
                            ->with('location')
                            ->whereHas('location', function ($query) use ($userId) {
                                $query->whereHas('Locataire', function ($subQuery) use ($userId) {
                                    $subQuery->where('user_account_id', $userId);
                                });
                            })
                            ->count();

                            $countNotif=0;
                        //notification arrive de nouveau quittance ou recu
                        $quittance_nonLue = \App\quittance::where('user_id_destinataire', Auth::id())
                            ->where('is_lue', 0)
                            ->count();
                           if($quittance_nonLue>0){
                               $countNotif++;
                           }
                        //notification de ticket modifie
                        $ticket_modifie=\App\Ticket::where('User_destinated_ticket', Auth::id())
                            ->where('is_modifie', 1)
                            ->count();
                        if($ticket_modifie>0){
                            $countNotif++;
                        }
                        //notification de loyer en retard et fin de bail
                        $user_id = Auth::id();
                        $locataires = \App\LocatairesGeneralInformations::where('user_account_id',$user_id)->get();
                        $locataireId = [];
                        foreach($locataires as $locataire){
                            $locataireId[] = $locataire->id;
                        }
                        //recuperation de location
                        $locations = \App\Location::whereIn('locataire_id',$locataireId)->get();
                        //finances non paye
                        $etat_finance   = [];
                        $etat_location=[];
                        foreach ($locations as $location) {
                            $date_fin = \Carbon\Carbon::parse($location->fin);
                            $date_actuelle=date('m');

                            //fin du bail d'une location
                            if ($date_fin->month==$date_actuelle) {
                                $etat_location[] = [$location->identifiant,$location->fin,$location->id];
                            }
                            $finances = \App\Finance::where('location_id', $location->id)
                                ->where('Etat', 1)
                                ->get();
                            foreach ($finances as $finance) {
                                $etat_finance[] = [$location->identifiant,$location->id];
                            }
                        }
                        if(count($etat_finance)>0){
                            $countNotif++;
                        }
                        if(count( $etat_location)>0){
                            $countNotif++;
                        }

                        //notification agenda en attente
                        $agenda = \App\Agenda::where('userId_locataire',$userId)->where('status',0)->where('cree_par','<>',1)->count();
                        if( $agenda>0){
                            $countNotif++;
                        }
                        //notification de qui se passe rdv aujourd'hui
                        $today = \Carbon\Carbon::today()->toDateString();
                        $rdv_ajourdhui = \App\Agenda::whereDate('start_time',$today)->where('status',1)->where('cree_par',0)->count();
                        if( $rdv_ajourdhui>0){
                            $countNotif++;
                        }
                        ?>


                        <li class="menu-item {{ Request::is(['/edition','location*','document_CAF*']) ? 'active' : '' }}">
                            <a href="{{route('location.liste_locataire')}}" class="menu-link justify-content-between">
                                <div><i class="menu-icon fa fa-key"></i>Location</div>
                                @if ($count > 0)
                                    <span id="pas_payé">{{ $count }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="menu-item ">
                            <a href="{{route('locataire.quittance')}}" class="menu-link">
                                <i class="menu-icon fa-solid fa-solid fa-coins"></i>
                                <div>Quittances</div>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a class="menu-link" data-bs-toggle="collapse" href="#collapseDocument" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                <i class="menu-icon fa fa-briefcase"></i>
                                <div data-i18n="Analytics">Documents</div>
                            </a>
                            <ul class="collapse list-group list-group-flush list-bien" id="collapseDocument">
                                <li>
                                    <a href="{{ route('documents.index') }}" class="menu-link">
                                        <i class="fa-solid fa-briefcase left-bien-n" style="margin-left:2px;"></i>
                                        <div class="left-bien">{{ __('documents.mes_documents') }}</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/documents/modeles-documents" class="menu-link">
                                        <i class="fa-solid fa-copy left-bien-n" style="margin-left:2px;"></i>
                                        <div class="left-bien">{{ __('documents.modele') }}</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item ">
                            <a href="{{ route('message.index') }}" class="menu-link">
                                <i class="menu-icon fa-solid fa-message"></i>
                                <div>Messages</div>
                            </a>
                        </li>

                        <li class="menu-item ">
                            <a href="{{ route('ticket.index') }}" class="menu-link">
                                <i class="menu-icon fa-solid fa-clipboard-check"></i>
                                <div>Tickets</div>
                            </a>
                        </li>
                        <li class="menu-item ">
                            <a href="{{ route('carnet.index') }}" class="menu-link">
                                <i class="menu-icon fa fa-address-card" aria-hidden="true"></i>
                                <div>Contact</div>
                            </a>
                        </li>
                        <li class="menu-item ">
                            <a href="{{ route('agenda.index') }}" class="menu-link">
                                <i class="menu-icon fa-solid fa-calendar"></i>
                                <div>Agenda</div>
                            </a>
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
                                    $hideSearch = (request()->is('proprietaire*'));
                                @endphp

                                @if (!$hideSearch)
                                    <div class="nav-item d-flex align-items-center">
                                        <i class="bx bx-search fs-4 lh-0"></i>
                                        <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                                            aria-label="Search..." aria-controls="example" id="rec" />
                                    </div>
                                @endif

                            </div>
                            <!-- /Search -->

                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <li class="drop-btn">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                      <i class="fas fa-bell p-2"  style="font-size: 25px;display: inline-flex">@if($countNotif>0)<span id="notif">{{$countNotif}}</span>@endif</i>
                                    </a>
                                    @if($countNotif>0)
                                        <ul class="dropdown-menu dropdown-menu-end custom-drop-toggle" style="position: fixed !important;">
                                            @if($quittance_nonLue>0)  <li><a class="dropdown-item" href="{{route('locataire.quittance')}}"><span id="quittance_notif">{{$quittance_nonLue}}</span> <span style="margin-left: 5px;">{{__('quittance.Nouveau_quittance')}}</span></a></li>@endif
                                            @if(count($etat_finance)>0) <li> <a class="dropdown-item" > <span style="margin-left: 5px;"><i class="fas fa-exclamation-triangle text-warning" style="font-size: 20px;"></i> {{__('quittance.Vous_avez')}} {{count($etat_finance)}} {{__('finance.Loyer_en_retard')}}</span></a></li> @endif
                                            @if(count($etat_location)>0) <li> <a class="dropdown-item" > <span style="margin-left: 5px;"><i class="fas fa-exclamation-triangle text-warning" style="font-size: 20px;"></i>{{__('quittance.Vous_avez')}} {{count($etat_location)}} {{__('quittance.rappel_de_fin_de_bail')}} </span></a></li> @endif
                                            @if($ticket_modifie>0) <li> <a class="dropdown-item" href="{{ route('ticket.index') }}"><span id="quittance_notif">{{$ticket_modifie}}</span> <span style="margin-left: 5px;"> {{__('quittance.Votre_proprietaire_modifier_un_ticket')}}</span></a></li> @endif
                                            @if($agenda>0) <li> <a class="dropdown-item" href="{{ route('agenda.index') }}"><span id="quittance_notif">{{$agenda}}</span> <span style="margin-left: 5px;"> {{__('quittance.Vous_avez')}} {{$agenda}} rendez-vous en attente de confirmation</span></a></li> @endif
                                            @if($rdv_ajourdhui>0) <li> <a class="dropdown-item" href="{{ route('agenda.index') }}"><span id="quittance_notif">{{$rdv_ajourdhui}}</span> <span style="margin-left: 5px;"> {{__('quittance.Vous_avez')}} {{$rdv_ajourdhui}} rendez-vous aujourd'hui</span></a></li> @endif
                                            @if(count($etat_location)>0 || count($etat_finance)>0) <li><a  class="dropdown-item" href="{{route('locataire.notification')}}"><span style="margin-left: 5px;"> <i class="fa-solid fa-list text-info"></i>  {{__('quittance.Voir_plus')}}...</span></a></li> @endif
                                        </ul>
                                    @endif
                                </li>
                                <li class="nav-item navbar-dropdown dropdown-user dropdown drop-btn">
                                    <a class="nav-link dropdown-toggle hide-arrow " href="javascript:void(0);"
                                        data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            <img class="w-px-40 h-auto rounded-circle"
                                                @if (
                                                    !empty(Auth::user()->user_profiles) &&
                                                    !empty(Auth::user()->user_profiles->profile_pic) &&
                                                    File::exists(storage_path('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic))) src="{{ URL::asset('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic) }}" style="transform: rotate({{ Auth::user()->user_profiles->pdp_rotate }}deg);"
                                                    @else src="{{ URL::asset('images/profile_avatar.jpeg') }}"
                                                @endif
                                            >
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end custom-drop-toggle">
                                        <li>
                                            <form action="{{ route('user.toggle') }}" method="post">
                                                {{ csrf_field() }}
                                                @method('put')
                                                <input type="text" value="1" name="role_id" hidden>
                                                <a class="dropdown-item" href=""
                                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                                    <i class="bx bx-power-off me-2"></i>
                                                    <span class="align-middle">Espace Proprietaire</span>
                                                </a>
                                            </form>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('edit.profile') }}">
                                                <i class="bx bx-user me-2"></i>
                                                <span class="align-middle">{{ __('header.profile') }}</span>
                                            </a>
                                        </li>
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
                    <div class="padding-full-calendar">
                        @yield('locataire-contenue')
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <!-- / Layout wrapper -->

        <!-- Core JS -->
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

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js"></script>

        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script> --}}




        @stack('js')
        @stack('script')
    </body>

</html>
