@extends('espace_locataire.index')
<style>
    .tdClass{
        padding-top: 12px !important;
    }
</style>
@section('locataire-contenue')
    <div class="container">
            <div class="card mt-5">
                <h4 class="card-title" style="padding: 10px;background-color: #F2F5F6;">{{__("ticket.infos")}}</h4>
                <div class="card-body">
                   <div class="row">
                     <div class="col-md-2">
                          <span class="badge badge-center rounded-pill bg-primary"
                                style="width:80px;height:80px;font-size: 20px;">{{ strtoupper(substr($proprio->first_name, 0, 2)) }}
                          </span>
                     </div>
                       <div class="col-md-10 pull-left">
                           {{	$proprio->first_name}} <br>
                           {{	$proprio->email}} <br>
                           {{	$proprio->mobile_no}}
                       </div>
                   </div><br><hr>
                     <div>
                         <table width="100%" border="0" cellspacing="0">
                             <thead>
                             <th style="border-bottom: 1px solid gray;">{{__("ticket.Adresse")}}</th>
                             <th></th>
                             <th style="border-bottom: 1px solid gray;">{{__("ticket.Beaux")}}</th>
                             </thead>
                             <tbody >
                             <td class="tdClass">
                                     <h6>{{__("ticket.Adresse")}} : {{$proprio->address_register}}</h6>
                                     <h6>{{__("ticket.ville")}} : {{$proprio->city}}</h6>
                                     <h6>{{__("ticket.postal")}} : {{$proprio->postal_code}}</h6>
                             </td>
                             <td class="tdClass"></td>
                             <td class="tdClass">
                                 <h6> {{__("ticket.Identifiant")}} : {{$proprio->identifiant}}</h6>
                                 <h6>{{__("ticket.Adresse")}} : {{$proprio->adresse}}</h6>
                                 <h6>{{__("ticket.respectivement")}} : {{$proprio->loyer.' et '.$proprio->charge}}</h6>
                             </td>
                             </tbody>
                         </table>
                     </div>
                    <div class="mt-3">
                        <h5 style="border-bottom: 1px solid gray;">{{__('depense.Autres_informations')}}</h5><br>
                        <span>{{__('ticket.Batiment')}} : @if($proprio->batiment){{$proprio->batiment}} @endif </span> <br>
                        <span>{{__('ticket.superfice')}}  : @if($proprio->superficie){{$proprio->superficie}} @endif </span> <br>
                        <span>{{__("ticket.Déscription")}}: @if($proprio->description){{$proprio->description}} @endif </span>  <br>
                        <span>{{__('ticket.chambre')}} :@if($proprio->nbr_chambre){{$proprio->nbr_chambre}} @endif  </span>
                    </div>
                </div>
        </div>
    </div>
@endsection


