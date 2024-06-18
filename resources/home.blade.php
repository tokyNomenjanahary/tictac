<!DOCTYPE html>
<!-- saved from url=(0037) -->
<html lang="fr-FR" prefix="og: http://ogp.me/ns#"><!--<![endif]--><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ config('app.name', 'TicTacHouse') }} | Colocation, Colocataire et Logement</title>
    <meta name="title" content="bailti">
	<meta name="description" content="{{__('acceuil.description_site')}}">
    <link rel="icon" href="img/favicon.png">
	<link href="/css/bootstrap2.min.css" rel="stylesheet" type="text/css">
	<link href="/css/styleAdModal.min.css" rel="stylesheet">
	<link href="/css/developer.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,500i,700,900" rel="stylesheet">
	<link href="/css/owl.carousel.min.css" rel="stylesheet" type="text/css" />
	<link href="/css/media-home.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="/slick/slick.min.css"/>
	<style type="text/css">
		@import url(/css/styleHome.min.css);
	</style>
</head>
{{fb_pixel_code()}}
{{google_analytic_code()}}
<body>
    <!--star header-->
    <section class="header clearfix">
        <div class="bar-haut clearfix">
            <h1><a href="/"><img src="img/logo-tictoc.png" alt="logo-tictoc.png"></a></h1>
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

							<ul class="dropdown-menu" role="menu">
								<li class="dropdown-item">
									<a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
								</li>
								<li class="dropdown-item">
									<a href="{{ route('logout') }}"
									   onclick="event.preventDefault();
											   document.getElementById('logout-form').submit();">
										{{ __('Logout') }}
									</a>

									<form id="logout-form" action="{{ route('logout') }}" method="POST">
										{{ csrf_field() }}
									</form>
								</li>
							</ul>
						</li>
					@endguest
                    </li>
                    <li>
						<a class="annonce post-job-btn post_an_ad" href="javascript:">{{ __("acceuil.post_ad") }}</a>
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
		<div id="slider-holder clearfix" class="slider-holder clearfix"> 
		<div  class="slider-in">
		<div class="slider">
		<div>
		<img height="700" src="img/slider.jpg" alt="slider.jpg" class="slide-img">
		</div>
		<div>
		<img height="700"  @if($lang=="Français") class="img-slider-french" @else src="img/lefond.jpg" @endif  class="slide-img">
		</div>
		<div>
		<img height="700" src="img/lefond.jpg" alt="lefond.jpg" class="slide-img">
		</div>
		</div>
		<div class="overrall ">
            <div class="title-slider">
                <h1>{{ __('acceuil.embleme1') }}  <br> {{ __('acceuil.embleme2') }}</h1>
            </div>
            <form id="home-search-sc2" method="POST" action="{{ route('rent.accommodation') }}">
				{{ csrf_field() }}
                <div class="adress-slider">
                    <label>
                        <input id="address" name="address" placeholder="{{ __('acceuil.searchPlaceHolder') }}" value="Paris, île-de-France, France">
                        <input type="hidden" id="first_latitude" name="latitude" value="48.8546">
						<input type="hidden" id="first_longitude" name="longitude" value="2.34771">
						<input type="hidden" name="Search" value="Search">
						<a href="javascript:" id="buttonSearch"><button>OK</button></a>
                    </label>
                </div>
                <div class="liste-chambre">
                    <label form-action="{{ route('looking.accommodation') }}" class="type_scenario">
						<a href="javascript:" class="active">
							<input type="radio" name="drone"><span>{{ __("acceuil.chambre") }}</span>
						</a>
					</label>
                    <label form-action="{{ route('rent.property') }}" class="type_scenario">
						<a href="javascript:">
							<input type="radio" name="drone"><span>{{ __("acceuil.logement") }}</span>
						</a>
					</label>
                    <label form-action="{{ route('looking.accommodation') }}" class="type_scenario">
						<a href="javascript:">
							<input type="radio" name="drone" ><span><span>@if($lang=="Français")  {{ __("acceuil.strong_colocataire") }} @else {{__("acceuil.colocataire")}} @endif </span>
						</a>
					</label>
                        
                    <label form-action="{{ route('looking.property') }}" class="type_scenario">
						<a href="javascript:">
							<input type="radio" name="drone"><span>{{ __("acceuil.locataire") }}</span>
						</a>
                    </label>
                </div>
            </form>
            
			</div>
			<div class="about">
                <img src="img/resize-dynamique.png" alt="resize-dynamique.png" />
            </div>
        </div>
		</div>
    </section>
    <!--star content-->
    <!--star content-->
    <section class="content clearfix">
        <div class="inner">
            <div class="collocataire clearfix">
                <h3>{{ __("acceuil.futur_colocataire") }}</h3>
                <h4>{{ __("acceuil.find_logement") }}</h4>
                <div class="composer-equipe clearfix">
                    <div class="items-equipe">
                        <img src="img/trouver.png" alt="trouver.png">
                        <h4>{{ __("acceuil.find_coloc") }}</h4>
                        <p>{{ __("acceuil.school_coloc_text") }}</p>
                    </div>
                    <div class="items-equipe regroupe">
                        <img src="img/regroupez.png" alt="regroupez.png">
                        <h4>{{ __("acceuil.regroupement") }}</h4>
                        <p>{{ __("acceuil.regroupement_text") }}</p>
                    </div>
                    <div class="items-equipe echange">
                        <img src="img/echanger.png" alt="echanger.png">
                        <h4>{{ __("acceuil.echange") }}</h4>
                        <p>{{ __("acceuil.echange_text") }}</p>
                    </div>
                    <div class="items-equipe ">
                        <img src="img/candidature.png" alt="candidature.png">
                        <h4>{{ __("acceuil.send_apply") }}</h4>
                        <p>{{ __("acceuil.send_apply_text") }}</p>
                    </div>
                    <div class="items-equipe ">
                        <img src="img/compser.png" alt="compser.png">
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
                        <img src="img/icon-gerer.png" alt="icon-gerer.png">
                        <h4>{{ __("acceuil.manage_apply") }}</h4>
                    </div>
                    <div class="items-equipe chattez">
                        <img src="img/chatter.png" alt="chatter.png">
                        <h4>{{ __("acceuil.chat") }}</h4>
                    </div>
                    <div class="items-equipe regroupez">
                        <img src="img/regroupezg.png" alt="regroupez.png">
                        <h4>{{ __("acceuil.regroupez_coloc") }}</h4>
                    </div>
                    <div class="items-equipe louez">
                        <img src="img/louez.png" alt="louer.png">
                        <h4>{{ __("acceuil.louez") }}</h4>
                    </div>
                    <div class="items-equipe proposez">
                        <img src="img/icon-visiter.png" alt="visiter.png">
                        <h4>{{ __("acceuil.proposer") }}</h4>
                    </div>
                    <div class="items-equipe location">
                        <img src="img/scurise.png" alt="securise.png">
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
				<a  href="{{ route('view.ad', [str_slug($featured_room_mate->ad_details->property_type->property_type),$featured_room_mate->url_slug . '-' . $featured_room_mate->id]) }}">
                <div itemscope itemtype="{{ route('view.ad', [str_slug($featured_room_mate->ad_details->property_type->property_type),$featured_room_mate->url_slug . '-' . $featured_room_mate->id]) }}" class="block-items publ-room-mate">
					<span itemprop="Titre du logement" class="item-prop-hidden">{{$featured_room_mate->title}}</span>
                    <img itemprop="Image" class="profile-image" @if(!empty($featured_room_mate->user->user_profiles) && !empty($featured_room_mate->user->user_profiles->profile_pic))  src="{{URL::asset('uploads/profile_pics/' . $featured_room_mate->user->user_profiles->profile_pic)}}" alt="{{$featured_room_mate->user->user_profiles->profile_pic}}" @else  src="{{URL::asset('images/profile_avatar.jpeg')}}" alt="{{ __('no pic') }}" @endif>
                    <div class="holder-big">
                        <div class="nom-age">
                            <span itemprop="Nom du colocataire"  class="nom">@if(!empty($featured_room_mate->user) && !empty($featured_room_mate->user->first_name)){{$featured_room_mate->user->first_name}}@endif</span>
                            <span itemprop="Age du colocataire" class="age">@if(!empty($featured_room_mate->user->user_profiles) && !empty($featured_room_mate->user->user_profiles->birth_date)) {{Age(date('d-m-Y', strtotime($featured_room_mate->user->user_profiles->birth_date)))}}  {{__("acceuil.age_label")}} @endif</span>
                        </div>
                        <p itemprop="Description">@if(!empty($featured_room_mate) && !empty($featured_room_mate->description)){{substr($featured_room_mate->description, 0, 130)}} @if(strlen($featured_room_mate->description) >= 130)...@endif @endif</p>
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
					<a itemscope itemtype="{{ route('view.ad', [str_slug($featured_room->ad_details->property_type->property_type),$featured_room->url_slug . '-' . $featured_room->id]) }}" href="{{ route('view.ad', [str_slug($featured_room->ad_details->property_type->property_type),$featured_room->url_slug . '-' . $featured_room->id]) }}" class="publ">
						<span itemprop="Prix" class="prix">@if(!empty($featured_room) && !empty($featured_room->min_rent)) {{'&euro;'.$featured_room->min_rent}} @endif/ {{__("acceuil.mois")}}</span>
						@if(!empty($featured_room->ad_files) && count($featured_room->ad_files) > 1)
							<div class="slick-slider" id="slick-slider-room-{{$featured_room->id}}">
								@foreach($featured_room->ad_files as $ad_file)
								<div>
									<img itemprop="Image" class="ad-image active-custom-carousel" src="{{'uploads/ads_images/' . $ad_file->filename}}" alt="{{$ad_file->user_filename}}">
								</div>
								@endforeach
							</div>
						<div class='custom-carousel-prev' data-id="slick-slider-room-{{$featured_room->id}}"></div>
						<div class='custom-carousel-next' data-id="slick-slider-room-{{$featured_room->id}}"></div>
						@else
						<img itemprop="image" class="ad-image active-custom-carousel" @if(!empty($featured_room->ad_files) && count($featured_room->ad_files) > 0)  src="{{URL::asset('uploads/ads_images/' . $featured_room->ad_files[0]->filename )}}" alt="{{$featured_room->ad_files[0]->user_filename}}" @else  src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif>
						@endif
						<div class="items-publ">
							<img itemprop="Photo du propriétaire" class="profile-image" @if(!empty($featured_room->user->user_profiles->profile_pic))  src="{{URL::asset('uploads/profile_pics/' . $featured_room->user->user_profiles->profile_pic )}}" alt="user image" @else  src="{{URL::asset('images/profile_avatar.jpeg')}}" alt="{{ __('no pic') }}" @endif>
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
            <h3>{{ __("acceuil.visit_list") }}</h3>
            <div class="publication">
				@foreach($fractured_visits as $fractured_visit)
                <a itemscope itemtype="{{ route('view.ad', [str_slug($fractured_visit->ad_details->property_type->property_type),$fractured_visit->url_slug . '-' . $fractured_visit->id]) }}" href="{{ route('view.ad', [str_slug($fractured_visit->ad_details->property_type->property_type),$fractured_visit->url_slug . '-' . $fractured_visit->id]) }}" class="publ">
                    <span itemprop="Titre de la publication" class="item-prop-hidden">{{$fractured_visit->title}}</span>
					<span itemprop="Prix" class="prix">@if(!empty($fractured_visit) && !empty($fractured_visit->min_rent)) {{'&euro;'.$fractured_visit->min_rent}} @endif/ {{__("acceuil.mois")}}</span>
                    @if(!empty($fractured_visit->ad_files) && count($fractured_visit->ad_files) > 1)
						<div class="slick-slider" id="slick-slider-visit-{{$fractured_visit->id}}">
							@foreach($fractured_visit->ad_files as $ad_file)
							<div>
								<img itemprop="Image" class="ad-image active-custom-carousel" src="{{'uploads/ads_images/' . $ad_file->filename}}" alt="{{$ad_file->user_filename}}">
							</div>
							@endforeach
						</div>
					<div class='custom-carousel-prev' data-id="slick-slider-visit-{{$fractured_visit->id}}"></div>
					<div class='custom-carousel-next' data-id="slick-slider-visit-{{$fractured_visit->id}}"></div>
					@else
					<img itemprop="Image" class="ad-image" @if(!empty($fractured_visit->ad_files) && count($fractured_visit->ad_files) > 0)  src="{{URL::asset('uploads/ads_images/' . $fractured_visit->ad_files[0]->filename )}}" alt="{{$fractured_visit->ad_files[0]->user_filename}}" @else  src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif>
                    @endif
					
					<div class="items-publ">
                        <img itemprop="Photo du propriétaire" class="profile-image" @if(!empty($fractured_visit->user->user_profiles->profile_pic))  src="{{URL::asset('uploads/profile_pics/' . $fractured_visit->user->user_profiles->profile_pic )}}" alt="user image" @else  src="{{URL::asset('images/profile_avatar.jpeg')}}" alt="{{ __('no pic') }}" @endif>
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
						
                        <h5 itemprop="Adresse" class="nantes address-visit">@if(!empty($fractured_visit) && !empty($fractured_visit)) {{$fractured_visit->address}} @endif</h5>
						<span itemprop="Date de publication" class="date">{{translateDuration($fractured_visit->created_at)}}</span>
                    
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
                <a @if(!empty($france_ad->ad_files) && count($france_ad->ad_files) > 0) style="background-image: url('{{'uploads/ads_images/' . $france_ad->ad_files[0]->filename}}');" @else style="background-image: url('{{'images/room_no_pic.png'}}');" @endif class="annonces" href="{{ route('view.ad', [str_slug($france_ad->ad_details->property_type->property_type),$france_ad->url_slug . '-' . $france_ad->id]) }}">
                    <h4 @if(empty($france_ad->ad_files) || count($france_ad->ad_files) == 0) class="adresse-black" @endif>{{getAddressVille($france_ad->address)}}</h4>
                </a>
				@endforeach
            </div>
        </div>
    </div>
	@endif
    <div class="all-utli clearfix">
        <div class="inner">
            <div class="utilisateur-items">
                <img src="img/icon-maison.png" alt="maison.png">
                <span>2135</span>
            </div>
            <div class="utilisateur-items">
                <img src="img/icon-homme-two.png" alt="home.png">
                <span>1564</span>
            </div>
            <div class="utilisateur-items">
                <img src="img/icon-calucl.png" alt="calcuul.png">
                <span>364+</span>
            </div>
            <div class="utilisateur-items">
                <img src="img/icon-homme.png" alt="home.png">
                <span>100</span>
            </div>
        </div>
    </div>
    <div class="collocataire recherchant logements moyenne clearfix">
        <div class="inner">
            <h3>{{ __("acceuil.avis_moyenne") }}</h3>
            <div class="moyenne">
				@if(getTotalAvis()!=0)
                <div id="div_avis" class="avis_stars">
						
					@for($i=1; $i <= 5; $i++)
						@if($i < calculMoyenneAvis())
							<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{__('header.tooltip_excellent')}}" >
								<img data-id="{{$i}}" class="stars_avis" width="60" height="60" alt="stars.png" src="{{URL::asset('img/filled_stars_avis_home.png')}}">
							</a>
						@endif
						@if($i == calculMoyenneAvis())
							<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{__('header.tooltip_excellent')}}" >
								<img data-id="{{$i}}" class="stars_avis selected" alt="stars.png" width="60" height="60" src="{{URL::asset('img/filled_stars_avis_home.png')}}">
							</a>
						@endif
						
						@if($i > calculMoyenneAvis())
							@if(($i == (intval(calculMoyenneAvis()) + 1)) && getPartieDecimale(calculMoyenneAvis()) !=0) 
							
							<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{__('header.tooltip_excellent')}}" >
								<img data-id="{{$i}}" class="stars_avis" alt="stars.png" width="60" height="60" src="{{URL::asset('img/filled_stars_avis_home_' . getPartieDecimale(calculMoyenneAvis()) . '.png')}}">
							</a>
							@else
							<a  href="javascript:" data-toggle="tooltip" data-placement="top" title="{{__('header.tooltip_excellent')}}" >
								<img data-id="{{$i}}" class="stars_avis" alt="stars.png" width="60" height="60" src="{{URL::asset('img/empty_stars_avis.png')}}">
							</a>
							@endif
						@endif
					@endfor
				</div>
                <span>{{calculMoyenneAvis()}}/5</span>
				@endif
                <a class="avis" href="/all-reviews">{{ __("acceuil.voir_avis") }}</a>
            </div>
        </div>
    </div>
    <footer class="footer clearfix">
        <div class="inner">
            <div class="footer-items-haut">
                <img src="img/icon-tictoc.png" alt="tictoc.png">
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
                        <li> <a href="{{url('/terms-and-condition')}}">{{ __("acceuil.cgu") }}</a> </li>
						<li> <a href="{{url('/privacy')}}">{{ __("acceuil.privacy") }}</a> </li>
                    </ul>
                    <div class="reseau">
                        <a href="#"><img src="img/icon-facebook.png" alt="facebok.png"></a>
                        <a href="#"><img src="img/icon-twitter.png" alt="twitter.png"></a>
                        <a href="#"><img src="img/icon-youtube.png" alt="youtube.png"></a>
                        <a href="#"><img src="img/icon-in.png" alt="icon-in.png"></a>
                        <a href="#"><img src="img/icon-istagram.png" alt="instagram.png"></a>
                    </div>
                </div>
                <div class="footer-items">
                    <form id="contact_form" action="" method="post">
                        <input id="nomSend" type="text" placeholder="{{ __('acceuil.nom') }}">
                        <input id="mailSend" type="text" placeholder="{{ __('acceuil.mail') }}">
                        <input id="mobileSend" type="text" name="email" required="" aria-required="true" placeholder="{{ __('acceuil.mobile_no') }}">
                        <textarea id="messageSend" type="text" required="" aria-required="true" placeholder="{{ __('acceuil.message') }}"></textarea>
                        <input type="submit" id="sendButton" value="{{ __('acceuil.send') }}">
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
	                <h4 class="modal-title modal-title-annonce text-center">{{ __('Choose a scenario to search') }}</h4>
	            </div>
	            <div class="modal-body">
	                <div class="fadeIn home-searching-sec blue-bg text-center">
	                    <ul>
	                        <li class="ad-type-1">
	                            <a id="rent-a-prop-search-loc" scenario_id="{{base64_encode(1)}}" href="#"><div class="home-search-option-bx">
	                                    <div class="home-search-option-bx-inside">
	                                        <div class="search-icon ad_type">
	                                            <img class="without-hover" src="{{URL::asset('images/search-option-icon-1.png')}}" alt="search-option-icon-1.png" />
	                                            <img class="with-hover" src="{{URL::asset('images/search-option-icon-1-hover.png')}}" alt="search-option-icon-1-hover.png" />
	                                        </div>
	                                        <h6>{{ __('Rent a property') }}</h6>
	                                    </div>
	                                </div>
	                            </a>
	                        </li>
	                        <li class="ad-type-2"><a id="share-an-accom-search-loc" scenario_id="{{base64_encode(2)}}" href="#"><div class="home-search-option-bx">
	                                    <div class="home-search-option-bx-inside">
	                                        <div class="search-icon ad_type">
	                                            <img class="without-hover" src="{{URL::asset('images/search-option-icon-2.png')}}" alt="search-option-icon-2.png" />
	                                            <img class="with-hover" src="{{URL::asset('images/search-option-icon-3-hover.png')}}" alt="search-option-icon-3-hover.png" />
	                                        </div>
	                                        <h6>{{ __('Share an accomodation') }}</h6>
	                                    </div>
	                                </div>
	                            </a>
	                        </li>
	                        <li class="ad-type-3"><a id="seek-rent-a-prop-search-loc" scenario_id="{{base64_encode(3)}}" href="#"><div class="home-search-option-bx">
	                                    <div class="home-search-option-bx-inside">
	                                        <div class="search-icon ad_type">
	                                            <img class="without-hover" src="{{URL::asset('images/search-option-icon-3.png')}}" alt="search-option-icon-3.png" />
	                                            <img class="with-hover" src="{{URL::asset('images/search-option-icon-3-hover.png')}}" alt="search-option-icon-3-hover.png" />
	                                        </div>
	                                        <h6>{{ __('Seek to rent a property') }}</h6>
	                                    </div>
	                                </div>
	                            </a>
	                        </li>
	                        <li class="ad-type-4"><a id="seek-share-an-accom-search-loc" scenario_id="{{base64_encode(4)}}" href="#"><div class="home-search-option-bx">
	                                    <div class="home-search-option-bx-inside">
	                                        <div class="search-icon ad_type">
	                                            <img class="without-hover" src="{{URL::asset('images/search-option-icon-4.png')}}" alt="search-option-icon-4.png" />
	                                            <img class="with-hover" src="{{URL::asset('images/search-option-icon-4-hover.png')}}" alt="search-option-icon-4-hover.png" />
	                                        </div>
	                                        <h6>{{ __('Seek to share an accomodation') }}</h6>
	                                    </div>
	                                </div>
	                            </a>
	                        </li>
	                        <li class="ad-type-5"><a id="seek-comp-a-search-loc" scenario_id="{{base64_encode(5)}}" href="#"><div class="home-search-option-bx">
	                                    <div class="home-search-option-bx-inside">
	                                        <div class="search-icon ad_type">
	                                            <img class="without-hover" src="{{URL::asset('images/search-option-icon-5.png')}}" alt="earch-option-icon-5.png" />
	                                            <img class="with-hover" src="{{URL::asset('images/search-option-icon-5-hover.png')}}" alt="search-option-icon-5-hover.png" />
	                                        </div>
	                                        <h6>{{ __('Seek someone to search together for a property') }}</h6>
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
	<div id="newsletter-modal" class="modal ">
		<div class="modal-dialog ">
			<div class="modal-content ">
				<div class="modal-body" >
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h5 id="modal-news-text" class="modal-title text-center">{{ __("application.common_friend_sender") }}</h5>
				</div>
			</div>
		</div>
	</div>
	<script>window.jQuery || document.write('<script src="js/jquery-slim.min.js"><\/script>')</script>
	@stack('scripts')
	
	<script type="text/javascript" src="js/jquery-1.12.min.js"></script>
	<script type="text/javascript" src="js/jquery.lazyload.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/fonctions.min.js" async defer></script>
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
	<script type="text/javascript" src="slick/slick.min.js"></script>

	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap2.min.js"></script>
	<script src="{{ asset('js/home.min.js') }}" async defer></script>
		<script async defer>
			$('body').on('click', '.post_an_ad', function(){
        
				$("#rent-a-prop-search-loc").attr('href', "{{ route('rent.property') }}");
				
				$("#share-an-accom-search-loc").attr('href', "{{ route('rent.accommodation') }}");
				
				$("#seek-rent-a-prop-search-loc").attr('href', "{{ route('looking.property') }}");
				
				$("#seek-share-an-accom-search-loc").attr('href', "{{ route('looking.accommodation') }}");
				
				$("#seek-comp-a-search-loc").attr('href', "{{ route('looking.partner') }}");
				$("#chooseSearchScenarioModal").modal('show');
			});
		</script>
		<script async defer>
			$(document).ready(function(){
				$(".type_scenario").on('click', function(){
					$(this).parent().find("a").removeClass("active");
					$(this).children("a").addClass("active");
					$("#home-search-sc2").attr("action", $(this).attr("form-action"));
				});
				
				$("#button_search").on("click", function(){
					$("#home-search-sc2").submit();
				});
				
				$("#sendButton").on("click", function(e){
					 $('#nomSend').val('');
					 $('#messageSend').val('');
					 $('#mailSend').val('');
					 $('#mobileSend').val('');
					 $("#modal-news-text").text("{{__('acceuil.send_message_text')}}");
					 $("#newsletter-modal").modal('show');
					 e.preventDefault(); // prevents default
					 return false;
				});
			});
		</script>
		<script async defer>
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
		<script async defer>
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
		
		<script async defer>
			$(document).ready(function(){
				$('.slick-slider').slick();
				$(".custom-carousel-prev").on("click", function(e){
					e.stopPropagation();
					e.preventDefault();
					var id = $(this).attr("data-id");
					$('#'+id).slick('slickPrev');
				});
				$(".custom-carousel-next").on("click", function(e){
					e.stopPropagation();
					e.preventDefault();
					var id = $(this).attr("data-id");
					$('#'+id).slick('slickNext');
				});
			});
		</script>
		

@include('common.cookie')
</body></html>
