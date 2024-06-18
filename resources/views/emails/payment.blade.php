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
                        <table align="center" border="0" cellpadding="0" cellspacing="0" style="border:1px solid; width:600px">
                            <tbody>
                                <tr>
                                    <td style="background-color:#171714">
                                        <table align="center" border="0" cellpadding="15" cellspacing="0" style="width:100%">
                                            <tbody>
                                                <tr>
                                                    <td><span style="color:#FFFFFF">{{ __('mail.payment_detail') }}</span></td>
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
                                                        <table border="0" cellpadding="10" cellspacing="0" style="border:1px solid; width:100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td>

                                                                        <p>&nbsp;</p>

                                                                        <h2>{{ __('mail.dear') }} {{ $packageDetail['userName'] }},</h2>

                                                                        <p>&nbsp;</p>
                                                                        <p><h3>{{ __('mail.thank_subscribing') }}.</h3></p>
                                                                        <p><b>{{ __('mail.subs_detail') }}:</b></p>
                                                                        <p>{{ __('mail.plan_name') }} : {{ $packageDetail['packageTitle']}}</p>

                                                                        <p>{{ __('mail.plan_duration') }} : {{ $packageDetail['packageDuration'] }} {{ traduct_info_bdd($packageDetail['unite'])}}</p>
                                                                        <p>{{ __('mail.plan_amount') }} : {{$packageDetail['currency']}} {{ $packageDetail['packageAmount']/100 }}</p>
                                                                        <p>{{ __('mail.start_valid') }} {{ $packageDetail['packageStartDate'] }} {{ __('mail.to') }} {{ $packageDetail['packageEndDate'] }}</p>
                                                                        <p><b>{{ __('mail.amount_paid') }}: {{$packageDetail['currency']}} {{ $packageDetail['packageAmount']/100 }}</b></p>
                                                                        <pre>
                                                                        {{ __('mail.thanks') }},<br />
                                                                        {{ __('mail.team' ) }}
                                                                        </pre>
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
                                    <td style="background-color:black">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
