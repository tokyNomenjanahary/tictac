@extends('espace_locataire.index')

@section('locataire-contenue')
    <div class="container">
        <div class="row tete mt-4">
            <div class="col-lg-4 col-sm-4 col-md-4 titre">
                <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i
                    class="fas fa-chevron-left"></i> </a>{{$location->identifiant}}</h3>
            </div>
        </div>
        <div class="row" style="background: white;margin-top:10px;margin-left: var(--bs-gutter-x)\);margin-right: calc(-0.5 * var(---gutter-x));">
            <div class="card" style="margin-top: 5px">
                <div class="card-header"
                    style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                   {{__('location.information')}}
                </div>
                <div class="card-body" style="margin-top: 20px;">
                    <h4>{{$location->identifiant . ' - ' . $location->typelocation->description}}</h4>
                    <h6>{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</h6>
                    <div class="row  mt-2 p-3" style="background: #F5F5F9">
                        <div class="col-2 filtre" style="width: 100px">
                            <span aria-hidden="true" class=" fas fa-coins pull-left circle m-r-10 " style="font-size:50px"></span>
                        </div>
                        <div class="col-10" style="line-height: 10px;margin-top:10px">
                            <p style="text-transform: uppercase">{{__('location.loyers')}}</p>
                            <p style="font-size:20px;" class="text-success">{{$location->Logement->loyer + $location->Logement->charge}} €</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 col-sm-12">
                            <div style="border-bottom: #4C8DCB 1px solid;">
                                <h5><b>Adresse</b></h5>
                            </div>
                            <p>{{$location->Logement->adresse}}</p>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div style="border-bottom: #4C8DCB 1px solid;">
                                <h5><b>{{__('location.proprietaire')}}</b></h5>
                            </div>
                            <p>{{$location->user->first_name}}</p>
                        </div>
                    </div>
                    <div class="row mt-3 ">
                        <div class="col-md-6 col-sm-12" style="line-height: 5px">
                            <div style="border-bottom: #4C8DCB 1px solid;">
                                <h5><b>{{__('location.location')}}</b></h5>
                            </div>
                            <p class="mt-2"><b>Type : </b>{{$location->typelocation->description}}</p>
                            <p><b>Durée : </b>{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</p>
                            {{-- <p><b>Renouvellement : </b>Oui</p> --}}
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div style="border-bottom: #4C8DCB 1px solid;">
                                <h5><b>{{__('location.locataire')}}</b></h5>
                            </div>
                            <a href="" style="margin-top:20px;">{{$location->Locataire->civilite . ' '}}{{$location->Locataire->TenantFirstName . ' '}}{{$location->Locataire->TenantLastName}}</a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 col-sm-12" style="line-height: 5px">
                            <div style="border-bottom: #4C8DCB 1px solid;">
                                <h5><b>{{__('location.loyers')}}</b></h5>
                            </div>
                            <p class="mt-2"><b>{{__('location.loyers')}}   : </b>{{$location->Logement->loyer}} €</p>
                            <p class="mt-2 text-capitalize"><b class="text-capitalize" style="text-transform: capitalize !important">{{__('location.charge')}}   : </b>@if($location->Logement->charge == null) 0.00 € @else {{$location->Logement->charge}} € @endif</p>
                            <p><b class="text-capitalize">{{__('location.payment')}} : </b>{{$location->typepayement->description}}</p>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div style="border-bottom: #4C8DCB 1px solid;">
                                <h5><b>Dépôts</b></h5>
                            </div>
                            <p><b>{{__('location.depotGarantie')}} : </b>@if($location->garantie == null) 0.00 € @else {{$location->garantie}} € @endif</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
