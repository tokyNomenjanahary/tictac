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
                                            {{ __('mail.hi') }} {{$infoUsers->first_name}}, <br><br>
                                             @if(count($ads) > 1)
                                             {{count($ads)}} {{ __('mail.new_ad') }}
                                             @else
                                             {{ __('mail.new_ad') }}
                                             @endif
                                             <br><br>
                                             <div class="icon-alert">
                                                <img src="{{URL::asset('img/alert-img.png')}}" width="50" height="50" />
                                             </div>
                                             @if(count($ads) < 1)
                                             <p class="txt-icon-alert">{{ __('mail.new_select') }}</p>
                                             @else
                                             <p class="txt-icon-alert">{{ __('mail.new_select') }}</p>
                                             @endif
                                             <div class="mui-divider-bottom"></div>
                                             @foreach($ads as $key => $ad)
                                             <div class="container-ads">
                                                @if($ad->scenario_id == 1 || $ad->scenario_id == 2)
                                                     @if(count($ad->ad_files) > 0 && File::exists(storage_path('/uploads/images_annonces/' . $ad->ad_files[0]->filename)))
                                                     <div class="div-img">
                                                            <img src="{{URL::asset('/uploads/images_annonces/' . $ad->ad_files[0]->filename)}}" width="250" height="200" />
                                                     </div>
                                                     @else
                                                     <div class="div-img div-no-pic">
                                                     <img class="no_pic" src="{{URL::asset('images/room_no_pic.png')}}" width="120" height="80" />
                                                    </div>
                                                     @endif
                                                @else
                                                    @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)))
                                                    <div class="div-img">
                                                     <img class="pic_user" src="{{URL::asset('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)}}" width="130" height="80" />
                                                    </div>
                                                    @else
                                                    <div class="div-img">
                                                     <img class="pic_user" src="{{URL::asset('images/profile_avatar.jpeg')}}" width="130" height="80" />
                                                    </div>
                                                    @endif
                                                @endif
                                                 <div class="div-ads">
                                                     <p class="address">
                                                     <img class="icon-l" width="25" height="25" src="{{URL::asset('img/icone-lieux.png')}}"/>
                                                     <span>{{$ad->address}}</span>
                                                     </p>
                                                     <p class="title">{{$ad->title}}</p>
                                                     <p>
                                                     @if($ad->scenario_id == 1 || $ad->scenario_id == 2)
                                                     <span class="price">{{$ad->min_rent}}€</span> {{ __('mail.per_month') }}
                                                     @else
                                                     <span class="price">
                                                     @if(!empty($ad) && !empty($ad->min_rent)){{$ad->min_rent}}€@endif 
                                                     @if(!empty($ad->min_rent) && !empty($ad->max_rent)) - @endif
                                                     @if(!empty($ad->max_rent)){{$ad->max_rent}}€
                                                     @endif
                                                     </span> 
                                                    @if((empty($ad->min_rent) || $ad->min_rent == 0) && (is_null($ad->max_rent)))
                                                    <span class="price">
                                                    {{__('searchlisting.a_negocier')}}
                                                    </span>
                                                    @else
                                                    {{ __('mail.per_month') }}
                                                    @endif
                                                    @endif
                                                     </p>
                                                     <div class="p-btn"><a href="{{adUrl($ad->id)}}" class="btn-view-ads">{{ __('mail.voir_annonce') }}</a></div>
                                                 </div>
                                             </div>
                                             <div class="mui-divider-bottom"></div>
                                             @endforeach
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
</html>