<a href="javascript:" class="close-notif"><span>x</span></a>
<div class="row">
	<p class="notif-title">
		<a href="javascript:"> <img width="30" height="20" src="/img/icons/notification-cloche1.png">Notification</a>
	</p>
	<p class="notif-date">- {{translateDuration($unread_reg->created_at)}}</p>
	</div>
	<div class="row">
	<div class="text-notif-div">
		<p class="notif-nom">{{getFirstWord($userInfo->first_name)}}</p>
		<p class="notif-message">vient de s'inscrire</p>
	</div>
	
	<!-- <img class="img-notif" @if(!is_null($userInfo->user_profiles->profile_pic)) src="/uploads/profile_pics/{{$userInfo->user_profiles->profile_pic}}" @else src="/images/profile_avatar.jpeg" @endif alt="image-utilisateur"/> -->
</div>
