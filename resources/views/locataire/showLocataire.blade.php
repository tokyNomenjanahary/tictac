<style>
    #showLocataire_length {
        display: none;
    }

    th {
        color: blue !important;
        font-size: 10px !important;
    }

    td {
        font-size: 13px;
    }

    .dataTables_empty {
        display: none !important;
    }

    .dataTables_info {
        display: none !important;
    }

    .h5 {
        display: none;
    }
</style>
<div class="card mt-2">
    <div class="table-responsive text-nowrap">
        <table id="showLocataire" class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="master" class="checkbox_input align-middle "
                        style="height: 20px;width:20px;"></th>
                    <th>Locataire</th>
                    <th>Type</th>
                    <th>Bien</th>
                    <th>Téléphone</th>
                    <th>E-mail</th>
                    <th>Modèle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($allLocataires as $allLocataire)
                    <tr id="tr{{ $allLocataire->id }}">
                        <td style="width: 5px" class="check">
                            <input type="checkbox" id="selectionner"
                                class="checkbox_input align-middle sub_chk"
                                style="height: 15px;width:15px;" data-id="{{ $allLocataire->id}}">
                        </td>
                        <td>
                            @if ($allLocataire->TenantPhoto)
                                @if (file_exists(storage_path('/uploads/locataire/profil/' . $allLocataire->TenantPhoto)))
                                    <img src="{{ '/uploads/locataire/profil/' . $allLocataire->TenantPhoto }}"
                                        alt="Avatar" class="rounded-circle avatar" />
                                @endif
                            @else
                                <span class="badge badge-center rounded-pill bg-primary"
                                    style="width:40px;height:40px;">{{ strtoupper(substr($allLocataire->TenantFirstName, 0, 2)) }}</span>
                            @endif
                            <strong>{{ $allLocataire->TenantFirstName . ' ' . $allLocataire->TenantLastName }}</strong>
                        </td>
                        <td>{{ $allLocataire->locataireType }}</td>
                        <td>
                            @if ($allLocataire->locations)
                                <a
                                    href="{{ route('proprietaire.detail', $allLocataire->locations->Logement->id) }}">{{ $allLocataire->locations->Logement->identifiant }}</a>
                            @else
                                <a href="{{ route('location.create') }}"><button type="button"
                                        class="btn btn-xs btn-primary">Créer une location</button></a>
                            @endif
                        </td>
                        <td>{{ $allLocataire->TenantMobilePhone }}</td>
                        <td>{{ $allLocataire->TenantEmail }}</td>
                        <td>
                            <div class="btn-group" style="position: static !important;">
                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Modèles
                                </button>
                                <ul class="dropdown-menu" data-url="{{ route('locataire.file-view', $allLocataire->id)}}">
                                    <li><a class="dropdown-item handle-default-action" data-file-type="justificatif-d-assurance"><i class="fas fa-download"></i>  Lettre de demande de justificatif d’assurance</a></li>
                                    <li><a class="dropdown-item handle-default-action" data-file-type="depart-du-locataire"><i class="fas fa-download"></i>  Déclaration au départ du locataire</a></li>
                                    <li><a class="dropdown-item handle-default-action" data-file-type="entree-du-locataire"><i class="fas fa-download"></i>  Déclaration d'entrée du locataire</a></li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown" style="position: static !important;">
                                <button type="button" class="btn dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu" style="z-index: 2000">
                                    <a class="dropdown-item"
                                        href="{{ route('locataire.edit', ['id' => $allLocataire->id]) }}"><i
                                            class="fa fa-pencil me-1"></i>
                                        {{ __('Modifier') }}</a>

                                    @if ($allLocataire->locations)
                                    <a class="dropdown-item" href="{{route('ticket.formulaire',$allLocataire->locations->id)}}"><i
                                        class="fa fa-solid fa-clipboard-check me-1"></i>
                                       Créer un ticket</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('locataire.fiche', $allLocataire->id) }}"><i
                                            class="fa fa-eye me-1"></i>
                                        Fiche
                                        locataire</a>
{{--                                    <a class="dropdown-item" href=""><i--}}
{{--                                            class="fa fa-paper-plane me-1"></i>Inviter</a>--}}
                                    <a class="dropdown-item"
                                        href="{{ route('location.message', $allLocataire->id) }}"><i
                                            class="fa fa-comments me-1"></i>
                                        Envoyer
                                        un
                                        message</a>
                                    <a id="archive-link" class="dropdown-item archive-link"
                                        data-id="tr{{ $allLocataire->id }}" data-archive="0" href="javascript:void(0);"
                                        data-href="{{ route('locataire.archive', ['id' => $allLocataire->id]) }}"><i
                                            class="fas fa-archive me-1"></i>
                                        {{ __('Archiver') }}</a>
                                    <hr style="margin:0px;">
                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deletelocataire{{$allLocataire->id}}"><i class="fa-solid fa-trash" style="color:red;"></i> {{__('finance.Supprimer')}}</a>
                                </div>
                            </div>
                            <div class="modal fade" id="deletelocataire{{$allLocataire->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
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
                                             <a href="{{ route('locataire.delete', $allLocataire->id) }}" type="button" class="btn btn-danger ">{{__('finance.Supprimer')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty">
                        <td class="align-middle text-center" colspan="10">
                            <div style="margin-top: 15px;">
                                <div class="card" style="margin-top: 5px">
                                    <center>
                                        <img src="https://www.lexpressproperty.com/local/cache-gd2/5c/bdc26a1bd667d2212c86fd1c86c3a7.jpg?1647583702"
                                            alt="" style="width:200px;height:200px;" class="img-responsive">
                                    </center>
                                    <br>
                                    <h4 class="text-center">Il n'y a rien par ici...</h4>
                                    <p class="text-center">Cette page permet de gérer les locataires. Vous pouvez les
                                        inviter à s'inscrire au site et avoir accès à leur zone membres dédiée.</p>
                                    <center><button class="btn btn-info" style="margin-bottom: 10px;"><a
                                                href="{{ route('locataire.ajouterColocataire') }}"
                                                style="color: white;">Créer un locataire</a> </button></center>
                                </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>
            <tr style="background-color: #F3F5F6;">
                <td colspan="10">
                    <div class="d-flex  ">

                        <div class="dropdown" style="display: none;margin-left:10px" id="export">
                            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-download"></i>&nbsp;EXPORTER
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{route('locataire.exportLoctaire')}}" style="font-size: 14px"><i class="fa-regular fa-file-excel"></i>&nbsp;Export format Excel</a>
                                <a class="dropdown-item" href="{{route('locataire.exportexportLoctaireODS')}}" style="cursor:pointer;font-size: 14px"><i class="fa-solid fa-file-excel"></i>&nbsp;Export format Open Office</a>
                            </div>
                        </div>

                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="modal fade" id="TelechargerModele" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #F5F5F9">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <div class="d-flex justify-content-center" id="file-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
