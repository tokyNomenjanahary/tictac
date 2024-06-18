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
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row no-print" style="margin-top: 15px;padding-left:50px;">
                <div class="col-md-10 p-3">
                    <div class="float-start">
                        <h3>{{__('finance.Avis_échéance')}}</h3>
                    </div>
                </div>
                <div class="col-md-2 p-3">
                    <div class="dropdown" style="position: static !important;">
                        <button type="button" class="btn dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-horizontal-rounded" style="width: 35px;height:35px;font-size:40px;"></i>
                        </button>
                        <div class="dropdown-menu" style="z-index: 2000">
                            <a class="dropdown-item"
                                href="{{ route('echeance.telechargement', ['id' => $location->id]) }}"><i class="fas fa-download"></i>  {{__('echeance.Télécharger')}}</a>
                            <a class="dropdown-item" href="javascript:window.print()"><i class="fas fa-print"></i>  {{__('echeance.Imprimer')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid" style="background-color:#F3F5F6;padding-bottom:20px;">
                <div class="span10 offset1" >
                    <div class="dash-tile dash-tile-dark no-opacity" style="border:none;">
                        <div class="dash-tile-content">
                            <div class="dash-tile-content-inner-fluid dash-tile-content-light printarea">
                                <div id="quittance_div">
                                    <!-- Header Logo and adresses -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                        style="height:250px;">
                                        <tbody>
                                            <tr>
                                                <td valign="top">
                                                    <div
                                                        style="width: 251px; position: relative; float:right; text-align:right; line-height: 11px;">
                                                        <font style="font-size:12px">
                                                            <strong>{{__('finance.Date')}}</strong>
                                                            {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }}
                                                        </font>
                                                        <br><br>
                                                        <font style="font-size:9px">
                                                            <b>{{__('echeance.Période')}}</b>
                                                            {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }} {{__('echeance.au')}}
                                                            <?php $date = new DateTime($location->debut);
                                                            $date->modify('last day of this month');
                                                            echo $date->format('d/m/Y'); ?>
                                                        </font>
                                                        <br>
                                                    </div>
                                                    <!-- End Header Payment details -->
                                                    <!-- End Header Logo and and payment details -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="bottom">
                                                    <!-- Header Adresses -->
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <!-- Header Landlord Adress  -->
                                                                <td width="50%" valign="top">
                                                                    <br><br>
                                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td width="30" valign="top">
                                                                                    <strong>
                                                                                        <font
                                                                                            style="font-size:12px; text-transform: uppercase;">
                                                                                            {{__('echeance.De')}}&nbsp;</font>
                                                                                    </strong>
                                                                                </td>
                                                                                <td width="320" valign="top"
                                                                                    style="color: black;text-transform:uppercase;line-height: 25px;">
                                                                                    {{ Auth::user()->first_name }}
                                                                                    {{ Auth::user()->last_name }}
                                                                                    <br>
                                                                                    {{ Auth::user()->address_register }}
                                                                                    <br>
                                                                                    @foreach ($numbre as $numbres)
                                                                                        {{ $numbres->mobile_no }}
                                                                                    @endforeach
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <!-- Header Tenant Adress  -->
                                                                <td width="50%" valign="top">
                                                                    <br><br>
                                                                    <div>
                                                                        <table border="0" cellspacing="0"
                                                                            cellpadding="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td width="30" valign="baseline">
                                                                                        <strong>
                                                                                            <font
                                                                                                style="font-size:12px; text-transform: uppercase;">
                                                                                                {{__('echeance.A')}}&nbsp;</font>
                                                                                        </strong>
                                                                                    </td>
                                                                                    <td width="320" valign="top"
                                                                                        style="color: black;text-transform:uppercase;line-height: 25px;">
                                                                                        {{ $location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName }}
                                                                                        <br>
                                                                                        {{ $location->Locataire->TenantAddress }}
                                                                                        <br>{{ $location->Locataire->TenantZip }}
                                                                                        {{ $location->Locataire->TenantState }}
                                                                                        <br>
                                                                                        {{ $location->Locataire->tenant_country }}
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!-- End Header Adresses -->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- End Header Logo and adresses -->

                                    <br class="noprint"><br class="noprint"><br class="noprint">
                                    <center>
                                        <table width="100%" cellpadding="4" cellspacing="4"
                                            style="border-top:1px solid black; border-bottom:1px solid black">
                                            <colgroup>
                                                <col width="603">
                                            </colgroup>
                                            <tbody>
                                                <tr>
                                                    <td width="603" align="center"
                                                        style="padding:20px;border-style:none;">
                                                        <font style="font-size:24px; text-transform: uppercase;">
                                                            <b>
                                                                {{__('finance.Avis_échéance')}}  {{getNomDumois(\Carbon\Carbon::parse($location->debut)->format('m') )}}
                                                            </b>
                                                        </font>
                                                        <br><br>
                                                        <strong>
                                                            <font
                                                                style="font-size:10px; line-height: 12px; color:#999999; text-transform: uppercase;">
                                                                {{__('echeance.document')}}
                                                            </font>
                                                        </strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br><br>
                                    </center>

                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td width="45%" valign="top">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                                        style="border-top:2px solid black; border-bottom:1px solid black">
                                                        <tbody>
                                                            <tr>
                                                                <td height="25" align="center" valign="middle"
                                                                    style="padding: 10px;"><strong>
                                                                        <font style="font-size:12px; text-transform: uppercase;">{{__('echeance.info_locataire')}} </font>
                                                                    </strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table width="100%" border="0" cellspacing="0" cellpadding="3"
                                                        style="margin-top: 20px;">
                                                        <tbody>
                                                            <tr>
                                                                <td valign="top"><strong>
                                                                        <font style="font-size:12px;">{{__('echeance.Locataire')}}
                                                                        </font>
                                                                    </strong></td>
                                                                <td align="right" valign="top">
                                                                    <font style="font-size:12px">
                                                                        {{ $location->Locataire->civilite }}
                                                                        {{ $location->Locataire->TenantFirstName . ' ' . $location->Locataire->TenantLastName }}
                                                                    </font>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td valign="top"><strong>
                                                                        <font style="font-size:12px;">{{__('echeance.Téléphone')}}
                                                                        </font>
                                                                    </strong></td>
                                                                <td align="right" valign="top">
                                                                    <font style="font-size:12px">
                                                                        {{ $location->Locataire->tenant_mobile_phone }}
                                                                    </font>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td valign="top"><strong>
                                                                        <font style="font-size:12px;">{{__('echeance.Email')}}
                                                                        </font>
                                                                    </strong></td>
                                                                <td align="right" valign="top">
                                                                    <font style="font-size:12px">
                                                                        {{ $location->Locataire->TenantEmail }}</font>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top">&nbsp;</td>
                                                                <td valign="top">&nbsp;</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>

                                                <td width="10%">&nbsp;</td>

                                                <td width="45%" valign="top">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                                        style="border-top:2px solid black; border-bottom:1px solid black">
                                                        <tbody>
                                                            <tr>
                                                                <td height="25" align="center" valign="middle"
                                                                    style="padding: 10px;">
                                                                    <strong>
                                                                        <font
                                                                            style="font-size:12px; text-transform: uppercase;">
                                                                            {{__('echeance.detail_terme')}} </font>
                                                                    </strong>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table width="100%" border="0" cellspacing="0"
                                                        style="margin-top: 20px;" cellpadding="3">
                                                        <tbody>
                                                            <tr>
                                                                <td width="60%"><strong>
                                                                        <font style="font-size:12px;">
                                                                            {{__('echeance.Loyer')}}
                                                                        </font>
                                                                    </strong></td>
                                                                <td align="right">
                                                                    <font style="font-size:12px">
                                                                        {{ $location->loyer_HC }} €</font>
                                                                </td>
                                                            </tr>
                                                            <tr style="border-bottom: 1px solid black;">
                                                                <td width="60%"><strong>
                                                                        <font style="font-size:12px;">
                                                                            {{__('echeance.Charges')}}
                                                                        </font>
                                                                    </strong></td>
                                                                <td align="right" id="charge">
                                                                    <font style="font-size:12px">
                                                                        {{ $location->charge }} €</font>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top" width="60%"><strong>
                                                                        <font style="font-size:12px;">
                                                                            {{__('echeance.Loyer_charges_comprises')}}
                                                                        </font>
                                                                    </strong></td>
                                                                <td align="right" valign="top">
                                                                    <font style="font-size:12px">
                                                                        <strong>
                                                                            {{ $location->loyer_HC + $location->charge }} €
                                                                        </strong>
                                                                    </font>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="height: 190px;">
                                        <p style="font-size:12px;">
                                            {{__('echeance.Locataire_a_payé')}} {{ $location->loyer_HC + $location->charge }} €
                                            {{__('echeance.le')}} {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }} <br>
                                        </p>
                                        <p
                                            style="font-family:Arial, Helvetica, sans-serif; font-size:12px;text-transform:uppercase;">
                                            <span style=" border: 1px solid #000000; padding: 5px;">
                                                <b>
                                                    <font style="font-size:12px; text-transform: uppercase;">{{__('echeance.TOTAL_PAYÉ')}}
                                                    </font>
                                                    {{ $location->loyer_HC + $location->charge }}
                                                </b>
                                            </span>
                                        </p>

                                        <p style="font-size:13px;">
                                                {{__('echeance.location_adresse')}} <b>
                                                    {{$location->Logement->adresse}}
                                                {{-- {{ $location->Locataire->TenantCity }} ,
                                                {{ $location->Locataire->TenantState }} , <br>
                                                {{ $location->Locataire->TenantAddress }} --}}
                                                <br>
                                                    {{__('echeance.Pour_la_période_du')}} <b>
                                                    {{ \Carbon\Carbon::parse($location->debut)->format('d/m/Y') }} {{__('echeance.au')}}
                                                    <?php $date = new DateTime($location->debut);
                                                    $date->modify('last day of this month');
                                                    echo $date->format('d/m/Y'); ?>
                                        </p>
                                    </div>
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
                                    <table class="no-print" width="100%" border="0" cellspacing="1"
                                        cellpadding="5">
                                        <tbody>
                                            <tr>
                                                <td height="90">
                                                    &nbsp;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <br class="noprint">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                        class="documentfooter">
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
    </div>
</div>
@endsection
