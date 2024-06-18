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
                                <a href="{{ url('/') }}"><img src="{{URL::asset('images/blue-logo-1.png')}}" alt="{{ config('app.name', 'TicTacHouse') }}"></a>
                                <div class="mui-divider-bottom"></div>

                                <p style="padding:20px; text-align:left;">
                                    {{ __('mail.hi') }}, <br><br>
                                    {{ __('mail.title_registration')}}.<br>
									{{ __('mail.mail_caption') . $user->email}}<br>
									{{ __('mail.first_name') . $user->first_name}}<br>
									{{ __('mail.last_name') . $user->last_name}}<br>
									{{ __('mail.mobile_number') . $user->mobile_no}}<br><br></p>
									{{ __('mail.thanks') }}.<br />
                            </div>

                        </div>
                        <!--[if mso]></td></tr></table><![endif]-->
                    </center>
                </td>
            </tr>
        </table>
    </body>
</html>