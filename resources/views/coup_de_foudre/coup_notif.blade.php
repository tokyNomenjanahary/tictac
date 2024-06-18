<a href="javascript:" class="close-notif"><span>x</span></a>
<div class="row toctoc_popup" @if(!is_null($unread_notif->sender_ad_id)) data-href="{{adUrl($unread_notif->sender_ad_id)}}" @else data-href="{{userUrl($userInfo->id) . "/" . $adInfo->id}}" @endif>
	<p class="notif-title">
		<a href="javascript:"> <img width="30" height="20" src="/img/icons/toctoc-blue.png">{{__('notif.coup_de_foudre_title')}}</a>
	</p>
	<p class="notif-date">- {{translateDuration($unread_notif->created_at)}}</p>
	<div class="row">
		<div class="text-notif-div text-notif-flash">
			<p class="notif-ad">{{$adInfo->title}}</p>
			<p><?php echo __('notif.coup_de_foudre_notif', ["first_name" => $userInfo->first_name, "url_user" => userUrl($userInfo->id) . "/" . $adInfo->id]); ?></p>
		</div>
		<img class="img-notif-flash" @if(!is_null($userInfo->profile_pic)) src="/uploads/profile_pics/{{$userInfo->profile_pic}}" @else src="/images/profile_avatar.jpeg" @endif alt="image-utilisateur"/>
	</div>
</div>