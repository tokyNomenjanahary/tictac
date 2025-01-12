<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width" />
        <!-- NOTE: external links are for testing only -->
        <link href="https://www.tictachouse.tk/css/mail/email-style.css" rel="stylesheet">
        <link href="{{ storage_path('css/mail/email-styletag.css') }}" rel="stylesheet">
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    </head>
    <body>
        <table class="mui-body" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="mui-panel">
                    <center>
<!--[if mso]><table><tr><td class="mui-container-fixed"><![endif]-->
                        <div style="text-align:center;" class="mui-container">
                            <div class="mui-divider-bottom">
                                <div class="logo-containter">
                                    <a href="{{ url('/') }}"><img src="{{URL::asset('images/blue-logo-1.png')}}" alt="{{ config('app.name', 'TicTacHouse') }}">
                                    </a>
                                </div>
                                
                                <div class="mui-divider-bottom"></div>
                                <div class="body-mail">
                                    <div class="div-text">
                                        <p style="padding:20px; text-align:left;">
                                            {{ __('mail.hi') }} {{ __('mail.clients') }}, <br><br>
                                             {{ __('mail.chambre_dispo') }}
                                             <br><br>
                                             <div class="icon-alert">
                                                <img src="{{URL::asset('img/alert-img.png')}}" width="50" height="50" />
                                             </div>
                                             <p class="txt-icon-alert">{{ __('mail.new_select') }}</p>
                                             <div class="mui-divider-bottom"></div>
                                             
                                             <div class="container-ads">
                                                <div class="div-img">
                                                 <img class="pic_user" src="{{URL::asset('images/profile_avatar.jpeg')}}" width="150" height="80" />
                                                </div>
                                                 <div class="div-ads">
                                                     <p class="address"><img class="icone-lieux" style="position: relative;top: 5px;" width="30" height="30" src="/img/icone-lieux.png"/> <span>{{ __('mail.paris') }}</span></p>
                                                     <p class="title">{{ __('mail.alert_title') }}</p>
                                                     <p><span class="price">450€</span> {{ __('mail.per_month') }}</p>
                                                     <p class="p-btn"><a href="" class="btn-view-ads">{{ __('mail.voir_annonce') }}</a></p>
                                                 </div>
                                             </div>
                                        </p>
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
    <style type="text/css">
        body {
  width: 100% !important;
  min-width: 100%;
  margin: 0;
  padding: 0;
}

img {
  border: 0 none;
  height: auto;
  line-height: 100%;
  outline: none;
  text-decoration: none;
}

a img {
  border: 0 none;
}

table {
  border-spacing: 0;
  border-collapse: collapse;
}

td {
  padding: 0;
  text-align: left;
  word-break: break-word;
  -webkit-hyphens: auto;
  -moz-hyphens: auto;
  hyphens: auto;
  border-collapse: collapse !important;
}

table, td {
  mso-table-lspace: 0pt;
  mso-table-rspace: 0pt;
}

body,
table,
td,
p,
a,
li,
blockquote {
  -webkit-text-size-adjust: 100%;
  -ms-text-size-adjust: 100%;
}

img {
  -ms-interpolation-mode: bicubic;
}

body {
  color: #212121;
  font-family: "Helvetica Neue", Helvetica, Arial, Verdana, "Trebuchet MS";
  font-weight: 400;
  font-size: 14px;
  line-height: 1.429;
  letter-spacing: 0.001em;
  background-color: #FFF;
}

a {
  color: #2196F3;
  text-decoration: none;
}

p {
  margin: 0 0 10px;
}

hr {
  color: #e0e0e0;
  background-color: #e0e0e0;
  height: 1px;
  border: none;
}

.mui-body {
  margin: 0;
  padding: 0;
  height: 100%;
  width: 100%;
  color: #212121;
  font-family: "Helvetica Neue", Helvetica, Arial, Verdana, "Trebuchet MS";
  font-weight: 400;
  font-size: 14px;
  line-height: 1.429;
  letter-spacing: 0.001em;
  background-color: #FFF;
}

.mui-container, .mui-container-fixed {
  max-width: 600px;
  display: block;
  margin: 0 auto;
  clear: both;
  text-align: left;
  /*padding-left: 15px;
  padding-right: 15px;*/
}

.mui-container-fixed {
  width: 600px;
}

strong {
  font-weight: 700;
}

h1, h2, h3 {
  margin-top: 20px;
  margin-bottom: 10px;
}

