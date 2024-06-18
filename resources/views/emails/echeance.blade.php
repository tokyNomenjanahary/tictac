<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width" />
    <!-- NOTE: external links are for testing only -->
</head>

<body>
    <table border="0" cellpadding="0" cellspacing="0" style="width:100%">
        <tbody>
            <tr>
                <td style="background-color:#fff">&nbsp;
                    <table align="center" border="0" cellpadding="0" cellspacing="0"
                        style="border:1px solid; width:600px">
                        <tbody>
                            <tr>
                                <td style="background-color:#f5f5f9">
                                    <table align="center" border="0" cellpadding="15" cellspacing="0"
                                        style="width:100%">
                                        <tbody>
                                            <tr>
                                                <td><span style="color:black;">{{__('echeance.details_location')}}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" cellpadding="10" cellspacing="0" style="width:100%">
                                        <tbody>
                                            <tr>
                                                <td style="background-color:#fff">
                                                    <table border="0" cellpadding="10" cellspacing="0"
                                                        style="border:1px solid; width:100%">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <p style="margin-top: 10px;">{{__('recu.Bonjour')}} </p>
                                                                    <p> <small>{{__('echeance.avis_decheance')}}</small> </p>
                                                                    <p><b>
                                                                            <h4>{{__(('recu.details'))}} : </h4>
                                                                        </b></p>
                                                                    <p>{{__('recu.Bien_loué')}} {{ $infoLocataire['bien'] }}</p>
                                                                    <p>{{__('echeance.type')}} : {{ $infoLocataire['type'] }}</p>
                                                                    <p> {{__('echeance.Adresse')}} : {{ $infoLocataire['TenantAddress'] }}
                                                                    </p>
                                                                    <p><b>{{__('echeance.Période')}} : {{ \Carbon\Carbon::parse($infoLocataire['debut'])->format('d/m/Y') }} -
                                                                        {{ \Carbon\Carbon::parse($infoLocataire['fin'])->format('d/m/Y') }}</b></p>
                                                                    <p><small> {{__('echeance.notes')}}</small></p>
                                                                    <pre> <small>
                                                                        {{__('recu.Cordialement')}}<br />
                                                                         {{ $infoLocataire['first_name'] }} <span style="text-transform: uppercase;">{{ $infoLocataire['last_name'] }}
                                                                      </small>
                                                                    </pre>
                                                                    <p
                                                                        style="text-align:center; font-size:10px; line-height: 10px;">
                                                                        © Bailti
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color:#f5f5f9">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
