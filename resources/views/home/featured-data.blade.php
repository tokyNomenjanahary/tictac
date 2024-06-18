<div id="featured_room_mates_div">
@include("home.featured_room_mates")
</div>
<!--h  -->
@if(!empty($featured_rooms) && count($featured_rooms) > 0 )
<div class="collocataire recherchant logements clearfix">
    <div class="inner">
        <h3>
        	{{ __("acceuil.logement_list") }} @if(isset($ville)) {{__("acceuil.a_ville", ["ville" => $ville])}} @endif
        </h3>
        <!-- <div class="custom-pagination">
        	<div class="pagination-button pagination-button-prev">
        	<a href="javascript:"><</a>
        	</div>
        	<div class="pagination-button pagination-button-next">
        	<a href="javascript:">></a>
        	</div>
        </div> -->
        <div class="publication">
			@foreach($featured_rooms as $featured_room)
				<a itemscope itemtype="{{ adUrl($featured_room->id)  }}" href="{{ adUrl($featured_room->id)  }}" class="publ">
					<span itemprop="Prix" class="prix">@if(!empty($featured_room) && !empty($featured_room->min_rent)) {{'&euro;'.$featured_room->min_rent}} @endif/ {{__("acceuil.mois")}}</span>
					@if(!empty($featured_room->ad_files) && count($featured_room->ad_files) > 1)
						<div class="slick-slider" id="slick-slider-room-{{$featured_room->id}}">
							@foreach($featured_room->ad_files as $ad_file)
							<div>
								<img itemprop="Image" class="ad-image active-custom-carousel" src="{{'/uploads/images_annonces/' . $ad_file->filename}}" >
							</div>
							@endforeach
						</div>
					<div class='custom-carousel-prev' data-id="slick-slider-room-{{$featured_room->id}}"><img src="/img/small-img.png" width="40" height="40"/></div>
					<div class='custom-carousel-next' data-id="slick-slider-room-{{$featured_room->id}}"><img src="/img/small-img.png" width="40" height="40"/></div>
					@else
					<img itemprop="image" width="1024" height="683" class="ad-image active-custom-carousel" @if(!empty($featured_room->ad_files) && count($featured_room->ad_files) > 0)  src="{{'/uploads/images_annonces/' . $featured_room->ad_files[0]->filename }}" @else  src="/images/room_no_pic.png" @endif>
					@endif
					<div class="items-publ">
						<img itemprop="Photo du propriétaire"  width="80" height="80"class="profile-image" @if(!empty($featured_room->user->user_profiles->profile_pic))  src="{{'/uploads/profile_pics/' . $featured_room->user->user_profiles->profile_pic}}" @else  src="/images/profile_avatar.jpeg"  @endif>
						<h5 itemprop="Adresse">@if(!empty($featured_room) && !empty($featured_room->address)) {{$featured_room->address}} @endif</h5>
						<span itemprop="Titre de la publication" class="logement">@if(!empty($featured_room) && !empty($featured_room->title)) {{$featured_room->title}} @endif</span>
						<p itemprop="Description" > @if(!empty($featured_room) && !empty($featured_room->description)){{substr($featured_room->description, 0, 130)}} @if(strlen($featured_room->description) > 130)...@endif @endif</p>
						<span itemprop="Date de publication" class="date"> {{translateDuration($featured_room->created_at)}}</span>
					</div>
				</a>
			@endforeach
        </div>
    </div>
</div>
@endif
<!-- h -->

@if(!empty($fractured_visits) && count($fractured_visits) > 0 )
<div class="collocataire recherchant logements clearfix">
    <div class="inner">
        <h3>{{ __("acceuil.visit_list") }} @if(isset($ville)) {{__("acceuil.a_ville", ["ville" => $ville])}} @endif</h3>
        <div class="publication">
			@foreach($fractured_visits as $fractured_visit)
            <a itemscope itemtype="{{ adUrl($fractured_visit->id)  }}" href="{{ adUrl($fractured_visit->id)  }}" class="publ">
                <span itemprop="Titre de la publication" class="item-prop-hidden">{{$fractured_visit->title}}</span>
				<span itemprop="Prix" class="prix">@if(!empty($fractured_visit) && !empty($fractured_visit->min_rent)) {{'&euro;'.$fractured_visit->min_rent}} @endif/ {{__("acceuil.mois")}}</span>
                @if(!empty($fractured_visit->ad_files) && count($fractured_visit->ad_files) > 1)
					<div class="slick-slider" id="slick-slider-visit-{{$fractured_visit->id}}">
						@foreach($fractured_visit->ad_files as $ad_file)
						<div>
							<img itemprop="Image" class="ad-image active-custom-carousel" src="{{'/uploads/images_annonces/' . $ad_file->filename}}" >
						</div>
						@endforeach
					</div>
				<div class='custom-carousel-prev' data-id="slick-slider-visit-{{$fractured_visit->id}}"><img src="/img/small-img.png" width="40" height="40"/></div>
				<div class='custom-carousel-next' data-id="slick-slider-visit-{{$fractured_visit->id}}"><img src="/img/small-img.png" width="40" height="40"/></div>
				@else
				<img itemprop="Image" width="1024" height="683" class="ad-image" @if(!empty($fractured_visit->ad_files) && count($fractured_visit->ad_files) > 0)  src="{{'/uploads/images_annonces/' . $fractured_visit->ad_files[0]->filename }}" @else  src="/images/room_no_pic.png" @endif>
                @endif
				
				<div class="items-publ">
                    <img width="80" height="80" itemprop="Photo du propriétaire" class="profile-image" @if(!empty($fractured_visit->user->user_profiles->profile_pic))  src="{{'/uploads/profile_pics/' . $fractured_visit->user->user_profiles->profile_pic }}" @else  src="/images/profile_avatar.jpeg" @endif>
                    <h6>{{__("acceuil.plannified_visit")}}</h6>
                    <div class="temps">
						@foreach($fractured_visit->ad_visiting_details as $visiting_detail)	
							@if(!empty($visiting_detail) && !empty($visiting_detail->visiting_date))
							<div class="temps-items">
								<span itemprop="Date de visite" class="calendrier">
								 {{date('d-m-Y', strtotime($visiting_detail->visiting_date)) }}
								 </span>
							</div>
							@endif
							@if(!empty($visiting_detail) && !empty($visiting_detail->start_time))
								<div class="temps-items">
									<span itemprop="Heure de visite" class="heure">
									 {{" " . date('h:i', strtotime($visiting_detail->start_time)) }} @if(!empty($visiting_detail) && !empty($visiting_detail->end_time)) {{"- " . date('h:i', strtotime($visiting_detail->end_time)) }}@endif
									</span>
								</div>
							@endif
						@endforeach
                    </div>
					
                    <h5 itemprop="Adresse" class="nantes  address-visit">@if(!empty($fractured_visit) && !empty($fractured_visit)) {{$fractured_visit->address}} @endif</h5>
					<span itemprop="Date de publication" class="date">{{translateDuration($fractured_visit->created_at)}}</span>
                
                </div>
				
            </a>
			@endforeach
        </div>
    </div>
</div>
@endif