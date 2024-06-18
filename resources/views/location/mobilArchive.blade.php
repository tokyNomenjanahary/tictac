<div class="container mobil">
    @foreach ($locations as $key => $location)
            @if($location->archive == 1)
                <div class="card" style="margin-top: 10px">
                    <div class="card-header"style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        <div class="row">
                            <div class="col-12 align-middle">
                                <div class="float-start">
                                    <span style="cursor: pointer" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='tol'>
                                        <p>Type :  {{$location->typelocation->description}}</p>
                                        <p>Payement :  {{$location->typepayement->description}}</p>
                                        <p>Renouvelement :  Oui</p>
                                        <p>loyer : {{$location->loyer_HC}} €</p>
                                        <p>durée : {{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->debut)->format('d/m/Y')}}</p>
                                    </div>"  data-id="{{$location->id}}" class="lolo">{{$location->Logement->identifiant}}</span>
                                </div>
                                <div class="float-end">
                                    {{-- <span class="btn btn-info btn-sm" style="margin-top:-9px;">Active</span> --}}
                                    @if(strtotime($location->fin) < time())
                                        <span class="btn btn-secondary btn-sm" style="font-size: 10px">Inactive</span>
                                    @else
                                        <div class="d-flex">
                                            @if($location->etat == 2 || strtotime($location->fin) < time())
                                            <span style="cursor: pointer;font-size: 10px;margin-left:3px" data-bs-toggle="modal" data-bs-target="#activation_{{$location->id}}" data-location="{{$location->id}}"  class="btn btn-secondary btn-sm" >Inactive</span>
                                            @else
                                                <span class="btn btn-info btn-sm" style="font-size: 10px" data-bs-toggle="modal" data-bs-target="#modalId_{{$location->id}}" data-location="{{$location->id}}">Active</span>
                                            @endif
                                            @if($location->depart == 1)
                                                <span style="cursor: pointer;font-size: 10px;margin-left:3px" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='tol'>
                                                    <p>Départ du locataire déja enregistré</p>
                                                </div>" >départ</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 align-middle">
                                <div class="float-start">
                                    <a href="" style="font-size:10px;color:black"><i class="fa-solid fa-user"></i>&nbsp;&nbsp;{{$location->Locataire->civilite . ' '}}{{$location->Locataire->TenantFirstName . ' '}}{{$location->Locataire->TenantLastName}}</a> <br>
                                    <a href="" style="font-size:10px;color:#4C8DCB">{{$location->loyer_HC}} €</a><br>
                                    <a href="" style="font-size:10px;color:black"><i class="fa-regular fa-calendar-days"></i>&nbsp;&nbsp;De &nbsp; {{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' à '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</a>
                                </div>
                                <div class="float-end">
                                    <button type="button" style="margin-top:20px;margin-rigth:20px" class="btn p-0 dropdown-toggle hide-arrow dropdown-toggle-split" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample_{{$location->Logement->id}}" aria-controls="offcanvasExample"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded" style="font-size: 35px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                        <div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasExample_{{$location->Logement->id}}" aria-labelledby="offcanvasExampleLabel" style="height: 500px;">
                            <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">{{$location->Logement->identifiant}}</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <a class="dropdown-item" href="{{route('edition',$location->encoded_id)}}"><i class="fa fa-pencil me-1"></i>
                                    Modifier</a>
                                    <a class="dropdown-item" href="{{route('ficheLocation',$location->encoded_id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                        <p>Fiche de la location</p>
                                        </div>"><i class="fa fa-eye me-1"></i>
                                        Fiche
                                        location</a>
                                    <a class="dropdown-item" href="{{route('ticket.formulaire',$location->encoded_id)}}"><i
                                            class="fa fa-solid fa-clipboard-check me-1"></i>
                                    Créer un ticket</a>
                                    <a class="dropdown-item" href="{{route('location.message',$location->Locataire->id)}}"><i
                                            class="fa fa-comments me-1"></i>
                                        Envoyer
                                        un
                                        message</a>
                                    <a class="dropdown-item" href="/finance" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                        <p>Historique des payement</p>
                                        </div>"><i class="fa-solid fa-coins"></i>&nbsp;&nbsp;Finance</a>
                                    <a class="dropdown-item" href="{{route('location.regularisation',$location->encoded_id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                        <p>Régularisation des charges récuperable</p>
                                        </div>"><i class="fa-solid fa-calculator"></i>&nbsp;&nbsp;Regularisation des charges</a>
                                        <a class="dropdown-item" href="{{route('location.revision',$location->encoded_id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                            <p>Révision de loyer</p>
                                            </div>"><i class="fa-solid fa-chart-simple"></i>&nbsp;&nbsp;Révision de loyer</a>
                                    <a href="{{route('location.terminer',$location->encoded_id)}}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                        <p>Date de départ, relevé des compteur,...</p>
                                        </div>"><i class="fa fa-sign-out-alt me-1"></i>
                                        Terminer
                                        la location</a>
                                    @if($location->etat !== 2)
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalId_{{$location->id}}" style="cursor: pointer;"><i class="fa-regular fa-circle-xmark"></i>&nbsp;&nbsp;Desactiver</a>
                                    @endif
                                    @if($location->etat == 2)
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#activation_{{$location->id}}" style="cursor: pointer;"><i class="fa-regular fa-circle-xmark"></i>&nbsp;&nbsp;Activer</a>
                                    @endif
                                    @if($location->depart == 1)
                                    <a class="dropdown-item" href="{{route('location.annuler_depart',$location->encoded_id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                        <p>Annuler départ</p>
                                        </div>"><i class="fa-solid fa-arrow-right-to-bracket"></i>&nbsp;
                                        Annuler la depart</a>
                                    @endif
                                    <a class="dropdown-item" href="{{route('location.ajoutCommentaire',$location->encoded_id)}}" style="cursor:pointer">
                                        <i class="fa-solid fa-message" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol align-middle' >
                                        <p>Observation diverses,relevé des compteurs,...</p>
                                        </div>"></i>&nbsp;
                                        Ajouter une commentaire</a>
                                    <a class="dropdown-item archiverSimple"  data-id="{{$location->id}}" id="{{$location->id}}" style="cursor:pointer"><i
                                            class="fas fa-archive me-1"></i>
                                        Archiver</a>
                                    <a class="dropdown-item manala" id="deee" log="{{$location->id}}" style="cursor:pointer"><i
                                            class="fas fa-archive me-1"></i>
                                        Desarchiver</a>
                                    {{-- <a class="dropdown-item " href="{{route('location.revision',$location->encoded_id)}}" style="cursor:pointer"><i
                                            class="fas fa-archive me-1"></i>
                                        Revision du loyer</a> --}}
                                    <a class="dropdown-item "  style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#regeneration_{{$location->id}}">
                                        <i class="fa-solid fa-arrow-rotate-left"></i>&nbsp;
                                        Regeneration du loyer</a>
                                    <a class="dropdown-item" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#assurance_{{$location->id}}"><i class="fa-solid fa-paper-plane"></i>
                                            &nbsp;Rappel assurance locataire	</a>
                                    <hr style="margin:0px;">
                                    <form action="{{route('location.destroy',$location->id)}}" Method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"  class="dropdown-item delete" >
                                            <i class="fa-solid fa-trash" style="color:red;"></i>
                                            Supprimer
                                        </button>
                                    </form>
                            </div>
                        </div>
                        </div>
                </div>
            @endif
    @endforeach
</div>
