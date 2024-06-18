<div class="all-utli clearfix">
        <div class="inner">
            <div class="utilisateur-items">
                <img src="/img/icon-maison.png" width="125" height="94" alt="maison.png">
                <span>{{$nbAdsCount}}</span>
            </div>
            <div class="utilisateur-items">
                <img src="/img/icon-homme-two.png" alt="home.png" width="125" height="100">
                <span>{{$nbUsersCount}}</span>
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
							<a  href="javascript:">
								<img data-id="{{$i}}" class="filled-star-home stars_avis" alt="stars.png" width="60" height="60" src="/img/small-img.png">
							</a>
						@endif
						@if($i == calculMoyenneAvis())
							<a  href="javascript:">
								<img data-id="{{$i}}" class="filled-star-home stars_avis" alt="stars.png" width="60" height="60" src="/img/small-img.png" >
							</a>
						@endif
						
						@if($i > calculMoyenneAvis())
							@if(($i == (intval(calculMoyenneAvis()) + 1)) && getPartieDecimale(calculMoyenneAvis()) !=0) 
							
							<a  href="javascript:" >
								<img data-id="{{$i}}" class="stars_avis {{'filled-star-home-' . getPartieDecimale(calculMoyenneAvis())}}" alt="stars.png" width="60" height="60"
                                src="/img/small-img.png">
							</a>
							@else
							<a  href="javascript:">
								<img data-id="{{$i}}" class="stars_avis empty-star" alt="stars.png" width="60" height="60"  src="/img/small-img.png">
							</a>
							@endif
						@endif
					@endfor

				</div>
                <span>{{calculMoyenneAvis()}}/5</span>
				@endif
                <a class="avis" href="{{url('/tous-les-avis')}}">{{ __("acceuil.voir_avis") }}</a>
            </div>
        </div>
    </div>