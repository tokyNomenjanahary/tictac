<input type="hidden" id="pageNotifCoupFoudre" class="pageNotif" value="{{$page}}"/>
@foreach($read_messages as $message)
<div class="flash-div">
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
<div class="row div-pagination-notif">
    @if($page > 1)
    <a href="javascript:" data-id="{{$page-1}}" class="prev-notif pagination-notif"><img src="/images/prev.png" width="30" height="30" alt="prev"/> </a>
    @endif
    @if(($count_message / 5) > $page)
    <a href="javascript:" data-id="{{$page+1}}" class="next-notif pagination-notif"><img src="/images/next.png" width="30" height="30" alt="prev"/></a>
    @endif
</div>