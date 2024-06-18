<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width" />
        <!-- NOTE: external links are for testing only -->
        {{-- <link href="{{ storage_path('css/mail/email-inline.css') }}" rel="stylesheet">
        <link href="{{ storage_path('css/mail/email-styletag.css') }}" rel="stylesheet"> --}}
        <link rel="stylesheet" href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1652360845/bailti/css/mail/email-inline_iwrpav.css">
        <link rel="stylesheet" href="https://res.cloudinary.com/dwajoyl2c/raw/upload/v1652360845/bailti/css/mail/email-styletag_cal3f8.css">
        <style>
            a.mui-btns.mui-btn-primary{
                color: #FFF;
                background-color: #2196F3;
                border-top: 1px solid #2196F3;
                border-left: 1px solid #2196F3;
                border-right: 1px solid #2196F3;
                border-bottom: 1px solid #2196F3;
            }

            a.mui-btn-lgs{
                padding: 19px 25px;
            }

            a.mui-btns{
                display: inline-block;
                text-decoration: none;
                text-align: center;
                font-weight: 400;
                font-size: 14px;
                color: #212121;
                line-height: 14px;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                border-radius: 3px;
                background-color: transparent;
                border-top: 1px solid transparent;
                border-left: 1px solid transparent;
                border-right: 1px solid transparent;
                border-bottom: 1px solid transparent;
            }

            .mui-btns {
                cursor: pointer;
                white-space: nowrap;
            }
        </style>
    </head>
    <body>
    @if(!empty($userDetail))
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
                                                <td><span style="color:#FFFFFF">{{ __('User Detail') }}</span></td>
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

                                                                    <h2>{{ __('Dear') }} {{ $userDetail['name'] }},</h2>

                                                                    <p>&nbsp;</p>
                                                                    <h3>{{ __('mail.profile_save') }}.</h3>
                                                                    <p><b>{{ __('mail.identifiants_connex') }}:</b></p>
                                                                    <p>{{ __('Email') }} : {{ $userDetail['email'] }}</p>

                                                                    <p>{{ __('Password') }} : {{ $userDetail['password'] }}</p>
                                                                    <pre>
                                                                    {{ __('Thanks') }},<br /><br />
                                                                    {{ config('app.name', 'TicTacHouse') . ' ' . __('Team') }}
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
    @else
    <table class="mui-body" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td  style="padding:50px;" class="mui-panel">
                <center>
                <!--[if mso]><table><tr><td class="mui-container-fixed"><![endif]-->
                <div style="text-align:center;" class="mui-container">
                    <div class="mui-divider-bottom">
                        <a href="{{ url('/') }}"><img src="{{URL::asset('images/blue-logo-1.png')}}" alt="{{ config('app.name', 'TicTacHouse') }}">
                        </a>
                        <div class="mui-divider-bottom"></div>
                        <p style="padding:20px; text-align:left;">
                            {{ __('mail.hi') }}, <br><br>

                            {{ __('mail.account_created') }} {{ __('mail.cependant_click') }}
                        </p>
                        <a href="{{ $VerificationLink }}" title="Verify" target="_blank" class="mui-btns mui-btn-primary mui-btn-lgs">{{ __('mail.verify_account') }}</a>
                        <br><br>
                        <!-- @if(calculProfilPercent() < 100)
                        <p style="padding:20px; text-align:left;">
                            {{ __('mail.complete_profil_text', ["percent" => calculProfilPercent()]) }}
                        </p>
                        <p><a href="{{ url('/modifier-profile/') }}">{{__('mail.complete_profile')}}</a></p>
                        @endif -->
                        <div style="padding:20px; text-align:left;">
                        {{ __('mail.bien_a_vous') }}<br />
                        {{ __('mail.team' ) }}<br/>
                        <?php echo __('mail.telegram' );?>
                        </div>
                        <div style="padding:20px; text-align:right;">
                        <div  style="text-align:center;"><a href="bailti.fr">bailti.fr</a></div>
                        <div style="text-align:center;">
                            <p style="font-size:0.7em;background-color:rgb(245,245,245);color:rgb(153,153,153);display:inline-block;">
                            {{__("mail.message_membre")}}
                            <p>
                        </div>
                        </div>
                    </div>
                </div>
                <!--[if mso]></td></tr></table><![endif]-->
                </center>
            </td>
        </tr>
    </table>
    @endif
    </body>
</html>
