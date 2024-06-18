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
                                                    <td><span style="color:#FFFFFF">{{ __('boost.boost_ad_payement_detail') }}

                                                    </span></td>
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

                                                                        <h2>{{ __('boost.dear') }} {{ $adPayment['user']['first_name'].' '.$adPayment['user']['last_name'] }},</h2>

                                                                        <p>&nbsp;</p>
                                                                        <h3>{{ __('boost.thank_you') }}.</h3>
                                                                        <p><b>{{ __('boost.your_boost_detail') }}:</b></p>
                                                                        <p>{{ __('boost.ad_title') }} : {{ $adPayment['title'] }}</p>

                                                                        <p>{{ __('boost.ad_description') }} : {{ $adPayment['description'] }}</p>
                                                                        <p><b>{{ __('boost.amount') }}: {{get_current_symbol()}} {{ $amount }}</b></p>
                                                                        <p><b>{{__('payment.amount_with_tva', ['amount' => $amount, 'tva' => amountTVA(reverseAmount($amount)),'current'=> get_current_symbol()])}}</b></p>

                                                                        <h3>{{ __('boost.boost_choosed') }}</h3>
                                                                        @foreach($boostUpsInfos as $key => $ups)
                                                                        <p style="margin-left: 20px;">
                                                                        {{$ups['upsInfos']->$lang_title}} ({{__('boost.during')}} {{$ups['tarifInfos']->duration . ' ' . traduct_info_bdd($ups['tarifInfos']->unit)}}  {{__('boost.for')}} {{Conversion_devise($ups['tarifInfos']->price)}} {{get_current_symbol()}})
                                                                        </p>
                                                                        @endforeach
                                                                        <pre>
                                                            {{ __('boost.thanks') }},
                                                            <br />
                                                            {{ __('boost.team') }}
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
