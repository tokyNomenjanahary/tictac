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
<style>
    th {
        color: blue !important;
        font-size: 10px !important;
    }

    td {
        font-size: 13px;
    }

    .dataTables_length {
        display: none !important;
    }

    .dataTables_empty {
        display: none !important;
    }

    .dataTables_info {
        display: none !important;
    }

    div.dataTables_wrapper div.dt-row {
        position: static !important;
    }
</style>
@section($contenue)
    @if (isTenant())
        <style>
            .owner-filter, #documents_filter {
                display: none;
            }
        </style>
    @endif
    <div class="content-wrapper"
        style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row tete mt-4">
                <div class="col-lg-4 col-sm-4 col-12 col-md-4 titre">
                    <h3 class="page-header page-header-top m-0">Document</h3>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 arh">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="border: none;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" style="border:1px solid #EBF2E2;color:blue;" id="home-tab"
                                data-bs-toggle="tab" data-bs-target="#coloc_acif" type="button" role="tab"
                                aria-controls="home" aria-selected="true"><i class="fa fa-check m-r-5"></i>
                                {{ __('documents.Actifs') }} <span class="badge bg-primary"
                                    id="ActifsCounts">{{ count($documents) }}</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;color:blue;" id="profile-tab"
                                data-bs-toggle="tab" data-bs-target="#coloc_archive" type="button" role="tab"
                                aria-controls="profile" aria-selected="false"> <i class="fa fa-folder-open m-r-5"></i>
                                {{ __('documents.Archives') }}
                                <span class="badge bg-primary" id="ArchiveCounts">{{ count($document_archives) }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 nouv text-end">
                    <div>
                        <button type="button" class="btn btn-primary">
                            <a href="{{ route('documents.nouveau') }}" style="color: white;"><i
                                    class="fa fa-plus-circle"></i> {{ __('documents.Nouveau_document') }}</a>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 30px">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <p>{!! $message_status !!} <a class="text-primary"
                                    href="{{ route('documents.subscription_documents') }}">
                                    {{ __('Besoin de plus d\'espace ?') }}</a></p>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ round($storage_status['status']) }}%"
                                    aria-valuenow="{{ round($storage_status['status']) }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 30px">
                <div class="col">
                    <div class="card">
                        <div class="card-body" style="padding-top: 15px; padding-bottom: 0px;">
                            <div>
                                <p><span>{{ __('location.textFiltre') }}</span></p>
                            </div>
                            <div class="row">

                                <div class="col-lg-2 col-md-12 mb-3">
                                    <div class="form-group">
                                        <div>
                                            <select id="filter-select-date" class="form-select form-select-sm">
                                                <option value="All">{{ __('finance.Tous') }}</option>
                                                <option value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">
                                                    {{ __('finance.aujourdhui') }}</option>
                                                <option value="{{ \Carbon\Carbon::now()->subDays(1)->format('d/Y') }}">
                                                    {{ __('finance.hier') }}</option>
                                                <option value="{{ \Carbon\Carbon::now()->format('m/Y') }}">
                                                    {{ __('finance.Ce_mois') }}</option>
                                                <option value="{{ \Carbon\Carbon::now()->subDays(7)->format('d/Y') }}">
                                                    {{ __('finance.Dernier_7_jours') }}
                                                </option>
                                                <option value="{{ \Carbon\Carbon::now()->subDays(30)->format('d/Y') }}">
                                                    {{ __('finance.Dernier_30_jours') }}
                                                </option>
                                                <option value="{{ \Carbon\Carbon::now()->subMonth(1)->format('m/Y') }}">
                                                    {{ __('finance.mois_dernier') }}
                                                </option>
                                                <option value="{{ \Carbon\Carbon::now()->subMonth(3)->format('m/Y') }}">
                                                    {{ __('finance.trois_mois_dernier') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-12 mb-3 owner-filter">
                                    <div class="form-group">
                                        <div>
                                            <select id="filter-select-bien" class="form-select form-select-sm">
                                                <option value="All">{{ __('finance.Tous_les_biens') }}</option>
                                                @foreach ($bien as $b)
                                                    <option value="{{ $b->identifiant }}">{{ $b->identifiant }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-12 mb-3 owner-filter">
                                    <div class="form-group">
                                        <div>
                                            <select id="type_document" class="form-select form-select-sm">
                                                <option value="All">{{ __('documents.Tout_type') }}</option>
                                                <option value="{{ __('documents.Appels_de_fonds') }}">
                                                    {{ __('documents.Appels_de_fonds') }}</option>
                                                <option value="{{ __('documents.Assemblée_générale') }}">
                                                    {{ __('documents.Assemblée_générale') }}</option>
                                                <option value="{{ __('documents.Attestation_assurance') }}">
                                                    {{ __('documents.Attestation_assurance') }}</option>
                                                <option value="{{ __('documents.Attestation_de_scolarité') }}">
                                                    {{ __('documents.Attestation_de_scolarité') }}</option>
                                                <option value="{{ __('documents.Attestation_employeurs') }}">
                                                    {{ __('documents.Attestation_employeurs') }}</option>
                                                <option value="{{ __('documents.Certificat') }}">
                                                    {{ __('documents.Certificat') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-12 mb-3 owner-filter">

                                    <div>
                                        <select id="filter-select-etats" class="form-select form-select-sm">
                                            <option value="All">{{ __('documents.Tous') }}</option>
                                            <option value="{{ __('documents.Partagé') }}">{{ __('documents.Partagé') }}
                                            </option>
                                            <option value="{{ __('documents.Privé') }}">{{ __('documents.Privé') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-12 mb-3">

                                    <div>
                                        <input id="recherche" class="form-control form-control-sm" type="text"
                                            placeholder={{ __('finance.Recherche') }}>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:15px">
                <div class="col">
                    <div class="tab-content" style="padding-left: 12px;margin-top:-38px;padding-right: 12px;">
                        <div class="tab-pane active" id="coloc_acif" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row"
                                style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;padding:25px;">
                                <div>
                                    <table class="table table-striped table-hover" id="documents"
                                        style="margin-bottom:0px;border:2px solid #F3F5F6;">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="master"
                                                        class="checkbox_input align-middle "style="height: 20px;width:20px;">
                                                </th>
                                                <th>{{ __('documents.Fichier') }}</th>
                                                <th>{{ __('finance.Bien') }}</th>
                                                <th>{{ __('finance.Description') }}</th>
                                                <th>{{ __('documents.Partagé_avec') }}</th>
                                                <th>{{ __('documents.Partage') }}</th>
                                                <th>{{ __('finance.Date') }}</th>
                                                <th>{{ __('finance.Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($documents as $document)
                                                <tr>
                                                    <td style="width: 5px" class="check">
                                                        <input type="checkbox" id="selectionner"
                                                            class="checkbox_input align-middle sub_chk"
                                                            style="height: 15px;width:15px;" data-id={{ $document->id }}>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            {{-- <div class="file-type-icon mr-2">
                                                            <span class="corner"></span>
                                                            <span
                                                                class="type {{ pathinfo(storage_path('/uploads/locataire/profil/' . $document->file_name), PATHINFO_EXTENSION) }}">{{ pathinfo(storage_path('/uploads/locataire/profil/' . $document->file_name), PATHINFO_EXTENSION) }}</span>
                                                        </div> --}}
                                                            <div class="w-100">{{ $document->nomFichier }}</div>
                                                        </div>
                                                    </td>
                                                    <td>@if($document->getBien){{ $document->getBien->identifiant }}@endif</td>
                                                    <td>{{ $document->description }}</td>
                                                    <td></td>
                                                   <td>
                                                    @if($document->partage == 0)
                                                        <span title="Le document n'est pas partagé"
                                                        style="background-color:#999999;padding:2px 5px 2px 5px;color:#EBF2E2;font-size:12px;border-radius:5px">{{__('documents.Non')}}</span>
                                                    @else
                                                    <span title="Le document est partagé" class="bg-success"
                                                        style="padding:2px 5px 2px 5px;color:#EBF2E2;font-size:12px;border-radius:5px">Oui</span>
                                                    @endif
                                                </td>
                                                    <td> {{ \Carbon\Carbon::parse($document->created_at)->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a href="{{ route('documents.modifier', ['id' => $document->id]) }}"
                                                                    class="dropdown-item"> <i class="fa fa-pencil"></i>
                                                                    {{ __('finance.Modifier') }}</a>
                                                                <a href="{{ route('documents.telecharger', ['id' => $document->id]) }}"
                                                                    class="dropdown-item"> <i
                                                                        class="fa-solid fa-download"></i>{{ __('documents.Télécharger') }}</a>
                                                                <a href="{{ route('documents.archiver', $document->id) }}"
                                                                    class="dropdown-item"> <i
                                                                        class="fas fa-archive me-1"></i>{{ __('documents.Archiver') }}</a>
                                                                {{-- <a href="{{ route('documents.supprimer', ['id' => $document->id]) }}"
                                                                    class="dropdown-item"> <i class="fa-solid fa-trash"
                                                                        style="color: red"></i>{{ __('finance.Supprimer') }}</a> --}}
                                                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteloyer{{$document->id}}"><i class="fa-solid fa-trash" style="color:red;"></i> {{__('finance.Supprimer')}}</a>

                                                            </div>
                                                        </div>
                                                        <div class="modal fade" id="deleteloyer{{$document->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="modalTitleId">{{ __('quittance.suppression') }}</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                    {{ __('quittance.Voulez-vous') }}
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                                                         <a href="{{ route('documents.supprimer', ['id' => $document->id]) }}" type="button" class="btn btn-danger ">{{__('finance.Supprimer')}}</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        @if (count($documents) < 1)
                                            <tr>
                                                <td colspan="10">
                                                    <div class="card" style="margin-top: 5px">
                                                        <center>
                                                            <img src="https://www.lexpressproperty.com/local/cache-gd2/5c/bdc26a1bd667d2212c86fd1c86c3a7.jpg?1647583702"
                                                                alt="" style="width:300px;height:200px;"
                                                                class="img-responsive">
                                                        </center>
                                                        <br>
                                                        <h4 class="text-center">{{ __('finance.rien') }}...</h4>
                                                        <p class="text-center">{{ __('documents.text_rien') }}</p>
                                                        <center>
                                                            <button class="btn btn-outline-warning"
                                                                style="margin-bottom: 10px;"><a
                                                                    href="{{ route('documents.nouveau') }}">{{ __('documents.Nouveau_document') }}</a>
                                                            </button>
                                                        </center>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr style="background-color: #F3F5F6;">
                                            <td colspan="10">
                                                <div class="d-flex  ">
                                                    <button class="btn btn-danger btn-sm" id="delete"
                                                        style="display: none"><i
                                                            class="fa-solid fa-trash"></i>&nbsp;{{ __('finance.Supprimer') }}</button>
                                                    <div class="dropdown" style="margin-left: 13px;">
                                                        <button type="button" class="btn btn-primary btn-sm "
                                                            id="export" style="display: none" aria-expanded="false">
                                                            <a href="{{ route('downloadE') }}" style="color: white;"><i
                                                                    class="fa fa-archive">
                                                                </i> Download</a>
                                                        </button>
                                                    </div>

                                                </div>
                                                <div class="modal fade" id="deleteModalDocuments" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalTitleId">{{ __('quittance.suppression') }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                            {{ __('quittance.Voulez-vous') }}
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary ferme" data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                                                <button type="button" class="btn btn-danger delete-confirm">{{__('finance.Supprimer')}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="coloc_archive" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row"
                                style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;padding:25px;">
                                <div>
                                    <table class="table table-striped table-hover" id="archiveTab"
                                        style="margin-bottom:0px;border:2px solid #F3F5F6;">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="master"
                                                        class="checkbox_input align-middle "style="height: 20px;width:20px;">
                                                </th>
                                                <th>{{ __('documents.Fichier') }}</th>
                                                <th>{{ __('finance.Bien') }}</th>
                                                <th>{{ __('finance.Description') }}</th>
                                                <th>{{ __('documents.Partagé_avec') }}</th>
                                                <th>{{ __('documents.Partage') }}</th>
                                                <th>{{ __('finance.Date') }}</th>
                                                <th>{{ __('finance.Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($document_archives as $document_archive)
                                                <tr>
                                                    <td style="width: 5px" class="check">
                                                        <input type="checkbox" id="selectionner"
                                                            class="checkbox_input align-middle sub_chk"
                                                            style="height: 15px;width:15px;"
                                                            data-id={{ $document_archive->id }}>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">

                                                            <div class="w-100">{{ $document_archive->nomFichier }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $document_archive->logement_id }}</td>
                                                    <td>{{ $document_archive->description }}</td>
                                                    <td></td>
                                                    <td><span title="Le document n'est pas partagé"
                                                            style="background-color:#999999;padding:2px 5px 2px 5px;color:#EBF2E2;font-size:12px;border-radius:5px">{{ __('documents.Non') }}</span>
                                                    </td>
                                                    <td> {{ \Carbon\Carbon::parse($document_archive->created_at)->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu" style="z-index: 2000">
                                                                <a href="{{ route('documents.modifier', ['id' => $document_archive->id]) }}"
                                                                    class="dropdown-item"> <i class="fa fa-pencil"></i>
                                                                    {{ __('finance.Modifier') }}</a>
                                                                <a href="{{ route('documents.telecharger', ['id' => $document_archive->id]) }}"
                                                                    class="dropdown-item"> <i
                                                                        class="fa-solid fa-download"></i>{{ __('documents.Télécharger') }}</a>
                                                                <a href="{{ route('documents.desarchiver', $document_archive->id) }}"
                                                                    class="dropdown-item"> <i
                                                                        class="fas fa-archive me-1"></i>Désarchiver</a>
                                                                {{-- <a href="{{ route('documents.supprimer', ['id' => $document_archive->id]) }}"
                                                                    class="dropdown-item"> <i class="fa-solid fa-trash"
                                                                        style="color: red"></i>{{ __('finance.Supprimer') }}</a> --}}
                                                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteloyer{{$document_archive->id}}"><i class="fa-solid fa-trash" style="color:red;"></i> {{__('finance.Supprimer')}}</a>

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <div class="modal fade" id="deleteloyer{{$document_archive->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalTitleId">{{ __('quittance.suppression') }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                {{ __('quittance.Voulez-vous') }}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                                                     <a href="{{ route('documents.supprimer', ['id' => $document_archive->id]) }}" type="button" class="btn btn-danger ">{{__('finance.Supprimer')}}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        @if (count($document_archives) < 1)
                                            <tr>
                                                <td colspan="10">
                                                    <div class="card" style="margin-top: 5px">
                                                        <center>
                                                            <img src="https://www.lexpressproperty.com/local/cache-gd2/5c/bdc26a1bd667d2212c86fd1c86c3a7.jpg?1647583702"
                                                                alt="" style="width:300px;height:200px;"
                                                                class="img-responsive">
                                                        </center>
                                                        <br>
                                                        <h4 class="text-center">{{ __('finance.rien') }}...</h4>
                                                        <p class="text-center">{{ __('documents.text_rien') }}</p>
                                                        <center>
                                                            <button class="btn btn-outline-warning"
                                                                style="margin-bottom: 10px;"><a
                                                                    href="{{ route('documents.nouveau') }}">{{ __('documents.Nouveau_document') }}</a>
                                                            </button>
                                                        </center>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr style="background-color: #F3F5F6;">
                                            <td colspan="10">
                                                <div class="d-flex  ">
                                                    <button class="btn btn-danger btn-sm" id="delete"
                                                        style="display: none"><i
                                                            class="fa-solid fa-trash"></i>&nbsp;{{ __('finance.Supprimer') }}</button>
                                                    <div class="dropdown" style="margin-left: 13px;">
                                                        <button type="button" class="btn btn-primary btn-sm "
                                                            id="export" style="display: none" aria-expanded="false">
                                                            <a href="{{ route('downloadE') }}" style="color: white;"><i
                                                                    class="fa fa-archive">
                                                                </i> {{ __('documents.Télécharger') }}</a>
                                                        </button>
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="content-backdrop fade"></div>
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        var finance = $('#documents').DataTable({
            "pageLength": 10,
            "language": {
                "paginate": {
                    "previous": "&lt;", // Remplacer "previous" par "<"
                    "next": "&gt;" // Remplacer "next" par ">"
                },
                "lengthMenu": "Filtre _MENU_ ",
                "zeroRecords": "Pas de recherche corespondant",
                "info": "Affichage _PAGE_ sur _PAGES_",
                "infoEmpty": "Pas de recherche corespondant",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });
        var archiveTab = $('#archiveTab').DataTable({
            "pageLength": 10,
            "language": {
                "paginate": {
                    "previous": "&lt;", // Remplacer "previous" par "<"
                    "next": "&gt;" // Remplacer "next" par ">"
                },
                "lengthMenu": "Filtre _MENU_ ",
                "zeroRecords": "Pas de recherche corespondant",
                "info": "Affichage _PAGE_ sur _PAGES_",
                "infoEmpty": "Pas de recherche corespondant",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });
        $('#rec').on('keyup', function() {
            finance.search(this.value).draw();
            archiveTab.search(this.value).draw();
        });


        $(".sub_chk").change(function() {
            if ($(".sub_chk:checked").length > 0) {
                document.getElementById('delete').style.display = "block"
                document.getElementById('export').style.display = "block"
            } else {
                document.getElementById('delete').style.display = "none"
                document.getElementById('export').style.display = "none"
            }
            $("#master").prop("checked", false);
            if ($(".sub_chk:checked").length == $(".checkbox").length) {
                $("#master").prop("checked", false);
            }
        });

        $("#master").change(function() {
            if (this.checked) {
                $(".sub_chk").prop("checked", true);
                document.getElementById('delete').style.display = "block";
                document.getElementById('export').style.display = "block"
            } else {
                $(".sub_chk").prop("checked", false);
                document.getElementById('delete').style.display = "none";
                document.getElementById('export').style.display = "none"
            }
        });
        $("#delete").on('click', function() {
            var id = []
            $('.sub_chk:checked').each(function() {
                id.push($(this).attr('data-id'))
            });
            console.log(id)
            if (id.length <= 0) {
                alert('pas des finances')
            } else {
                $("#deleteModalDocuments").modal("show")
                $(".delete-confirm").on("click", function(e) {
                e.preventDefault()
                e.stopPropagation()
                $("#myLoader").removeClass("d-none")
                var strIds = id.join(",");
                $("#myLoader").removeClass("d-none")
                $.ajax({
                    type: "GET",
                    url: "/suppdoc",
                    data: {
                        strIds: strIds
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == true) {
                            toastr.success("suppression  success !");
                            $("#myLoader").addClass("d-none")
                            location.reload();
                            $(".sub_chk:checked").each(function() {
                                var row = $(this).closest('tr');
                                var rowIndex = finance.row(row).index();
                                finance.row(rowIndex).remove().draw();
                                $('#ActifsCounts').text(finance.rows().count())
                            })
                            document.getElementById('delete').style.display = "none"
                            document.getElementById('export').style.display = "none"
                        }
                    }
                });
            });
            }
        });
        $('#filter-select-date').on('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'All') {
                finance.search('').columns().search('').draw();
                archiveTab.search('').columns().search('').draw();
            } else {
                finance.column(6).search(selectedValue).draw();
                archiveTab.column(6).search(selectedValue).draw();
            }
        });
        $('#filter-select-bien').on('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'All') {
                finance.search('').columns().search('').draw();
                archiveTab.search('').columns().search('').draw();
            } else {
                finance.column(2).search(selectedValue).draw();
                archiveTab.column(2).search(selectedValue).draw();
            }
        });
        $('#type_document').on('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'All') {
                finance.search('').columns().search('').draw();
                archiveTab.search('').columns().search('').draw();
            } else {
                finance.column(3).search(selectedValue).draw();
                archiveTab.column(3).search(selectedValue).draw();
            }
        });
        $('#filter-select-etats').on('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'All') {
                finance.search('').columns().search('').draw();
                archiveTab.search('').columns().search('').draw();
            } else {
                finance.column(5).search(selectedValue).draw();
                archiveTab.column(5).search(selectedValue).draw();
            }
        });
        $('#recherche').on('keyup', function() {
            finance.search(this.value).draw();
            archiveTab.search(this.value).draw();
        });
    })
</script>
