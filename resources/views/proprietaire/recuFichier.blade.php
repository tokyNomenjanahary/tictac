<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('recu.Recu') }}</title>
</head>

<body>
        <div class="row-fluid" style="padding-bottom:20px;">
            <div class="span10 offset1"
                style="border: 3px solid gray;margin:50px;padding:30px;color:black;background-color:#FFFFFF;">
                <div class="dash-tile dash-tile-dark no-opacity" style="border:none;">
                    <div class="dash-tile-content">
                        <div class="dash-tile-content-inner-fluid dash-tile-content-light printarea">
                            <div id="recu_div" style="margin-top: 50px;">
                                <!-- Header Logo and adresses -->
                                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                    style="height:250px;">
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
                                                    <b>{{ __('finance.Date') }} :</b>
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

                                <div class="span12 letter-title center">
                                    <center>
                                        <div
                                            style="border-top: 1px solid #333333; border-bottom: 1px solid #333333; padding:10px;font-size: 18px;">
                                            {{ $location->Description }}
                                        </div>
                                    </center>
                                </div>

                                <table class="table table-borderless table-hover"
                                    style="margin-top: 20px;text-align:center;width:100%;">
                                    <thead>
                                        <tr>
                                            <th>{{ __('recu.Description') }}</th>
                                            <th class="text-center">{{ __('recu.Prix_unité') }}</th>
                                            <th class="text-center">{{ __('recu.TVA') }}</th>
                                            <th class="text-right">{{ __('finance.Montant') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td>{{ $location->Description }}</td>
                                            <td class="text-center" style="text-align: center">{{ $location->montant }}
                                                €</td>
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
                                        style="font-size: 12px; text-align: center; padding: 5px; background-color: #dcf5d1;margin-top:35px;">
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
                                                    </span>
                                                </p>
                                                @if(!empty($signature))
                                                    <p>
                                                        <img
                                                            style="height: {{$signature['desiredHeight']}};width: {{$signature['desiredWidth']}}"
                                                            src={{$signature['path']}}>
                                                    </p>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
    <script type="text/javascript" src="../assets/client/layout1/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"
        integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>
