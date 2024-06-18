@extends('proprietaire.index')

@section('contenue')
    <style>
        /*  */
        #active_records {
            background-color: rgba(114, 163, 51, 0.14) !important;
            border: 1px solid rgba(114, 163, 51, 0.6);
            border-right: 1px solid rgba(114, 163, 51, 0.4);
        }

        #archived_records {
            /* background-color: rgba(114, 163, 51, 0.14) !important; */
            border: 1px solid rgba(114, 163, 51, 0.6);
            border-right: 1px solid rgba(114, 163, 51, 0.4);
        }
        div.dataTables_wrapper div.dt-row {
            position: static !important;
        }
        th {
            color: blue !important;
            font-size: 10px !important;
        }
        td{
            font-size:13px;
        }
        p{
            line-height: 8px;
            font-size: 12px;
        }
        .tol{
            padding-top: 10px;

        }
        .tol p{
            font-size: 10px;
        }
        .lolo:hover{
            text-decoration: underline;
            color: #4C8DCB;
        }
        .table-responsive {
            z-index: 1;
        }
        .dataTables_length{
            display: none;
        }

        .disabled-row{
            opacity: 0.5;
        }

        @media only screen and (min-width: 600px) {
            .mobil{
                display: none;
            }
        }
        @media only screen and (max-width: 600px) {
            .filtre{
                display: none;
            }


            /* .mobil_reg{
                c
            } */
            .recap {
                margin-top: 10px;

            }
            .tete{
                display: flex;
            }
            /* .arh{
                margin-left: 13%;
            } */
            .nouv{
                margin-top:-85px;
                margin-left: 0px;
            }
            .filtre{
                margin-top: 20px;
            }
            #safidy{
                display: none;
            }
            #safidy_paginate{
                display: none;
            }
            #safidy_info{
                display: none;
            }
            #offcanvas {
                height: 800px;
            }

        }

        .rating {
            font-size: 3rem;
            cursor: pointer;
        }

        .star {
            display: inline-block;
            margin-right: 5px;
            color: gray;
        }

        .star.selected {
            color: orange; /* Couleur des étoiles sélectionnées */
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <div class="container" >
        <?php
            $userId = auth()->user()->id; // Obtenez l'ID de l'utilisateur connecté
            $count = \App\Document_caf::where('etat', 1)
                ->where('cree_par', 2)
                ->with('location')
                ->whereHas('location', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->get();
        ?>
        {{-- <div class="row" style="margin-top: 30px;"> --}}
            <div class="row tete mt-4">
                <div class="col-lg-4 col-sm-4 col-md-4 titre">
                    <h3 class="page-header page-header-top">{{__('location.location')}}</h3>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 arh">
                    <ul class="nav nav-tabs text-center" id="myTab" role="tablist" style="border: none;">
                        <li class="nav-item col" role="presentation" style="cursor: pointer">
                            <a class="nav-link active" style="border:1px solid #EBF2E2;color:blue;" id="home-tab"
                                data-bs-toggle="tab" data-bs-target="#coloc_acif" type="button" role="tab"
                                aria-controls="home" aria-selected="true"><i class="fa fa-check m-r-5"></i> Actifs <span
                                    class="badge bg-primary" id="ActifsCounts">0</span>
                            </a>
                        </li>
                        <li class="nav-item col" role="presentation">
                            <a class="nav-link" style="border:1px solid #f9f9f9;color:blue;" id="profile-tab"
                                data-bs-toggle="tab" data-bs-target="#coloc_archi" type="button" role="tab"
                                aria-controls="profile" aria-selected="false"> <i class="fa fa-folder-open m-r-5"></i> Archives
                                <span class="badge bg-primary" id="ArchiveCounts">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 nouv text-end">
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle @if(count($locations) < 1) animate__flipInX animate__animated  animate__infinite  @endif" data-bs-toggle="dropdown">
                            {{__('location.nouveaux')}}
                        </button>

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('location.create') }}"><i class="fa fa-plus-circle"></i> {{__('location.nouveaux')}} </a>
                            <a class="dropdown-item" href="{{route('location.import')}}" style="cursor:pointer"><i class="fa fa-cloud-upload me-1"></i> {{__('location.importer')}}</a>
                        </div>
                    </div>
                </div>
            </div>

        {{-- </div> --}}
        @if(count($count) > 0)
            <div class="row p-3">
                <div class="alert m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7; border-left: 4px solid rgb(58,135,173);">
                    <p style="margin-top:10px;font-size:14px !important;">Vous avez recu une document CAF de la part de votre locataire; des location suivant :</p>
                    @foreach ($count as $loc)
                        <a class="mt-3" href="{{route('location.documentCAF',$loc->location->id)}}">-&nbsp;&nbsp;{{$loc->location->identifiant}}</a> <br>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="card mb-4 p-3 filtre">
            <div>
              <p><span class="h5">Filter</span> <span>{{__('location.textFiltre')}}</span></p>
            </div>
            <div class="row">

              {{-- <div class="col-2">
                <div>
                    <select id="filter-select-date" class="form-select form-select-sm">
                        <option value="All" selected>Date</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="yesterday">Hier</option>
                        <option value="this_month">Ce mois</option>
                        <option value="last_7_days">Dernier 7 jours</option>
                        <option value="last_30_days">Dernier 30 jours</option>
                        <option value="last_month">Mois dernier</option>
                        <option value="last_3_months">Trois derniers mois</option>
                    </select>
                </div>
              </div> --}}
              <div class="col-2">
                <div>
                  <select id="filter-select-bien" class="form-select form-select-sm">
                    <option value="All">{{__('location.ToutBien')}}</option>
                    @foreach ($logements as $logement)
                        <option value="{{$logement->identifiant}}">{{$logement->identifiant}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div>
                  <select id="filter-select-type" class="form-select form-select-sm">
                    <option value="All">{{__('location.ToutType')}}</option>
                    @foreach ($type_locations as $type_location)
                        <option value="{{ __('location.'.$type_location->description)}}">{{ __('location.'.$type_location->description)}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div>
                  <select id="filter-select-etat" class="form-select form-select-sm">
                    <option value="All">{{__('location.ToutEtat')}}</option>
                    <option value="active">active</option>
                    <option value="inactive">inactive</option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div>
                  <select id="filter-select-fin" class="form-select form-select-sm">
                    <option value="All" selected>{{__('location.ToutFinBail')}}</option>
                    <option value="0-30">0-30 jours</option>
                    <option value="30-60">30-60 jours</option>
                    <option value="60-90">60-90 jours</option>
                    <option value="90+">90+ jours</option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div>
                  <input id="recherche" class="form-control form-control-sm" type="text" placeholder={{__('location.recherche')}}>
                </div>
              </div>
            </div>
        </div>

        <div class="row recap">
            <div class="col-md-12 col-lg-3 col-xl-4 col-sm-6 order-0 mb-4">
                <div class="card h-100">
                  <div class="card-body p-3">
                    <div class="row ">
                        <div class="col-3 p-3 text-center ml-2 rounded-pill filtre" style="background: #EBF2E2;border-radius: 100%;margin-left: 12px;height: 70px;">
                            <div class="p-1">
                                <i class="menu-icon fa fa-key" style="font-size: 35px;"></i>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-2">
                            <h6 style="margin-top: 5px;">ACTIVE</h6>
                            <h2 class="text-success" style="margin-top: -8px;">{{count($count_active)}}</h2>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-3 col-xl-4 order-0 mb-4">
                <div class="card h-100">
                  <div class="card-body p-3">
                    <div class="row">
                        <div class="col-3 p-3 text-center ml-2 rounded-pill filtre" style="background: #EBF2E2;border-radius: 100%;margin-left: 12px;height: 70px;">
                            <div class="p-1">
                                <i class="fa-solid fa-coins" style="font-size: 35px;"></i>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-2">
                            <h6 style="margin-top: 5px;text-transform: uppercase">{{__('location.loyers')}}</h6>
                            <h2 class="" style="margin-top: -8px;">0.00 €</h2>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-3 col-xl-4 order-0 mb-4">
                <div class="card h-100">
                  <div class="card-body p-3">
                    <div class="row align-content-sm-between">
                        <div class="col-3 p-3 text-center ml-2 rounded-pill filtre" style="background: #EBF2E2;border-radius: 100%;margin-left: 12px;height: 70px;">
                            <div class="p-1">
                                <i class="fa-solid fa-hand-holding-dollar" style="font-size: 35px;"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-2 ">
                            <h6 style="margin-top: 5px;">{{__('location.depot')}}</h6>
                            <h2 class="" style="margin-top: -8px;">@if($depot == 0) 0.00 € @else {{ number_format($depot, 0, ',', '.') }}  € @endif</h2>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>

        <div class="tab-content" style="padding-left: 12px;margin-top:-38px;padding-right: 12px;">
            <div class="tab-pane active " id="coloc_acif" role="tabpanel" aria-labelledby="home-tab" style="">
                <div class="row" style="background-color:white;margin-top: 20px;border:1px solid #eeeeee;">
                        <table class="table table-hover " id="safidy" style="margin-bottom:0px;">
                            <thead>
                                <tr>
                                    <th id="in"><input type="checkbox" id="master" class="checkbox_input align-middle "
                                        style="height: 20px;width:20px;"></th>
                                    <th>Bien</th>
                                    <th>type</th>
                                    <th style="width: 200px">{{__('location.locataire')}}</th>
                                    <th>{{__('location.loyers')}}</th>
                                    <th>{{__('location.dureLocation')}}</th>
                                    <th>{{__('location.modele')}}</th>
                                    <th>Etat</th>
                                    <th>Actions</th>
                                    <th style="display: none">fin</th>
                                    <th style="display: none">debut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($locations as $key => $location)
                                    @if ($location->archive == '0')
                                        <tr id="tr_{{$location->id}}" >
                                            <td style="width: 5px" class="check">
                                                <input type="checkbox" id="selectionner" class="checkbox_input align-middle sub_chk"
                                                            style="height: 15px;width:15px;" data-id="{{$location->id}}">
                                            </td>
                                            <td class="@if(strtotime($location->fin) < time() || $location->etat == 2) disabled-row @else @endif">
                                                <div class="align-middle">
                                                        <a href="/detail/{{$location->Logement->id}}" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='tol'>
                                                            <p>Type :  {{$location->typelocation->description}}</p>
                                                            <p>Payement :  {{$location->typepayement->description}}</p>
                                                            {{-- <p>Renouvelement :  Oui</p> --}}
                                                            <p>loyer : {{$location->loyer_HC}} €</p>
                                                            <p>durée: {{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</p>
                                                        </div>"  data-id="{{$location->id}}" class="lolo">{{$location->Logement->identifiant}}</a>
                                                </div>
                                                <span>{{$location->Logement->adresse}}</span><br>
                                                @for($i = 0; $i < count($etat_finance); $i++)
                                                    @if ($location->id == $etat_finance[$i][1])
                                                        <span class="text-danger"><i class="fa fa-exclamation-circle text-danger m-r-5"></i>&nbsp;Loyer impayé</span>
                                                    @endif
                                                @endfor
                                            </td>
                                            {{--{{ __('location.'.$location->typelocation->description)}}--}}
                                            <td class="@if(strtotime($location->fin) < time() || $location->etat == 2) disabled-row @else @endif">{{ __('location.'.$location->typelocation->description)}}</td>
                                            <td class="@if(strtotime($location->fin) < time() || $location->etat == 2) disabled-row @else @endif">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-1">
                                                        @if($location->Locataire->TenantPhoto)
                                                            @if(file_exists(storage_path('/uploads/locataire/profil/' . $location->Locataire->TenantPhoto)))
                                                                <img src="{{'/uploads/locataire/profil/' . $location->Locataire->TenantPhoto}}" alt="Avatar"
                                                                    class="rounded-circle avatar"/>
                                                            @endif
                                                        @else
                                                            {{-- <span class="badge badge-center rounded-pill bg-primary" style="width:40px;height:40px;">{{strtoupper(substr($allLocataire->TenantFirstName,0,2))}}</span> --}}
                                                            {{-- <img src="{{ asset('/Locataire/Profil.jpg') }}" alt="Avatar" class="rounded-circle avatar"/> --}}
                                                            <span class="badge badge-center rounded-pill random-bg" style="width:40px;height:40px;">{{strtoupper(substr($location->Locataire->TenantFirstName,0,2))}}</span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <a href="/ficheLocataire/{{$location->Locataire->id}}">{{$location->Locataire->civilite . ' '}}{{$location->Locataire->TenantFirstName . ' '}}{{$location->Locataire->TenantLastName}}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="@if(strtotime($location->fin) < time() || $location->etat == 2) disabled-row @else @endif">{{number_format($location->loyer_HC, 0, ',', '.')}} €</td>
                                            <td class="@if(strtotime($location->fin) < time() || $location->etat == 2) disabled-row @else @endif">{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown" style="font-size:12px;background: #EBF2E2;">
                                                        Modèle
                                                    </button>
                                                    <div class="dropdown-menu" style="font-size:14px;">

                                                          @if(count($location->garants)>1)
                                                        <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#caution_{{$location->id}}"><i class="fa fa-file"></i> Modèle caution solidaire </a>
                                                          @endif

                                                        <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#contrat_{{$location->id}}"><i class="fa fa-file"></i>{{__('location.modelePreRemplit')}} </a>
                                                        <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#justificatif_assurance_{{$location->id}}"><i class="fa fa-file"></i> {{__('location.demandeAssurance')}} </a>
                                                        <a class="dropdown-item" href="{{route('location.documentCAF',$location->id)}}" ><i class="fa fa-file"></i> Document CAF</a>
                                                        {{-- <a class="dropdown-item" href=""><i class="fa fa-file"></i> Lettre de demande de justificatif d’assurance</a> --}}
                                                        <a class="dropdown-item" href="{{ route('proprietaire.inventaireLocation', ['location_id' => $location->id]) }}"><i class="fa-solid fa-plus"></i></i>{{__('location.creeInventaire')}}</a>
                                                        <a class="dropdown-item" href="{{ route('proprietaire.ajout-etat-location', ['location_id' => $location->id,'type_id' => 1]) }}"><i class="fa-solid fa-plus"></i></i> {{__('location.creeEtatEntre')}}</a>
                                                        <a class="dropdown-item" href="{{ route('proprietaire.ajout-etat-location', ['location_id' => $location->id,'type_id' => 2]) }}"><i class="fa-solid fa-plus"></i></i> {{__('location.creeEtatSortie')}}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td scope="row" class="" data-column-name=""  class="@if(strtotime($location->fin) < time()) disabled-row @else @endif">
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
                                                                <p>{{__('location.textDepart')}}</p>
                                                            </div>" >{{__('location.depart')}}</span>
                                                        @endif
                                                    </div>
                                                @endif

                                            </td>

                                            <td class="dropdown">
                                                <div class="dropdown dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" id="but">
                                                        <i class="bx bx-dots-horizontal-rounded" ></i>
                                                    </button>
                                                    <div class="dropdown-menu" style="font-size:14px">
                                                        <a class="dropdown-item" href="{{route('edition',$location->encoded_id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                                            <p>{{__('location.textModifier')}}</p>
                                                            </div>"><i class="fa fa-pencil me-1"></i>
                                                            {{__('location.modifier')}}</a>
                                                        <a class="dropdown-item" href="{{route('ficheLocation',$location->encoded_id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                                            <p>{{__('location.textFiche')}}</p>
                                                            </div>"><i class="fa fa-eye me-1"></i>
                                                            {{__('location.ficheLocation')}}</a>
                                                        <a class="dropdown-item" href="{{route('ticket.formulaire',$location->encoded_id)}}"><i
                                                                class="fa fa-solid fa-clipboard-check me-1"></i>
                                                           {{__('location.creeTicket')}}</a>
                                                        <a class="dropdown-item" href="{{route('location.message',$location->Locataire->id)}}"><i
                                                                class="fa fa-comments me-1"></i>
                                                            {{__('location.envoiMessage')}}</a>
                                                        <a class="dropdown-item" href="/finance" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                                            <p>{{__('location.textFinance')}}</p>
                                                            </div>"><i class="fa-solid fa-coins"></i>&nbsp;&nbsp;Finance</a>
                                                        <a class="dropdown-item" href="{{route('location.regularisation',$location->encoded_id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                                            <p>{{__('location.textRegularisation')}}</p>
                                                            </div>"><i class="fa-solid fa-calculator"></i>&nbsp;&nbsp;{{__('location.regularisation')}}</a>
                                                        <a class="dropdown-item" href="{{route('location.revision',$location->encoded_id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                                                <p>{{__('location.RevisionLoyer')}}</p>
                                                                </div>"><i class="fa-solid fa-chart-simple"></i>&nbsp;&nbsp;{{__('location.RevisionLoyer')}}</a>
                                                        <a href="{{route('location.terminer',$location->encoded_id)}}" class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                                            <p>{{__('location.textDropDepart')}}</p>
                                                            </div>"><i class="fa fa-sign-out-alt me-1"></i>
                                                            {{__('location.terminer')}}</a>
                                                        @if($location->etat !== 2)
                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalId_{{$location->id}}" style="cursor: pointer;"><i class="fa-regular fa-circle-xmark"></i>&nbsp;&nbsp;{{__('location.desactiver')}}</a>
                                                        @endif
                                                        @if($location->etat == 2)
                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#activation_{{$location->id}}" style="cursor: pointer;"><i class="fa-regular fa-circle-xmark"></i>&nbsp;&nbsp;Activer</a>
                                                        @endif
                                                        @if($location->depart == 1)
                                                        <a class="dropdown-item" href="{{route('location.annuler_depart',$location->encoded_id)}}" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol' >
                                                            <p>{{__('location.annulerDepart')}}</p>
                                                            </div>"><i class="fa-solid fa-arrow-right-to-bracket"></i>&nbsp;
                                                            {{__('location.annulerDepart')}}</a>
                                                        @endif
                                                        <a class="dropdown-item" href="{{route('location.ajoutCommentaire',$location->encoded_id)}}" style="cursor:pointer">
                                                            <i class="fa-solid fa-message" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" title="<div class='tol align-middle' >
                                                            <p>{{__('location.textCommentaireDrop')}}</p>
                                                            </div>"></i>&nbsp;
                                                            {{__('location.ajoutCommentaire')}}</a>
                                                            @if ($location->Locataire->user_account_id != 0)
                                                                @if (\Carbon\Carbon::now() > $location->fin)
                                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#note_locataire{{$location->id}}" type="" class="dropdown-item">
                                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                                        Note locataire
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        <a class="dropdown-item archiverSimple"  data-id="{{$location->id}}" id="{{$location->id}}" style="cursor:pointer"><i
                                                                class="fas fa-archive me-1"></i>
                                                            Archiver</a>
                                                        <a class="dropdown-item manala" id="deee" log="{{$location->id}}" style="cursor:pointer"><i
                                                                class="fas fa-archive me-1"></i>
                                                            Desarchiver</a>
                                                        <a class="dropdown-item "  style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#regeneration_{{$location->id}}">
                                                            <i class="fa-solid fa-arrow-rotate-left"></i>&nbsp;
                                                            {{__('location.regeneration')}}</a>
                                                        <a class="dropdown-item" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#assurance_{{$location->id}}"><i class="fa-solid fa-paper-plane"></i>
                                                                &nbsp;{{__('location.rappelAssurance')}}</a>
                                                        <a class="dropdown-item "  style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#confirmationModal{{$location->id}}">
                                                            <i class="fa-solid fa-trash" style="color:red;"></i>{{__('location.supprimer')}}
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="confirmationModal{{$location->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                                    <div class="modal-dialog " role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background: #F5F5F9;height: 50px;">
                                                                <h5 class="modal-title" id="modalTitleId">{{__('location.avertissement')}}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{__('location.ModalSuppresion')}}
                                                            </div>
                                                            <div class="modal-footer" style="background: #F5F5F9;height: 50px;">
                                                                <button type="button" class="btn  btn-sm btn-dark" data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                                                <a href="{{route('location.delete',$location->id)}}" type="button" class="btn btn-primary btn-sm">{{__('finance.Supprimer')}}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                            <td style="display: none" class="check">{{\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</td>
                                            <td style="display: none" class="check">{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y')}}</td>
                                        </tr>
                                    @endif
                                    @if ($location->Locataire->user_account_id != 0)
                                        @if (\Carbon\Carbon::now() > $location->fin)
                                            <div class="modal fade" id="note_locataire{{$location->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                    <div class="modal-content" id="form_contract_diagnostic">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalTitleId">Donner une note à un locataire</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="row" style="background-color: #D9EDF7; padding: 1rem;">
                                                            <div class="col-lg-2 col-md-3">
                                                                <span class="label m-r-2" style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
                                                            </div>
                                                            <div class="col-lg-10 col-md-9">
                                                                <span> Ajoutez une note au locataire lors de ces locations. 5 étoiles pour la satisfaction et 1 étoile pour le mécontentement.</span>
                                                            </div>
                                                        </div>
                                                        <form action="{{ route('location.note_avis') }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="location_id" value="{{$location->id}}">
                                                            <input type="hidden" name="locataire_id" value="{{$location->Locataire->id}}">
                                                            <input required hidden type="text" name="user_id_locataire" value="{{$location->Locataire->user_account_id}}">
                                                            <div class="modal-body">
                                                                <div class="row" >
                                                                    <div class="col-md-2 col-sm-10 mt-4 ">
                                                                        {{__('location.note')}}
                                                                    </div>
                                                                    <div class="col-md-10 col-sm-10">
                                                                        <input hidden type="text" name="note_star" id="note_star" value="0">
                                                                        <div class="rating">
                                                                            <span class="star" data-value="1">&#9733;</span>
                                                                            <span class="star" data-value="2">&#9733;</span>
                                                                            <span class="star" data-value="3">&#9733;</span>
                                                                            <span class="star" data-value="4">&#9733;</span>
                                                                            <span class="star" data-value="5">&#9733;</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2 col-sm-10 mt-4 ">
                                                                        {{__('location.avis')}}
                                                                    </div>
                                                                    <div class="col-md-10 col-sm-10">
                                                                        <textarea required name="avis" class="form-control" rows="2">{{ old('avis') }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary" id="save_note">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="modal fade" id="modalId_{{$location->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header" style="background: #F5F5F9">
                                              <h5 class="modal-title" id="exampleModalLabel" style="margin-top:-15px;">{{__('location.avertissement')}}</h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('location.desactivation')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="location_desativation_id" value="{{$location->id}}">
                                                    <h6>{{__('location.ModalDesactive')}}</h6>
                                            </div>
                                            <div class="modal-footer" style="background: #F5F5F9;height: 50px;">
                                              <button type="button" class="btn  btn-sm btn-dark" data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                              <button type="submit" class="btn btn-primary btn-sm">{{__('location.confirmer')}}</button>
                                            </form>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="activation_{{$location->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header" style="background: #F5F5F9">
                                              <h5 class="modal-title" id="exampleModalLabel" style="margin-top:-15px;">{{__('location.avertissement')}}</h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('location.reactivation')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="location_desativation_id" value="{{$location->id}}">
                                                    <p style="font-size: 15px">{{__('location.ModalReactive')}}</p>

                                            </div>
                                            <div class="modal-footer" style="background: #F5F5F9;height: 50px;">
                                              <button type="button" class="btn  btn-sm btn-dark" data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                              <button type="submit" class="btn btn-primary btn-sm">{{__('location.confirmer')}}</button>
                                            </form>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="regeneration_{{$location->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header" style="background: #F5F5F9">
                                              <h5 class="modal-title" id="exampleModalLabel" style="margin-top:-15px;">{{__('location.avertissement')}}</h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('location.regeneration')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="location_regeneration_id" value="{{$location->id}}">
                                                    <h6  class="mobil_reg">{{__('location.ModalRegenerer')}}</h6>

                                            </div>
                                            <div class="modal-footer" style="background: #F5F5F9;height: 50px;">
                                              <button type="button" class="btn  btn-sm btn-dark" data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                                              <button type="submit" class="btn btn-primary btn-sm">{{__('location.confirmer')}}</button>
                                            </form>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                    <div class="modal fade" id="assurance_{{$location->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: #F5F5F9">
                                                    <h5 class="modal-title" id="exampleModalLabel" style="margin-top:-15px;">{{__('location.envoieParEmail')}}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('location.rapelle')}}" method="post">
                                                        @csrf
                                                    <p>Envoyer le rappel d'assurance au locataire ?</p>
                                                    <p>Email principal : {{$location->Locataire->TenantEmail}}</p>
                                                    <input type="hidden" name="location_id" value="{{$location->id}}">
                                                    <textarea name="" id="" style="width: 100%;color:#474845;font-size:12px;"  rows="8">Bonjour {{$location->Locataire->civilite . ' '}}{{$location->Locataire->TenantFirstName . ' '}}{{$location->Locataire->TenantLastName}}.  A ce jour et sauf erreur de ma part, je n'ai pas reçu de justificatif ou d'attestation prouvant que le bien que vous occupez en qualité de locataire, est bien assuré.
                                                        Je vous rappelle que vous êtes dans l'obligation légale de vous couvrir contre les risques dont vous avez à répondre en qualité de locataire, aussi je vous remercie de bien vouloir me communiquer dans les meilleurs délais les pièces justifiant de cette assurance.
                                                        Vous pouvez envoyer le document par e-mail. Vous pouvez aussi le charger dans votre compte locataire, rubrique Mes documents et le partager.
                                                        Cordialement,
                                                    </textarea>
                                                </div>
                                                <div class="modal-footer" style="background: #F5F5F9;height: 50px;">
                                                    <button type="button" class="btn  btn-sm btn-dark" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Confirmer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="caution_{{$location->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                        <div class="modal-dialog " role="document">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: #F5F5F9">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card">
                                                        <a href="{{route('location.cautionPdf',['id' => $location->id])}}">
                                                            <div class="card-body" >
                                                                <h5 class="card-title text-center mb-0" style="font-size:40px;"><i class="fa-regular fa-file-pdf" style="color:#9f1318"></i></h5>
                                                                <h6 class="card-title text-center mb-0 mt-1">{{__('location.telechargementPDF')}}</h6>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="card mt-2 ">
                                                        <a href={{route('location.cautiondoc',['id' => $location->id])}}>
                                                            <div class="card-body">
                                                                <h5 class="card-title text-center mb-0" style="font-size:40px;"><i class="fa-regular fa-file-word" style="color:#4C8DCB"></i></h5>
                                                                <h6 class="card-title text-center mb-0 mt-1">{{__('location.telechargementWORD')}}</h6>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="justificatif_assurance_{{$location->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                        <div class="modal-dialog " role="document">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: #F5F5F9">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card">
                                                        <a href="{{route('location.justificatifPdf',['id' => $location->id])}}">
                                                            <div class="card-body" >
                                                                <h5 class="card-title text-center mb-0" style="font-size:40px;"><i class="fa-regular fa-file-pdf" style="color:#9f1318"></i></h5>
                                                                <h6 class="card-title text-center mb-0 mt-1">{{__('location.telechargementPDF')}}</a></h6>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="card mt-2 ">
                                                        <a href={{route('location.justificatifdoc',['id' => $location->id])}}>
                                                            <div class="card-body">
                                                                <h5 class="card-title text-center mb-0" style="font-size:40px;"><i class="fa-regular fa-file-word" style="color:#4C8DCB"></i></h5>
                                                                <h6 class="card-title text-center mb-0 mt-1"><a href={{route('location.justificatifdoc',['id' => $location->id])}}>{{__('location.telechargementWORD')}}</a></h6>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="contrat_{{$location->id}}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                        <div class="modal-dialog " role="document">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: #F5F5F9">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card">
                                                        <a href="{{route('location.telechargerContrat',$location->id)}}">
                                                            <div class="card-body" >
                                                                <h5 class="card-title text-center mb-0" style="font-size:40px;"><i class="fa-regular fa-file-pdf" style="color:#9f1318"></i></h5>
                                                                <h6 class="card-title text-center mb-0 mt-1">{{__('location.telechargementPDF')}}</h6>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="card mt-2 ">
                                                        <a href="{{route('location.telechargerContratWord',$location->id)}}">
                                                            <div class="card-body">
                                                                <h5 class="card-title text-center mb-0" style="font-size:40px;"><i class="fa-regular fa-file-word" style="color:#4C8DCB"></i></h5>
                                                                <h6 class="card-title text-center mb-0 mt-1">{{__('location.telechargementWORD')}}</h6>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Optional: Place to the bottom of scripts -->
                                    <script>
                                        const myModal = new bootstrap.Modal(document.getElementById('modalId'), options)

                                    </script>
                                @endforeach
                            </tbody>
                        </table>
                        @include('location.locationMobile')
                        <div class="d-flex p-3 mt-2">
                            <button class="btn btn-danger btn-sm" id="delete" style="display: none"><i class="fa-solid fa-trash"></i>&nbsp;{{__('location.supprimer')}}</button>
                            <button class="btn btn-secondary btn-sm ms-2" id="archive" style="display: none"><i class="fa-solid fa-box-archive"></i>&nbsp;{{__('location.archive')}}</button>
                            {{-- <a href="{{route('export')}}" class="btn btn-success btn-sm ms-2" id="export" style="display: none"><i class="fa-solid fa-download"></i>&nbsp;EXPORTER</a> --}}
                            <div class="dropdown" style="display: none;margin-left:10px" id="export">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-download"></i>&nbsp;{{__('location.exporter')}}
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{route('export')}}" style="font-size: 14px"><i class="fa-regular fa-file-excel"></i>&nbsp;{{__('location.exporterExel')}}</a>
                                    <a class="dropdown-item" href="{{route('exportODS')}}" style="cursor:pointer;font-size: 14px"><i class="fa-solid fa-file-excel"></i>&nbsp;{{__('location.exporterOpenOffice')}}</a>
                                </div>
                            </div>
                        </div>
                    {{-- </div> --}}
                </div>
            </div>
            <div class="modal fade" id="deleteModalLocation" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header" style="background: #F5F5F9">
                        <h5 class="modal-title" id="exampleModalLabel" style="margin-top:-15px;">{{__('location.avertissement')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">

                              <h6  class="mobil_reg"> {{ __('quittance.Voulez-vous') }}</h6>

                      </div>
                      <div class="modal-footer" style="background: #F5F5F9;height: 50px;">
                        <button type="button" class="btn  btn-sm btn-dark" data-bs-dismiss="modal">{{ __('depense.Annuler') }}</button>
                        <button class="btn btn-primary btn-sm delete-confirm">{{__('location.confirmer')}}</button>

                      </div>
                    </div>
                  </div>
            </div>
            <div class="tab-pane " id="coloc_archi" role="tabpanel" aria-labelledby="profile-tab" >
                <div class="tab-pane active" id="coloc_acif" role="tabpanel" aria-labelledby="home-tab"
                    style="">
                        @include('location.archiveTable')
                </div>
            </div>
        </div>
        @include('proprietaire.suggestion')
    </div>
    <!-- Modal trigger button -->
    {{-- <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalId">
      Launch
    </button> --}}

    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $('.manala').hide()
        $('#archiveTab .archiverSimple').hide()
        function modification(id){
            document.getElementById('inputModal').value = id;
        }



       $(document).ready(function () {

            $('.star').click(function() {
                var ratingValue = $(this).data('value');
                $('#note_star').val(ratingValue);

                // Retirer la classe "selected" de toutes les étoiles
                $('.star').removeClass('selected');

                // Ajouter la classe "selected" aux étoiles jusqu'à la note cliquée
                $(this).prevAll('.star').addBack().addClass('selected');
            });

            $('#in').removeClass('sorting');
            var safidy = $('#safidy').DataTable({
                "pageLength": 10,
                "language": {
                    "lengthMenu": "Filtre _MENU_ ",
                    "zeroRecords": "Pas de recherche corespondant",
                    "info": "Affichage _PAGE_ sur _PAGES_",
                    "infoEmpty": "Pas de recherche corespondant",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "paginate": {
                        "previous": "&lt;", // Remplacer "previous" par "<"
                        "next": "&gt;" // Remplacer "next" par ">"
                    },
                },
                "columnDefs": [
                    { "orderable": false, "targets":[0, 8] } // Désactiver le tri sur la première colonne (index 0)
                ],
                "order": []
            });
            $('#rec').on('keyup', function() {
                    safidy.search(this.value).draw();
                    // dep.search(this.value).draw();
                    // console.log('teste');
                    // alert('teste')
            });
            $('#filter-select-fin').on('change', function() {
                var filterValue = $(this).val();

                var today = moment();
                if (filterValue === "All") {
                    safidy.search('').columns().search('').draw();
                }
                safidy.column(8).data().each(function(value, index) {
                    var endDate = moment(value, 'DD/MM/YYYY');
                    var today = moment();
                    var diff = endDate.diff(today, 'days');
                    if (filterValue === '0-30' && diff <= 30) {
                        safidy.cell(index, 8).search(value).draw();
                    } else if (filterValue === '30-60' && diff > 30 && diff <= 60) {
                        safidy.cell(index, 8).search(value).draw();
                    } else if (filterValue === '60-90' && diff > 60 && diff <= 90) {
                        safidy.cell(index, 8).search(value).draw();
                    } else if (filterValue === '90+' && diff > 90) {
                        safidy.cell(index, 8).search(value).draw();
                    } else {
                        safidy.cell(index, 8).search('').draw();
                    }
                });
            });
            $('#ActifsCounts').text(safidy.rows().count())

            $('#recherche').on('keyup', function() {
                safidy.search(this.value).draw();
            });
            safidy.on( 'draw.dt', function () {
                function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
                }

                var elements = document.getElementsByClassName("random-bg");
                var color = getRandomColor();

                for (var i = 0; i < elements.length; i++) {
                    elements[i].style.backgroundColor = color;
                }
                // $("#master").prop("checked", false);

                // initialise la checkbox "sélectionner tout" à décochée
                $(".sub_chk").change(function(){
                    if($(".sub_chk:checked").length > 0){
                        document.getElementById('delete').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                        document.getElementById('archive').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                        document.getElementById('export').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                    } else {
                        document.getElementById('delete').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                        document.getElementById('archive').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                        document.getElementById('export').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                    }
                    $("#master").prop("checked", false); // décoche la checkbox "sélectionner tout" lorsqu'une checkbox individuelle est modifiée
                    if($(".sub_chk:checked").length == $(".checkbox").length){
                        $("#master").prop("checked", false); // coche la checkbox "sélectionner tout" si toutes les checkbox individuelles sont cochées
                    }
                });
                $("#master").change(function(){
                    if(this.checked){
                        $(".sub_chk").prop("checked", true);
                        document.getElementById('delete').style.display = "block";
                        document.getElementById('archive').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                        document.getElementById('export').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                    } else {
                        $(".sub_chk").prop("checked", false);
                        document.getElementById('delete').style.display = "none";
                        document.getElementById('archive').style.display = "none"
                        document.getElementById('export').style.display = "none"
                    }
                });

            } );

            $('#filter-select-type').on('change', function() {
                var selectedValue = this.value;
                if (selectedValue === 'All') {
                    safidy.search('').columns().search('').draw();
                } else {
                    safidy.column(2).search(selectedValue).draw();
                }
            });
            $('#filter-select-etat').on('change', function() {
                var selectedValue = this.value;
                if (selectedValue === 'All') {
                    safidy.search('').columns().search('').draw();
                } else {
                    safidy.column(6).search(selectedValue).draw();
                }
            });
            $('#filter-select-bien').on('change', function() {
                var selectedValue = this.value;
                if (selectedValue === 'All') {
                    safidy.search('').columns().search('').draw();
                } else {
                    safidy.column(1).search(selectedValue).draw();
                }
            });
            $('#filter-select-date').on('change', function() {
                var filterValue = $(this).val();
                safidy.column(9).data().each(function(value, index) {
                    var startDate = moment(value, 'DD/MM/YYYY');
                    var today = moment();
                    var yesterday = moment().subtract(1, 'days');
                    var last30days = moment().subtract(30, 'days');
                    var startOfMonth = moment().startOf('month');
                    var last7days = moment().subtract(7, 'days');
                    var lastMonth = moment().subtract(1, 'month').startOf('month');
                    var last3Months = moment().subtract(3, 'months').startOf('month');

                    if (startDate.isSame(today, 'day')) {
                        alert('today1')
                        safidy.cell(index, 9).search(value).draw();
                    } else if (filterValue === '' && startDate.isSame(yesterday, 'day')) {
                    safidy.cell(index, 9).search(value).draw();
                    } else if (filterValue === '' && startDate.isBetween(startOfMonth, today)) {
                    safidy.cell(index, 9).search(value).draw();
                    } else if (filterValue === '' && startDate.isBetween(last7days, today)) {
                    safidy.cell(index, 9).search(value).draw();
                    } else if (filterValue === '' && startDate.isBetween(last30days, today)) {
                    safidy.cell(index, 9).search(value).draw();
                    } else if (filterValue === '' && startDate.isBetween(lastMonth, startOfMonth)) {
                    safidy.cell(index, 9).search(value).draw();
                    } else if (filterValue === '' && startDate.isBetween(last3Months, lastMonth)) {
                    safidy.cell(index, 9).search(value).draw();
                    } else if (filterValue === 'All') {
                    safidy.cell(index, 9).search(value).draw();
                    } else {
                    safidy.cell(index, 9).search('').draw();
                    }
                });
            });


            // $('#safidy').DataTable().columns().header().each(function (th, index) {
            //     console.log('Colonne ' + index + ' : ' + $(th).text());
            // });
            $('#safidy').DataTable().column(9).data().each(function (date, index) {
                console.log('Date ' + index + ' : ' + date);
            });
            var archiveTab = $('#archiveTab').DataTable({
                "pageLength": 5,
                "language": {
                    "lengthMenu": "Filtre _MENU_ ",
                    "zeroRecords": "Pas de recherche corespondant",
                    "info": "Affichage _PAGE_ sur _PAGES_",
                    "infoEmpty": "Pas de recherche corespondant",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "paginate": {
                        "previous": "&lt;", // Remplacer "previous" par "<"
                        "next": "&gt;" // Remplacer "next" par ">"
                    },
                },
            });
            archiveTab.on( 'draw.dt', function () {
                function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
                }

                var elements = document.getElementsByClassName("random-bg");
                var color = getRandomColor();

                for (var i = 0; i < elements.length; i++) {
                    elements[i].style.backgroundColor = color;
                }
                // $("#master").prop("checked", false);

                // initialise la checkbox "sélectionner tout" à décochée
                $(".sub_chk").change(function(){
                    if($(".sub_chk:checked").length > 0){
                        document.getElementById('delete').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                        document.getElementById('archive').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                        document.getElementById('export').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                    } else {
                        document.getElementById('delete').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                        document.getElementById('archive').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                        document.getElementById('export').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                    }
                    $("#master").prop("checked", false); // décoche la checkbox "sélectionner tout" lorsqu'une checkbox individuelle est modifiée
                    if($(".sub_chk:checked").length == $(".checkbox").length){
                        $("#master").prop("checked", false); // coche la checkbox "sélectionner tout" si toutes les checkbox individuelles sont cochées
                    }
                });
                $("#master").change(function(){
                    if(this.checked){
                        $(".sub_chk").prop("checked", true);
                        document.getElementById('delete').style.display = "block";
                        document.getElementById('archive').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                        document.getElementById('export').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                    } else {
                        $(".sub_chk").prop("checked", false);
                        document.getElementById('delete').style.display = "none";
                        document.getElementById('archive').style.display = "none"
                        document.getElementById('export').style.display = "none"
                    }
                });

            } );

            $('#ArchiveCounts').text(archiveTab.rows().count())
            $('#recherche').on('keyup', function() {
                archiveTab.search(this.value).draw();
            });
            $('#filter-select-type').on('change', function() {
                var selectedValue = this.value;
                if (selectedValue === 'All') {
                    archiveTab.search('').columns().search('').draw();
                } else {
                    archiveTab.column(1).search(selectedValue).draw();
                }
            });
            $('#filter-select-etat').on('change', function() {
                var selectedValue = this.value;
                if (selectedValue === 'All') {
                    archiveTab.search('').columns().search('').draw();
                } else {
                    archiveTab.column(5).search(selectedValue).draw();
                }
            });
            $('#filter-select-bien').on('change', function() {
                var selectedValue = this.value;
                if (selectedValue === 'All') {
                    archiveTab.search('').columns().search('').draw();
                } else {
                    archiveTab.column(0).search(selectedValue).draw();
                }
            });

            function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
            }

            var elements = document.getElementsByClassName("random-bg");
            var color = getRandomColor();

            for (var i = 0; i < elements.length; i++) {
                elements[i].style.backgroundColor = color;
            }
            $("#master").prop("checked", false); // initialise la checkbox "sélectionner tout" à décochée
            $(".sub_chk").change(function(){
                if($(".sub_chk:checked").length > 0){
                    document.getElementById('delete').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                    document.getElementById('archive').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                    document.getElementById('export').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                } else {
                    document.getElementById('delete').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                    document.getElementById('archive').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                    document.getElementById('export').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                }
                $("#master").prop("checked", false); // décoche la checkbox "sélectionner tout" lorsqu'une checkbox individuelle est modifiée
                if($(".sub_chk:checked").length == $(".checkbox").length){
                    $("#master").prop("checked", false); // coche la checkbox "sélectionner tout" si toutes les checkbox individuelles sont cochées
                }
            });
            $("#master").change(function(){
                if(this.checked){
                    $(".sub_chk").prop("checked", true);
                    document.getElementById('delete').style.display = "block";
                    document.getElementById('archive').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                    document.getElementById('export').style.display = "block" // Affiche le bouton lorsqu'au moins une checkbox est cochée
                } else {
                    $(".sub_chk").prop("checked", false);
                    document.getElementById('delete').style.display = "none";
                    document.getElementById('archive').style.display = "none"
                    document.getElementById('export').style.display = "none"
                }
            });

            $("#delete").on('click',function(){
                var id = []
                $('.sub_chk:checked').each(function(){
                    id.push($(this).attr('data-id'))
                });
                console.log(id)
                if(id.length <= 0){
                    toastr.error("veillez d'abord sélectionner");
                }else{
                    var strIds = id.join(",");
                    $("#deleteModalLocation").modal("show")
                    $(".delete-confirm").on("click", function(e) {
                    e.preventDefault()
                    e.stopPropagation()
                    $("#myLoader").removeClass("d-none")
                    $.ajax({
                        type: "GET",
                        url: "/deleteMultiple",
                        data: {
                            strIds:strIds
                        },
                        dataType: "json",
                        success: function (data) {
                            if(data['status'] == true){
                                toastr.success("Location supprimer avec succès !");
                                $("#deleteModalLocation").modal("hide")
                                $("#myLoader").addClass("d-none")
                               $(".sub_chk:checked").each(function(){
                                var row = $(this).closest('tr');
                                var rowIndex = safidy.row(row).index();
                                safidy.row(rowIndex).remove().draw();
                                $('#ActifsCounts').text(safidy.rows().count())

                               })
                                document.getElementById('delete').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                                document.getElementById('archive').style.display = "none"
                                document.getElementById('export').style.display = "none"
                            }
                        }
                    });
                });
                }
            })
            $('#archive').on('click',function(){
                var id = []
                $('.sub_chk:checked').each(function(){
                    id.push($(this).attr('data-id'))
                });
                if(id.length <= 0){
                    toastr.error("veillez d'abord sélectionner");
                }else{
                    var strIds = id.join(",");
                    $.ajax({
                        type: "GET",
                        url: "/ArchiveMultiple",
                        data: {
                            strIds:strIds
                        },
                        dataType: "json",
                        success: function (data) {
                            if(data['status'] == true){
                                toastr.success("Location archiver avec succès !");
                                window.location = "{{ redirect()->route('location.index')->getTargetUrl() }}";
                               $(".sub_chk:checked").each(function(){
                                var row = $(this).closest('tr');
                                var rowIndex = safidy.row(row).index();
                                var rowData = safidy.row(rowIndex).data();
                                safidy.row(rowIndex).remove().draw();
                                // rowData = rowData.slice(1);
                                archiveTab.row.add(rowData).draw();
                                $('#ActifsCounts').text(safidy.rows().count())
                                $('#ArchiveCounts').text(archiveTab.rows().count())
                                })
                                $('#archiveTab .manala').show();
                                $('#archiveTab .archiverSimple').hide();
                                document.getElementById('delete').style.display = "none"  // Masque le bouton lorsqu'aucune checkbox n'est cochée
                                document.getElementById('archive').style.display = "none"
                                document.getElementById('export').style.display = "none"
                            }
                        }
                    });
                }
            })

            function archiverLocation(id, safidyTable, archiveTab) {
                $.ajax({
                    type: "GET",
                    url: "/archiver",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $("#myLoader").removeClass("d-none");
                    },
                    success: function (response) {
                        window.location = "{{ redirect()->route('location.index')->getTargetUrl() }}";
                        $("#myLoader").addClass("d-none");
                        // var row = $('#' + id).closest('tr');
                        // var rowIndex = safidyTable.row(row).index();
                        // var rowData = safidyTable.row(rowIndex).data();
                        // safidyTable.row(rowIndex).remove().draw();
                        // archiveTab.row.add(rowData).draw();
                        // $('#ActifsCounts').text(safidyTable.rows().count());
                        // $('#ArchiveCounts').text(archiveTab.rows().count());
                        // toastr.success("Location archivée avec succès !");
                        // $('#safidy #deee').removeClass('manala');
                        // $('#archiveTab .manala').show();
                        // $('#archiveTab .archiverSimple').hide();
                        // $('#safidy #deee').removeClass('archiverSimple');
                        // // $('#safidy #'+id).addClass(id);
                        // desarchiverLocation(archiveTab);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error("Une erreur s'est produite lors de l'archivage de la location !");
                    }
                });
            }

            // Fonction pour désarchiver une location
            function desarchiverLocation(id, safidyTable, archiveTab) {
                $.ajax({
                    type: "GET",
                    url: "{{route('location.desarchive')}}",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $("#myLoader").removeClass("d-none");
                    },
                    success: function (response) {
                        $("#myLoader").addClass("d-none");
                        window.location = "{{ redirect()->route('location.index')->getTargetUrl() }}";
                        // var row = $('#' + id).closest('tr');
                        // var rowIndex = archiveTab.row(row).index();
                        // var rowData = archiveTab.row(rowIndex).data();
                        // archiveTab.row(rowIndex).remove().draw();
                        // safidyTable.row.add(rowData).draw();
                        // $('#ActifsCounts').text(safidyTable.rows().count());
                        // $('#ArchiveCounts').text(archiveTab.rows().count());
                        // toastr.success("Location désarchivée avec succès");
                        // $('#archiveTab .manala').show();
                        // $('#archiveTab .archiverSimple').hide();
                        // $('#safidy #deee').removeClass('archiverSimple');
                        // $('#safidy #'+id).addClass(id);
                        // handleArchiverLocationClick(safidy, archiveTab,id);
                        // $('#safidy .desa').hide()
                        // $('#safidy .archiverSimple').show()
                        // archiverLocation(safidy)
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error("Une erreur s'est produite lors du désarchivage de la location !");
                    }
                });
            }

            // Fonction pour gérer le clic sur le bouton d'archivage
            $('.archiverSimple').on('click', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                archiverLocation(id, safidy, archiveTab);
            });

            // Fonction pour gérer le clic sur le bouton de désarchivage
            function handleDesarchiverLocationClick(archiveTab) {
                $('#archiveTab').on('click', '#deee', function(e){
                    e.preventDefault();
                    var id = $(this).attr('log');
                    desarchiverLocation(id, safidy, archiveTab);
                });
            }
            function handleArchiverLocationClick(safidyTable, archiveTab,id) {
                $('#safidy').off('click', '.' + id).on('click', '.' + id, function(e) {
                    e.preventDefault();
                    var id = $(this).attr('data-id');
                    archiverLocation(id, safidyTable, archiveTab);
                });
            }

            handleDesarchiverLocationClick(archiveTab);
            $('#archiveTab').on('click', '.desa', function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                desarchiverLocation(id, safidy, archiveTab);
            });
       });

    </script>
@endsection
