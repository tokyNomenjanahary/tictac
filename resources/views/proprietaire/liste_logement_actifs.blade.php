<h4>Liste des logements actifs</h4>
<div class="tab-pane active " id="coloc_acif" role="tabpanel" aria-labelledby="home-tab" style="">
    <div class="row" style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;">

        <table class="table table-hover" style="margin-bottom:0px;" id="table_logement_actif">

            <thead>
                <tr>
                    <th class="show-mobile"></th>
                    <th class="show-mobile">Lot</th>
                    <th></th>
                    <th>Type</th>
                    <th>Batiment</th>
                    <th>Superficie</th>
                    <th>Locataire</th>
                    <th>Loyer</th>
                    <th class="show-mobile">Action</th>
                    <th>
                        <input type="checkbox" id="check_all" class="align-middle" style="height: 18px;width:18px;">
                    </th>
                </tr>
            </thead>
            <tbody>

                @foreach ($listLogement as $logement)
                <tr>

                    <td class="show-mobile">
                        @if ($logement->property_type_id != 2)
                            <button class="btn btn-secondary btn-sm chambre " data-id="{{ $logement->id }}"><i class="fa fa-plus"></i></button>
                        @endif
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
                    <td class="show-mobile">
                        <a href="{{route('proprietaire.detail',$logement->id)}}">
                            <i >{{ $logement->identifiant }}</i><br>
                            <p class="map" style="font-size: 10px"><i class='bx bx-map'></i>{{ $logement->adresse }}</p>
                        </a>
                    </td>
                    <td>
                        {{-- <button style="margin-left: -20px;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne{{$logement->id}}" aria-expanded="true" aria-controls="flush-collapseOne">
                            Chambre<span class="badge bg-success" style="margin-left: 20px;">3</span>
                         </button> --}}
                        @if ($logement->property_type == 'chambre')
                            <div class="d-grid gap-2 mx-auto">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal{{$logement->id}}">
                                    chambre
                                </button>
                            </div>
                        @else
                            {{ $logement->property_type }}
                        @endif

                    </td>
                    <td class="text-break">{{ $logement->batiment }}</td>
                    <td>
                        @if ($logement->superficie)
                            {{ $logement->superficie }} m<sup>2</sup>
                        @endif
                    </td>
                    <td class="text-break">
                        @if (!$logement->locations->isEmpty())
                            <span class="name_locataire" hidden>log_loue</span>
                            @foreach ($logement->locations as $location)
                                {{ $location->Locataire->TenantFirstName.' '.$location->Locataire->TenantLastName }},<br><br>
                            @endforeach
                        @else
                            <span class="name_locataire" hidden>non_loue</span>
                            <div class="btn-group">
                                <button
                                        class="btn btn-primary btn-xs dropdown-toggle"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                >
                                    {{__('Ajouter')}}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a data-bs-toggle="modal" data-bs-target="#modalInviteMail{{$logement->id}}" class="dropdown-item"
                                           href="javascript:void(0);">{{__('Insérer un locataire')}}</a></li>
                                    <li><a class="dropdown-item"
                                           href="{{ route('logement.create.location', ['logement_id' => $logement->id])}}">{{__('Créer une location')}}</a></li>
                                </ul>
                            </div>
                            {{--                            <a href="{{ route('locataire.ajouterColocataire') }}" class="btn btn-warning btn-sm">Ajouter</a>--}}
                        @endif
                    </td>
                    <td>{{$logement->loyer}}€</td>
                    <td class="show-mobile">
                        <div class="dropdown pop-up-mobile-no">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{route('proprietaire.detail',$logement->id)}}">
                                    <i class="bx bxl-figma me-1"></i>Détails
                                </a>
                                <a class="dropdown-item" href="{{route('proprietaire.editlogement',$logement->id)}}">
                                    <i class="bx bx-edit-alt me-1"></i>Modifier
                                </a>
                                {{-- Verification si le logement est déjà publié,
                                    s'il est deja publier alors le boutton Géner annonce n'est plus affiché  --}}
                                @if ($logement->publish == 0)
                                    <a class="dropdown-item" href="{{route('proprietaire.genererAnonceLog',$logement->id)}}">
                                        <i class='bx bxs-megaphone'></i> Générer l’annonce
                                    </a>
                                @endif
                                @if ($logement->property_type_id != 2)
                                    <a class="dropdown-item" href="{{route('proprietaire.addLogementEnfant',$logement->id)}}">
                                        <i class="fa-solid fa-door-open"></i> Ajouter une chambre
                                    </a>
                                @endif

                                {{-- <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal2">
                                    <i class="fa-solid fa-folder-open"></i> Document
                                </a> --}}
                                <a class="dropdown-item" href="{{route('proprietaire.archiveLogement',base64_encode($logement->id))}}">
                                    <i class="fa-solid fa-file-zipper"></i> Archiver
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteLogement{{$logement->id}}">
                                    <i class="bx bx-trash me-1"></i> Supprimer
                                </a>
                            </div>
                        </div>
                        <div class="container pop-up-mobile">

                            {{-- <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">Toggle bottom offcanvas</button>

                            <div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
                              <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasBottomLabel">Offcanvas bottom</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                              </div>
                              <div class="offcanvas-body small">
                                ...
                              </div>
                            </div> --}}


                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="offcanvas" data-bs-target="#offcanvas{{$logement->id}}" >
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                        </div>
                    </td>
                    <td style="width: 5px" class="check sorting_1">
                        <input type="checkbox" id="{{ $logement->id }}" class="check_logement checkbox_input align-middle" style="height: 15px;width:15px;">
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
                                    Voulez vous vraiment supprimer ce logement ?
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
                    <!-- Modal pour inserer l'email du locataire -->
                    <div class="modal fade" id="modalInviteMail{{$logement->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"
                                        id="modalCenterTitle">{{__('Insérer l\'email')}}</h4>
                                    <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"
                                    ></button>
                                </div>
                                <div class="modal-body">
                                    <h5 class="modal-title mb-3"
                                        id="modalCenterTitle">{{__('Veuillez saisir l\'email de la personne à inviter')}}</h5>
                                    <form id="form-invite-mail-{{$logement->id}}">
                                        @csrf
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="nameWithTitle"
                                                       class="form-label">{{__('Email')}}</label>
                                                <input
                                                        type="email"
                                                        id="invite-mail-{{$logement->id}}"
                                                        class="form-control"
                                                        placeholder="Entrer l'email"
                                                        data-logement-id="{{$logement->id}}"
                                                        required
                                                />
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                {{('Annuler')}}
                                            </button>
                                            <button type="submit"
                                                    class="btn btn-primary">{{__('Inviter')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- fin du modal inserer email --}}
                </tr>
                <div class="offcanvas offcanvas-bottom offcanvas-locataire" tabindex="-1" id="offcanvas{{$logement->id}}" aria-labelledby="offcanvasExampleLabel" style="height: auto;">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">{{ $logement->identifiant }}</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <a class="dropdown-item" href="{{route('proprietaire.detail',$logement->id)}}">
                            <i class="bx bxl-figma me-1"></i>
                            Detail
                        </a>
                        <a class="dropdown-item" href="{{route('proprietaire.editlogement',$logement->id)}}">
                            <i class="bx bx-edit-alt me-1"></i>Modifier
                        </a>
                        @if ($logement->publish == 0)
                            <a class="dropdown-item" href="{{route('proprietaire.genererAnonceLog',$logement->id)}}">
                                <i class='bx bxs-megaphone'></i> Génerer l'annonce
                            </a>
                        @endif
                        <a class="dropdown-item" href="{{route('proprietaire.addLogementEnfant',$logement->id)}}">
                            <i class='bx bxs-door-open'></i> Ajouer une chambre
                        </a>
                        <a class="dropdown-item" href="{{route('proprietaire.archiveLogement',base64_encode($logement->id))}}">
                            <i class='bx bxs-file-archive'></i></i> Archiver
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);"  data-bs-toggle="modal" data-bs-target="#deleteLogement{{$logement->id}}">
                            <i class="bx bx-trash me-1 danger"></i> Supprimer
                        </a>
                        <a class="dropdown-item modaleIsertLocataire" data-bs-toggle="modal" data-bs-target="#modalInviteMail{{$logement->id}}">
                            <i class='bx bxs-user-plus'></i> Insérer un locataire
                        </a>
                        <a class="dropdown-item" href="{{ route('logement.create.location', ['logement_id' => $logement->id])}}">
                            <i class='bx bxs-user-plus'></i> Créer une location
                        </a>
                    </div>
                </div>
                <!-- Modal pour supprimer un logement -->
                <div class="modal fade" id="deleteLogementMultiple" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTitleId">Suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Voulez vous vraiment supprimer ce logement ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Annuler</button>
                                {{-- <a href="#" type="button" class="btn btn-danger ">Suprimmer</a> --}}
                                <a href="#" type="button" class="btn btn-danger" id="delete_logement_multiple">Suprimmer</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin du modal pour supprimer un logement -->
                @push('js')
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                    <script>
                        $(document).ready(function() {

                            $('.modaleIsertLocataire').on('click', function(){
                                $('.offcanvas-locataire').offcanvas("hide");
                            });

                            $('#form-invite-mail-{{$logement->id}}').submit(function(e) {
                                e.preventDefault();
                                var data = new FormData();
                                data.append("_token", "{{ csrf_token() }}");
                                data.append("email",  $('#invite-mail-{{$logement->id}}').val());
                                data.append("idLogement",  $('#invite-mail-{{$logement->id}}').attr("data-logement-id"));
                                $.ajax({
                                    url: "{{ route('locataire.inviteMail') }}",
                                    type: "POST",
                                    processData: false,
                                    contentType: false,
                                    data: data,
                                    beforeSend: function () {
                                        $('#myLoader').removeClass('d-none')
                                    },
                                    success: function (data) {
                                        $('#myLoader').addClass('d-none')
                                        $('#modalInviteMail{{$logement->id}}').modal('hide')
                                        toastr.success('{{__('E-mail envoyer avec succès')}}');
                                        $('#form-invite-mail-{{$logement->id}}')[0].reset();
                                    },
                                    error: function (xhr) {
                                        $('#myLoader').addClass('d-none')
                                        toastr.error('{{__('Le mail n\'a pas pu etre envoyer')}}');
                                    }
                                });
                            })
                        })
                    </script>
                @endpush
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3" id="section_btn_logement" style="display: none">
    <button type="button" class="btn btn-success btn-sm" id="export_info_logement"><i class='bx bxs-download'></i> Exporter</button>
    <button type="button" class="btn btn-secondary btn-sm" id="archive_multiple_logement"><i class='bx bxs-box'></i> Archiver</button>
    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteLogementMultiple" ><i class="bx bx-trash me-1"></i> Supprimer</button>
</div>
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
