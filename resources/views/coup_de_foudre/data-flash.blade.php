@foreach($flashs as $key => $flash)
<div class="flash-div">
	<div class="row">
	<div class="text-notif-div">
		<p class="notif-nom-flash">{{$flash->title}}</p>
		<p class="notif-text-flash">{{__('header.flash_message', ["first_name" => $flash->first_name])}}</p>
		<p class="notif-date-flash">{{translateDuration($flash->created_at)}}</p>
	</div>
	<img class="img-notif-flash" @if(!is_null($flash->profile_pic)) src="/uploads/profile_pics/{{$flash->profile_pic}}" @else src="/images/profile_avatar.jpeg" @endif alt="image-utilisateur"/>
	</div>
</div>
@endforeach