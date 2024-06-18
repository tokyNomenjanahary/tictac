@push('scripts')
    <script src="/js/slick-img.js"></script>
@endpush

    @if($ads)
        {{$ads->links('vendor.pagination.new-default')}}
    @endif

    <div class="room-grid-bx-outer-row roommate-grid-bx-outer-row d-flex flex-wrap full-width ">
        @if(count($ads) == 0 || count($ads)==1)
            @include("common.pub-subscription-2")
        @endif

        @if(!empty($ads) && count($ads) > 0)
            @foreach($ads as $i => $ad)
                @if(count($ads) >= 2)
                    @if($i == 2)
                        @include("common.pub-subscription-2")
                    @endif
                @endif

                <div class="roommate-grid-bx-outer list-view-time col-xs-12 col-sm-6 col-md-6 col-lg-4">
                    <div ad-href="{{ adUrl($ad->id, $id) }}"
                         class="grid-row-ad room-grid-listing-bx-inside d-flex flex-wrap">
                        <a class="grid-img-sec-wrapper" target="_blank" href="{{ adUrl($ad->id, $id) }}">
                            <div class="grid-img-sec d-flex justify-content-center align-items-center">

                                <figure class="d-flex justify-content-center align-items-center">
                                    <img
                                        @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic))) class="pic_available"
                                        src="{{URL::asset('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)}}"
                                        alt="{{$ad->user->user_profiles->profile_pic}}" @else class="no_pic_available"
                                        @if(!empty($ad->user->user_profiles))
                                            @if($ad->user->user_profiles->sex == 0) src="{{URL::asset('images/pdp-homme.jpg')}}"
                                            @else  src="{{URL::asset('images/pdp-femme.jpg')}}"
                                            @endif
                                        @else  src="{{URL::asset('images/pdp-femme.jpg')}}"
                                        @endif alt="{{ __('no pic') }}" @endif/>

                                </figure>
                            </div>
                        </a>
                        <div class="grid-content new-grid-content ad-right-item full-height flex-one d-flex flex-wrap">
                            <div class="details-item-left flex-one full-height">
                                @if($layout == 'inner')
                                    <a href="javascript:void(0);" ad_id="{{base64_encode($ad->id)}}"
                                       ad_search_id="{{base64_encode($id)}}"
                                       id="add_to_favorites">
                                        @if(isUrgent($ad) || isTopList($ad) || isAnnonceAcceuil($ad))
                                            <span class="sponsorised">Sponsorisé</span>
                                        @endif
                                        @if(in_array($ad->ad_id, $favourites))
                                            <img class="icon-star" src="/img/full-icon-star.png" width="40" height="40">
                                        @else
                                            <img class="icon-star" src="/img/empty-icon-star.png" width="40"
                                                 height="40">
                                        @endif
                                    </a>
                                @endif
                                <div
                                    class="grid-content-first full-width full-height d-flex flex-column @if(empty($ad->bedrooms) && empty($ad->bathrooms)) max-div @endif">
                                    <div class="gird-room-location-line d-flex flex-column justify-content-center">
                                        <h6 class="overflow-ellipsis">
                                            @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->origin_country_code))
                                                <a class="iti-flag flag-profil-search {{$ad->user->user_profiles->origin_country_code}}"></a>
                                            @endif
                                            @if(!empty($ad->user) && !empty($ad->user->first_name) &&!empty($ad->user->user_profiles))
                                                <a href="{{userUrl($ad->user_id)}}">
                                                    {{getFirstWord($ad->user->first_name)}}
                                                    @php
                                                        if(!$ad->user->user_profiles->sex){
                                                            $ad->user->user_profiles->sex = 0;
                                                        }
                                                        if(!$ad->user->user_profiles->professional_category){
                                                            $ad->user->user_profiles->professional_category = 2;
                                                        }
                                                    @endphp
                                                    @if(!is_null($ad->user->user_profiles->professional_category))
                                                        , {{__('addetails.profession_' . $ad->user->user_profiles->professional_category . '_' . $ad->user->user_profiles->sex)}}
                                                    @endif
                                                    <span></span>@if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->birth_date) && Age($ad->user->user_profiles->birth_date) != 0)
                                                        , {{Age($ad->user->user_profiles->birth_date)}}
                                                        {{__('addetails.years_old')}}
                                                    @endif
                                                </a>
                                            @endif
                                        </h6>


                                        <p>{{ __('searchlisting.posted') }} <span class="bolder">{{translateDuration($ad->updated_at)}}.</span>
                                        </p>
                                    </div>

                                    <div
                                        class="content-details-list flex-one d-flex flex-column justify-content-evenly" style="margin-bottom: 8px;">
                                        <div
                                            class="gird-room-area-line gird-room-title d-flex align-items-center flex-wrap">
                                            <h6 class="location-title overflow-ellipsis flex-one">
                                                {{__('searchlisting.budget_max')}}
                                                @if(!empty($ad) && !empty($ad->min_rent))
                                                    {{Conversion_devise($ad->min_rent)}} {{get_current_symbol()}}
                                                @endif
                                                @if(!empty($ad->min_rent) && !empty($ad->max_rent))
                                                    -
                                                @endif
                                                @if(!empty($ad->max_rent))
                                                    {{Conversion_devise($ad->max_rent)}} {{get_current_symbol()}} <sub> {{ __('searchlisting.per_month') }}</sub>
                                                @endif
                                                @if((empty($ad->min_rent) || $ad->min_rent == 0) && (is_null($ad->max_rent)))
                                                    {{__('searchlisting.a_negocier_roommate')}}
                                                @endif
                                            </h6>

                                            <!-- <div class="first-available"><span>{{ __('searchlisting.available') }}</span></div> -->

                                            @if(isDisponible($ad->available_date))
                                                <div
                                                    class="looking-now active d-flex align-items-center">{{ __('searchlisting.looking_now') }} </div>
                                            @endif

                                        </div>

                                        <div class="roomate-grid-descrip d-flex flex-wrap"> <!-- flex-one  -->
                                            @if(isUrgent($ad) || isTopList($ad) || isAnnonceAcceuil($ad))
                                                <p class="other_details_info d-flex justify-content-center align-items-center">
                                                    @if(isUrgent($ad))
                                                        <a href="javascript:" class="link-logo-urgent">
                                                            <span class="glyphicon glyphicon-star"></span>
                                                            {{__('searchlisting.urgent')}}
                                                        </a>
                                                    @endif
                                                </p>
                                            @endif
                                            <p class="description">@if(!empty($ad) && !empty($ad->description))
                                                    {{$ad->description}}
                                                @endif</p>
                                            @if(count($ad->nearby_facilities) > 0 && !empty(concatNearByFacilities($ad->nearby_facilities)))
                                                <p><img class="metro-icone" src="/img/metro-icone.png" width="25"
                                                        height="25"><span
                                                        class="metro-text">{{concatNearByFacilities($ad->nearby_facilities)}}</span>
                                                </p>
                                            @endif

                                            <?php
                                            $nbPhone = countSignalAd($ad->id, "no_phone_respond");
                                            $nbfb = countSignalAd($ad->id, "no_fb_respond");
                                            ?>
                                            @if($nbPhone > 0 || $nbfb > 0)
                                                <div class="signal-info-list">
                                                    @if($nbPhone > 0)
                                                        <span class="
                                        span-icon span-signal-info"><img class="img-icon-button" width="25" height="25"
                                                                         src="/img/icons/icone-phone.png">
                                        <span class="icon-x">X</span>
                                        <span
                                            class="span-nb-signal">({{countSignalAd($ad->id, "no_phone_respond")}})</span>
                                    </span>
                                                    @endif
                                                    @if($nbfb > 0)
                                                        <span class="
                                        span-icon span-signal-info"><i class="fa fa-facebook-f fb-nb"></i><span
                                                                class="icon-x icon-x-info">X</span>
                                        <span
                                            class="span-nb-signal nb-fb">({{countSignalAd($ad->id, "no_fb_respond")}})</span>
                                    </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="ad-contact d-flex justify-content-between">
                                        <div class="left-item d-flex align-items-center" style="margin-left: 1rem;margin-top: -8rem;">
                                            <div class="div-more-detail btn-detail">
                                                <a class="btn-more-detail btn-icon" href="{{ adUrl($ad->id, $id) }}">
                                                    <img class="icon-search-web" src="/img/search_white.png"
                                                         alt="search white">
                                                    <img class="icon-search-mobile"
                                                         src="/img/icon_search_plus_white.png" alt="search white">

                                                    <span class="text-more">{{ __('searchlisting.more') }}...</span>
                                                    <!-- <span class="search-icon-plus">+</span> -->
                                                </a>
                                            </div>

                                            @if($ad->user_id != Auth::id())
                                                @if(isSentMessageFlash($ad->id, Auth::id()))
                                                    <div class="div-more-detail">
                                                        <a class="sent-message-flash-search btn-more-detail btn-icon btn-toctoc-recherche d-flex justify-content-center align-items-center"
                                                           href="javascript:"><img
                                                                class="icon-toctoc object-fit-contain"
                                                                src="/img/toctoc.png" width="25"
                                                                height="25"><span>{{__('searchlisting.interet_signale')}}</span></a>
                                                    </div>
                                                @else
                                                    <div class="div-more-detail">
                                                        <a data-id="{{$ad->user_id}}" data-ad-id="{{$ad->id}}"
                                                           class="message-flash-button btn-more-detail btn-icon btn-toctoc-recherche btn-signal-toctoc d-flex justify-content-center align-items-center"
                                                           href="javascript:"><img
                                                                class="icon-toctoc object-fit-contain"
                                                                src="/img/toctoc.png" width="25"
                                                                height="25"><span>{{__('searchlisting.signale_interet')}}</span></a>
                                                        <a id="sent-flash-{{$ad->id}}"
                                                           class="sent-message-flash-search sent-message-flash btn-more-detail btn-icon btn-toctoc-recherche justify-content-center align-items-center"
                                                           href="javascript:"><img
                                                                class="icon-toctoc object-fit-contain"
                                                                src="/img/toctoc.png" width="25"
                                                                height="25"><span>{{__('searchlisting.interet_signale')}}</span></a>
                                                    </div>
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
                                                <img class="user-profile-search contact-info object-fit-cover"
                                                     class="pic_available"
                                                     src="{{URL::asset('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)}}"
                                                     alt="{{$ad->user->user_profiles->profile_pic}}" width="40"
                                                     height="40">
                                            </a>
                                        @else
                                            <a target="_blank" href="{{userUrl($ad->user_id)}}">
                                                <img class="user-profile-search contact-info object-fit-cover"
                                                @if(!empty($ad->user->user_profiles))
                                                    @if($ad->user->user_profiles->sex == 0) src="{{URL::asset('images/pdp-homme.jpg')}}"
                                                    @else  src="{{URL::asset('images/pdp-femme.jpg')}}"
                                                    @endif
                                                @else src="{{URL::asset('images/pdp-femme.jpg')}}"
                                                @endif alt="avatar" width="40" height="40">

                                            </a>
                                        @endif
                                    </div>
                                    <div class="identity flex-one d-flex flex-wrap">
                                        @if(isset($ad->user))
                                        @if(!empty($ad->user->user_profiles))
                                            <span class="user-name">{{$ad->user->first_name}}</span>
                                        @endif
                                            @if(isUserSubscribed($ad->user->id))
                                            <a href="javascript:"
                                               class="membre_premium info-item">{{ __('searchlisting.premium') }}</a>
                                        @else
                                            <a href="javascript:"
                                               class="membre_basique info-item">{{ __('searchlisting.basique') }}</a>
                                        @endif

                                        @if(!empty($ad->user->user_profiles->sex))
                                            <span
                                                class="sexe info-item">{{ ($ad->user->user_profiles->sex ==0) ? __('searchlisting.men') : __('searchlisting.women')}}</span>
                                        @endif

                                        @if(!empty($ad->user->user_profiles->salarie))
                                            <span
                                                class="post-type info-item">{{ $ad->user->user_profiles->salarie }}</span>
                                        @endif

                                        @if(!empty($ad->user->user_profiles->profession))
                                            <span
                                                class="post-name info-item">{{ $ad->user->user_profiles->profession }}</span>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="coloc-content d-flex flex-wrap">
                                    <div class="map-info d-flex flex-wrap">
                                        @if(!empty($ad->user->user_profiles->school))
                                            <span class="label-info">{{ __('searchlisting.scool_university') }}</span>
                                            <span
                                                class="map-info-value overflow-ellipsis">{{$ad->user->user_profiles->school}}</span>
                                        @endif

                                        @if(!empty($ad->user->user_profiles->hometown))
                                            <span class="label-info">{{ __('searchlisting.city') }}</span>
                                            <span
                                                class="map-info-value overflow-ellipsis">{{$ad->user->user_profiles->city}}</span>
                                        @endif

                                        @if(!empty($ad->user->user_profiles->hometown))
                                            <span class="label-info">{{ __('searchlisting.hometown') }}</span>
                                            <span
                                                class="map-info-value overflow-ellipsis">{{$ad->user->user_profiles->hometown}}</span>
                                        @endif
                                    </div>
                                    <div class="hobby">
                                        @if(count(userSocialInterests($ad->user_id)) > 0)
                                            <span class="label-title">Sorties:</span>
                                            <div class="full-width d-flex flex-wrap">
                                                @foreach(userSocialInterests($ad->user_id) as $i => $interest)
                                                    @if($i < 5)
                                                        <div
                                                            class="info-item overflow-ellipsis">{{$interest->interest_name}}</div>
                                                    @endif
                                                @endforeach
                                                @if(count(userSocialInterests($ad->user_id)) > 5)
                                                    <div class="info-item so-on">...</div>
                                                @endif
                                            </div>

                                        @endif
                                    </div>
                                    <div class="sports">
                                        @if(count(userSport($ad->user_id)) > 0)
                                            <div class="label-title">Sports:</div>
                                            <div class="full-width d-flex flex-wrap">
                                                @foreach(userSport($ad->user_id) as $i => $sport)
                                                    @if($i < 2)
                                                        <div class="info-item overflow-ellipsis">{{$sport->label}}</div>
                                                    @endif
                                                @endforeach
                                                @if(count(userSport($ad->user_id)) > 2)
                                                    <div class="info-item so-on">...</div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="coloc-footer d-flex align-items-start">
                                    <div class="music">
                                        @if(count(userMusic($ad->user_id)) > 0)
                                            <span class="label-title">Musique:</span>
                                            <div class="full-width d-flex flex-wrap">
                                                @foreach(userMusic($ad->user_id) as $i => $music)
                                                    @if($i < 3)
                                                        <div class="info-item overflow-ellipsis">{{$music->label}}</div>
                                                    @endif
                                                @endforeach
                                                @if(count(userMusic($ad->user_id)) > 3)
                                                    <div class="info-item so-on">...</div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="is-smoker">
                                        <div class="info-item d-flex flex-wrap align-items-center">
                                            @if(!empty($ad->user->user_profiles->smoker))
                                                <span class="bold">{{__('searchlisting.not')}}</span>
                                            @else
                                            <span class="lighter">{{__('searchlisting.fumeur')}}</span>
                                            @endif
                                        </div>
                                    </div>


                                    @if($ad->user_id != Auth::id())
                                        <button data-id="{{ adUrl($ad->id, $id) }}" type="button" class="sendAdMail btn btn-primary contact-info" style="bottom: 28px;width: 43px;height: 40px;">
                                            <img class="icon-envellope" src="/img/envellope.png">
                                        </button>
                                    @endif
                                </div>
                                <div class="right-item d-flex align-items-center" style="margin-bottom: -30px;justify-content: right;margin-right: 35px;">
                                    @if($ad->user_id != Auth::id())
                                        @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->mobile_no))
                                            <div class="contact-div">
                                                <button type="button" id="userPhone"
                                                        class="userPhone return_handle_button btn btn-primary contact-info"
                                                        other-id="{{$ad->id}}" title-info="{{$ad->user->id}}">
                                                    <span class="glyphicon glyphicon-earphone"></span></button>
                                            </div>
                                        @endif
                                        <!-- <div class="contact-div"><button data-id="{{ adUrl($ad->id, $id) }}" type="button" class="sendAdMail btn btn-primary contact-info"><span class="glyphicon glyphicon-envelope"></span></button></div> -->
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
    @if($ads)
        {{$ads->links('vendor.pagination.new-default')}}
    @endif
    <script>
        $('body').on('click', '.grid-view-icon', function () {
        
        $(".room-grid-bx-outer, .roommate-grid-bx-outer").removeClass("list-view-time");
        $(".room-grid-bx-outer, .roommate-grid-bx-outer").addClass("grid-view-time");
        $(".room-grid-bx-outer-row").addClass("grid-view-row-on");
    });
 
    $('body').on('click', '.listing-view-icon', function () {
    
 
        $(".room-grid-bx-outer, .roommate-grid-bx-outer").removeClass("grid-view-time");
        $(".room-grid-bx-outer, .roommate-grid-bx-outer").addClass("list-view-time");
        $(".room-grid-bx-outer-row").removeClass("grid-view-row-on");
    });
    </script>