h4, h5, h6 {
  margin-top: 10px;
  margin-bottom: 10px;
}

.mui-text-display4 {
  font-weight: 300;
  font-size: 112px;
  line-height: 112px;
  letter-spacing: -0.01em;
  color: rgba(33, 33, 33, 0.54);
  color: #212121;
}

.mui-text-display4.mui-text-black {
  color: rgba(0, 0, 0, 0.54);
}

.mui-text-display4.mui-text-white {
  color: rgba(255, 255, 255, 0.7);
}

.mui-text-display4.mui-text-accent {
  color: rgba(255, 64, 129, 0.54);
}

.mui-text-display4.mui-text-black {
  color: #212121;
}

.mui-text-display4.mui-text-white {
  color: #FFF;
}

.mui-text-display4.mui-text-accent {
  color: #ff82ad;
}

.mui-text-display3 {
  font-weight: 400;
  font-size: 56px;
  line-height: 56px;
  letter-spacing: -0.005em;
  color: rgba(33, 33, 33, 0.54);
  color: #212121;
}

.mui-text-display3.mui-text-black {
  color: rgba(0, 0, 0, 0.54);
}

.mui-text-display3.mui-text-white {
  color: rgba(255, 255, 255, 0.7);
}

.mui-text-display3.mui-text-accent {
  color: rgba(255, 64, 129, 0.54);
}

.mui-text-display3.mui-text-black {
  color: #212121;
}

.mui-text-display3.mui-text-white {
  color: #FFF;
}

.mui-text-display3.mui-text-accent {
  color: #ff82ad;
}

.mui-text-display2 {
  font-weight: 400;
  font-size: 45px;
  line-height: 48px;
  letter-spacing: 0em;
  color: rgba(33, 33, 33, 0.54);
  color: #212121;
}

.mui-text-display2.mui-text-black {
  color: rgba(0, 0, 0, 0.54);
}

.mui-text-display2.mui-text-white {
  color: rgba(255, 255, 255, 0.7);
}

.mui-text-display2.mui-text-accent {
  color: rgba(255, 64, 129, 0.54);
}

.mui-text-display2.mui-text-black {
  color: #212121;
}

.mui-text-display2.mui-text-white {
  color: #FFF;
}

.mui-text-display2.mui-text-accent {
  color: #ff82ad;
}

.mui-text-display1, h1 {
  font-weight: 400;
  font-size: 34px;
  line-height: 40px;
  letter-spacing: 0em;
  color: rgba(33, 33, 33, 0.54);
  color: #212121;
}

.mui-text-display1.mui-text-black, h1.mui-text-black {
  color: rgba(0, 0, 0, 0.54);
}

.mui-text-display1.mui-text-white, h1.mui-text-white {
  color: rgba(255, 255, 255, 0.7);
}

.mui-text-display1.mui-text-accent, h1.mui-text-accent {
  color: rgba(255, 64, 129, 0.54);
}

.mui-text-display1.mui-text-black, h1.mui-text-black {
  color: #212121;
}

.mui-text-display1.mui-text-white, h1.mui-text-white {
  color: #FFF;
}

.mui-text-display1.mui-text-accent, h1.mui-text-accent {
  color: #ff82ad;
}

.mui-text-headline, h2 {
  font-weight: 400;
  font-size: 24px;
  line-height: 32px;
  letter-spacing: 0em;
  color: rgba(33, 33, 33, 0.87);
  color: #212121;
}

.mui-text-headline.mui-text-black, h2.mui-text-black {
  color: rgba(0, 0, 0, 0.87);
}

.mui-text-headline.mui-text-white, h2.mui-text-white {
  color: white;
}

.mui-text-headline.mui-text-accent, h2.mui-text-accent {
  color: rgba(255, 64, 129, 0.87);
}

.mui-text-headline.mui-text-black, h2.mui-text-black {
  color: #212121;
}

.mui-text-headline.mui-text-white, h2.mui-text-white {
  color: #FFF;
}

.mui-text-headline.mui-text-accent, h2.mui-text-accent {
  color: #ff82ad;
}

.mui-text-title, h3 {
  font-weight: 400;
  font-size: 20px;
  line-height: 28px;
  letter-spacing: 0.005em;
  color: rgba(33, 33, 33, 0.87);
  color: #212121;
}

.mui-text-title.mui-text-black, h3.mui-text-black {
  color: rgba(0, 0, 0, 0.87);
}

