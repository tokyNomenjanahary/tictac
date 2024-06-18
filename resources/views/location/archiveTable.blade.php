<style>
    #archiveTab .check{
        display: none;
    }
    #archiveTab .sorting_1{
        display: none;
    }
</style>
<div class="row filtre" style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;padding:10px;">
    <table class="table table-hover " id="archiveTab" style="margin-bottom:0px;width:100%">
        <thead>
            <tr>
                <th class="check"><input type="checkbox" id="master" class="checkbox_input align-middle "
                    style="height: 20px;width:20px;"></th>
                <th>Bien</th>
                <th>type</th>
                <th>Locataire</th>
                <th>Loyer</th>
                <th>Durée</th>
                <th>Etat</th>
                <th>Actions</th>
                {{-- <th style="display: none">Debut</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($locations as $key => $location)
                @if ($location->archive == 1)
                    <tr id="tr_{{$location->id}}">
                        <td style="width: 5px;">
                            <input type="checkbox" id="selectionner" class="checkbox_input align-middle sub_chk"
                                        style="height: 15px;width:15px;" data-id="{{$location->id}}">
                        </td>
                        <td>


                                <div class="align-middle">
                                        <span style="cursor: pointer" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='tol'>
                                            <p>Type :  {{$location->typelocation->description}}</p>
                                            <p>Payement :  {{$location->typepayement->description}}</p>
                                            <p>Renouvelement :  Oui</p>
                                            <p>loyer : {{$location->loyer_HC}} €</p>
                                            <p>durée: {{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->debut)->format('d/m/Y')}}</p>
                                        </div>"  data-id="{{$location->id}}" class="lolo">{{$location->Logement->identifiant}}</span>
                                </div>
                                <span>{{$location->Logement->adresse}}</span>

                        </td>
                        <td>{{$location->typelocation->description}}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-1">
                                    <span class="badge badge-center rounded-pill random-bg" style="width:40px;height:40px;">{{strtoupper(substr($location->Locataire->TenantFirstName,0,2))}}</span>
                                </div>
                                <div>
                                    <a href="">{{$location->Locataire->civilite . ' '}}{{$location->Locataire->TenantFirstName . ' '}}{{$location->Locataire->TenantLastName}}</a>
                                </div>
                            </div>
                        </td>
                        <td>{{$location->loyer_HC}} €</td>
                        <td>{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</td>

                        <td scope="row" class="" data-column-name=""  class="@if(strtotime($location->fin) < time()) disabled-row @else @endif">
                            @if(strtotime($location->fin) < time())
                                <span class="btn btn-secondary btn-sm" style="font-size: 10px">Inactive</span>
                            @else
                                <div class="d-flex">
                                    @if($location->etat == 2 || strtotime($location->fin) < time())
                                    <span style="cursor: pointer;font-size: 10px;margin-left:3px" class="btn btn-secondary btn-sm" >Inactive</span>
                                    @else
                                        <span class="btn btn-info btn-sm" style="font-size: 10px" >Active</span>
                                    @endif
                                    @if($location->depart == 1)
                                        <span style="cursor: pointer;font-size: 10px;margin-left:3px" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='tol'>
                                            <p>Départ du locataire déja enregistré</p>
                                        </div>" >départ</span>
                                    @endif
                                </div>
                            @endif

                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow dropdown-toggle-split"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu ">
                                    {{-- <a class="dropdown-item" href="{{route('edition',$location->id)}}"><i class="fa fa-pencil me-1"></i>
                                        Modifier</a>
                                    <a class="dropdown-item" href=""><i class="fa fa-eye me-1"></i>
                                        Fiche
                                        locataire</a>
                                    <a class="dropdown-item" href=""><i
                                            class="fa fa-comments me-1"></i>
                                        Envoyer
                                        un
                                        message</a>
                                    <a class="dropdown-item" href=""><i
                                            class="fa fa-sign-out-alt me-1"></i>
                                        Terminer
                                        la location</a>
                                    <a class="dropdown-item" href=""><i
                                            class="fas fa-file-invoice me-1"></i>
                                        Solde
                                        locataire</a>
                                    <a class="dropdown-item" href=""><i
                                            class="fa fa-money-bill-alt me-1"></i>Finances</a> --}}
                                    <a class="dropdown-item archiverSimple"  data-id="{{$location->id}}" id="{{$location->id}}" style="cursor:pointer"><i
                                        class="fas fa-archive me-1"></i>
                                    Archiver</a>
                                    <a class="dropdown-item  desa " log="{{$location->id}}"   data-id="{{$location->id}}" style="cursor:pointer"><i
                                            class="fas fa-archive me-1"></i>
                                        Désarchiver</a>
                                    <a class="dropdown-item "  style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#confirmationModal{{$location->id}}">
                                        <i class="fa-solid fa-trash" style="color:red;"></i>{{__('location.supprimer')}}</a>
                                </div>
                            </div>
                            <div class="modal fade" id="confirmationModal{{$location->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalTitleId">{{('location.suppression')}}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{__('location.ModalSuppresion')}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                            <a href="{{route('location.delete',$location->id)}}" type="button" class="btn btn-danger">{{__('finance.Supprimer')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        {{-- <td style="display: none">{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y')}}</td> --}}
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
<div class="row" style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;padding:10px;">
    @include('location.mobilArchive')
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

</script>
