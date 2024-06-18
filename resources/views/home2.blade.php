<!DOCTYPE html>
<!-- saved from url=(0037) -->
<html lang="fr-FR" prefix="og: http://ogp.me/ns#"><!--<![endif]--><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>bailti</title>
    <meta name="title" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="icon" href="img/favicon.png">
	<link href="css/css" rel="stylesheet">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styleHome.css" rel="stylesheet" type="text/css">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/developer.css" rel="stylesheet">

    
</head>
{{fb_pixel_code()}}
<body>
    <!--star header-->
    <section class="header clearfix">
        <div class="bar-haut clearfix">
            <h1><img src="img/logo-tictoc.png" alt=" "></h1>
            <div class="holder-nav">
                <ul>
					@guest
					<li>
						<a class="active" href="{{ route('register') }}">{{ __("acceuil.register") }}</a>
                    </li>
                    <li>
						<a href="{{ route('login') }}">{{ __("acceuil.login") }}</a>
                    </li>
					@else
					 <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ __('Hi') }},&nbsp{{ Auth::user()->first_name }} <span class="caret"></span>
                        </a>

                        <ul style="z-index : 10;" class="dropdown-menu" role="menu">
                            <li class="dropdown-item">
                                <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                    <li><a class="annonce post-job-btn post_an_ad" href="#">{{ __("acceuil.post_ad") }}</a>
                    </li>
                    <li class="langue">
                        <select class="changeLangHome">
                            <option value="fr" @if($lang == 'Français') selected @endif>{{ __("acceuil.français") }}</option>
                            <option value="en" @if($lang == 'English') selected @endif >{{ __("acceuil.anglais") }}</option>
                        </select>
                    </li>
                </ul>
            </div>
        </div>
        <div id="slider-holder" class="slider-holder clearfix"  style="background-image: url(&quot;img/slider.jpg&quot;);">
            <div class="title-slider">
                <h2>{{ __('acceuil.embleme1') }}  <br> {{ __('acceuil.embleme2') }}</h2>
            </div>
            <form id="home-search-sc2" method="POST" action="{{ route('rent.accommodation') }}">
				{{ csrf_field() }}
                <div class="adress-slider">
                    <label>
						<input type="text" id="address" onfocus="geolocate()"name="address" placeholder="{{ __('acceuil.searchPlaceHolder') }}">
						<input type="hidden" id="first_latitude" name="latitude">
						<input type="hidden" id="first_longitude" name="longitude">
						<input type="hidden" name="Search" value="Search">
						<a href="javascript:" id="buttonSearch"><button>OK</button></a>
                    </label>
                </div>
                <div class="liste-chambre">
                    <label form-action="{{ route('rent.accommodation') }}" class="type_scenario">
						<a href="javascript:" class="active">
							<input type="radio" name="drone" checked=""><span>{{ __("acceuil.chambre") }}</span>
						</a>
					</label>
                    <label form-action="{{ route('rent.property') }}" class="type_scenario">
						<a href="javascript:">
							<input type="radio" name="drone" checked=""><span>{{ __("acceuil.logement") }}</span>
						</a>
					</label>
                    <label form-action="{{ route('looking.accommodation') }}" class="type_scenario">
						<a href="javascript:">
							<input type="radio" name="drone" checked=""><span><span>{!! __('acceuil.strong_colocataire') !!}</span>
						</a>
					</label>
                        
                    <label form-action="{{ route('looking.property') }}" class="type_scenario">
						<a href="javascript:">
							<input type="radio" name="drone" checked=""><span>{{ __("acceuil.locataire") }}</span>
						</a>
                    </label>
                </div>
            </form>
            <div class="about">
                <img src="img/resize-dynamique.png" alt="">
            </div>
        </div>
    </section>
    <!--star content-->
    <section class="content clearfix">
        <div class="inner">
            <div class="collocataire clearfix">
                <h3>{{ __("acceuil.futur_colocataire") }}</h3>
                <h4>{{ __("acceuil.find_logement") }}</h4>
                <div class="composer-equipe clearfix">
                    <div class="items-equipe">
                        <img src="img/trouver.png">
                        <h4>{{ __("acceuil.find_coloc") }}</h4>
                        <p>{{ __("acceuil.school_coloc_text") }}</p>
                    </div>
                    <div class="items-equipe regroupe">
                        <img src="img/regroupez.png">
                        <h4>{{ __("acceuil.regroupement") }}</h4>
                        <p>{{ __("acceuil.regroupement_text") }}</p>
                    </div>
                    <div class="items-equipe echange">
                        <img src="img/echanger.png">
                        <h4>{{ __("acceuil.echange") }}</h4>
                        <p>{{ __("acceuil.echange_text") }}</p>
                    </div>
                    <div class="items-equipe ">
                        <img src="img/candidature.png">
                        <h4>{{ __("acceuil.send_apply") }}</h4>
                        <p>{{ __("acceuil.send_apply_text") }}</p>
                    </div>
                    <div class="items-equipe ">
                        <img src="img/compser.png">
                        <h4>{{ __("acceuil.team") }}</h4>
                        <p>{{ __("acceuil.team_text") }}</p>
                    </div>
                </div>
            </div>
            <div class="collocataire bailleurs clearfix">
                <h3>{{ __("acceuil.solution") }}</h3>
                <h4>{{ __("acceuil.gagner_temp") }}</h4>
                <div class="composer-equipe clearfix">
                    <div class="items-equipe gerez">
                        <img src="img/icon-gerer.png">
                        <h4>{{ __("acceuil.manage_apply") }}</h4>
                    </div>
                    <div class="items-equipe chattez">
                        <img src="img/chatter.png">
                        <h4>{{ __("acceuil.chat") }}</h4>
                    </div>
                    <div class="items-equipe regroupez">
                        <img src="img/regroupezg.png">
                        <h4>{{ __("acceuil.regroupez_coloc") }}</h4>
                    </div>
                    <div class="items-equipe louez">
                        <img src="img/louez.png">
                        <h4>{{ __("acceuil.louez") }}</h4>
                    </div>
                    <div class="items-equipe proposez">
                        <img src="img/icon-visiter.png">
                        <h4>{{ __("acceuil.proposer") }}</h4>
                    </div>
                    <div class="items-equipe location">
                        <img src="img/scurise.png">
                        <h4>{{ __("acceuil.securiser") }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
	@if(!empty($featured_room_mates) && count($featured_room_mates) > 0 )
    <div class="collocataire recherchant clearfix">
        <div class="inner">
            <h3>{{ __("acceuil.coloc_searcher") }}</h3>
            <div class="recherchant-holder list_coloc">
				@foreach($featured_room_mates as $featured_room_mate)
				<a class="" href="{{ route('view.ad', [str_slug($featured_room_mate->ad_details->property_type->property_type),$featured_room_mate->url_slug . '-' . $featured_room_mate->id]) }}">
                <div class="block-items publ-room-mate">
                    <img class="profile-image" @if(!empty($featured_room_mate->user->user_profiles) && !empty($featured_room_mate->user->user_profiles->profile_pic))  src="{{URL::asset('uploads/profile_pics/' . $featured_room_mate->user->user_profiles->profile_pic)}}" alt="{{$featured_room_mate->user->user_profiles->profile_pic}}" @else  src="{{URL::asset('images/profile_avatar.jpeg')}}" alt="{{ __('no pic') }}" @endif>
                    <div class="holder-big">
                        <div class="nom-age">
                            <span class="nom">@if(!empty($featured_room_mate->user) && !empty($featured_room_mate->user->first_name)){{$featured_room_mate->user->first_name}}@endif</span>
                            <span class="age">@if(!empty($featured_room_mate->user->user_profiles) && !empty($featured_room_mate->user->user_profiles->birth_date)) {{Age(date('d-m-Y', strtotime($featured_room_mate->user->user_profiles->birth_date)))}}  {{__("acceuil.age_label")}} @endif</span>
                        </div>
                        <p>@if(!empty($featured_room_mate) && !empty($featured_room_mate->description)){{substr($featured_room_mate->description, 0, 130)}} @if(strlen($featured_room_mate->description) >= 130)...@endif @endif</p>
                    </div>
                </div>
				</a>
				@endforeach
            </div>
			
			@if(($countroomates / 8) > 1)
                <div class="suivant">
                   <ul class="page">
					   @for ($i = 1; $i < ($countroomates / 8); $i++)
						<li><a @if($i==0) class="active" @endif data-id = "{{$i}}" href="javascript:">{{$i}}</a></li>
					   @endfor
                       <li><a href="javascript:" data-id="{{($countroomates/8)}}">></a></li>
					   <li><a href="javascript:" data-id="{{($countroomates/8)}}">>></a></li>
                   </ul> 
                </div>
			@endif
        </div>
    </div>
	@endif
    <!--h  -->
	@if(!empty($featured_rooms) && count($featured_rooms) > 0 )
    <div class="collocataire recherchant logements clearfix">
        <div class="inner">
            <h3>{{ __("acceuil.logement_list") }}</h3>
            <div class="publication">
				@foreach($featured_rooms as $featured_room)
					<a href="{{ route('view.ad', [str_slug($featured_room->ad_details->property_type->property_type),$featured_room->url_slug . '-' . $featured_room->id]) }}" class="publ">
						<span class="prix">@if(!empty($featured_room) && !empty($featured_room->min_rent)) {{'&euro;'.$featured_room->min_rent}} @endif/ {{__("acceuil.mois")}}</span>
						<img class="ad-image" @if(!empty($featured_room->ad_files) && count($featured_room->ad_files) > 0)  src="{{URL::asset('uploads/ads_images/' . $featured_room->ad_files[0]->filename )}}" alt="{{$featured_room->ad_files[0]->user_filename}}" @else  src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif>
						<div class="items-publ">
							<img class="profile-image" @if(!empty($featured_room->user->user_profiles->profile_pic))  src="{{URL::asset('uploads/profile_pics/' . $featured_room->user->user_profiles->profile_pic )}}" alt="user image" @else  src="{{URL::asset('images/profile_avatar.jpeg')}}" alt="{{ __('no pic') }}" @endif>
							<h5>@if(!empty($featured_room) && !empty($featured_room->address)) {{$featured_room->address}} @endif</h5>
							<span class="logement">@if(!empty($featured_room) && !empty($featured_room->title)) {{$featured_room->title}} @endif</span>
							<p> @if(!empty($featured_room) && !empty($featured_room->description)){{substr($featured_room->description, 0, 130)}} @if(count($featured_room->description) > 130)...@endif @endif</p>
							<span class="date"> {{date_diff(date_create($featured_room->created_at), date_create('today'))->days}} {{ __('days ago') }}</span>
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
            <h3>{{ __("acceuil.visit_list") }}</h3>
            <div class="publication">
				@foreach($fractured_visits as $fractured_visit)
                <a href="{{ route('view.ad', [str_slug($fractured_visit->ad_details->property_type->property_type),$fractured_visit->url_slug . '-' . $fractured_visit->id]) }}" class="publ">
                    <span class="prix">@if(!empty($fractured_visit) && !empty($fractured_visit->min_rent)) {{'&euro;'.$fractured_visit->min_rent}} @endif/ {{__("acceuil.mois")}}</span>
                    <img class="ad-image" @if(!empty($fractured_visit->ad_files) && count($fractured_visit->ad_files) > 0)  src="{{URL::asset('uploads/ads_images/' . $fractured_visit->ad_files[0]->filename )}}" alt="{{$fractured_visit->ad_files[0]->user_filename}}" @else  src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif>
                    <div class="items-publ">
                        <img class="profile-image" @if(!empty($fractured_visit->user->user_profiles->profile_pic))  src="{{URL::asset('uploads/profile_pics/' . $fractured_visit->user->user_profiles->profile_pic )}}" alt="user image" @else  src="{{URL::asset('images/profile_avatar.jpeg')}}" alt="{{ __('no pic') }}" @endif>
                        <h6>{{__("acceuil.plannified_visit")}}</h6>
                        <div class="temps">
							@foreach($fractured_visit->ad_visiting_details as $visiting_detail)	
								@if(!empty($visiting_detail) && !empty($visiting_detail->visiting_date))
								<div class="temps-items">
									<span class="calendrier">
									 {{date('d-m-Y', strtotime($visiting_detail->visiting_date)) }}
									 </span>
								</div>
								@endif
								@if(!empty($visiting_detail) && !empty($visiting_detail->start_time))
									<div class="temps-items">
										<span class="heure">
										 {{" " . date('h:i', strtotime($visiting_detail->start_time)) }} @if(!empty($visiting_detail) && !empty($visiting_detail->end_time)) {{"- " . date('h:i', strtotime($visiting_detail->end_time)) }}@endif
										</span>
									</div>
								@endif
							@endforeach
                        </div>
						
                        <h5 class="nantes">@if(!empty($fractured_visit) && !empty($fractured_visit)) {{$fractured_visit->address}} @endif</h5>
						<span class="date">{{ __('Posted') }} {{date_diff(date_create($fractured_visit->created_at), date_create('today'))->days}} {{ __('days ago') }}</span>
                    
                    </div>
					
                </a>
				@endforeach
            </div>
        </div>
    </div>
	@endif
    <!--  h -->
	@if(!empty($france_ads) && count($france_ads) > 0 )
    <div class="collocataire recherchant logements clearfix">
        <div class="inner">
            <h3>{{ __("acceuil.search_ad_france") }}</h3>
            <div class="publication">
				@foreach($france_ads as $france_ad)
                <a @if(!empty($france_ad->ad_files) && count($france_ad->ad_files) > 0) style="background-image: url('{{URL::asset('uploads/ads_images/' . $france_ad->ad_files[0]->filename )}}');" @else style="background-image: url('{{URL::asset('images/room_no_pic.png')}}');color:black;" @endif class="annonces" href="{{ route('view.ad', [str_slug($france_ad->ad_details->property_type->property_type),$france_ad->url_slug . '-' . $france_ad->id]) }}">
                    <h4 @if(empty($france_ad->ad_files) || count($france_ad->ad_files) == 0) style="color:black;" @endif>{{getAddressVille($france_ad->address)}}</h4>
                </a>
				@endforeach
            </div>
        </div>
    </div>
	@endif
    <div class="all-utli clearfix">
        <div class="inner">
            <div class="utilisateur-items">
                <img src="img/icon-maison.png">
                <span>2135</span>
            </div>
            <div class="utilisateur-items">
                <img src="img/icon-homme-two.png">
                <span>1564</span>
            </div>
            <div class="utilisateur-items">
                <img src="img/icon-calucl.png">
                <span>364+</span>
            </div>
            <div class="utilisateur-items">
                <img src="img/icon-homme.png">
                <span>100</span>
            </div>
        </div>
    </div>
    <div class="collocataire recherchant logements moyenne clearfix">
        <div class="inner">
            <h3>{{ __("acceuil.avis_moyenne") }}</h3>
            <div class="moyenne">
                <img src="img/etoile.png">
                <span>4,5/5</span>
                <a class="avis" href="#">{{ __("acceuil.voir_avis") }}</a>
            </div>
        </div>
    </div>
    <footer class="footer clearfix">
        <div class="inner">
            <div class="footer-items-haut">
                <img src="img/icon-tictoc.png">
            </div>
            <div class="bottom-form">
                <div class="footer-items right-footer">
                    <ul class="center-presse">
                        <li> <a class="active" href="#">TictacHOUSE</a> </li>
                        <li> <a href="#">{{ __("acceuil.presse") }}</a> </li>
                        <li> <a href="#">{{ __("acceuil.blog") }}</a> </li>
                        <li> <a href="#">{{ __("acceuil.faq") }}</a> </li>
                        <li> <a href="#">{{ __("acceuil.selection_ville") }}</a> </li>
                        <li> <a href="#">{{ __("acceuil.presse") }}</a> </li>
                        <li> <a href="#">{{ __("acceuil.cgu") }}</a> </li>
                    </ul>
                    <div class="reseau">
                        <a href="#"><img src="img/icon-facebook.png"></a>
                        <a href="#"><img src="img/icon-twitter.png"></a>
                        <a href="#"><img src="img/icon-youtube.png"></a>
                        <a href="#"><img src="img/icon-in.png"></a>
                        <a href="#"><img src="img/icon-istagram.png"></a>
                    </div>
                </div>
                <div class="footer-items">
                    <form id="contact_form" action="" method="post">
                        <input type="text" placeholder="{{ __('acceuil.nom') }}">
                        <input type="text" placeholder="{{ __('acceuil.mail') }}">
                        <input type="mail" name="email" required="" aria-required="true" placeholder="{{ __('acceuil.mobile_no') }}">
                        <textarea type="mail" required="" aria-required="true" placeholder="{{ __('acceuil.message') }}"></textarea>
                        <input type="submit" value="{{ __('acceuil.send') }}">
                    </form>
                </div>
            </div>
        </div>
    </footer>
    <div class="copyright clearfix">
        <div class="copyright-items left-copy">
            <p>©2018 {{ __('acceuil.all_right') }}</p>
        </div>
        <div class="copyright-items email">
            <label for="contact_name"><span>{{ __('acceuil.receive_news') }}</span>
                <input type="text" placeholder="Email">
            </label>
        </div>
    </div>
	<div id="chooseSearchScenarioModal" class="modal fade">
	    <div class="modal-dialog modal-lg ad-senario-popup">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	                <h4 class="modal-title text-center">{{ __('acceuil.choose_scenario') }}</h4>
	            </div>
	            <div class="modal-body">
	                <div class="fadeIn home-searching-sec blue-bg text-center">
	                    <ul>
	                        <li>
	                            <a id="rent-a-prop-search-loc" scenario_id="{{base64_encode(1)}}" href="#"><div class="home-search-option-bx">
	                                    <div class="home-search-option-bx-inside">
	                                        <div class="search-icon ad_type">
	                                            <img class="without-hover" src="{{URL::asset('images/search-option-icon-1.png')}}" alt="" />
	                                            <img class="with-hover" src="{{URL::asset('images/search-option-icon-1-hover.png')}}" alt="" />
	                                        </div>
	                                        <h6>{{ __('acceuil.rent_property') }}</h6>
	                                    </div>
	                                </div>
	                            </a>
	                        </li>
	                        <li><a id="share-an-accom-search-loc" scenario_id="{{base64_encode(2)}}" href="#"><div class="home-search-option-bx">
	                                    <div class="home-search-option-bx-inside">
	                                        <div class="search-icon ad_type">
	                                            <img class="without-hover" src="{{URL::asset('images/search-option-icon-2.png')}}" alt="" />
	                                            <img class="with-hover" src="{{URL::asset('images/search-option-icon-3-hover.png')}}" alt="" />
	                                        </div>
	                                        <h6>{{ __('acceuil.share_accomodation') }}</h6>
	                                    </div>
	                                </div>
	                            </a>
	                        </li>
	                        <li><a id="seek-rent-a-prop-search-loc" scenario_id="{{base64_encode(3)}}" href="#"><div class="home-search-option-bx">
	                                    <div class="home-search-option-bx-inside">
	                                        <div class="search-icon ad_type">
	                                            <img class="without-hover" src="{{URL::asset('images/search-option-icon-3.png')}}" alt="" />
	                                            <img class="with-hover" src="{{URL::asset('images/search-option-icon-3-hover.png')}}" alt="" />
	                                        </div>
	                                        <h6>{{ __('Seek to rent a property') }}</h6>
	                                    </div>
	                                </div>
	                            </a>
	                        </li>
	                        <li><a id="seek-share-an-accom-search-loc" scenario_id="{{base64_encode(4)}}" href="#"><div class="home-search-option-bx">
	                                    <div class="home-search-option-bx-inside">
	                                        <div class="search-icon ad_type">
	                                            <img class="without-hover" src="{{URL::asset('images/search-option-icon-4.png')}}" alt="" />
	                                            <img class="with-hover" src="{{URL::asset('images/search-option-icon-4-hover.png')}}" alt="" />
	                                        </div>
	                                        <h6>{{ __('Seek to share an accomodation') }}</h6>
	                                    </div>
	                                </div>
	                            </a>
	                        </li>
	                        <li><a id="seek-comp-a-search-loc" scenario_id="{{base64_encode(5)}}" href="#"><div class="home-search-option-bx">
	                                    <div class="home-search-option-bx-inside">
	                                        <div class="search-icon ad_type">
	                                            <img class="without-hover" src="{{URL::asset('images/search-option-icon-5.png')}}" alt="" />
	                                            <img class="with-hover" src="{{URL::asset('images/search-option-icon-5-hover.png')}}" alt="" />
	                                        </div>
	                                        <h6>{{ __('acceuil.seek_to_search') }}</h6>
	                                    </div>
	                                </div>
	                            </a>
	                        </li>
	                    </ul>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<script>window.jQuery || document.write('<script src="js/jquery-slim.min.js"><\/script>')</script>
	@stack('scripts')
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap-datepicker.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="{{ asset('js/intlTelInput/intlTelInput.js') }}"></script>
		<script src="{{ asset('js/main.js') }}"></script>
		<script src="{{ asset('js/home.js') }}"></script>
		<script>
			$('body').on('click', '.post_an_ad', function(){
        
				$("#rent-a-prop-search-loc").attr('href', "{{ route('rent.property') }}");
				
				$("#share-an-accom-search-loc").attr('href', "{{ route('rent.accommodation') }}");
				
				$("#seek-rent-a-prop-search-loc").attr('href', "{{ route('looking.property') }}");
				
				$("#seek-share-an-accom-search-loc").attr('href', "{{ route('looking.accommodation') }}");
				
				$("#seek-comp-a-search-loc").attr('href', "{{ route('looking.partner') }}");
				$("#chooseSearchScenarioModal").modal('show');
			});
		</script>
		<script>
			$(document).ready(function(){
				$(".type_scenario").on('click', function(){
					$(this).parent().find("a").removeClass("active");
					$(this).children("a").addClass("active");
					$("#home-search-sc2").attr("action", $(this).attr("form-action"));
				});
				
				$('#slides').on('slide.bs.carousel', function (e) {
				   if("{{$lang}}" == "Français" && e.to == 1) {
						$("#embleme").css("opacity", "0");
				   } else {
						$("#embleme").css("opacity", "initial");
				   }
				});
				
				$("#button_search").on("click", function(){
					$("#home-search-sc2").submit();
				});
			});
		</script>
		<script>
			$(document).ready(function(){
			    var placeSearch, autocomplete;
			});
			function initAutocomplete() {
				// Create the autocomplete object, restricting the search to geographical
				// location types.
				autocomplete = new google.maps.places.Autocomplete(
				    /** @type {!HTMLInputElement} */(document.getElementById('address')),
				    {types: ['geocode']});

				    // When the user selects an address from the dropdown, populate the address
				    // fields in the form.
				    autocomplete.addListener('place_changed', fillInAddress);

				}

				function fillInAddress() {
				    // Get the place details from the autocomplete object.
				    var place = autocomplete.getPlace();
				    
				    if (place.geometry.viewport) {
				        $("#first_latitude").val(place.geometry.location.lat());
				        $("#first_longitude").val(place.geometry.location.lng());
				    } else {
				        $("#first_latitude").val('');
				        $("#first_longitude").val('');
				    }

				}
				// Bias the autocomplete object to the user's geographical location,
				// as supplied by the browser's 'navigator.geolocation' object.
				function geolocate() {
				if (navigator.geolocation) {
				  navigator.geolocation.getCurrentPosition(function(position) {
				    var geolocation = {
				      lat: position.coords.latitude,
				      lng: position.coords.longitude
				    };
				    var circle = new google.maps.Circle({
				      center: geolocation,
				      radius: position.coords.accuracy
				    });
				    autocomplete.setBounds(circle.getBounds());
				  });
				}
				}
				
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAEzsiKkQyW0pDBXxaDlQZt8Hh5jeCpk5o&libraries=places&callback=initAutocomplete"
        async defer>
		</script>
		<script>
			var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
			(function(){
			var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
			s1.async=true;
			s1.src='https://embed.tawk.to/5a79a8f3d7591465c7076bc7/default';
			s1.charset='UTF-8';
			s1.setAttribute('crossorigin','*');
			s0.parentNode.insertBefore(s1,s0);
			})();
        </script>
    <script>
		
        var images = [
            "img/slider.jpg",
            "images/couverture-site-{{$lang}}.jpg",
            "images/lefond.png",
        ];
        var imageHead = document.getElementById("slider-holder");
        var i = 0;
        setInterval(function() {
			if("{{$lang}}" == "Français" && i==1) {
				$(".title-slider").css("opacity", "0");
				$("#slider-holder").css("background-size", "90%");
			} else {
				$(".title-slider").css("opacity", "initial");
				$("#slider-holder").css("background-size", "initial");
				
			}
            imageHead.style.backgroundImage = "url(" + images[i] + ")";
            i = i + 1;
            if (i == images.length) {
                i = 0;
            }
        }, 7000);
    </script>


@include('common.cookie')
</body>
</html>