.mui-text-title.mui-text-white, h3.mui-text-white {
  color: white;
}

.mui-text-title.mui-text-accent, h3.mui-text-accent {
  color: rgba(255, 64, 129, 0.87);
}

.mui-text-title.mui-text-black, h3.mui-text-black {
  color: #212121;
}

.mui-text-title.mui-text-white, h3.mui-text-white {
  color: #FFF;
}

.mui-text-title.mui-text-accent, h3.mui-text-accent {
  color: #ff82ad;
}

.mui-text-subhead, h4 {
  font-weight: 400;
  font-size: 16px;
  line-height: 24px;
  letter-spacing: 0.001em;
  color: rgba(33, 33, 33, 0.87);
  color: #212121;
}

.mui-text-subhead.mui-text-black, h4.mui-text-black {
  color: rgba(0, 0, 0, 0.87);
}

.mui-text-subhead.mui-text-white, h4.mui-text-white {
  color: white;
}

.mui-text-subhead.mui-text-accent, h4.mui-text-accent {
  color: rgba(255, 64, 129, 0.87);
}

.mui-text-subhead.mui-text-black, h4.mui-text-black {
  color: #212121;
}

.mui-text-subhead.mui-text-white, h4.mui-text-white {
  color: #FFF;
}

.mui-text-subhead.mui-text-accent, h4.mui-text-accent {
  color: #ff82ad;
}

.mui-text-body2, h5 {
  font-weight: 500;
  font-size: 14px;
  line-height: 24px;
  letter-spacing: 0.001em;
  color: rgba(33, 33, 33, 0.87);
  color: #212121;
}

.mui-text-body2.mui-text-black, h5.mui-text-black {
  color: rgba(0, 0, 0, 0.87);
}

.mui-text-body2.mui-text-white, h5.mui-text-white {
  color: white;
}

.mui-text-body2.mui-text-accent, h5.mui-text-accent {
  color: rgba(255, 64, 129, 0.87);
}

.mui-text-body2.mui-text-black, h5.mui-text-black {
  color: #212121;
}

.mui-text-body2.mui-text-white, h5.mui-text-white {
  color: #FFF;
}

.mui-text-body2.mui-text-accent, h5.mui-text-accent {
  color: #ff82ad;
}

.mui-text-body1 {
  font-weight: 400;
  font-size: 14px;
  line-height: 20px;
  letter-spacing: 0.001em;
  color: rgba(33, 33, 33, 0.87);
  color: #212121;
}

.mui-text-body1.mui-text-black {
  color: rgba(0, 0, 0, 0.87);
}

.mui-text-body1.mui-text-white {
  color: white;
}

.mui-text-body1.mui-text-accent {
  color: rgba(255, 64, 129, 0.87);
}

.mui-text-body1.mui-text-black {
  color: #212121;
}

.mui-text-body1.mui-text-white {
  color: #FFF;
}

.mui-text-body1.mui-text-accent {
  color: #ff82ad;
}

.mui-text-caption {
  font-weight: 400;
  font-size: 12px;
  line-height: 16px;
  letter-spacing: 0.002em;
  color: rgba(33, 33, 33, 0.54);
  color: #212121;
}

.mui-text-caption.mui-text-black {
  color: rgba(0, 0, 0, 0.54);
}

.mui-text-caption.mui-text-white {
  color: rgba(255, 255, 255, 0.7);
}

.mui-text-caption.mui-text-accent {
  color: rgba(255, 64, 129, 0.54);
}

.mui-text-caption.mui-text-black {
  color: #212121;
}

.mui-text-caption.mui-text-white {
  color: #FFF;
}

.mui-text-caption.mui-text-accent {
  color: #ff82ad;
}

.mui-text-menu {
  font-weight: 500;
  font-size: 13px;
  line-height: 17px;
  letter-spacing: 0.001em;
  color: rgba(33, 33, 33, 0.87);
  color: #212121;
}

.mui-text-menu.mui-text-black {
  color: rgba(0, 0, 0, 0.87);
}

.mui-text-menu.mui-text-white {
  color: white;
}

.mui-text-menu.mui-text-accent {
  color: rgba(255, 64, 129, 0.87);
}

.mui-text-menu.mui-text-black {
  color: #212121;
}

.mui-text-menu.mui-text-white {
  color: #FFF;
}

