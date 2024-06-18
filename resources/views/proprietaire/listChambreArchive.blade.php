<h4>Liste des logements archivées</h4>
<div class="tab-pane active " id="coloc_acif" role="tabpanel" aria-labelledby="home-tab" style="">
    <div class="row" style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;">

        <table class="table" style="margin-bottom:0px;" id="table_logement_archive">

            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check_all_archive" class="align-middle" style="height: 18px;width:18px;">
                    </th>
                    <th>Lot</th>
                    <th></th>
                    <th>Type</th>
                    <th>Batiment</th>
                    <th>Superficie</th>
                    <th>Locataire</th>
                    <th>Loyer</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($listChambreArchive as $logement)
                <tr>
                    <td style="width: 5px" class="check sorting_1">
                        <input type="checkbox" id="{{ $logement->id }}" class="check_logement_archive checkbox_input align-middle" style="height: 15px;width:15px;">
                    </td>
                    <td>
                        <a href="{{route('proprietaire.detail',$logement->id)}}">
                            <div class="d-flex justify-content-center align-items-center circle" >
                                @if (count($logement->files) > 0)
                                    <img class="circle" src="{{ URL::asset('uploads/images_annonces/'.$logement->files[0]->file_name) }}" alt="{{ $logement->files[0]->user_filename }}">
                                @else
                                {{ strtoupper(substr($logement->property_type,0,2)) }}
                                @endif
                            </div>
                        </a>
                    </td>
                    <td>
                        <a href="{{route('proprietaire.detail',$logement->id)}}">
                            <i >{{ $logement->identifiant }}</i><br>
                            <p class="map" style="font-size: 10px"><i class='bx bx-map'></i>{{ $logement->adresse }}</p>
                        </a>
                    </td>
                    <td>
                        {{-- <button style="margin-left: -20px;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne{{$logement->id}}" aria-expanded="true" aria-controls="flush-collapseOne">
                            Chambre<span class="badge bg-success" style="margin-left: 20px;">3</span>
                         </button> --}}
                        @if ($logement->logement_id == 0)
                            <span class="type_chambre" hidden>chambre_individuelle</span>
                            <div class="d-grid gap-2 mx-auto">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal{{$logement->id}}">
                                    chambre individuelle
                                </button>
                            </div>
                        @else
                            <span class="type_chambre" hidden>chambre_avec_parent</span>
                            <div class="d-grid gap-2 mx-auto">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal{{$logement->id}}">
                                    chambre avec parent
                                </button>
                            </div>
                        @endif

                    </td>
                    <td>{{ $logement->batiment }}</td>
                    <td>
                        @if ($logement->superficie)
                            {{ $logement->superficie }} m<sup>2</sup>
                        @endif
                    </td>
                    <td>
                        @if (!$logement->locations->isEmpty())
                            <span class="name_locataire" hidden>log_loue</span>
                            @foreach ($logement->locations as $location)
                                {{ $location->Locataire->TenantFirstName.' '.$location->Locataire->TenantLastName }},<br><br>
                            @endforeach
                        @else
                            <span class="name_locataire" hidden>non_loue</span>
                            <a href="{{ route('locataire.ajouterColocataire') }}" class="btn btn-warning btn-sm">Ajouter</a>
                        @endif
                    </td>
                    <td>{{$logement->loyer}}€</td>
                    <td>
                    <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-horizontal-rounded"></i>
                    </button>

                    <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route('proprietaire.detail',$logement->id)}}">
                                <i class="bx bxl-figma me-1"></i>Detail
                            </a>
                            <a class="dropdown-item" href="{{route('proprietaire.editlogement',$logement->id)}}">
                                <i class="bx bx-edit-alt me-1"></i>Modifier
                            </a>
                            {{-- Verification si le logement est déjà publié,
                                 s'il est deja publier alors le boutton Géner annonce n'est plus affiché  --}}
                            @if ($logement->publish == 0)
                                <a class="dropdown-item" href="{{route('proprietaire.genererAnonceLog',$logement->id)}}">
                                    <i class='bx bxs-megaphone'></i> Génerer l'annonce
                                </a>
                            @endif

                            {{-- <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal2">
                                <i class="fa-solid fa-folder-open"></i> Document
                            </a> --}}
                            <a class="dropdown-item" href="{{route('proprietaire.archiveLogement',base64_encode($logement->id))}}">
                                <i class="fa-solid fa-file-zipper"></i> Désarchiver
                            </a>
                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteLogement{{$logement->id}}">
                                <i class="bx bx-trash me-1"></i> Delete
                            </a>
                    </div>
                    </div>
                    </td>
                    <!-- Modal pour supprimer un logement -->
                    <div class="modal fade" id="deleteLogement{{$logement->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTitleId">Suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Voulez vous vraiment suprimer cette logement de merde?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Annuler</button>
                                {{-- <a href="#" type="button" class="btn btn-danger ">Suprimmer</a> --}}
                                <a href="{{route('proprietaire.deleteLogement',$logement->id)}}" type="button" class="btn btn-danger ">Suprimmer</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- Fin du modal pour supprimer un logement -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3" id="section_btn_logement_archive" style="display: none">
    <button type="button" class="btn btn-success btn-sm" id="export_info_logement_archive"><i class='bx bxs-download'></i> Exporter</button>
    <button type="button" class="btn btn-secondary btn-sm" id="desarchive_multiple_logement"><i class='bx bxs-box'></i> Désarchiver</button>
    <button type="button" class="btn btn-danger btn-sm" id="delete_logement_multiple_archive"><i class="bx bx-trash me-1"></i> Supprimer</button>
</div>
