<div id="carouselExampleControls" style="margin-top:15px;" class="fb-frnd-list white-bx-shadow m-b-1 carousel" data-ride="carousel">
	<div class="carousel-inner">
	@foreach($friendsFb as $key => $fbFriend)
		@if($key==0)
			<div class="item active">
		@elseif($key%10==0 && $key != count($friendsFb))
			</div>
			<div class="item">
		@endif
		<div style="display:inline-block;"><img style="border-radius:50%;display:block;margin:auto;" width="40" height="40" src="{{$fbFriend->pdp}}" /><p style="font-size:0.7em;">{{$fbFriend->fb_friend_name}}</p></div>
	@endforeach
	  </div>
	</div>
</div>