.mui-text-menu.mui-text-accent {
  color: #ff82ad;
}

.mui-text-button {
  font-weight: 400;
  font-size: 14px;
  line-height: 18px;
  letter-spacing: 0.05em;
  color: rgba(33, 33, 33, 0.87);
  text-transform: uppercase;
  color: #212121;
}

.mui-text-button.mui-text-black {
  color: rgba(0, 0, 0, 0.87);
}

.mui-text-button.mui-text-white {
  color: white;
}

.mui-text-button.mui-text-accent {
  color: rgba(255, 64, 129, 0.87);
}

.mui-text-button.mui-text-black {
  color: #212121;
}

.mui-text-button.mui-text-white {
  color: #FFF;
}

.mui-text-button.mui-text-accent {
  color: #ff82ad;
}

.mui-btn {
  cursor: pointer;
  white-space: nowrap;
}

a.mui-btn {
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
  padding: 10px 25px;
  background-color: transparent;
  border-top: 1px solid transparent;
  border-left: 1px solid transparent;
  border-right: 1px solid transparent;
  border-bottom: 1px solid transparent;
}

a.mui-btn.mui-btn-default {
  color: #212121;
  background-color: #FFF;
  border-top: 1px solid #FFF;
  border-left: 1px solid #FFF;
  border-right: 1px solid #FFF;
  border-bottom: 1px solid #FFF;
}

a.mui-btn.mui-btn-default.mui-btn-raised {
  border-top: 1px solid #f2f2f2;
  border-left: 1px solid #e6e6e6;
  border-right: 1px solid #e6e6e6;
  border-bottom: 2px solid #bababa;
}

a.mui-btn.mui-btn-default.mui-btn-flat {
  background-color: transparent;
  color: #212121;
  border-top: 1px solid transparent;
  border-left: 1px solid transparent;
  border-right: 1px solid transparent;
  border-bottom: 1px solid transparent;
}

a.mui-btn.mui-btn-primary {
  color: #FFF;
  background-color: #2196F3;
  border-top: 1px solid #2196F3;
  border-left: 1px solid #2196F3;
  border-right: 1px solid #2196F3;
  border-bottom: 1px solid #2196F3;
}

a.mui-btn.mui-btn-primary.mui-btn-raised {
  border-top: 1px solid #51adf6;
  border-left: 1px solid #2196F3;
  border-right: 1px solid #2196F3;
  border-bottom: 2px solid #0a6ebd;
}

a.mui-btn.mui-btn-primary.mui-btn-flat {
  background-color: transparent;
  color: #2196F3;
  border-top: 1px solid transparent;
  border-left: 1px solid transparent;
  border-right: 1px solid transparent;
  border-bottom: 1px solid transparent;
}

a.mui-btn.mui-btn-danger {
  color: #FFF;
  background-color: #F44336;
  border-top: 1px solid #F44336;
  border-left: 1px solid #F44336;
  border-right: 1px solid #F44336;
  border-bottom: 1px solid #F44336;
}

a.mui-btn.mui-btn-danger.mui-btn-raised {
  border-top: 1px solid #f77066;
  border-left: 1px solid #F44336;
  border-right: 1px solid #F44336;
  border-bottom: 2px solid #d2190b;
}

a.mui-btn.mui-btn-danger.mui-btn-flat {
  background-color: transparent;
  color: #F44336;
  border-top: 1px solid transparent;
  border-left: 1px solid transparent;
  border-right: 1px solid transparent;
  border-bottom: 1px solid transparent;
}

a.mui-btn.mui-btn-accent {
  color: #FFF;
  background-color: #FF4081;
  border-top: 1px solid #FF4081;
  border-left: 1px solid #FF4081;
  border-right: 1px solid #FF4081;
  border-bottom: 1px solid #FF4081;
}

a.mui-btn.mui-btn-accent.mui-btn-raised {
  border-top: 1px solid #ff73a3;
  border-left: 1px solid #FF4081;
  border-right: 1px solid #FF4081;
  border-bottom: 2px solid #f30053;
}

a.mui-btn.mui-btn-accent.mui-btn-flat {
  background-color: transparent;
  color: #FF4081;
  border-top: 1px solid transparent;
  border-left: 1px solid transparent;
  border-right: 1px solid transparent;
  border-bottom: 1px solid transparent;
}

table.mui-btn > tr > td,
table.mui-btn > tbody > tr > td {
  border-radius: 3px;
}

