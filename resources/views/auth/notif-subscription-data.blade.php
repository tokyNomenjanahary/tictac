<a href="javascript:" class="close-notif"><span>x</span></a>
<div class="row">
	<p class="notif-title">
		<a href="javascript:"> <img width="30" height="20" src="/img/icons/notification-cloche1.png">Notification</a>
	</p>
	<!-- <p class="notif-date">- {{translateDuration($unread_pack->created_at)}}</p> -->
	</div>
	<div class="row">
	<div class="text-notif-div">
		<p class="notif-nom">{{getFirstWord($userInfo->first_name)}}</p>
		<p class="notif-text-visit">a souscrit à l'abonnement {{traduct_info_bdd($packageInfo->title)}} <!-- pour {{$packageInfo->duration}} {{traduct_info_bdd($packageInfo->unite)}} --></p>
	</div>
	<!-- <img class="img-notif" @if(isset($userInfo->user_profiles) && !is_null($userInfo->user_profiles->profile_pic)) src="/uploads/profile_pics/{{$userInfo->user_profiles->profile_pic}}" @else src="/images/profile_avatar.jpeg" @endif alt="image-utilisateur"/> -->
</div>