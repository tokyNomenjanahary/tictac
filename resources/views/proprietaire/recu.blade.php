<style type="text/css" media="print">
    .no-print {
        display: none !important;
    }
</style>
<style>
    .span10{
        border: 3px solid gray;
        padding:30px;
        color:black;
        background-color:#FFFFFF;
        margin-top:30px;
    }
    @media only screen and (max-width: 500px) {
        .span10{
        background-color:#F3F5F6 !important;
        border: none !important;
        }
    }
</style>
@extends('proprietaire.index')
@section('contenue')
        <div class="row no-print" style="margin-top: 15px;padding-left:50px;">
            <div class="col-md-10 p-3">
                <div class="float-start">
                    <h3> {{ __('recu.Recu') }}</h3>
                </div>
            </div>
            <div class="col-md-2 p-3">
                <div class="dropdown" style="position: static !important;">
                    <button type="button" class="btn dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-horizontal-rounded" style="width: 35px;height:35px;font-size:40px;"></i>
                    </button>
                    <div class="dropdown-menu" style="z-index: 2000">
                        <a class="dropdown-item"
                            href="{{ route('recu.telechargement', ['id' => $location->id]) }}"><i class="fas fa-download"></i>  {{__('echeance.Télécharger')}}</a>
                        <a class="dropdown-item" href="javascript:window.print()"><i class="fas fa-print"></i>  {{__('echeance.Imprimer')}}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid" style="background-color:#F3F5F6;padding-bottom:20px;">
            <div class="span10 offset1"
                style="border: 3px solid gray;margin:50px;padding:30px;color:black;background-color:#FFFFFF;">
                <div class="dash-tile dash-tile-dark no-opacity" style="border:none;">
                    <div class="dash-tile-content">
                        <div class="dash-tile-content-inner-fluid dash-tile-content-light printarea">
                            <div id="recu_div" style="margin-top: 50px;">
                                <!-- Header Logo and adresses -->
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:250px;">
                                    <tbody>
                                        <tr>
                                            <td width="60%" valign="top" style="vertical-align: top;">
                                                {{ Auth::user()->first_name }}
                                                {{ Auth::user()->last_name }}
                                                <br>
                                                {{ Auth::user()->address_register }}
                                                <br>
                                                    {{ $NumberPhone->mobile_no }}

                                            </td>
                                            <td width="1%" valign="top" style="vertical-align: top;">
                                                <strong>{{ __('recu.a') }}&nbsp;</strong>
                                            </td>
                                            <td width="39%" valign="top" style="vertical-align: top;">
                                                {{ $location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName }}
                                                <br>
                                                {{ $location->Locataire->TenantAddress }}
                                                <br>{{ $location->Locataire->TenantZip }}
                                                <br>
                                                {{ $location->Locataire->TenantState }}
                                                <br>
                                                {{ $location->Locataire->tenant_country }}
                                                <br> <br>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="date" colspan="4">
                                                <p>
                                                    <br>
                                                    <b>{{ __('finance.Date') }} </b>
                                                    {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }}
                                                    <br>
                                                    <b>{{ __('recu.Bien_loué') }}</b>
                                                    {{ $location->Logement->identifiant }}
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- End Header Logo and adresses -->
                                <div class="row-fluid">
                                    <div class="span12 letter-title center">
                                        <center>
                                            <div
                                                style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; padding:10px; font-weight:bold; font-size: 18px;">
                                                {{ __('recu.Recu') }} </div>
                                        </center>
                                    </div>
                                </div>

                                <div class="row-fluid">
                                    <div class="span12">
                                        <center>
                                            <div style="padding:2%; font-weight:normal; font-size: 13px;">
                                                {{ $location->Description }} </div>
                                        </center>
                                    </div>
                                </div>

                                <table class="table table-borderless table-hover">
                                    <thead>
                                        <tr style="border-top: 2px solid #333333;">
                                            <th>{{ __('recu.Description') }}</th>
                                            <th class="text-center">{{ __('recu.Prix_unité') }}</th>
                                            <th class="text-center">{{ __('recu.TVA') }}</th>
                                            <th class="text-right">{{ __('finance.Montant') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td>{{ $location->Description }}</td>
                                            <td class="text-center" style="text-align: center">{{ $location->montant }} €
                                            </td>
                                            <td class="text-center" style="text-align: center">0.00%</td>
                                            <td class="text-right" style="text-align: right">{{ $location->montant }} €
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right" style="border-top: 1px solid #999999;">
                                                <strong>{{__('finance.Total')}}</strong>
                                            </td>
                                            <td class="text-right" style="border-top: 1px solid #999999;">
                                                <span><strong>{{ $location->montant }}€</strong></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right"><strong>{{ __('recu.Net_à_payer') }}</strong></td>
                                            <td class="text-right" style="text-align: right">
                                                <span><strong>{{ $location->montant }} €</strong></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="clearfix">

                                    <div
                                        style="font-size: 12px; text-align: center; padding: 5px; background-color: #dcf5d1;">
                                        <b class="green">{{ __('recu.PAYÉ_LE') }}
                                            {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }}</b>
                                    </div>

                                </div>
                                <br>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td width="50%" valign="top">
                                            </td>
                                            <td width="50%" valign="top" align="right">
                                                {{--
                                                    <div style="width: 200px; height: 100px;">&nbsp;</div>
                                                    <br style="clear: both;"> --}}
                                                <p style="font-size:12px;">
                                                    {{ Auth::user()->first_name }} <span
                                                        style="text-transform: uppercase;">{{ Auth::user()->last_name }}
                                                    </span> </p>
                                                @if(!empty($signature))
                                                    <p>
                                                        <img
                                                            style="height: {{$signature['desiredHeight']}};width: {{$signature['desiredWidth']}}; margin-top: 5px"
                                                            src={{$signature['path']}}>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <p style="margin-bottom: 0.14in"></p>
                                <table class="no-print" width="100%" border="0" cellspacing="1" cellpadding="5">
                                    <tbody>
                                        <tr>
                                            <td height="90">
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <br class="noprint">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="documentfooter">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p style="text-align:center; font-size:10px;">© Bailti
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br class="noprint">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
