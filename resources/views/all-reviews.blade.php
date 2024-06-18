@extends('layouts.app')
<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/reviews.css') }}" rel="stylesheet">
@endpush
@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 my-ad-main-outer user-visit-main-outer">
                <div class="user-visit-main-hdr" style="float: left; width: 100%;">
                    <h4>{{__("reviews.title")}}</h4>
                </div>
				<div class="myad-cont-bx white-bg m-t-2 col-md-4" style="margin-top: 40px;width : 100%;">
					<div class="myad-bx">
					<div>
						<a  href="javascript:void(0);" class="avis_moyenne" style="display:inline-block;">{{calculMoyenneAvis()}}<span style="margin-left : 5px;" class="glyphicon glyphicon-star" ></span></a>
						<div class="review-recap">
							@if( calculMoyenneAvis() == 1 ) <span>{{calculMoyenneAvis()}} {{__('reviews.etoile_sur_singulier')}} 5</span>
							@else <span>{{calculMoyenneAvis()}} {{__('reviews.etoile_sur')}} 5</span>
							@endif
							
							@if(count($reviews) == 1 ) <span style="display : block;">{{count($reviews)}} {{__('reviews.avis')}}</span>
							@else <span style="display : block;">{{count($reviews)}} {{__('reviews.avis')}}</span>
							@endif
						</div>
					</div>
					</div>
					<div class="myad-bx">
						@for($i=5;$i>=1;$i--)
							<div>
								@if($i==1) <span class="etoile-text ">{{$i}} {{__('reviews.etoile_singulier')}}</span>
								@else <span class="etoile-text ">{{$i}} {{__('reviews.etoiles')}}</span>
								@endif
								<div class="rate_percent" style="width:{{getPercentRatePerNote($i) * 1.8}}px;">
								</div>
								<span>{{getNbPerNote($i)}}</span>
							</div>
						@endfor
						@guest
						<div class="review-login">{{__('reviews.review_login')}}</div>
						@else
						<div class="review-button my_review_button" ><a href="javascript:"><span class="glyphicon glyphicon-star-empty" style="font-size:15px;;"></span>@if(is_null(getUserAvis())){{ __('header.avis') }}@else {{ __('header.update_avis') }} @endif</a></div>
						@endguest
					</div>
				</div>
				<div style="width : 100%;display:inline-block;">
					<div class="myad-cont-bx white-bg m-t-2 col-md-8" style="margin-top: 20px;width : 100%;">
						@foreach($reviews as $review)
						<div class="myad-bx">
							@if(!is_null($review->profile_pic) && !empty($review->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $review->profile_pic)))
							<img width="60" height="60" src="{{URL::asset('uploads/profile_pics/' . $review->profile_pic)}}" class="user-profile-image"/>
							@else
							<img width="60" height="60" src="{{URL::asset('images/profile_avatar.jpeg')}}" class="user-profile-image"/>
							@endif
							<div class="review-text">
								<span class="user-name">{{$review->first_name}} {{$review->last_name}} </span><span>{{__('reviews.sentence')}}</span>
								- <a  href="javascript:void(0);" class="avis_notes" style="display:inline-block;">{{$review->notes}}<span class="glyphicon glyphicon-star" ></span></a>
								  <span class="review-date">{{dateLocale($review->date)}}</span>
							</div>	
							<p class="review-comment">
								{{$review->comments}}
							</p>
						</div>
						@endforeach
					</div>
				</div>
				
            </div>
        </div>
    </div>
</section>

<style>
#my_review_button
{
	display : none;
}
</style>
@endsection