<input type="hidden" id="pageNotifVisit" class="pageNotif" value="{{$page}}"/>
@foreach($read_messages as $message)
<div class="flash-div">
    <div class="row">
    <div class="text-notif-div">
        <p class="notif-nom-flash">{{$message->title}}</p>
        <p class="notif-text-flash">{{__('notification.request_notif', ["first_name" => $message->userInfo->first_name])}}</p>
        <p class="notif-date-flash">{{translateDuration($message->created_at)}}</p>
    </div>
    <img class="img-notif" @if(!is_null($message->userInfo->user_profiles->profile_pic)) src="/uploads/profile_pics/{{$message->userInfo->user_profiles->profile_pic}}" @else src="/images/profile_avatar.jpeg" @endif alt="image-utilisateur"/>
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