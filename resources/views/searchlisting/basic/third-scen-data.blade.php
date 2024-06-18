@if( $grille == 1)
<div class="row room-grid-bx-outer-row grid-row">
@endif
@if( $grille == 2)
 <div class="row room-grid-bx-outer-row grid-view-row-on">
@endif


    @if(count($ads) == 0 || count($ads)==1)
    @include("common.pub-subscription")
    @endif
    @if(!empty($ads) && count($ads) > 0)

        @foreach($ads as $i => $ad)
            @if (!is_null($ad->comunity_id))
                @if($ad->updated_at > $nbrJourAdsCommutyExp)
                    @if(count($ads) >= 2)
                        @if($i == 2)
                            @include("common.pub-subscription")
                        @endif
                    @endif

                    @if($grille == 1)
                            <div class="room-grid-ad-content col-xs-12 col-sm-6 col-md-6 col-lg-4 room-grid-bx-outer list-view-time basic-list">
                    @endif

            @if($grille == 2)

                    <div class="ad-short-view col-xs-12 col-sm-12 col-md-12 col-lg-4 room-grid-bx-outer grid-view-time">
            @endif

                <div ad-href="{{ adUrl($ad->id, $id) }}" class="room-grid-listing-bx-inside new-room-grid-listing-bx-inside grid-row-ad">
                    <a target="_blank" href="{{ adUrl($ad->id, $id) }}">
                        <div class="grid-img-sec">
                            <div class="grid-price-bx">
                                <h4>@if(!empty($ad) && !empty($ad->min_rent)){{Conversion_devise($ad->min_rent)}} {{get_current_symbol()}} <small>{{ __('searchlisting.par_mois') }}</small>@endif
                                @if(!empty($ad) && (empty($ad->min_rent) || $ad->min_rent == 0))
                                {{__('searchlisting.a_negocier')}}
                                @endif
                                </h4>
                            </div>
                            <div class="grid-pics-bx">
                            <i class="fa fa-camera" aria-hidden="true"></i><span>@if(!empty($ad->ad_files)){{count($ad->ad_files)}}@endif</span></div>

                            <figure data-id="{{$ad->id}}"


                                @if(count($ad->ad_files) > 0)
                                data-img="
                                    @foreach($ad->ad_files as $key => $img)
                                        {{ url('/uploads/images_annonces/' . $img->filename) . '|'}}
                                    @endforeach"
                                @else
                                    img-class="no_pic_available" data-img="{{url('/images/noimage.png')}}"
                                @endif
                                class="brdr-rect-right fig-annonce">
                                    <img class="loader-img" src="/img/loader.gif">
                            </figure>


                            <div class="featured-bx-location">
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i>@if(!empty($ad) && !empty($ad->address)){{$ad->address}}@endif</p>
                            </div>
                        </div>
                    </a>
                    <div class="grid-content new-grid-content" style="">
                        <div class="grid-content-first @if(empty($ad->bedrooms) && empty($ad->bathrooms)) max-div @endif">
                            <div class="gird-room-location-line">
                                <i class="location-icon fa fa-map-marker" aria-hidden="true"></i>
                                <h6  class="grid-only-loca">@if(!empty($ad) && !empty($ad->address)){{$ad->address}}@endif</h6>


                                <p>{{ __('searchlisting.posted') }} {{translateDuration($ad->updated_at)}}.</p>

                                @if($layout == 'inner')
                                <a href="javascript:void(0);" ad_id="{{base64_encode($ad->ad_id)}}" ad_search_id="{{base64_encode($id)}}" id="add_to_favorites">

                                    @if(in_array($ad->ad_id, $favourites))
                                    <i class="heart-icon fa fa-heart active"></i>
                                    @else
                                    <i class="heart-icon fa fa-heart"></i>
                                    @endif
                                </a>
                                @endif

                                @if(isStatUser())
                                <p>{{ __('searchlisting.vue') }} : {{$ad->view}} | Clic : {{$ad->clic}}| Message : {{$ad->message}} | Toc Toc : {{$ad->toc_toc}} | Fb : {{$ad->contact_fb}} | Phone : {{$ad->phone}}</p>
                                @endif
                            </div>

                        <div classe="sof_favori testt">
                            <div class="gird-room-area-line gird-room-title" ad-href="{{ adUrl($ad->id, $id) }}">
                                <h6 class="title-searchlisting" >@if(!empty($ad) && !empty($ad->title)){{$ad->title}}@endif</h6>
                                @if(isDisponible($ad->available_date))
                                <div class="first-available">{{ __('searchlisting.available') }}</div>
                                @endif
                            </div>
                            @if(!empty($ad) && !empty($ad->min_surface_area))
                            <div class="gird-room-area-line">

                                <img class="ft-squar-icon" src="{{URL::asset('images/ft-squar-icon.png')}}" alt="" />
                                <h4>{{$ad->min_surface_area}} <sub>{{ __('searchlisting.sq_meter') }}</sub></h4>

                            </div>
                            @endif

                            <div class="gird-room-descrp-line testt" ad-href="{{ adUrl($ad->id, $id) }}">
                                @if(isUrgent($ad) || isTopList($ad) || isAnnonceAcceuil($ad))
                                <p class="other_details_info">
                                    @if(isUrgent($ad))
                                    <a href="javascript:" class="link-logo-urgent">
                                    <span class="glyphicon glyphicon-star"></span>
                                    {{__('searchlisting.urgent')}}
                                    </a>
                                    @endif
                                    @if(isTopList($ad) || isAnnonceAcceuil($ad))
                                    <span class="sponsorised">{{ __('searchlisting.sponsorise') }}</span>
                                    @endif
                                    <img width="25" height="25" alt="boost" src="/img/booster_annonce.png">
                                </p>
                                @endif
                                @if(!empty($ad))
                                <p class="other_details_info">@if($ad->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</p>
                                @endif
                                <p class="details_info">{{$ad->bedrooms}} {{ __('searchlisting.bedroom') }}</p>
                                @if(!empty($ad) && !empty($ad->bathrooms))
                                <p class="details_info">{{$ad->bathrooms}} {{ __('searchlisting.bathroom') }}</p>
                                @endif
                                @if(!empty($ad) && !empty($ad->description))
                                <p class="basic-description  @if(empty($ad->min_surface_area) &&  count($ad->nearby_facilities) == 0) line-5 @elseif((empty($ad->min_surface_area) &&  count($ad->nearby_facilities) > 0) || (!empty($ad->min_surface_area) &&  count($ad->nearby_facilities) == 0) ) line-3 @endif">{{$ad->description}}</p>
                                @endif
                                @if(count($ad->nearby_facilities) > 0 && !empty(concatNearByFacilities($ad->nearby_facilities)))
                                <p class="metro-info"><img class="metro-icone" src="/img/metro-icone.png" width="25" height="25"><span class="metro-text">{{concatNearByFacilities($ad->nearby_facilities)}}</span></p>
                                @endif
                            </div>

                            </div>
                        </div>
                        @if(!empty($ad) && (!empty($ad->bedrooms) || !empty($ad->bathrooms) ))
                        <div class="grid-content-second">
                            <ul>
                                @if(!empty($ad) && !empty($ad->bedrooms))
                                <li>
                                    <h4>{{$ad->bedrooms}}</h4><span><img src="{{URL::asset('images/bed-icon.png')}}" alt="" /></span>
                                    <p>{{ __('searchlisting.bedroom') }}</p>

                                </li>
                                @endif
                                @if(!empty($ad) && !empty($ad->bathrooms))
                                <li>
                                    <h4>{{$ad->bathrooms}}</h4><span><img src="{{URL::asset('images/bathroom-icon.png')}}" alt="" /></span>
                                    <p>{{ __('searchlisting.bathroom') }}</p>
                                </li>
                                @endif
                            </ul>
                        </div>
                        @endif

                        <div class="ad-contact">
                            <div class="div-more-detail testt btn-detail" ad-href="{{ adUrl($ad->id, $id) }}" >
                                <a class="btn-more-detail"  href="javascript:">{{ __('searchlisting.more') }}..</a>
                            </div>

                            @if($ad->user_id != Auth::id())
                            @if(isSentMessageFlash($ad->id, Auth::id()))
                            <div class="div-more-detail">
                                <a class="sent-message-flash-search btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.interet_signale')}}</a>
                            </div>
                            @else
                            <div class="div-more-detail">
                                <a data-id="{{$ad->user_id}}" data-ad-id="{{$ad->id}}" class="message-flash-button btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.signale_interet')}}</a>
                            </div>
                            <div class="div-more-detail sent-message-flash" id="sent-flash-{{$ad->id}}">
                                <a class="sent-message-flash-search btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.interet_signale')}}</a>
                            </div>
                            @endif
                            @endif

                            <div class="div-user-infos">

                                @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)))
                                <div class="contact-div">
                                    <a target="_blank" href="{{userUrl($ad->user_id)}}">
                                        <img class="user-profile-search contact-info" @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic)) class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)}}" alt="{{$ad->user->user_profiles->profile_pic}}" @endif width="40" height="40">
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
                            <div class="contact-div div-type-membre">
                                @if(isset($ad->user))
                                   @if(isUserSubscribed($ad->user->id))<a href="javascript:" class="membre_premium">{{ __('searchlisting.premium') }}</a>@else<a href="javascript:" class="membre_basique">{{ __('searchlisting.basique') }}</a>@endif</div>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
                @endif
            @else
            @if(count($ads) >= 2)
            @if($i == 2)
                @include("common.pub-subscription")
            @endif
        @endif

        @if($grille == 1)
                <div class="room-grid-ad-content col-xs-12 col-sm-6 col-md-6 col-lg-4 room-grid-bx-outer list-view-time basic-list">
        @endif

        @if($grille == 2)

                <div class="ad-short-view col-xs-12 col-sm-12 col-md-12 col-lg-4 room-grid-bx-outer grid-view-time">
        @endif

            <div ad-href="{{ adUrl($ad->id, $id) }}" class="room-grid-listing-bx-inside new-room-grid-listing-bx-inside grid-row-ad">
                <a target="_blank" href="{{ adUrl($ad->id, $id) }}">
                    <div class="grid-img-sec">
                        <div class="grid-price-bx">
                            <h4>@if(!empty($ad) && !empty($ad->min_rent)){{Conversion_devise($ad->min_rent)}} {{get_current_symbol()}} <small>{{ __('searchlisting.par_mois') }}</small>@endif
                            @if(!empty($ad) && (empty($ad->min_rent) || $ad->min_rent == 0))
                            {{__('searchlisting.a_negocier')}}
                            @endif
                            </h4>
                        </div>
                        <div class="grid-pics-bx">
                        <i class="fa fa-camera" aria-hidden="true"></i><span>@if(!empty($ad->ad_files)){{count($ad->ad_files)}}@endif</span></div>

                        <figure data-id="{{$ad->id}}"


                            @if(count($ad->ad_files) > 0)
                            data-img="
                                @foreach($ad->ad_files as $key => $img)
                                    {{ url('/uploads/images_annonces/' . $img->filename) . '|'}}
                                @endforeach"
                            @else
                                img-class="no_pic_available" data-img="{{url('/images/noimage.png')}}"
                            @endif
                            class="brdr-rect-right fig-annonce">
                                <img class="loader-img" src="/img/loader.gif">
                        </figure>


                        <div class="featured-bx-location">
                            <p><i class="fa fa-map-marker" aria-hidden="true"></i>@if(!empty($ad) && !empty($ad->address)){{$ad->address}}@endif</p>
                        </div>
                    </div>
                </a>
                <div class="grid-content new-grid-content" style="">
                    <div class="grid-content-first @if(empty($ad->bedrooms) && empty($ad->bathrooms)) max-div @endif">
                        <div class="gird-room-location-line">
                            <i class="location-icon fa fa-map-marker" aria-hidden="true"></i>
                            <h6  class="grid-only-loca">@if(!empty($ad) && !empty($ad->address)){{$ad->address}}@endif</h6>


                            <p>{{ __('searchlisting.posted') }} {{translateDuration($ad->updated_at)}}.</p>

                            @if($layout == 'inner')
                            <a href="javascript:void(0);" ad_id="{{base64_encode($ad->ad_id)}}" ad_search_id="{{base64_encode($id)}}" id="add_to_favorites">

                                @if(in_array($ad->ad_id, $favourites))
                                <i class="heart-icon fa fa-heart active"></i>
                                @else
                                <i class="heart-icon fa fa-heart"></i>
                                @endif
                            </a>
                            @endif

                            @if(isStatUser())
                            <p>{{ __('searchlisting.vue') }} : {{$ad->view}} | Clic : {{$ad->clic}}| Message : {{$ad->message}} | Toc Toc : {{$ad->toc_toc}} | Fb : {{$ad->contact_fb}} | Phone : {{$ad->phone}}</p>
                            @endif
                        </div>

                    <div classe="sof_favori testt">
                        <div class="gird-room-area-line gird-room-title" ad-href="{{ adUrl($ad->id, $id) }}">
                            <h6 class="title-searchlisting" >@if(!empty($ad) && !empty($ad->title)){{$ad->title}}@endif</h6>
                            @if(isDisponible($ad->available_date))
                            <div class="first-available">{{ __('searchlisting.available') }}</div>
                            @endif
                        </div>
                        @if(!empty($ad) && !empty($ad->min_surface_area))
                        <div class="gird-room-area-line">

                            <img class="ft-squar-icon" src="{{URL::asset('images/ft-squar-icon.png')}}" alt="" />
                            <h4>{{$ad->min_surface_area}} <sub>{{ __('searchlisting.sq_meter') }}</sub></h4>

                        </div>
                        @endif

                        <div class="gird-room-descrp-line testt" ad-href="{{ adUrl($ad->id, $id) }}">
                            @if(isUrgent($ad) || isTopList($ad) || isAnnonceAcceuil($ad))
                            <p class="other_details_info">
                                @if(isUrgent($ad))
                                <a href="javascript:" class="link-logo-urgent">
                                <span class="glyphicon glyphicon-star"></span>
                                {{__('searchlisting.urgent')}}
                                </a>
                                @endif
                                @if(isTopList($ad) || isAnnonceAcceuil($ad))
                                <span class="sponsorised">{{ __('searchlisting.sponsorise') }}</span>
                                @endif
                                <img width="25" height="25" alt="boost" src="/img/booster_annonce.png">
                            </p>
                            @endif
                            @if(!empty($ad))
                            <p class="other_details_info">@if($ad->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</p>
                            @endif
                            <p class="details_info">{{$ad->bedrooms}} {{ __('searchlisting.bedroom') }}</p>
                            @if(!empty($ad) && !empty($ad->bathrooms))
                            <p class="details_info">{{$ad->bathrooms}} {{ __('searchlisting.bathroom') }}</p>
                            @endif
                            @if(!empty($ad) && !empty($ad->description))
                            <p class="basic-description  @if(empty($ad->min_surface_area) &&  count($ad->nearby_facilities) == 0) line-5 @elseif((empty($ad->min_surface_area) &&  count($ad->nearby_facilities) > 0) || (!empty($ad->min_surface_area) &&  count($ad->nearby_facilities) == 0) ) line-3 @endif">{{$ad->description}}</p>
                            @endif
                            @if(count($ad->nearby_facilities) > 0 && !empty(concatNearByFacilities($ad->nearby_facilities)))
                            <p class="metro-info"><img class="metro-icone" src="/img/metro-icone.png" width="25" height="25"><span class="metro-text">{{concatNearByFacilities($ad->nearby_facilities)}}</span></p>
                            @endif
                        </div>

                        </div>
                    </div>
                    @if(!empty($ad) && (!empty($ad->bedrooms) || !empty($ad->bathrooms) ))
                    <div class="grid-content-second">
                        <ul>
                            @if(!empty($ad) && !empty($ad->bedrooms))
                            <li>
                                <h4>{{$ad->bedrooms}}</h4><span><img src="{{URL::asset('images/bed-icon.png')}}" alt="" /></span>
                                <p>{{ __('searchlisting.bedroom') }}</p>

                            </li>
                            @endif
                            @if(!empty($ad) && !empty($ad->bathrooms))
                            <li>
                                <h4>{{$ad->bathrooms}}</h4><span><img src="{{URL::asset('images/bathroom-icon.png')}}" alt="" /></span>
                                <p>{{ __('searchlisting.bathroom') }}</p>
                            </li>
                            @endif
                        </ul>
                    </div>
                    @endif

                    <div class="ad-contact">
                        <div class="div-more-detail testt btn-detail" ad-href="{{ adUrl($ad->id, $id) }}" >
                            <a class="btn-more-detail"  href="javascript:">{{ __('searchlisting.more') }}..</a>
                        </div>

                        @if($ad->user_id != Auth::id())
                        @if(isSentMessageFlash($ad->id, Auth::id()))
                        <div class="div-more-detail">
                            <a class="sent-message-flash-search btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.interet_signale')}}</a>
                        </div>
                        @else
                        <div class="div-more-detail">
                            <a data-id="{{$ad->user_id}}" data-ad-id="{{$ad->id}}" class="message-flash-button btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.signale_interet')}}</a>
                        </div>
                        <div class="div-more-detail sent-message-flash" id="sent-flash-{{$ad->id}}">
                            <a class="sent-message-flash-search btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.interet_signale')}}</a>
                        </div>
                        @endif
                        @endif

                        <div class="div-user-infos">

                            @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)))
                            <div class="contact-div">
                                <a target="_blank" href="{{userUrl($ad->user_id)}}">
                                    <img class="user-profile-search contact-info" @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic)) class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)}}" alt="{{$ad->user->user_profiles->profile_pic}}" @endif width="40" height="40">
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
                        <div class="contact-div div-type-membre">
                            @if(isset($ad->user))
                                @if(isUserSubscribed($ad->user->id) != null)<a href="javascript:" class="membre_premium">{{ __('searchlisting.premium') }}</a>@else<a href="javascript:" class="membre_basique">{{ __('searchlisting.basique') }}</a>@endif</div>
                            @endif

                    </div>
                </div>
            </div>
        </div>
            @endif


        @endforeach
    @endif
