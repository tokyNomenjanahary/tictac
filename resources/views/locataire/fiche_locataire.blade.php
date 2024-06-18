@extends('proprietaire.index')
<style>
     .nav-tabs .nav-item .nav-link:not(.active) {
            background-color: rgb(250, 250, 250);
        }
        .nav-tabs .nav-item .nav-link.active  {
            border-top: 3px solid blue !important;
            border-bottom: 3px solid white !important;
        }
        .nav-tabs .nav-item .nav-link   {
            color: blue !important;
            font-size: 13px !important;

        }
        p{
            font-size: 13px;
            line-height: 12px;
        }
        .tol{
            font-size: 8px;
        }
        @media only screen and (max-width: 600px) {
        .mobile{
            margin-top:20px;
        }
        .mobileH{
            margin-top:20px;
            text-align: center;
        }
        .card{
            box-shadow: none !important;
            padding: 0 !important;
        }
    }
</style>
@section('contenue')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<div class="p-12">
    <header class="bg-white " style="margin:25px auto;margin-left:25px;margin-right: 25px">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" style="padding-bottom: 20px">
            <div class="row">
                <div class="col-md-12 p-3">
                    <div class="float-start">
                        <h3><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>Fiche locataire</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header" style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                            Informations locataire
                        </div>
                        <div class="card-body p-3" style="padding: 10px">
                            <div class="row mt-2">
                                <div class="col-md-3 col-sm-10 text-center">
                                    {{-- <span class="badge badge-center rounded-pill random-bg" style="width:40px;height:40px;">{{strtoupper(substr($location->Locataire->TenantFirstName,0,2))}}</span> --}}
                                    {{-- <span class="badge badge-center rounded-pill " style="width:100px;height:100px;background: #4C8DCB;font-size:40px">{{strtoupper(substr($locataire->TenantLastName,0,2))}}</span>
                                    --}}
                                    @if($locataire->TenantPhoto)
                                        @if(file_exists(storage_path('/uploads/locataire/profil/' . $locataire->TenantPhoto)))
                                            <img src="{{'/uploads/locataire/profil/' . $locataire->TenantPhoto}}" style="width:100px;height:100px" alt="Avatar"
                                                class="rounded-circle avatar"/>
                                        @endif
                                    @else
                                        {{-- <span class="badge badge-center rounded-pill bg-primary" style="width:40px;height:40px;">{{strtoupper(substr($allLocataire->TenantFirstName,0,2))}}</span> --}}
                                        {{-- <img src="{{ asset('/Locataire/Profil.jpg') }}" alt="Avatar" class="rounded-circle avatar"/> --}}
                                        <span class="badge badge-center rounded-pill " style="width:100px;height:100px;background: #4C8DCB;font-size:40px">{{strtoupper(substr($locataire->TenantLastName,0,2))}}</span>
                                        {{-- <span class="badge badge-center rounded-pill random-bg" style="width:40px;height:40px;">{{strtoupper(substr($location->Locataire->TenantFirstName,0,2))}}</span> --}}
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12 ms-1 ">
                                    <h4 class="mobileH">{{$locataire->TenantFirstName . ' ' . $locataire->TenantLastName}}</h4>
                                    <p> <u>Télephone</u> : {{$locataire->TenantMobilePhone}}</p>
                                    <p> <u>Email</u> : {{$locataire->TenantEmail}}</p>
                                </div>
                            </div>
                            <div class="row mt-3 ">
                                <div class="col-md-6 col-sm-12">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>Bien loué</b></h5>
                                    </div>
                                    @if($location)
                                        <p class="mt-2">{{$location->Logement->identifiant .' - '. $location->typelocation->description}}</p>
                                        <p>{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}} </p>
                                        <p>{{$location->Logement->adresse}}</p>
                                        {{-- <div class="d-flex">
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
                                        </div> --}}
                                    @else
                                        <p class="mt-2">Pas d'information</p>
                                    @endif
                                    {{-- <p>{{$location->Logement->adresse}}</p> --}}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>Adresse du locataire</b></h5>
                                    </div>
                                    <p class="mt-2">{{$locataire->TenantAddress}}</p>
                                    <p>{{$locataire->TenantCity}}</p>
                                    <p>{{$locataire->TenantZip}}</p>

                                </div>
                            </div>
                            <div class="row mt-3 ">
                                <div class="col-md-6 col-sm-12 " style="line-height: 5px">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>Situation professionnelle</b></h5>
                                    </div>
                                    {{-- <p class="mt-2"><b>Type : </b>{{$location->typelocation->description}}</p>
                                    <p><b>Durée : </b>{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</p> --}}
                                    {{-- <p><b>Renouvellement : </b>Oui</p> --}}
                                    <p class="mt-2">Proffession : {{$locataire->TenantProfession}}</p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>Adresse professionnelle</b></h5>
                                    </div>
                                    <p class="mt-2"> Pas d'information</p>
                                    {{-- <a href="" style="margin-top:20px;">{{$location->Locataire->civilite . ' '}}{{$location->Locataire->TenantFirstName . ' '}}{{$location->Locataire->TenantLastName}}</a> --}}
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 col-sm-12 " style="line-height: 5px">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>Banque</b></h5>
                                    </div>
                                    <p class="mt-2"> Pas d'information</p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>Notes</b></h5>
                                    </div>
                                    <p class="mt-2"> Pas d'information</p>
                                    {{-- <p><b>Dépôt de garantie : </b>@if($location->garantie == null) 0.00 € @else {{$location->garantie}} € @endif</p> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mobile">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">FINANCE</button>
                      </li>
                      @if($location)
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">BAUX</button>
                      </li>
                      @endif
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">ACTIVITES</button>
                      </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" style="padding: 0px;">
                      <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card-header"
                            style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                            Finances
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between" style="border-bottom: rgb(34, 239, 16) 2px solid !important">
                                        <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0 p-2 mb-0">
                                            <p class="m-0 me-2 w-auto">REVENUS</p>
                                        </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h4 class="text-success">{{$revenue}} €</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header  py-2 px-3 d-flex align-items-center justify-content-between" style="border-bottom: orangered 2px solid !important">
                                            <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0 p-2">
                                                <p class="m-0 me-2 w-auto mb-0">EN ATTENTE</p>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h4 style="color: orangered">{{$en_attente}} €</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        @if($location)
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{route('voir_finance',['id' => $location->id])}}" class="btn btn-primary btn-block shadow-none" style="width: 100%;background: #F5F5F9;color:#4C8DCB">finance</a>
                                </div>
                                <div class="col-6">
                                    <a href="{{route('voir_bilan',['id' => $location->id])}}" class="btn btn-primary btn-block shadow-none" style="width: 100%;background: #F5F5F9;color:#4C8DCB">bilan</a>
                                </div>
                            </div>
                        @endif
                      </div>
                      @if($location)
                      <div class="tab-pane " id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            {{-- <div class="card-header"
                            style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                            Baux
                            </div> --}}
                            <div class="card-body" style="padding:12px;" >
                               <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body p-3  ">
                                                <div class="d-flex justify-content-between">
                                                    <a href="">{{$location->Logement->identifiant}}</a>
                                                    <p class="">
                                                        @if($location->etat == 2 || strtotime($location->fin) < time())
                                                            <span style="cursor: pointer;font-size: 10px;margin-left:3px" data-bs-toggle="modal" data-bs-target="#activation_{{$location->id}}" data-location="{{$location->id}}"  class="btn btn-secondary btn-sm" >Inactive</span>
                                                        @else
                                                            <span class="btn btn-info btn-sm" style="font-size: 10px" data-bs-toggle="modal" data-bs-target="#modalId_{{$location->id}}" data-location="{{$location->id}}">Active</span>
                                                        @endif
                                                        @if($location->depart == 1)
                                                            <span style="cursor: pointer;font-size: 10px;margin-left:3px" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-html="true">départ</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="row">
                                                    <p>{{$location->Logement->adresse}}</p>
                                                    <p>{{$location->typelocation->description}}</p>
                                                    <p>{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}} </p>
                                                    <p class="mt-2"><b>Loyer   : </b>{{$location->Logement->loyer}} €</p>
                                                    <p class=""><b>Charge   : </b>@if($location->Logement->charge == null) 0.00 € @else {{$location->Logement->charge}} € @endif</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                               <div class="row mt-2">
                                    <div class="col-12">
                                        <a href="/location" class="btn btn-primary btn-block shadow-none" style="width: 100%;background: #F5F5F9;color:#4C8DCB">Tout afficher</a>
                                    </div>
                                </div>
                            </div>

                      </div>
                      @endif
                      <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                        <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        Historique
                        </div>
                        <div class="card-body" >
                            <div class="card text-start mt-1" >
                                <div class="card-body">
                                    <p><i class="fa-solid fa-circle-check " style="color:greenyellow" ></i> Crée le : {{\Carbon\Carbon::parse($locataire->created_at)->format('d/m/Y H:i:s')}}</p>
                                    @if($locataire->date_archive == null) @else <p><i class="fa-solid fa-box-archive"></i>&nbsp;Archiver le : {{\Carbon\Carbon::parse($locataire->date_archive)->format('d/m/Y H:i:s')}}</p> @endif
                                    @if($locataire->date_desarchive == null) @else <p><i class="fa-solid fa-boxes-packing"></i>&nbsp;Désarchiver le : {{\Carbon\Carbon::parse($locataire->date_desarchive)->format('d/m/Y H:i:s')}}</p> @endif
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
@endsection