table.mui-btn > tr > td > a,
table.mui-btn > tbody > tr > td > a {
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
  padding: 10px 25px;
  background-color: transparent;
  border-top: 1px solid transparent;
  border-left: 1px solid transparent;
  border-right: 1px solid transparent;
  border-bottom: 1px solid transparent;
}

table.mui-btn.mui-btn-default > tr > td,
table.mui-btn.mui-btn-default > tbody > tr > td {
  background-color: #FFF;
}

table.mui-btn.mui-btn-default > tr > td > a,
table.mui-btn.mui-btn-default > tbody > tr > td > a {
  color: #212121;
  border-top: 1px solid #FFF;
  border-left: 1px solid #FFF;
  border-right: 1px solid #FFF;
  border-bottom: 1px solid #FFF;
}

table.mui-btn.mui-btn-default.mui-btn-raised > tr > td > a,
table.mui-btn.mui-btn-default.mui-btn-raised > tbody > tr > td > a {
  border-top: 1px solid #f2f2f2;
  border-left: 1px solid #e6e6e6;
  border-right: 1px solid #e6e6e6;
  border-bottom: 2px solid #bababa;
}

table.mui-btn.mui-btn-default.mui-btn-flat > tr > td,
table.mui-btn.mui-btn-default.mui-btn-flat > tbody > tr > td {
  background-color: transparent;
}

table.mui-btn.mui-btn-default.mui-btn-flat > tr > td > a,
table.mui-btn.mui-btn-default.mui-btn-flat > tbody > tr > td > a {
  color: #212121;
  border-top: 1px solid transparent;
  border-left: 1px solid transparent;
  border-right: 1px solid transparent;
  border-bottom: 1px solid transparent;
}

table.mui-btn.mui-btn-primary > tr > td,
table.mui-btn.mui-btn-primary > tbody > tr > td {
  background-color: #2196F3;
}

table.mui-btn.mui-btn-primary > tr > td > a,
table.mui-btn.mui-btn-primary > tbody > tr > td > a {
  color: #FFF;
  border-top: 1px solid #2196F3;
  border-left: 1px solid #2196F3;
  border-right: 1px solid #2196F3;
  border-bottom: 1px solid #2196F3;
}

table.mui-btn.mui-btn-primary.mui-btn-raised > tr > td > a,
table.mui-btn.mui-btn-primary.mui-btn-raised > tbody > tr > td > a {
  border-top: 1px solid #51adf6;
  border-left: 1px solid #2196F3;
  border-right: 1px solid #2196F3;
  border-bottom: 2px solid #0a6ebd;
}

table.mui-btn.mui-btn-primary.mui-btn-flat > tr > td,
table.mui-btn.mui-btn-primary.mui-btn-flat > tbody > tr > td {
  background-color: transparent;
}

table.mui-btn.mui-btn-primary.mui-btn-flat > tr > td > a,
table.mui-btn.mui-btn-primary.mui-btn-flat > tbody > tr > td > a {
  color: #2196F3;
  border-top: 1px solid transparent;
  border-left: 1px solid transparent;
  border-right: 1px solid transparent;
  border-bottom: 1px solid transparent;
}

table.mui-btn.mui-btn-danger > tr > td,
table.mui-btn.mui-btn-danger > tbody > tr > td {
  background-color: #F44336;
}

table.mui-btn.mui-btn-danger > tr > td > a,
table.mui-btn.mui-btn-danger > tbody > tr > td > a {
  color: #FFF;
  border-top: 1px solid #F44336;
  border-left: 1px solid #F44336;
  border-right: 1px solid #F44336;
  border-bottom: 1px solid #F44336;
}

table.mui-btn.mui-btn-danger.mui-btn-raised > tr > td > a,
table.mui-btn.mui-btn-danger.mui-btn-raised > tbody > tr > td > a {
  border-top: 1px solid #f77066;
  border-left: 1px solid #F44336;
  border-right: 1px solid #F44336;
  border-bottom: 2px solid #d2190b;
}

table.mui-btn.mui-btn-danger.mui-btn-flat > tr > td,
table.mui-btn.mui-btn-danger.mui-btn-flat > tbody > tr > td {
  background-color: transparent;
}

table.mui-btn.mui-btn-danger.mui-btn-flat > tr > td > a,
table.mui-btn.mui-btn-danger.mui-btn-flat > tbody > tr > td > a {
  color: #F44336;
  border-top: 1px solid transparent;
  border-left: 1px solid transparent;
  border-right: 1px solid transparent;
  border-bottom: 1px solid transparent;
}

