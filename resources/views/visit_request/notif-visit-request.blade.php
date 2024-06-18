<a href="javascript:" class="close-notif"><span>x</span></a>
<div class="row">
	<p class="notif-title">
		<i class="glyph-image glyphicon glyphicon-calendar"></i>
		<span class="message-title">{{__('notification.demande_visite')}}</span>
	</p>
	<p class="notif-date">- {{translateDuration($unread_notif->created_at)}}</p>
	</div>
	<div class="row">
	<div class="text-notif-div">
		<p class="notif-ad">{{$adInfo->title}}</p>
		<p class="notif-text-visit">{{__('notification.request_notif', ["first_name" => $userInfo->first_name])}}</p>
	</div>
	<img class="img-notif" @if(!is_null($userInfo->profile_pic)) src="/uploads/profile_pics/{{$userInfo->profile_pic}}" @else src="/images/profile_avatar.jpeg" @endif alt="image-utilisateur"/>
</div>