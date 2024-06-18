@push('scripts')
<script src="/js/slick-img.js"></script>
@endpush
@if($ads){{$ads->links('vendor.pagination.map-search-pagination')}}@endif
<div class="search-map-bx-listing">
    <div class="row room-grid-bx-outer-row grid-view-row-on">
        @foreach($ads as $ad)
        <div data-id="{{$ad->id}}" lat="{{$ad->latitude}}" long="{{$ad->longitude}}" class="ad-short-view col-xs-12 col-sm-12 col-md-12 col-lg-6 room-grid-bx-outer grid-view-time basic-ad">
            <div data-id="{{$ad->id}}" ad-href="{{ adUrl($ad->id, $id) }}" class="room-grid-listing-bx-inside grid-row-ad" ad_id="{{str_replace('.', '', $ad->latitude) . '-' . str_replace('.', '', $ad->longitude)}}">

                <a target="_blank" href="{{ adUrl($ad->id, $id) }}">
                    <div class="grid-img-sec">
                        <div class="grid-price-bx">
                            <h4>@if(!empty($ad) && !empty($ad->min_rent)){{$ad->min_rent.'€'}}<small>{{ __('searchlisting.par_mois') }}</small>@endif
                            @if(!empty($ad) && (empty($ad->min_rent) || $ad->min_rent == 0))
                            {{__('searchlisting.a_negocier')}}
                            @endif
                            </h4>
                        </div>
                        <div class="grid-pics-bx"><i class="fa fa-camera" aria-hidden="true"></i><span>@if(!empty($ad->ad_files)){{count($ad->ad_files)}}@endif</span></div>

                        <figure class="brdr-rect-right">
                        @if(!empty($ad->ad_files) && count($ad->ad_files) > 0 && File::exists(storage_path('/uploads/images_annonces/' . $ad->ad_files[0]->filename)))
                        <div class="slick-slider" id="slick-slider-room-{{$ad->id}}">
                            @foreach($ad->ad_files as $key => $img)
                            <div>
                                <img class="ad-image-slick active-custom-carousel" src="{{'/uploads/images_annonces/' . $img->filename}}" >
                            </div>
                            @endforeach
                        </div>
                        @if(count($ad->ad_files) > 1)
                        <div class='custom-carousel-prev' data-id="slick-slider-room-{{$ad->id}}"><img src="/img/small-img.png" width="40" height="40"/></div>
                        <div class='custom-carousel-next' data-id="slick-slider-room-{{$ad->id}}"><img src="/img/small-img.png" width="40" height="40"/></div>
                        @endif
                        @else
                        <img class="no_pic_available" src="/images/room_no_pic.png" alt="{{ __('no pic') }}" />
                        @endif
                        </figure>
                        <div class="featured-bx-location">
                            <p><i class="fa fa-map-marker" aria-hidden="true"></i>@if(!empty($ad) && !empty($ad->address)){{$ad->address}}@endif</p>
                        </div>
                    </div>
                </a>
                <div class="grid-content grid-content-first-container">
                    <div class="grid-content-first">
                        <div class="gird-room-location-line">
                            <p>{{ __('searchlisting.posted') }} {{translateDuration($ad->updated_at)}}</p>
                            @if($layout == 'inner')
                            <a href="javascript:void(0);" ad_id="{{base64_encode($ad->ad_id)}}" ad_search_id="{{base64_encode($id)}}" id="add_to_favorites">
                                @if(isUrgent($ad) || isTopList($ad) || isAnnonceAcceuil($ad))
                                <span class="sponsorised">Sponsorisé</span>
                                @endif
                                @if(in_array($ad->ad_id, $favourites))
                                <i class="heart-icon fa fa-heart active"></i>
                                @else
                                <i class="heart-icon fa fa-heart"></i>
                                @endif
                            </a>
                            @endif
                        </div>
                        <div class="gird-room-area-line gird-room-title">
                            <h6 class="title-searchlisting" >@if(!empty($ad) && !empty($ad->title)){{$ad->title}}@endif</h6>
                            <!-- @if(date_create($ad->available_date) > date_create('today'))
                            <div class="first-available">{{ __('searchlisting.available_from') }} {{date('j M', strtotime($ad->available_date))}}</div>
                            @else
                            <div class="first-available">{{ __('searchlisting.available') }}</div>
                            @endif -->
                            @if(isDisponible($ad->available_date))
                            <div class="first-available">{{ __('searchlisting.available') }}</div>
                            @endif
                        </div>

                        <div class="gird-room-area-line">
                            @if(!empty($ad) && !empty($ad->min_surface_area))
                            <img class="ft-squar-icon" src="{{URL::asset('images/ft-squar-icon.png')}}" alt="" />
                            <h4>{{$ad->min_surface_area}} <sub>{{ __('searchlisting.sq_meter') }}</sub></h4>
                            @endif

                        </div>

                        <div class="gird-room-descrp-line">
                            @if(!empty($ad))
                            <p class="other_details_info">@if($ad->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</p>
                            @endif
                            @if(!empty($ad) && !empty($ad->bedrooms))
                            <p class="details_info">{{$ad->bedrooms}} {{ __('searchlisting.bedroom') }}</p>
                            @else
                            <p>@if(!empty($ad) && !empty($ad->description)){{$ad->description}}@endif</p>
                            @endif
                            @if(!empty($ad) && !empty($ad->bathrooms))
                            <p class="details_info">{{$ad->bathrooms}} {{ __('searchlisting.bathroom') }}</p>
                            @endif
                            @if(count($ad->nearby_facilities) > 0 && !empty(concatNearByFacilities($ad->nearby_facilities)))
                            <p><img class="metro-icone" src="/img/metro-icone.png" width="25" height="25"><span class="metro-text">{{concatNearByFacilities($ad->nearby_facilities)}}</span></p>
                            @endif
                            @if(isUrgent($ad) || isTopList($ad) || isAnnonceAcceuil($ad))
                            <p>
                                @if(isUrgent($ad))
                                <a href="javascript:" class="link-logo-urgent">
                                  <span class="glyphicon glyphicon-star"></span>
                                  {{__('searchlisting.urgent')}}
                                </a>
                                @endif
                                <img width="25" height="25" alt="boost" src="/img/booster_annonce.png">
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="ad-contact">
                            <div class="div-more-detail">
                                <a class="btn-more-detail" href="javascript:">{{ __('searchlisting.more') }}..</a>
                            </div>

                            @if($ad->user_id != Auth::id())
                            @if(isSentMessageFlash($ad->id, Auth::id()))
                            <div class="div-more-detail toctoc-map">
                                <a class="sent-message-flash-search btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.interet_signale')}}</a>
                            </div>
                            @else
                            <div class="div-more-detail toctoc-map">
                                <a data-id="{{$ad->user_id}}" data-ad-id="{{$ad->id}}" class="message-flash-button btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.signale_interet')}}</a>
                            </div>
                            <div class="div-more-detail sent-message-flash toctoc-map" id="sent-flash-{{$ad->id}}">
                                <a class="sent-message-flash-search btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.interet_signale')}}</a>
                            </div>
                            @endif
                            @endif
                            <div class="div-user-infos">
                                <div class="contact-div div-type-membre">
                                    @if(isset($ad->user))
                                      @if(isUserSubscribed($ad->user->id))<a href="javascript:" class="membre_premium">Membre Premium</a>@else<a href="javascript:" class="membre_basique">Membre basique</a>@endif</div>
                                    @endif
                                @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)) && ($ad->user->user_profiles->profile_pic != ""))
                                <div class="contact-div">
                                    <a target="_blank" href="{{userUrl($ad->user_id)}}">
                                        <img class="user-profile-search contact-info object-fit-cover" class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)}}" alt="{{$ad->user->user_profiles->profile_pic}}" width="40" height="40">
                                    </a>
                                </div>
                                @else
                                <div class="contact-div">
                                    <a target="_blank" href="{{userUrl($ad->user_id)}}">
                                        <img class="user-profile-search contact-info object-fit-cover" src="{{URL::asset('/images/profile_avatar.jpeg')}}" alt="avatar" width="40" height="40">
                                    </a>
                                </div>
                                @endif

                                @if($ad->user_id != Auth::id())
                                @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->mobile_no))
                                <div class="contact-div"><button type="button" id="userPhone" class="userPhone return_handle_button btn btn-primary contact-info" other-id="{{$ad->id}}" title-info="{{$ad->user->id}}"><span class="glyphicon glyphicon-earphone"></span></button></div>
                                @endif
                                <div class="contact-div"><button data-id="{{ adUrl($ad->id, $id) }}" type="button" class="sendAdMail btn btn-primary contact-info"><span class="glyphicon glyphicon-envelope"></span></button></div>
                                @endif
                            </div>
                        </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.slick-slider').slick();
    });
</script>

{{-- @if($ads){{$ads->links('vendor.pagination.map-search-pagination')}}@endif --}}

