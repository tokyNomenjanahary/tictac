@if($count_message==0)
<div class="flash-div flash-div-popup">
    <div class="row">
        <div class="div-englobe" style="height:50px;">
                <span class="spn-iln-block txt-content" style="padding-top: 18px;margin-left:50px;">
                    <span class="notif-text-flash spn-block txt-spn-block">
                        <span style="color: black;font-size:18px;">{{ __('notification.toctoc_vide') }}</span>
                    </span>
                </span> 
        </div>
      
    </div>
</div>
@endif
@foreach($read_messages as $message)
<div class="flash-div flash-div-popup">
    <div class="row">
        <div class="div-englobe">
            <a class="a-block" @if(!is_null($message->sender_ad_id)) href="{{adUrl($message->sender_ad_id)}}" @else href="{{userUrl($message->userInfo->id) . "/" . $message->adInfo->id}}" @endif>
                
                <span class="spn-iln-block txt-content">
                    <span class="notif-nom-flash spn-block">{{$message->adInfo->title}}</span>
                    <span class="notif-text-flash spn-block txt-spn-block">
                        <span class='mess_toctoc'>{{$message->userInfo->first_name}}</span> {{__('notification.toctoc_notif')}}
                    </span>
                    <span class="notif-date-flash spn-block">{{ translateDuration(date("Y-m-d H:i:s"),$message->created_at) }}</span>
                </span>
                <img class="img-toctoc-notif" @if(!is_null($message->userInfo->user_profiles->profile_pic)) src="/uploads/profile_pics/{{$message->userInfo->user_profiles->profile_pic}}" @else src="/images/profile_avatar.jpeg" @endif alt="image-utilisateur"/>
            </a>
        </div>
    </div>
</div>
@endforeach
<div class="beeperNub"></div>