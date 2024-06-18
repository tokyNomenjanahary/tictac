<a href="javascript:" class="close-notif"><span>x</span></a>
<div class="row">
	<p class="notif-title">
		<a href="javascript:"> <img width="30" height="20" src="/img/list-candidature-annonce.png">{{__('notif.application')}}</a>
	</p>
	<p class="notif-date">- {{translateDuration($unread_notif->created_at)}}</p>
</div>
<a href="{{ url('/mes-candidatures/recu') }}">
    <div class="row">
        <div class="text-notif-div">
            <p class="notif-ad">{{$adInfo->title}}</p>
            <p class="notif-text-visit">{{__('notif.application_notif_text', ["first_name" => $userInfo->first_name])}}</p>
        </div>
        <img class="img-notif" @if(!is_null($userInfo->profile_pic)) src="/uploads/profile_pics/{{$userInfo->profile_pic}}" @else src="/images/profile_avatar.jpeg" @endif alt="image-utilisateur"/>
    </div>
</a>

