<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width" />
        <!-- NOTE: external links are for testing only -->
        <link href="{{ storage_path('css/mail/email-inline.css') }}" rel="stylesheet">
        <link href="{{ storage_path('css/mail/email-styletag.css') }}" rel="stylesheet">
    </head>
    <body>
    
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
                        @if($type == "parrain")
                        <p style="padding:20px; text-align:left;">
                            {{ __('mail.hi') }} {{$UserName}}, <br><br>
                            {{ __('mail.parain_text', ['user_name' => $UserNameFileul, 'code_promo' => 'ZFD15TH']) }} .<br><br>
                        </p>
                        @else
                        <p style="padding:20px; text-align:left;">
                            {{ __('mail.hi') }}, <br><br>
                            {{ __('mail.parainnage_text', ['user_name' => $UserName]) }} .<br><br>
                        </p>
                        <a href="{{route('register')}}" title="Verify" target="_blank" class="mui-btn mui-btn-primary mui-btn-lg">{{ __('mail.register') }}</a>
                        <br><br>
                        @endif
                        
                        <div style="padding:20px; text-align:left;">
                        {{ __('mail.bien_a_vous') }}<br />
                        {{ __('mail.team' ) }}
                        </div>
                        <div style="padding:20px; text-align:right;">
                        <div  style="text-align:center;"><a href="bailti.fr">bailti.fr</a></div>
                        <div style="text-align:right;">
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
    </body>
</html>