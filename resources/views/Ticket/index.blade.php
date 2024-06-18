<?php
if (!isTenant()) {
    $entete = 'proprietaire.index';
    $contenue = 'contenue';
}else{
    $entete = 'espace_locataire.index';
    $contenue = 'locataire-contenue';
}
?>
@extends($entete)

@section($contenue)
    <style>
        .modal .modal-header .btn-close {
            margin-top: -5rem !important;
            margin-right: -2rem !important;
        }

        #active_records {
            background-color: rgba(114, 163, 51, 0.14) !important;
            border: 1px solid rgba(114, 163, 51, 0.6);
            border-right: 1px solid rgba(114, 163, 51, 0.4);
        }

        .h5,
        #showLocataireArchiver_length {
            display: none;
        }


        #archived_records {
            /* background-color: rgba(114, 163, 51, 0.14) !important; */
            border: 1px solid rgba(114, 163, 51, 0.6);
            border-right: 1px solid rgba(114, 163, 51, 0.4);
        }

        .table> :not(caption)>*>* {
            padding: 0.625rem 0.50rem !important;
            background-color: var(--bs-table-bg);
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
        }

        div.dataTables_wrapper div.dt-row {
            position: static !important;
        }

        @media only screen and (max-width: 600px) {
            .nouv {
                text-align: center;
            }
        }


        .creation_button {
            text-align: center;
        }

        @media (min-width: 768px) {
            .creation_button {
                text-align: right;
            }
        }
    </style>
    <div class="container">

        <div class="row align-items-end mb-3" style="margin-top: 30px;">
            <div class="col-lg-4 mb-2">
                <h3 class="page-header page-header-top mb-0">Ticket</h3>
            </div>
            <div class="col-12 col-lg-4 col-sm-7 col-md-5 mb-2">
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="border: none;">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" style="border:1px solid #EBF2E2;color:blue;" id="navs-top-actif-tab"
                            data-bs-toggle="tab" data-bs-target="#coloc_acif" type="button" role="tab"
                            aria-controls="home" aria-selected="true">
                            <i class="fa fa-check m-r-5"></i> {{  __('documents.Actifs')}}
                            <span id="ActifsCounts" class="badge bg-primary">0</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" style="border:1px solid #f9f9f9;color:blue;" id="navs-top-archive-tab"
                            data-bs-toggle="tab" data-bs-target="#coloc_archi" type="button" role="tab"
                            aria-controls="profile" aria-selected="false"> <i class="fa fa-folder-open m-r-5"></i> {{  __('documents.Archives')}}
                            <span id="ArchivesCounts" class="badge bg-primary">0</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-lg-4 col-sm-5 col-md-4 btn-new-state creation_button">
                <a class="btn btn-primary" href="{{ route('ticket.formulaire') }}">
                    {{ __('ticket.nouveau_ticket') }}
                </a>
            </div>
        </div>
        <div class="card mb-4 p-3 filtre" id="filter-b">
            <div>
                <p><span>{{__('texte_global.filtre')}}</span></p>
            </div>
            <div class="row">
                <div class="col-3">
                    <div>
                        <select id="filter-select-type" class="form-select form-select-sm">
                            <option value="All">Type</option>
                            @foreach ($types as $type)
                                <option value="{{$type->Name}}">{{$type->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div>
                        <select id="filter-select-etat" class="form-select form-select-sm">
                            <option value="All">Etat</option>
                            <option value="{{ __('ticket.nouveau') }}">{{ __('ticket.nouveau') }}</option>
                            <option value="{{ __('ticket.en_cours')}}">{{ __('ticket.en_cours')}}</option>
                            <option value="{{ __('ticket.termine') }}">{{ __('ticket.termine') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div>
                        <select id="filter-select-location" class="form-select form-select-sm">
                            <option value="All">{{ __('revenu.Location') }}</option>
                            @foreach ($locations as $location)
                                <option value="{{$location->identifiant}}">{{$location->identifiant}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div>
                        <input id="recherche" class="form-control form-control-sm" type="text" placeholder="Recherche">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body pb-0">

                <div class="tab-content" style="padding-left: 12px;margin-top:-38px;padding-right: 12px;border:0px;">
                    <div class="tab-pane active" id="coloc_acif" role="tabpanel" aria-labelledby="home-tab" style="">
                        <div class="row">
                            <!-- Basic Bootstrap Table -->
                            @include('Ticket.showTicket')
                            <!--/ Basic Bootstrap Table -->
                        </div>
                    </div>
                    <div class="tab-pane" id="coloc_archi" role="tabpanel" aria-labelledby="home-tab" style="">
                        <div class="row">
                            <!-- Basic Bootstrap Table -->
                            @include('Ticket.showTicketArchive')
                            <!--/ Basic Bootstrap Table -->
                        </div>
                    </div>
                </div>
                <div class="d-flex p-3 mt-4">
                    <div class="dropdown d-none" style="margin-left:px;margin-top:-55px" id="export">
                        <button type="button" id="delete" class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-trash"></i>&nbsp;{{ __('finance.Supprimer')}}
                        </button>
                        <button class="btn btn-secondary btn-sm " id="archive_data">
                            <i class="fa-solid fa-trash"></i>&nbsp;{{ __('documents.Archiver')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('locataire.showLocataireJs')
@endsection
