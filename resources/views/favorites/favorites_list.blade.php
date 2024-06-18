@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
@endpush
<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/favorites_list.js') }}"></script>
@endpush
@section('content')

<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 ad-details-outer fav-hdr">
                    <div class="user-visit-main-hdr">
                        <h4>{{__('favourites.my_favourites')}}</h4>

                        <div class="listing-grid-option-outer text-right pull-right">
                            <div class="gird-options-icon-outer">
                                <span class="listing-view-icon active"><i class="fa fa-bars" aria-hidden="true"></i></span>
                                <span class="grid-view-icon"><i class="fa fa-th" aria-hidden="true"></i></span>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
        <div class="row">
                @if(!empty($favDetails) && count($favDetails) > 0)
                    @foreach($favDetails as $favDetail)
                        @if(!empty($favDetail->ads->scenario_id) && ($favDetail->ads->scenario_id==1 || $favDetail->ads->scenario_id==2))
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 room-grid-bx-outer list-view-time fav-remove-div">
                                <div class="room-grid-listing-bx-inside favorites-listing-full">
                                    <a href="{{ adUrl($favDetail->ad_id, $favDetail->ad_search_id)}}">
										<div class="grid-img-sec">
                                            <div class="grid-price-bx">
                                            <h4>
                                                @if(!empty($favDetail->ads) && !empty($favDetail->ads->min_rent) && $favDetail->ads->min_rent != 0){{$favDetail->ads->min_rent}} € <small>{{__('favourites.month')}}.</small>
                                                @else
                                                {{__('searchlisting.a_negocier')}}
                                                @endif
                                            </h4>
                                            </div>
                                            <div class="grid-pics-bx"><i class="fa fa-camera" aria-hidden="true"></i><span>@if(!empty($favDetail->ads->ad_files)){{count($favDetail->ads->ad_files)}}@endif</span></div>

                                            <figure class="brdr-rect-right">
                                                <img @if(!empty($favDetail->ads->ad_files) && count($favDetail->ads->ad_files) > 0) class="pic_available" src="{{URL::asset('uploads/images_annonces/' . $favDetail->ads->ad_files[0]->filename )}}" alt="{{$favDetail->ads->ad_files[0]->user_filename}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif />
                                            </figure>
                                            <div class="featured-bx-location">
                                                <p><i class="fa fa-map-marker" aria-hidden="true"></i>@if(!empty($favDetail->ads) && !empty($favDetail->ads->address)){{$favDetail->ads->address}}@endif</p>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="grid-content">
                                        <div class="grid-content-first">
                                            <div class="gird-room-location-line">
                                                <i class="location-icon fa fa-map-marker" aria-hidden="true"></i>
                                                <h6  class="grid-only-loca">@if(!empty($favDetail->ads) && !empty($favDetail->ads->address)){{$favDetail->ads->address}}@endif</h6>
                                                <p>{{ __('searchlisting.posted') }} {{translateDuration($favDetail->ads->created_at)}}{{ __('Posted') }}
                                                </p>
                                                <a href="javascript:void(0);" ad_id="{{base64_encode($favDetail->ad_id)}}" ad_search_id="{{base64_encode($favDetail->ad_search_id)}}" id="add_to_favorites">
                                                    <i class="heart-icon fa fa-heart active"></i>
                                                </a>
                                            </div>
                                            <div class="gird-room-area-line">
                                                @if(!empty($favDetail->ads->ad_details) && !empty($favDetail->ads->ad_details->min_surface_area))
                                                <img class="ft-squar-icon" src="{{URL::asset('images/ft-squar-icon.png')}}" alt="" />
                                                <h4>
                                                {{$favDetail->ads->ad_details->min_surface_area}}
                                                <sub>{{__('favourites.square_meters')}}</sub></h4>
                                                 @endif
                                                @if(date_create($favDetail->ads->available_date) > date_create('today'))
                                                    <div class="first-available">{{__('favourites.available_from')}} {{date('j M', strtotime($favDetail->ads->available_date))}}</div>
                                                @else
                                                    <div class="first-available">{{__('favourites.available')}}</div>
                                                @endif
                                            </div>
                                            <div class="gird-room-descrp-line">
                                                <h6>@if(!empty($favDetail->ads) && !empty($favDetail->ads->title)){{$favDetail->ads->title}}@endif</h6>
                                                <p>@if(!empty($favDetail->ads) && !empty($favDetail->ads->description)){{substr($favDetail->ads->description,0,250)}}@endif @if(strlen($favDetail->ads->description) > 250)...@endif</p>
                                                <div class="more-btn-outer">
                                                    <div class="more-btn2"><a href="{{ adUrl($favDetail->ad_id, $favDetail->ad_search_id) }}">{{__('favourites.more')}}..</a></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid-content-second">
                                            <ul>
                                                <li>
                                                    <h4>@if(!empty($favDetail->ads->ad_details) && !empty($favDetail->ads->ad_details->bedrooms)){{$favDetail->ads->ad_details->bedrooms}}@endif</h4><span><img src="{{URL::asset('images/bed-icon.png')}}" alt="" /></span>
                                                    <p>{{__('favourites.bed_rooms')}}</p>
                                                </li>
                                                <li>
                                                    <h4>@if(!empty($favDetail->ads->ad_details) && !empty($favDetail->ads->ad_details->bathrooms)){{$favDetail->ads->ad_details->bathrooms}}@endif</h4><span><img src="{{URL::asset('images/bathroom-icon.png')}}" alt="" /></span>
                                                    <p>{{__('favourites.bath_rooms')}}</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif(!empty($favDetail->ads->scenario_id) && ($favDetail->ads->scenario_id==3 || $favDetail->ads->scenario_id==4 || $favDetail->ads->scenario_id==5))
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 roommate-grid-bx-outer list-view-time">
                                <div class="roomate-full-bx-inside favorites-listing-full">
                                    <div class="roomate-full-bx-inside2">
                                        <a href="{{ adUrl($favDetail->ad_id, $favDetail->ad_search_id) }}">
                                            <div class="roomategrid-left-bx">
                                                <figure class="brdr-radi">
                                                    <img @if(!empty($favDetail->ads->user->user_profiles) && !empty($favDetail->ads->user->user_profiles->profile_pic)) class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $favDetail->ads->user->user_profiles->profile_pic)}}" alt="{{$favDetail->ads->user->user_profiles->profile_pic}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif/>
                                                </figure>
                                            </div>
                                        </a>
                                        <div class="roomategrid-right-bx">
                                            <div class="roomate-grid-name">
                                                <h6>@if(!empty($favDetail->ads->user) && !empty($favDetail->ads->user->first_name)){{$favDetail->ads->user->first_name}}
                                                @if(!empty($favDetail->ads->user->last_name)){{' '.$favDetail->ads->user->last_name}}
                                                @endif
                                                @endif
                                                @if(!empty($favDetail->ads->user->user_profiles) && Age($favDetail->ads->user->user_profiles->birth_date) != 0 && !empty($favDetail->ads->user->user_profiles->birth_date))
                                                {{"," . Age($favDetail->ads->user->user_profiles->birth_date)}}
                                                @endif</h6>
                                                <a href="javascript:void(0);" ad_id="{{base64_encode($favDetail->ad_id)}}" ad_search_id="{{base64_encode($favDetail->ad_search_id)}}" id="add_to_favorites">
                                                    <i class="heart-icon fa fa-heart active"></i>
                                                </a>
                                            </div>
                                            <div class="roomate-grid-price-av">
                                                <div class="roomate-grid-price-only"><h4>@if(!empty($favDetail->ads) && !empty($favDetail->ads->min_rent) && $favDetail->ads->min_rent != 0){{$favDetail->ads->min_rent}} €
                                                @if(!empty($favDetail->ads) && !empty($favDetail->ads->max_rent)){{'- '.$favDetail->ads->max_rent}} € @endif<sub> {{__('favourites.per_month')}}</sub>
                                                @else
                                                {{__('searchlisting.a_negocier')}}
                                                @endif
                                                </h4>
                                                </div>
                                                <div class="looking-now active">@if(date_create($favDetail->ads->available_date) > date_create('today')){{__('favourites.looking_from')}} {{date('j M', strtotime($favDetail->ads->available_date))}} @else {{__('favourites.looking_now')}} @endif</div>
                                            </div>
                                            <div class="roomate-grid-descrip">
                                                <p>@if(!empty($favDetail->ads) && !empty($favDetail->ads->description)){{substr($favDetail->ads->description,0,250)}}@endif @if(strlen($favDetail->ads->description) > 250)...@endif</p>
                                                <div class="send-interest-outer"><a href="{{ adUrl($favDetail->ad_id, $favDetail->ad_search_id) }}">{{__('favourites.view_more')}}</a></div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 list-view-time fav-remove-div">
                    <div class="favorites-listing-full text-center" style="margin-top: 30px;font-size: 18px;">
                        {{ __('favourites.favorites_vide') }}
                    </div>
                </div>
                @endif

        </div>
        @if($favDetails){{$favDetails->links('vendor.pagination.default')}}@endif
    </div>
</section>
@endsection

