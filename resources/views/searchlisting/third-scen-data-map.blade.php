@push('scripts')
<script src="/js/slick-img.js"></script>
@endpush
@if($ads){{$ads->links('vendor.pagination.map-search-pagination')}}@endif
<div class="search-map-bx-listing">
    <div class="row room-grid-bx-outer-row grid-view-row-on">
        @foreach($ads as $ad)
        <div data-id="{{$ad->id}}" lat="{{$ad->latitude}}" long="{{$ad->longitude}}" class="ad-short-view col-xs-12 col-sm-12 col-md-12 col-lg-6 room-grid-bx-outer grid-view-time">
            <div data-id="{{$ad->id}}" ad-href="{{ adUrl($ad->id, $id) }}" class="room-grid-listing-bx-inside grid-row-ad" ad_id="{{str_replace('.', '', $ad->latitude) . '-' . str_replace('.', '', $ad->longitude)}}">

                <a class="grid-img-sec-wrapper" target="_blank" href="{{ adUrl($ad->id, $id) }}">
                    <div class="grid-img-sec d-flex justify-content-center">
                            <div class="grid-price-bx">
                                <h4>@if(!empty($ad) && !empty($ad->min_rent)){{$ad->min_rent}} € <small>{{ __('searchlisting.par_mois') }}</small>@endif
                                @if(!empty($ad) && (empty($ad->min_rent) || $ad->min_rent == 0))
                                {{__('searchlisting.a_negocier')}}
                                @endif
                                </h4>
                            </div>
                            @if(!empty($ad->ad_files) && count($ad->ad_files) > 0 && File::exists(storage_path('/uploads/images_annonces/' . $ad->ad_files[0]->filename)))
                            <figure class="brdr-rect-right">
                                <div class="slick-slider" id="slick-slider-room-{{$ad->id}}">
                                    @foreach($ad->ad_files as $key => $img)
                                    <div>
                                        <img class="ad-image-slick active-custom-carousel" src="{{'/uploads/images_annonces/' . $img->filename}}" >
                                    </div>
                                    @endforeach
                                </div>
                                @if(count($ad->ad_files) > 1)
                                <div class="custom-carousel-prev-wrapper">
                                    <div class='custom-carousel-prev d-flex justify-content-center align-items-center' data-id="slick-slider-room-{{$ad->id}}">
                                        <img src="/img/navigation-arrow-left-gray.png"/>
                                    </div>
                                </div>
                                <div class="custom-carousel-next-wrapper">
                                    <div class='custom-carousel-next d-flex justify-content-center align-items-center' data-id="slick-slider-room-{{$ad->id}}">
                                        <img src="/img/navigation-arrow-right-gray.png"/>
                                    </div>
                                </div>
                                @endif
                            </figure>
                            @else
                                <figure class="brdr-rect-right d-flex justify-content-center align-items-center">
                                    <div class="no-pic-border d-flex justify-content-center align-items-center">
                                        <img class="no_pic_available" src="/images/room_no_pic.png" alt="{{ __('no pic') }}" />
                                    </div>
                                </figure>
                            @endif
                    </div>
                </a>

                <div class="grid-content new-grid-content ad-right-item d-flex flex-wrap">
                    <div class="details-item-left full-height"> <!-- flex-one  -->
                        @if($layout == 'inner')
                            <a href="javascript:void(0);" ad_id="{{base64_encode($ad->ad_id)}}" ad_search_id="{{base64_encode($id)}}" id="add_to_favorites">
                                @if(isUrgent($ad) || isTopList($ad) || isAnnonceAcceuil($ad))
                                <span class="sponsorised">Sponsorisé</span>
                                @endif
                                @if(in_array($ad->ad_id, $favourites))
                                <img class="icon-star" src="/img/full-icon-star.png" width="40" height="40">
                                @else
                                <img class="icon-star" src="/img/empty-icon-star.png" width="40" height="40">
                                @endif
                            </a>
                        @endif
                        <div class="grid-content-first full-width full-height d-flex flex-column @if(empty($ad->bedrooms) && empty($ad->bathrooms)) max-div @endif">
                                <div class="gird-room-location-line d-flex flex-column justify-content-center">
                                    <img class="location-home" src="/img/pointer_home.png" alt="location home">
                                    <h6 class="overflow-ellipsis">@if(!empty($ad) && !empty($ad->address)){{$ad->address}}@endif</h6>


                                    <p>{{ __('searchlisting.posted') }} <span class="bolder">{{translateDuration($ad->updated_at)}}.</span></p>

                                </div>

                                <div class="content-details-list d-flex flex-column">
                                    <div class="gird-room-area-line gird-room-title d-flex flex-wrap">
                                        <h6 class="location-title overflow-ellipsis flex-one" >@if(!empty($ad) && !empty($ad->title)){{$ad->title}}@endif</h6>

                                        <div class="first-available"><span>{{ __('searchlisting.available') }}</span></div>

                                    </div>

                                    <div class="gird-room-area-line grid-room-surface">
                                        @if(!empty($ad) && !empty($ad->min_surface_area))
                                        <img class="ft-squar-icon" src="{{URL::asset('images/ft-squar-icon.png')}}" alt="" />
                                        <h4>{{$ad->min_surface_area}} <sub>{{ __('searchlisting.sq_meter') }}</sub></h4>
                                        @endif
                                    </div>


                                    <div class="gird-room-descrp-line d-flex flex-wrap">
                                        @if(!empty($ad))
                                        <p class="other_details_info d-flex align-items-center">@if($ad->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</p>
                                        @endif

                                        <p class="detail-sumary full-width d-flex align-items-center overflow-ellipsis">
                                            @if(!empty($ad) && !empty($ad->description))
                                                {{$ad->description}}
                                            @endif
                                        </p>
                                        <?php
                                            $nbPhone = countSignalAd($ad->id, "no_phone_respond");
                                            $nbfb = countSignalAd($ad->id, "no_fb_respond");
                                            $nbLoue = countSignalAd($ad->id, "ad_loue");
                                         ?>
                                        @if($nbLoue > 0 || $nbPhone > 0 || $nbfb > 0)
                                        <div class="signal-info-list">
                                            @if($nbLoue > 0)
                                            <span class="
                                                span-icon span-signal-info span-signal-info-loue"><i class="fa fa-home icon-loue"></i><span class="icon-x txt-loue">{{__('addetails.loue')}}</span>
                                                <span class="span-nb-signal">({{countSignalAd($ad->id, "ad_loue")}})</span>
                                            </span>
                                            @endif
                                            @if($nbPhone > 0)
                                            <span class="
                                                span-icon span-signal-info"><img class="img-icon-button" width="25" height="25" src="/img/icons/icone-phone.png">
                                                <span class="icon-x icon-x-list">X</span>
                                                <span class="span-nb-signal">({{countSignalAd($ad->id, "no_phone_respond")}})</span>
                                            </span>
                                            @endif
                                            @if($nbfb > 0)
                                            <span class="
                                                span-icon span-signal-info"><i class="fa fa-facebook-f fb-nb"></i><span class="icon-x icon-x-info icon-x-list">X</span>
                                                <span class="span-nb-signal nb-fb">({{countSignalAd($ad->id, "no_fb_respond")}})</span>
                                            </span>
                                            @endif
                                        </div>
                                        @endif

                                        @if(count($ad->nearby_facilities) > 0 && !empty(concatNearByFacilities($ad->nearby_facilities)))
                                        <p class="full-width info-metro d-flex align-items-center overflow-ellipsis">
                                            <img class="metro-icone" src="/img/metro-icone.png" width="25" height="25"><span class="metro-text">{{concatNearByFacilities($ad->nearby_facilities)}}</span>
                                        </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="ad-contact d-flex justify-content-between">
                                    <div class="left-item d-flex align-items-center">
                                        <div class="div-more-detail btn-detail">
                                            <a class="btn-more-detail btn-icon" href="{{ adUrl($ad->id, $id) }}">
                                                <img class="icon-search-web" src="/img/search_white.png" alt="search white">
                                                <img class="icon-search-mobile" src="/img/icon_search_plus_white.png" alt="search white">

                                                <span class="text-more">{{ __('searchlisting.more') }}...</span>
                                                <!-- <span class="search-icon-plus">+</span> -->
                                            </a>
                                        </div>

                                        @if($ad->user_id != Auth::id())
                                            @if(isSentMessageFlash($ad->id, Auth::id()))
                                            <div class="div-more-detail">
                                                <a class="sent-message-flash-search btn-more-detail btn-icon btn-toctoc-recherche d-flex justify-content-center align-items-center" href="javascript:"><img class="icon-toctoc object-fit-contain" src="/img/toctoc.png" width="25" height="25"><span>{{__('searchlisting.interet_signale')}}</span></a>
                                            </div>
                                            @else
                                            <div class="div-more-detail">
                                                <a data-id="{{$ad->user_id}}" data-ad-id="{{$ad->id}}" class="message-flash-button btn-more-detail btn-icon btn-toctoc-recherche btn-signal-toctoc d-flex justify-content-center align-items-center" href="javascript:"><img class="icon-toctoc object-fit-contain" src="/img/toctoc.png" width="25" height="25"><span>{{__('searchlisting.signale_interet')}}</span></a>
                                                <a id="sent-flash-{{$ad->id}}" class="sent-message-flash-search sent-message-flash btn-more-detail btn-icon btn-toctoc-recherche justify-content-center align-items-center" href="javascript:"><img class="icon-toctoc object-fit-contain" src="/img/toctoc.png" width="25" height="25"><span>{{__('searchlisting.interet_signale')}}</span></a>
                                            </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="left-item d-flex align-items-center">
                                        @if($ad->user_id != Auth::id())
                                        @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->mobile_no))
                                        <div class="contact-div"><button type="button" id="userPhone" class="userPhone return_handle_button btn btn-primary contact-info" other-id="{{$ad->id}}" title-info="{{$ad->user->id}}"><span class="glyphicon glyphicon-earphone"></span></button></div>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="details-item-right">
                        <div class="coloc-head d-flex align-items-center">
                            <div class="avatar">

                                @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)) && ($ad->user->user_profiles->profile_pic != ""))
                                    <a target="_blank" href="{{userUrl($ad->user_id)}}">
                                        <img class="user-profile-search contact-info object-fit-cover" class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)}}" alt="{{$ad->user->user_profiles->profile_pic}}" width="40" height="40">
                                    </a>
                                @else
                                    <a target="_blank" href="{{userUrl($ad->user_id)}}">
                                        <img class="user-profile-search contact-info object-fit-cover" src="{{URL::asset('/images/profile_avatar.jpeg')}}" alt="avatar" width="40" height="40">
                                    </a>
                                @endif
                            </div>
                            <div class="identity flex-one d-flex flex-wrap">
                                @if(isset($ad->user))
                                @if(!empty($ad->user->user_profiles))
                                    <span class="user-name">{{$ad->user->first_name}}</span>
                                @endif
                                    @if(isUserSubscribed($ad->user->id))
                                    <a href="javascript:" class="membre_premium info-item">{{ __('searchlisting.premium') }}</a>
                                @else
                                    <a href="javascript:" class="membre_basique info-item">{{ __('searchlisting.basique') }}</a>
                                @endif

                                @if(!empty($ad->user->user_profiles->sex))
                                <span class="sexe info-item">{{ ($ad->user->user_profiles->sex ==0) ? __('searchlisting.men') : __('searchlisting.women')}}</span>
                                @endif

                                @if(!empty($ad->user->user_profiles->salarie))
                                <span class="post-type info-item">{{ $ad->user->user_profiles->salarie }}</span>
                                @endif

                                @if(!empty($ad->user->user_profiles->profession))
                                <span class="post-name info-item">{{ $ad->user->user_profiles->profession }}</span>
                                @endif
                                @endif
                            </div>
                        </div>
                        <div class="coloc-content d-flex flex-wrap">
                                <div class="map-info d-flex flex-wrap">
                                    @if(!empty($ad->user->user_profiles->school))
                                    <span class="label-info">{{ __('searchlisting.scool_university') }}</span>
                                    <span class="map-info-value overflow-ellipsis">{{$ad->user->user_profiles->school}}</span>
                                    @endif

                                    @if(!empty($ad->user->user_profiles->hometown))
                                    <span class="label-info">{{ __('searchlisting.city') }}</span>
                                    <span class="map-info-value overflow-ellipsis">{{$ad->user->user_profiles->city}}</span>
                                    @endif

                                    @if(!empty($ad->user->user_profiles->hometown))
                                    <span class="label-info">{{ __('searchlisting.hometown') }}</span>
                                    <span class="map-info-value overflow-ellipsis">{{$ad->user->user_profiles->hometown}}</span>
                                    @endif
                                </div>
                                <div class="hobby">
                                @if(count(userSocialInterests($ad->user_id)) > 0)
                                    <span class="label-title">Sorties:</span>
                                    <div class="full-width d-flex flex-wrap">
                                    @foreach(userSocialInterests($ad->user_id) as $i => $interest)
                                        @if($i < 5)
                                            <div class="info-item overflow-ellipsis">{{$interest->interest_name}}</div>
                                        @endif
                                    @endforeach
                                    @if(count(userSocialInterests($ad->user_id)) > 5)<div class="info-item so-on">...</div>@endif
                                    </div>

                                @endif
                                </div>
                                <div class="sports">
                                @if(count(userSport($ad->user_id)) > 0)
                                    <div class="label-title">{{ __('searchlisting.sport') }}:</div>
                                    <div class="full-width d-flex flex-wrap">
                                    @foreach(userSport($ad->user_id) as $i => $sport)
                                        @if($i < 2)
                                            <div class="info-item overflow-ellipsis">{{$sport->label}}</div>
                                        @endif
                                    @endforeach
                                    @if(count(userSport($ad->user_id)) > 2)<div class="info-item so-on">...</div>@endif
                                    </div>
                                @endif
                                </div>
                        </div>
                        <div class="coloc-footer d-flex align-items-start">
                                <div class="music">
                                @if(count(userMusic($ad->user_id)) > 0)
                                    <span class="label-title">{{ __('searchlisting.musique') }}:</span>
                                    <div class="full-width d-flex flex-wrap">
                                    @foreach(userMusic($ad->user_id) as $i => $music)
                                        @if($i < 3)
                                            <div class="info-item overflow-ellipsis">{{$music->label}}</div>
                                        @endif
                                    @endforeach
                                    @if(count(userMusic($ad->user_id)) > 3)<div class="info-item so-on">...</div>@endif
                                    </div>
                                @endif
                                </div>
                                <div class="is-smoker">
                                    <div class="info-item d-flex flex-wrap align-items-center">
                                        @if(!is_null($ad->user->user_profiles) && $ad->user->user_profiles->smoker==0)
                                        <span class="bold">{{__('searchlisting.not')}}</span>
                                        @endif
                                        <span class="lighter">{{__('searchlisting.fumeur')}}</span>
                                    </div>
                                </div>


                                @if($ad->user_id != Auth::id())
                                <button data-id="{{ adUrl($ad->id, $id) }}" type="button" class="sendAdMail btn btn-primary contact-info">
                                    <img class="icon-envellope" src="/img/envellope.png" >
                                </button>
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

@if($ads){{$ads->links('vendor.pagination.map-search-pagination')}}@endif

