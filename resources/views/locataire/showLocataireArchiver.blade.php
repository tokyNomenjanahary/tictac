<style>
      .dataTables_empty
   {
    display: none !important;
   }
   .dataTables_info{
    display: none !important;
   }
   #showLocataireArchiver_length{
    display: none !important;
   }
</style>
<div class="card">
    <h5 class="card-header">Listes des locataires</h5>
    <div class="table-responsive text-nowrap">
        <table id="showLocataireArchiver" class="table">
            <thead>
            <tr>
                <th>Locataire</th>
                <th>Type</th>
                <th>Bien</th>
                <th>Téléphone</th>
                <th>E-mail</th>
                {{-- <th>Modèle</th> --}}
                <th>Actions</th>
            </tr>
            </thead>
            <tbody class="table-border-bottom-0">
            @forelse($allLocatairesArchives as $allLocatairesArchive)
                <tr id="tr{{$allLocatairesArchive->id}}">
                    <td>
                        @if ($allLocatairesArchive->TenantPhoto)
                        @if(file_exists(storage_path('/uploads/locataire/profil/' . $allLocatairesArchive->TenantPhoto)))
                            <img src="{{'/uploads/locataire/profil/' . $allLocatairesArchive->TenantPhoto}}" alt="Avatar"
                                 class="rounded-circle avatar"/>
                        @endif
                        @else
                            <img src="{{ asset('/Locataire/Profil.jpg') }}" alt="Avatar" class="rounded-circle avatar"/>
                        @endif
                        <strong>{{$allLocatairesArchive->TenantFirstName . ' ' . $allLocatairesArchive->TenantLastName}}</strong>
                    </td>
                    <td>{{$allLocatairesArchive->locataireType}}</td>
                    <td>
                        <a href="{{route('location.create')}}"><button type="button" class="btn btn-xs btn-primary">Créer une location</button></a>
                    </td>
                    <td>{{$allLocatairesArchive->TenantMobilePhone}}</td>
                    <td>{{$allLocatairesArchive->TenantEmail}}</td>
                    {{-- <td>
                        <div class="btn-group" style="position: static !important;">
                            <button
                                    class="btn btn-primary btn-xs dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                            >
                                Modèles
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="fa fa-pencil me-1"></i>Lettre
                                        de demande de justificatif d’assurance</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="fa fa-pencil me-1"></i>Déclaration
                                        au départ du locataire</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="fa fa-pencil me-1"></i>Déclaration
                                        d'entrée du locataire</a></li>
                            </ul>
                        </div>
                    </td> --}}
                    <td>
                        <div class="dropdown" style="position: static !important;">
                            <button type="button" class="btn dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <div class="dropdown-menu" style="z-index: 2000">
                                <a class="dropdown-item"
                                   href="{{ route('locataire.edit', ['id' => $allLocatairesArchive->id]) }}"><i
                                        class="fa fa-pencil me-1"></i>
                                    {{ __('Modifier') }}</a>
                                <a class="dropdown-item" href="{{ route('locataire.fiche', $allLocatairesArchive->id) }}"><i
                                        class="fa fa-eye me-1"></i>
                                    Fiche
                                    locataire</a>
                                {{--                                    <a class="dropdown-item" href=""><i--}}
                                {{--                                            class="fa fa-paper-plane me-1"></i>Inviter</a>--}}
                                <a class="dropdown-item"
                                   href="{{ route('location.message', $allLocatairesArchive->id) }}"><i
                                        class="fa fa-comments me-1"></i>
                                    Envoyer
                                    un
                                    message</a>
{{--                                <a class="dropdown-item" href=""><i--}}
{{--                                            class="fa fa-sign-out-alt me-1"></i>--}}
{{--                                    Terminer--}}
{{--                                    la location</a>--}}
                                {{-- <a class="dropdown-item" href=""><i
                                            class="fas fa-file-invoice me-1"></i>
                                    Solde
                                    locataire</a> --}}
                                <a id="archive-link" class="dropdown-item archive-link" data-archive="1" data-id ="tr{{$allLocatairesArchive->id}}" data-href="{{route('locataire.archive', ['id' => $allLocatairesArchive->id])}}" href="javascript:void(0);" ><i
                                            class="fas fa-archive me-1"></i>
                                    {{__('Désarchiver')}}</a>
                                <hr style="margin:0px;">
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deletelocataire{{$allLocatairesArchive->id}}"><i class="fa-solid fa-trash" style="color:red;"></i> {{__('finance.Supprimer')}}</a>

                            </div>
                        </div>
                    </td>
                    <div class="modal fade" id="deletelocataire{{$allLocatairesArchive->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
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
                                     <a href="{{ route('locataire.delete', $allLocatairesArchive->id) }}" type="button" class="btn btn-danger ">{{__('finance.Supprimer')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </tr>
            @empty
                <tr class="empty">
                    <td class="align-middle text-center" colspan="10">
                        <div  style="margin-top: 15px;">
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
        </table>
    </div>
</div>