table.mui-btn.mui-btn-accent > tr > td,
table.mui-btn.mui-btn-accent > tbody > tr > td {
  background-color: #FF4081;
}

table.mui-btn.mui-btn-accent > tr > td > a,
table.mui-btn.mui-btn-accent > tbody > tr > td > a {
  color: #FFF;
  border-top: 1px solid #FF4081;
  border-left: 1px solid #FF4081;
  border-right: 1px solid #FF4081;
  border-bottom: 1px solid #FF4081;
}

table.mui-btn.mui-btn-accent.mui-btn-raised > tr > td > a,
table.mui-btn.mui-btn-accent.mui-btn-raised > tbody > tr > td > a {
  border-top: 1px solid #ff73a3;
  border-left: 1px solid #FF4081;
  border-right: 1px solid #FF4081;
  border-bottom: 2px solid #f30053;
}

table.mui-btn.mui-btn-accent.mui-btn-flat > tr > td,
table.mui-btn.mui-btn-accent.mui-btn-flat > tbody > tr > td {
  background-color: transparent;
}

table.mui-btn.mui-btn-accent.mui-btn-flat > tr > td > a,
table.mui-btn.mui-btn-accent.mui-btn-flat > tbody > tr > td > a {
  color: #FF4081;
  border-top: 1px solid transparent;
  border-left: 1px solid transparent;
  border-right: 1px solid transparent;
  border-bottom: 1px solid transparent;
}

a.mui-btn-lg,
table.mui-btn-lg > tr > td > a,
table.mui-btn-lg > tbody > tr > td > a {
  padding: 19px 25px;
}

.mui-panel {
  /*padding: 15px;*/
  border-radius: 0;
  background-color: #FFF;
  border-top: 1px solid #ededed;
  border-left: 1px solid #e6e6e6;
  border-right: 1px solid #e6e6e6;
  border-bottom: 2px solid #d4d4d4;
}

.mui-divider {
  display: block;
  height: 1px;
  background-color: #e0e0e0;
}

.mui-divider-top {
  border-top: 1px solid #e0e0e0;
}

.mui-divider-bottom {
  border-bottom: 1px solid #e0e0e0;
}

.mui-divider-left {
  border-left: 1px solid #e0e0e0;
}

.mui-divider-right {
  border-right: 1px solid #e0e0e0;
}

.mui-text-left {
  text-align: left;
}

.mui-text-right {
  text-align: right;
}

.mui-text-center {
  text-align: center;
}

.mui-text-justify {
  text-align: justify;
}

.mui-image-fix {
  display: block;
}

.div-ads
{
    display: inline-block;
    vertical-align: top;
    margin-left: 10px;
    width: 58%;
}

.btn-view-ads
{
    padding: 10px;
    background: #2196F3;
    color: white;
    border-radius: 4px;
}

.price
{
    font-weight: bold;
    font-size: 20px;
}

.title
{
    font-size: 18px;
}

.address
{
    font-size: 18px;
    color: #2196F3;
}

.icon-adress
{
    margin-right: 10px;
}

.p-btn
{
    margin-top: 20px;
}

.icon-alert
{
    margin-bottom: 5px;
}

.txt-icon-alert
{
    font-size: 25px;
    font-weight: bold;
    margin-bottom: 25px;
}

.mui-container
{
    max-width: 100% !important;
}

.body-mail
{
    background: #e7f2ff;
    padding-top: 20px;
    padding-bottom: 20px
}

.div-text
{
    padding: 5px;
    background : white;
    width: 50%;
    margin: auto;
    padding: 10px;
}

@media screen and (min-width : 500px)
{
    .div-text
    {
        width: 50%;
        margin: auto;
        padding: 20px;
    }
}

.logo-containter
{
    padding-top: 20px;
}

.div-img
{
  display: inline-block;
}

.container-ads
{
  margin-top: 20px;
  margin-bottom: 20px;
}

.icon-l
{
  position: relative;
  top: 5px;
}

.div-no-pic
{
  width: 250px;
  height: 200px;
  text-align: center;
  border: 0.5px grey solid;
}

.no_pic
{
  margin-top: 35px;
}

.pic_user
{
  border-radius: 50%;
  margin-top: 22px;
}

    </style>
</html>