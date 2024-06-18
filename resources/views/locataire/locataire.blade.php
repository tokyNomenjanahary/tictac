@extends('proprietaire.index')

@section('contenue')
    <style>
        #active_records {
            background-color: rgba(114, 163, 51, 0.14) !important;
            border: 1px solid rgba(114, 163, 51, 0.6);
            border-right: 1px solid rgba(114, 163, 51, 0.4);
        }
        .h5{
        display:none;
    }

        #archived_records {
            /* background-color: rgba(114, 163, 51, 0.14) !important; */
            border: 1px solid rgba(114, 163, 51, 0.6);
            border-right: 1px solid rgba(114, 163, 51, 0.4);
        }

        th {
            /*color: blue !important;*/
            /*font-size: 10px !important;*/
        }
        .table > :not(caption) > * > * {
            padding: 0.625rem 0.50rem !important;
            background-color: var(--bs-table-bg);
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
        }
        div.dataTables_wrapper div.dt-row {
            position: static !important;
        }
        /*.tab-content {*/
        /*    !*padding: 0rem !important;*!*/
        /*}*/
    </style>
    <div class="container">
        <div class="row" style="margin-top: 30px;">
            <div class="col-lg-4">
                <h3 class="page-header page-header-top">Locataires</h3>
            </div>
            <div class="col-lg-4">
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="border: none;">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" style="border:1px solid #EBF2E2;color:blue;" id="home-tab"
                            data-bs-toggle="tab" data-bs-target="#coloc_acif" type="button" role="tab"
                            aria-controls="home" aria-selected="true">
                            <i class="fa fa-check m-r-5"></i> Actifs
                            <span id="ActifsCounts"
                                class="badge bg-primary">0</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" style="border:1px solid #f9f9f9;color:blue;" id="profile-tab"
                            data-bs-toggle="tab" data-bs-target="#coloc_archi" type="button" role="tab"
                            aria-controls="profile" aria-selected="false"> <i class="fa fa-folder-open m-r-5"></i> Archives
                            <span id="ArchivesCounts" class="badge bg-primary">0</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4" style="text-align: right;">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        Nouveau locataire
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('locataire.ajouterColocataire') }}"><i
                                class="fa fa-plus-circle" onclick="localStorage.clear();"></i> Nouveau locataire</a>
                        <a class="dropdown-item" href="{{route('locataire.import')}}"><i class="fa fa-cloud-upload me-1"></i> Importer</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4 p-3 filtre">
            <div>
              <p><span class="h5">Filter</span> <span>Utilisez les options pour filtrer</span></p>
            </div>
            <div class="row">

              {{-- <div class="col-2">
                <div>
                    <select id="filter-select-date" class="form-select form-select-sm">
                        <option value="All" selected>Date</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="yesterday">Hier</option>
                        <option value="this_month">Ce mois</option>
                        <option value="last_7_days">Dernier 7 jours</option>
                        <option value="last_30_days">Dernier 30 jours</option>
                        <option value="last_month">Mois dernier</option>
                        <option value="last_3_months">Trois derniers mois</option>
                    </select>
                </div>
              </div> --}}
              <div class="col-8">
                <div>
                  <select id="filter-select-bien" style="width: 200px" class="form-select form-select-sm">
                    <option value="All">Tout les biens</option>
                    @foreach ($logements as $logement)
                        <option value="{{$logement->identifiant}}">{{$logement->identifiant}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-4">
                <div>
                  <input id="recherche" class="form-control form-control-sm" type="text" placeholder="Recherche">
                </div>
              </div>
            </div>
        </div>
        <div class="tab-content" style="padding-left: 12px;margin-top:-38px;padding-right: 12px;">
            <div class="tab-pane active" id="coloc_acif" role="tabpanel" aria-labelledby="home-tab" style="">
                <div class="row">
                    <!-- Basic Bootstrap Table -->
                    @include('locataire.showLocataire')
                    <!--/ Basic Bootstrap Table -->
                </div>
            </div>
            <div class="tab-pane" id="coloc_archi" role="tabpanel" aria-labelledby="profile-tab">
                <div class="tab-pane active" id="coloc_acif" role="tabpanel" aria-labelledby="home-tab"
                     style="">
                    <div class="row">
                        <!-- Basic Bootstrap Table -->
                        @include('locataire.showLocataireArchiver')
                        <!--/ Basic Bootstrap Table -->
                    </div>
                </div>
            </div>
            {{-- @include('proprietaire.suggestion') --}}
        </div>
    </div>
    @include('locataire.showLocataireJs')
@endsection

@push('js')
<script src="{{ asset('assets/js/fichier-locataire.js') }}"></script>
@endpush