</div>
<script>
    $(document).ready(function(){

        $('.fig-annonce').each(function(){
            var src = $(this).attr('data-img');
            var ad_id = $(this).attr('data-id');
            var img_class = $(this).attr('img-class');
            if(img_class=="no_pic_available") {
                $(this).html("<img src='"+ src +"'/>");
            } else {
                var split = src.split('|');
                var content = '<div class="slick-slider" id="slick-slider-room-'+ ad_id +'">';
                for(var i=0;i<split.length-1;i++){
                    content+='<div>'
                            +'<img class="ad-image-slick active-custom-carousel" src="'+ split[i].trim() +'" >'
                            +'</div>'
                }
                content += "</div>";
                if(split.length > 1)
                {
                    content += '<div class="custom-carousel-prev" data-id="slick-slider-room-'+ad_id+'"><img src="/img/small-img.png" width="40" height="40"/></div>'
                    +'<div class="custom-carousel-next" data-id="slick-slider-room-'+ ad_id +'"><img src="/img/small-img.png" width="40" height="40"/></div>'
                }
                $(this).html(content);
            }

            $(this).removeClass("fig-annonce");
        });
        $('.slick-slider').slick();
    });
</script>
@if($ads){{$ads->links('vendor.pagination.default')}}@endif
<style>
    .grid-view-time .grid-content
      {
        padding: 5px 3px;
      }
</style>
