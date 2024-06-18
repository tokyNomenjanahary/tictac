@extends('proprietaire.index')
<style>
    .nav-dash-icon-size {
        font-size: 3.5rem;
    }

    .dataTables_length {
        display: none !important;
    }

    .table-responsive {
        z-index: 1;
    }

    th {
        color: blue !important;
        font-size: 10px !important;
    }
    .month{
        color: black !important;
        text-transform: uppercase !important;
    }

    td {
        font-size: 13px;
    }

    p {
        line-height: 8px;
        font-size: 12px;
    }

    @media only screen and (min-width: 600px) {
        .mobil {
            display: none;
        }
    }

    @media only screen and (max-width: 600px) {
        .tete {
            display: flex;
        }

        .arh {
            margin-left: 13%;
        }

        .nouv {
            margin-top: -85px;
            margin-left: 20px;
        }

        .filtre {
            margin-top: 20px;
        }

        #finances {
            display: none;
        }

        #safidy_paginate {
            display: none;
        }

        #safidy_info {
            display: none;
        }

        #offcanvas {
            height: 800px;
        }
    }
</style>
@section('contenue')
    <!-- Content wrapper -->
    <div class="content-wrapper"
        style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- HEADER -->
            <div class="row tete justify-content-between align-items-center mb-4">
                <div class="col-lg-4">
                    <h3 class="page-header page-header-top m-0">{{ __('finance.Finances') }}</h3>
                </div>
                <div class="col-lg-4  nouv" style="text-align: right;">
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ __('finance.Nouvelle_transaction') }}
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('ajout-revenu') }}">
                                <i class="fa fa-plus-circle"></i> {{__('finance.Ajouter_un_revenu')}}
                            </a>
                            <a class="dropdown-item" href="{{ route('ajout-depense') }}">
                                <i class="fa fa-minus-circle me-1"></i>{{__('finance.Ajouter_une_depense')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END HEADER -->

            <!-- CARD FILTER -->
            <div class="card mb-4 p-3">
                <div>
                    <p><span class="h5">{{__('finance.Filter')}}</span> <span>{{__('finance.Utilisez_les_options_pour_filtrer')}}</span></p>
                </div>
                <div class="row">
                    <div class="col-2">
                        <div>
                            <select id="filter-select-date" class="form-select form-select-sm">
                                <option value="All">{{__('finance.Tous')}}</option>
                                <option value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">{{__('finance.aujourdhui')}}</option>
                                <option value="{{ \Carbon\Carbon::now()->subDays(1)->format('d/Y') }}">{{__('finance.hier')}}</option>
                                <option value="{{ \Carbon\Carbon::now()->format('m/Y') }}">{{__('finance.Ce_mois')}}</option>
                                <option value="{{ \Carbon\Carbon::now()->subDays(7)->format('d/Y') }}">{{__('finance.Dernier_7_jours')}}
                                </option>
                                <option value="{{ \Carbon\Carbon::now()->subDays(30)->format('d/Y') }}">{{__('finance.Dernier_30_jours')}}
                                </option>
                                <option value="{{ \Carbon\Carbon::now()->subMonth(1)->format('m/Y') }}">{{__('finance.mois_dernier')}}
                                </option>
                                <option value="{{ \Carbon\Carbon::now()->subMonth(3)->format('m/Y') }}">{{__('finance.trois_mois_dernier')}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div>
                            <select id="filter-select-bien" class="form-select form-select-sm">
                                <option value="All">{{__('finance.Tous_les_biens')}}</option>
                                @foreach ($logements as $logement)
                                    <option value="{{ $logement->identifiant }}">{{ $logement->identifiant }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div>
                            <select id="filter-select-locataire" class="form-select form-select-sm">
                                <option value="All">{{__('finance.Toutes_les_locataires')}}</option>
                                @foreach ($locataires as $locataire)
                                    <option value="{{ $locataire->TenantLastName }}">{{ $locataire->TenantLastName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div>
                            <select id="filter-select-etats" class="form-select form-select-sm">
                                <option value="All">{{__('finance.Tous_les_etats')}}</option>
                                <option value="Payé">{{__('finance.Payé')}}</option>
                                <option value="Pas payé">{{__('finance.Pas_payé')}}</option>
                                <option value="En attente">{{__('finance.En_attente')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div>
                            <input id="recherche" class="form-control form-control-sm" type="text"
                                placeholder={{__('finance.Recherche')}}>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END CARD FILTER -->
               @if (isset($loyerEnretard) && $loyerEnretard->isNotEmpty())
               <div class="row alert" style="background-color:#F2DEDE">
                <div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="float:right;"></button>
                    <span class="lead" style="line-height: 40px;font-size: 25px;margin-bottom: 20px;color:#b94a48;">{{__('finance.Loyer_en_retard')}}</span>
                    <br>
                    <p style="margin-top:10px;font-size:15px;color:#b94a48">{{__('finance.loyers_en_retard_details')}}</p>
                        <ul>
                           @foreach ($loyerEnretard as $item)
                            <li>{{$item->Location->identifiant}}</li>
                           @endforeach
                        </ul>
                </div>
              </div> <br>
               @endif
            <!-- DETAILS -->
            <div class="row">
                <div class="card mb-4 p-3">
                    <div class="row">
                        <div class="col-6">
                            <div class="row align-items-center ms-2 col" style="border: 1px solid #F3F5F6;">
                                <div class="col-lg-6" style="border: 1px solid #F3F5F6;padding-top:10px;">
                                    <p class="form-label">{{__('finance.revenu_brut')}}</p>
                                    <h4 class="text-success"> <span id="total"></span></h4>
                                </div>
                                <div class="col-lg-6" style="border: 1px solid #F3F5F6;padding-top:10px;">
                                    <p class="form-label">{{__('finance.depense')}}</p>
                                    <h4 class="text-danger"><span id="depense" class="text-danger"></span></h4>
                                </div>
                            </div>
                            <div class="row align-items-center ms-2 col" style="border: 1px solid #F3F5F6;margin-top:8px;">
                                <div class="w-auto">
                                    <i class="fa-sharp fa-solid fa-coins nav-dash-icon-size"></i>
                                </div>
                                <div class="col">
                                    <div style="padding-top:10px;">
                                        <p>{{__('finance.resultat_net')}}</p>
                                    </div>
                                    <div>
                                        <h3 class="text-success"><span id="total1"></span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row ms-2">
                                <div class="col-lg-6" style="border: 1px solid #F3F5F6;padding-top:10px;">
                                    <p class="form-label">{{__('finance.loyer_encaisse')}}</p>
                                    <h4 class="text-success"><span id="loyerPayé"></span></h4>
                                    <p>{{__('finance.sur')}} <span id="total2" style="font-weight: bold;"></span></p>
                                </div>
                                <div class="col-lg-6" style="border: 1px solid #F3F5F6;padding-top:10px;">
                                    <p class="form-label">{{__('finance.En_attente')}}</p>
                                    <h4 class="text-warning"><span id="attente" class="text-warning"></span></h4>
                                </div>
                            </div>
                            <div class="ms-2" style="margin-top: 12px;">
                                <div class="col">
                                    <ul>
                                        <li>{{__('finance.Loyer_hors_Charges')}} <span class="text-success " id="Loyer"></span></li>
                                        <li>{{__('finance.Charges')}} <span class="text-success" id="Charge"></span></li>
                                        <li>{{__('finance.tva_encaissee')}} <span class="text-success">0.00 €</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- END DETAILS -->
            <div class="row">
                <div class="tab-content" style="padding-left: 12px;margin-top:-38px;padding-right: 12px;">
                    <div class="tab-pane active " id="coloc_acif" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row"
                            style="background-color:white;margin-top: 30px;border:1px solid #eeeeee;padding:25px;">
                            {{-- <div class="table-responsive"> --}}
                            <table class="table table-striped table-hover table-responsive" id="finances"
                                style="margin-bottom:0px;border:2px solid #F3F5F6;">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="master" class="checkbox_input align-middle "
                                                style="height: 20px;width:20px;"></th>
                                                <th>{{__('finance.Date')}}</th>
                                                <th>{{__('finance.Bien')}}</th>
                                                <th>{{__('finance.de')}}</th>
                                                <th>{{__('finance.Montant')}}</th>
                                                <th>{{__('finance.Description')}}</th>
                                                <th>{{__('finance.Etat')}}</th>
                                                <th>{{__('finance.Actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($months as $month => $locations)
                                    <tr>
                                        <td></td>
                                        <td class="month">{{ $month }}</td>
                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                    </tr>
                                    @foreach ($locations as $location)
                                        <tr>
                                            <td style="width: 5px" class="check">
                                                <input type="checkbox" id="selectionner"
                                                    class="checkbox_input align-middle sub_chk"
                                                    style="height: 15px;width:15px;" data-id="{{ $location->id }}">
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }}
                                            </td>
                                            <td><a href="">{{ $location->Logement->identifiant }}</a> </td>
                                            <td> <a
                                                    href="">{{ $location->Locataire->TenantFirstName . ' ' }}{{ $location->Locataire->TenantLastName }}</a>
                                            </td>
                                            <td class="text-success">
                                                @if ($location->type == 'depense')
                                                    {{-- <span class="text-danger"> - {{ $location->montant }} €</span> --}}
                                                    @php
                                                        $sommeMontant = 0;
                                                    @endphp
                                                    @foreach ($location->AutresPaiements as $AutresPaiement)
                                                        @php
                                                            $sommeMontant += $AutresPaiement->montant;
                                                        @endphp
                                                    @endforeach
                                                    {{-- de {{ $sommeMontant }} €  --}}
                                                    @if (isset($sommeMontant) && !empty($sommeMontant))
                                                        <span class="text-danger"> -
                                                            {{ $location->montant + $sommeMontant }} € </span><br>
                                                        <small style="color: black;"> {{__('finance.dea')}} {{ $sommeMontant }} € </small>
                                                    @else
                                                        <span class="text-danger"> - {{ $location->montant }} € </span>
                                                    @endif
                                                @elseif ($location->type == 'revenu')
                                                    {{-- {{ $location->montant }} € --}}
                                                    <br>
                                                    @php
                                                        $sommeMontant = 0;
                                                    @endphp
                                                    @foreach ($location->AutresPaiements as $AutresPaiement)
                                                        @php
                                                            $sommeMontant += $AutresPaiement->montant;
                                                        @endphp
                                                    @endforeach
                                                    {{-- de {{ $sommeMontant }} €  --}}
                                                    @if (isset($sommeMontant) && !empty($sommeMontant))
                                                        {{ $location->montant + $sommeMontant }} € <br>
                                                        <small style="color: black;"> {{__('finance.dea')}} {{ $sommeMontant }} € </small>
                                                    @else
                                                        {{ $location->montant }} €
                                                    @endif
                                                @else
                                                    {{ $location->montant }} €
                                                @endif
                                            </td>
                                            {{-- <td>Loyer : {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($location->fin)->format('d/m/Y') }} --}}
                                            <td>
                                                @if ($location->type == 'loyer')
                                                @if($location->Etat == __('bilan.Pas_paye')) <i class="fa fa-exclamation-circle text-danger m-r-5"></i> @endif {{__('finance.Loyer')}} : {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }}
                                                    -
                                                    <?php $date = new DateTime($location->debut);
                                                    $date->modify('last day of this month');
                                                    echo $date->format('d/m/Y'); ?>
                                                    <br>
                                                    <small style="color: black;">{{__('finance.Reçu')}}
                                                        {{ $location->loyer_HC + $location->charge }}€ de
                                                        {{__('finance.Locataire')}} {{__('finance.le')}}
                                                        {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }}</small>
                                                @elseif ($location->type == 'revenu' || $location->type == 'depense')
                                                    {!! $location->Description !!} <br>
                                                    <small style="color: black;"> {{__('finance.Reçu')}} {{ $location->montant }} {{__('finance.dea')}}
                                                        {{ $location->Locataire->TenantLastName }} le
                                                        {{ $location->debut }},
                                                    </small>
                                                    @foreach ($location->AutresPaiements as $AutresPaiement)
                                                        <small style="color: black;"> {{__('finance.Reçu')}} {{ $AutresPaiement->montant }}
                                                            {{__('finance.dea')}}
                                                            {{ $AutresPaiement->locataire }} {{__('finance.le')}}
                                                            {{ $AutresPaiement->date }},
                                                        </small>
                                                    @endforeach
                                                @else
                                                    {!! $location->Description !!}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($location->type == 'loyer')
                                                <form method="POST" action="{{route('Changer_Etat')}}">
                                                    @csrf
                                                     @method('UPDATE')
                                                     <select data-id="{{ $location->id }}" data-type={{ $location->type }} class="form-select filter-select-etat" name="etat">
                                                        <option value="{{ $location->Etat }}" selected hidden>{{ $location->Etat }}</option>
                                                        @foreach ([__('bilan.Pas_paye') => 1, __('bilan.Paye') => 0, __('bilan.Perdu') => 3, __('bilan.Partiel') => 4] as $option => $value)
                                                            @if ($option != $location->Etat)
                                                                <option value="{{ $value }}">{{ $option }} </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </form>
                                                @else
                                                <form method="POST" action="{{route('Changer_Etat')}}">
                                                    @csrf
                                                     @method('UPDATE')
                                                     <select data-id="{{ $location->id }}" data-type={{ $location->type }} class="form-select filter-select-etat" name="etat">
                                                        <option value="{{ $location->Etat }}" selected hidden>{{ $location->Etat }}</option>
                                                        @foreach ([ __('bilan.Pas_paye')  => 1, __('bilan.Paye') => 0, __('bilan.En_attente') => 2, __('bilan.Partiel') => 4] as $option => $value)
                                                            @if ($option != $location->Etat)
                                                                <option value="{{ $value }}">{{ $option }} </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </form>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-horizontal-rounded"></i>
                                                    </button>
                                                    @if ($location->type == 'revenu')
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route('modifier-revenu', ['id' => $location->id]) }}"
                                                                class="dropdown-item" id="modification"> <i
                                                                    class="fa fa-pencil"></i>
                                                                    {{__('finance.Modifier')}}</a>
                                                            <a href="{{ route('Enregistrer-paiement', ['id' => $location->id, 'type' => $location->type]) }}"
                                                                class="dropdown-item"> <i class="fa fa-cc-visa"></i>
                                                                {{__('finance.Enregistrer_un_paiement')}}</a>
                                                                  @if($location->Description =="Dépôt de garantie")
                                                            <a href="{{ route('telecharge-recu', ['id' => $location->id]) }}"
                                                                class="dropdown-item" id="modification"><i
                                                                    class="fa-solid fa-file"></i>
                                                                {{__('finance.Reçu')}}</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('recu.send', ['id' => $location->id, 'email' => $location->Locataire->TenantEmail]) }}"><i
                                                                    class="fa fa-money-bill-alt me-1"></i>{{__('finance.Envoyer_le_reçu')}}
                                                                {{__('finance.Reçu')}}</a>
                                                                @endif
                                                            <form action="{{ route('finance.delete', $location->id) }}"
                                                                Method="POST">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit" class="dropdown-item delete">
                                                                    <i class="fa-solid fa-trash" style="color:red;"></i>
                                                                    {{__('finance.Supprimer')}}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif($location->type == 'depense')
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route('modifier-depense', ['id' => $location->id]) }}"
                                                                class="dropdown-item" id="modification"> <i
                                                                    class="fa fa-pencil"></i>
                                                                    {{__('finance.Modifier')}}</a>
                                                            <a href="{{ route('Enregistrer-paiement', ['id' => $location->id, 'type' => $location->type]) }}"
                                                                class="dropdown-item"> <i class="fa fa-cc-visa"></i>
                                                                {{__('finance.Enregistrer_un_paiement')}}</a>
                                                            <form action="{{ route('depense.delete', $location->id) }}" Method="POST">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit" class="dropdown-item delete">
                                                                    <i class="fa-solid fa-trash" style="color:red;"></i>
                                                                    {{__('finance.Supprimer')}}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <div class="dropdown-menu">
                                                            {{-- <a class="dropdown-item" href=""><i class="fa fa-pencil me-1"></i>
                                                    Modifier</a> --}}
                                                            {{-- <a class="dropdown-item" href=""><i class="fa fa-eye me-1"></i>
                                                    Enregistrer un payement</a> --}}
                                                            <a href="{{ route('modifier-loyer', ['id' => $location->id]) }}"
                                                                class="dropdown-item" id="modification"
                                                                title="modifier les payements"> <i
                                                                    class="fa fa-pencil"></i>
                                                                    {{__('finance.Modifier')}}</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('locataire.invite', ['email' => $location->Locataire->TenantEmail]) }}"><i
                                                                    class="fa fa-paper-plane me-1"></i>{{__('finance.Inviter')}}</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('echeance.show', ['id' => $location->id]) }}"><i
                                                                    class="fa fa-comments me-1"></i>
                                                                    {{__('finance.Avis_échéance')}}</a>
                                                                {{-- <a href="{{ route('Enregistrer-paiement', ['id' => $location->id, 'type' => $location->type]) }}"
                                                                    class="dropdown-item"> <i class="fa fa-cc-visa"></i>
                                                                    Enregistrer un paiement</a> --}}
                                                            <a class="dropdown-item"
                                                                href="{{ route('locataire.quittance', ['id' => $location->id]) }}"><i
                                                                    class="fa fa-sign-out-alt me-1"></i>{{__('finance.Quittance_de_loyer')}}</a>
                                                            {{-- <a class="dropdown-item" href=""><i
                                                        class="fas fa-file-invoice me-1"></i>
                                                    Lien de partage</a> --}}
                                                            <a class="dropdown-item"
                                                                href="{{ route('quittance.send', ['id' => $location->id, 'email' => $location->Locataire->TenantEmail]) }}"><i
                                                                    class="fa fa-money-bill-alt me-1"></i>{{__('finance.Envoyer_la_quittance')}}</a>
                                                            {{-- <a class="dropdown-item" href=""><i class="fas fa-archive me-1"></i>
                                                    Dupliquer</a> --}}
                                                            <form action="{{ route('loyer.delete', $location->id) }}"
                                                                Method="POST">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit" class="dropdown-item delete">
                                                                    <i class="fa-solid fa-trash" style="color:red;"></i>
                                                                    {{__('finance.Supprimer')}}
                                                                </button>
                                                            </form>
                                                            <hr style="margin:0px;">
                                                            {{-- <a class="dropdown-item" href="" data-delete-confirm-button=""
                                                    data-delete-cancel-button="" data-delete-title=""><i
                                                        class="fa-solid fa-trash" style="color:red;"></i> Supprimer</a> --}}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                                <tr style="background-color: #F3F5F6;">
                                    <td colspan="4">
                                        <div class="d-flex  ">
                                            <button class="btn btn-danger btn-sm" id="delete" style="display: none"><i
                                                    class="fa-solid fa-trash"></i>&nbsp;{{__('finance.Supprimer')}}</button>
                                            <div class="dropdown" style="margin-left: 13px;">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    id="export" style="display: none" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fa-solid fa-download"></i>&nbsp;{{__('finance.exporter')}}
                                                </button>
                                                <div class="dropdown-menu" style="padding-left: 19px;">
                                                    <a href="{{ route('exporter') }}"><i
                                                            class="fa-regular fa-file-excel"></i> {{__('finance.Format_Excel')}}</a><br>
                                                    <a href="{{ route('exportation') }}"><i
                                                            class="fa-solid fa-file-excel"></i> {{__('finance.Format_Open_Office')}}</a><br>
                                                    <a href="{{ route('exportationZipp') }}"><i class="fa fa-archive">
                                                        </i>{{__('finance.Exporter_les_quittances')}}</a>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                    <div class="d-flex  ">
                                        <td colspan="6">
                                            <div style="padding-top: 8px;">
                                              <span style="color:blue;font-weight:bold;">{{__('finance.Total')}} : <span class="vivid-green"><span id="total3"></span></span> </span>
                                            </div>
                                        </td>
                                    </div>
                                </tr>
                            </table>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            @include('proprietaire.suggestion')
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        getvaleur()
        var finance = $('#finances').DataTable({
            "pageLength": 10,
            "language": {
                "lengthMenu": "Filtre _MENU_ ",
                "zeroRecords": "Pas de recherche corespondant",
                "info": "Affichage _PAGE_ sur _PAGES_",
                "infoEmpty": "Pas de recherche corespondant",
                "infoFiltered": "(filtered from _MAX_ total records)"
            },
            "createdRow": function( row, data, dataIndex ) {
                var etat = $(data[6]).find('select').val()  
                console.log(etat)
                if (etat == 'Pas paye' || etat == 'Not paid') {
                    $(row).css('background-color', '#fcdada');
                }
                else if(etat == 'Perdu' || etat == 'Lost' )
                {
                    $(row).css('opacity', '0.3');
                }
                else if (etat == 'En attente' || etat == 'Waiting'){
                    $(row).css('background-color', '#FFF3BD');
                }
            },
            "scrollCollapse" : true,
            ordering:false
        });

        $(".sub_chk").change(function() {
            if ($(".sub_chk:checked").length > 0) {
                document.getElementById('delete').style.display = "block"
                document.getElementById('export').style.display = "block"
            } else {
                document.getElementById('delete').style.display = "none"
                document.getElementById('export').style.display = "none"
            }
            $("#master").prop("checked", false);
            if ($(".sub_chk:checked").length == $(".checkbox").length) {
                $("#master").prop("checked", false);
            }
        });
        $("#master").change(function() {
            if (this.checked) {
                $(".sub_chk").prop("checked", true);
                document.getElementById('delete').style.display = "block";
                document.getElementById('export').style.display = "block"
            } else {
                $(".sub_chk").prop("checked", false);
                document.getElementById('delete').style.display = "none";
                document.getElementById('export').style.display = "none"
            }
        });
        $("#delete").on('click', function() {
            var id = []
            $('.sub_chk:checked').each(function() {
                id.push($(this).attr('data-id'))
            });
            console.log(id)
            if (id.length <= 0) {
                alert('pas des finances')
            } else {
                var strIds = id.join(",");
                $("#myLoader").removeClass("d-none")
                $.ajax({
                    type: "GET",
                    url: "/suppMultiple",
                    data: {
                        strIds: strIds
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['status'] == true) {
                            getvaleur()
                            toastr.success("suppression  success !");
                            $("#myLoader").addClass("d-none")
                            location.reload();
                            $(".sub_chk:checked").each(function() {
                                var row = $(this).closest('tr');
                                var rowIndex = finance.row(row).index();
                                finance.row(rowIndex).remove().draw();
                                $('#ActifsCounts').text(finance.rows().count())
                            })
                            document.getElementById('delete').style.display = "none"
                            document.getElementById('export').style.display = "none"
                        }
                    }
                });
            }
        })

        function getvaleur() {
            var id = <?php echo json_encode(Session::get('id')); ?>;
            $.ajax({
                type: "GET",
                url: "/getlocation/" + id,
                dataType: "json",
                success: function(data) {
                    for (var i = 0; i < data.length; i++) {
                        var total = document.getElementById("total");
                        total.innerHTML = data[7] + '€';

                        var total1 = document.getElementById("total1");
                        total1.innerHTML = data[1] + '€';

                        var total2 = document.getElementById("total2");
                        total2.innerHTML = data[5] + '€';

                        var loyerPayé = document.getElementById("loyerPayé");
                        loyerPayé.innerHTML = data[6] + '€';

                        var total3 = document.getElementById("total3");
                        total3.innerHTML = data[1] + '€';

                        var Loyer = document.getElementById("Loyer");
                        Loyer.innerHTML = data[0] + '€';

                        var attente = document.getElementById("attente");
                        attente.innerHTML = data[4] + '€';

                        var Charge = document.getElementById("Charge");
                        Charge.innerHTML = data[2] + '€';

                        var depense = document.getElementById("depense");
                        if (data[3] == 0)
                            depense.innerHTML = data[3] + '€';
                        else
                            depense.innerHTML = '-' + data[3] + '€';
                    }
                }
            });
        }
        $('#filter-select-date').on('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'All') {
                finance.search('').columns().search('').draw();
            } else {
                finance.column(1).search(selectedValue).draw();
            }
        });
        $('#filter-select-bien').on('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'All') {
                finance.search('').columns().search('').draw();
            } else {
                finance.column(2).search(selectedValue).draw();
            }
        });
        $('#filter-select-locataire').on('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'All') {
                finance.search('').columns().search('').draw();
            } else {
                finance.column(3).search(selectedValue).draw();
            }
        });
        $('#filter-select-etats').on('change', function() {
            var selectedValue = this.value;
            if (selectedValue === 'All') {
                finance.search('').columns().search('').draw();
            } else {
                finance.column(6).search(selectedValue).draw();
            }
        });
        $('.filter-select-etat').change(function (e) {
            e.preventDefault();
            var nouvelleValeur = $(this).val();
            if(nouvelleValeur === "Partiel"){
            var type = $(this).attr('data-type');
            var url = "{{ route('Etat_partiel') }}";
            var token = "{{ csrf_token() }}";
            var id = $(this).attr('data-id');
            var types="GET"
            $("#myLoader").removeClass("d-none")
            $.ajax({
                type: types,
                url: url,
                data: {
                    'etat': nouvelleValeur,
                    'id' : id,
                    'type' : type,
                    '_token': token
                },
                success: function(response) {
                $("#myLoader").addClass("d-none")
                // Display the response in the browser
                $('body').html(response);
            }
            })
            }else{
            var type = $(this).attr('data-type');
            var url = "{{ route('Changer_Etat') }}";
            var token = "{{ csrf_token() }}";
            var id = $(this).attr('data-id');
            var types="POST"
            $("#myLoader").removeClass("d-none")
            $.ajax({
                type: types,
                url: url,
                data: {
                    'etat': nouvelleValeur,
                    'id' : id,
                    'type' : type,
                    '_token': token
                },
                success: function (data) {
                    // toastr.success("update  success !");
                    location.reload();
                    $("#myLoader").addClass("d-none")                },
                error: function (data) {
                    alert('Erreur lors de l\'enregistrement du changement de valeur')
                }
            });
            }
        });
       
        $('#recherche').on('keyup', function() {
            finance.search(this.value).draw();
        });
    })
</script>
