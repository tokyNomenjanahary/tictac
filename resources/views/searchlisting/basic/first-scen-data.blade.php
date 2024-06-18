@if($ads){{$ads->links('vendor.pagination.new-default')}}@endif
<div class="row room-grid-bx-outer-row roommate-grid-bx-outer-row">
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
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 roommate-grid-bx-outer list-view-time basic-list">
        <div ad-href="{{ adUrl($ad->id, $id) }}" class="roomate-full-bx-inside grid-row-ad" style="height: 100%;">
                   {{-- par defaut --}}
            <div style="display:inline-block" class="roomate-full-bx-inside2 img-container-ad">
                <a target="_blank" href="{{ adUrl($ad->id, $id) }}">
                    <div class="roomategrid-left-bx ">
                    <img @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic)) class="pic_available" src="{{'/uploads/profile_pics/' . $ad->user->user_profiles->profile_pic}}" alt="{{$ad->user->user_profiles->profile_pic}}" @else @if(!empty($ad->user->user_profiles)) @if($ad->user->user_profiles->sex == 0) src="{{URL::asset('images/pdp-homme.jpg')}}" @else  src="{{URL::asset('images/pdp-femme.jpg')}}" @endif @endif alt="{{ __('no pic') }}" @endif/>
                                     </figure>
                                 </div>
                                 <div class="request-bx-right">
                    </div>
                </a>
                <div class="grillage" style="flex-grow:1;display: inline-block;width: 80%;padding: 2px;margin-left: 15px;vertical-align: middle;">

                    <div class="roomate-grid-name">
                        {{-- <h6>
                            @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->origin_country_code))
                            <a class="iti-flag flag-profil-search {{$ad->user->user_profiles->origin_country_code}}"></a>
                            @endif
                            @if(!empty($ad->user) && !empty($ad->user->first_name) && !empty($ad->user->user_profiles->professional_category))
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
                                @if(!is_null($ad->user->user_profiles->professional_category)), {{__('addetails.profession_' . $ad->user->user_profiles->professional_category . '_' . $ad->user->user_profiles->sex)}}@endif<span></span>@if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->birth_date) && Age($ad->user->user_profiles->birth_date) != 0), {{Age($ad->user->user_profiles->birth_date)}}
                            {{__('addetails.years_old')}}
                            @endif
                            </a>
                            @endif
                        </h6> --}}
                        <p class="posted-info">{{ __('searchlisting.posted') }} {{translateDuration($ad->updated_at)}}.</p>
                        @if($layout == 'inner')
                        <a href="javascript:void(0);" ad_id="{{base64_encode($ad->id)}}" ad_search_id="{{base64_encode($id)}}" id="add_to_favorites">
                            @if(isUrgent($ad) || isTopList($ad) || isAnnonceAcceuil($ad))
                            <p class="sponsorised">Sponsorisé</p>
                            @endif
                            @if(in_array($ad->id, $favourites))
                            <i class="heart-icon fa fa-heart active"></i>
                            @else
                            <i class="heart-icon fa fa-heart"></i>
                            @endif
                        </a>
                        @endif

                        @if(isStatUser())
                        <p>{{ __('searchlisting.vue') }} : {{$ad->clic}} | {{ __('searchlisting.message') }} : {{$ad->message}} | Toc Toc : {{$ad->toc_toc}} | Fb : {{$ad->contact_fb}} | Phone : {{$ad->phone}}</p>
                        @endif

                    </div>
                    <div class="roomate-grid-price-av">
                        <div class="roomate-grid-price-only">
                            <h4>
                                {{__('searchlisting.budget_max')}}
                                @if(!empty($ad) && !empty($ad->min_rent)){{Conversion_devise($ad->min_rent)}} {{get_current_symbol()}} @endif
                                @if(!empty($ad->min_rent) && !empty($ad->max_rent)) - @endif
                                @if(!empty($ad->max_rent)){{Conversion_devise($ad->max_rent)}} {{get_current_symbol()}} <sub> {{ __('searchlisting.per_month') }}</sub>@endif
                                @if((empty($ad->min_rent) || $ad->min_rent == 0) && (is_null($ad->max_rent)))
                                {{__('searchlisting.a_negocier_roommate')}}
                                @endif
                            </h4>
                        </div>
                        {{-- <!-- <div class="looking-now active">@if(date_create($ad->available_date) > date_create('today')){{ __('searchlisting.looking_from') }} {{date('j M', strtotime($ad->available_date))}} @else {{ __('searchlisting.looking_now') }} @endif</div> --> --}}
                        @if(isDisponible($ad->available_date))
                        <div class="looking-now active">
                            {{ __('searchlisting.looking_now') }}
                        </div>
                        @endif
                    </div>
                    <div class="roomate-grid-descrip">
                        @if(isUrgent($ad) || isTopList($ad) || isAnnonceAcceuil($ad))
                            <p class="other_details_info">
                                @if(isUrgent($ad))
                                    <a href="javascript:" class="link-logo-urgent">
                                        <span class="glyphicon glyphicon-star"></span>
                                        {{__('searchlisting.urgent')}}
                                    </a>
                                @endif
                            </p>
                        @endif
                        <p class="basic-description">
                            @if(!empty($ad) && !empty($ad->description))
                                {{$ad->description}}
                            @endif
                        </p>
                        @if(count($ad->nearby_facilities) > 0 && !empty(concatNearByFacilities($ad->nearby_facilities)))
                            <p class="metro-info">
                                <img class="metro-icone" src="/img/metro-icone.png" width="25" height="25">
                                <span class="metro-text">{{concatNearByFacilities($ad->nearby_facilities)}}</span>
                            </p>
                        @endif
                    </div>
                    <div class="ad-contact" style="">
                        <div class="send-interest-outer btn-detail-first"><a href="{{ adUrl($ad->id, $id) }}">{{ __('searchlisting.view_more') }}</a></div>

                        @if($ad->user_id != Auth::id())
                            @if(isSentMessageFlash($ad->id, Auth::id()))
                                <div class="send-interest-outer btn-toctoc-first">
                                    <a class="sent-message-flash-search btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.interet_signale')}}</a>
                                </div>
                            @else
                            <div class="send-interest-outer btn-toctoc-first">
                                <a data-id="{{$ad->user_id}}" data-ad-id="{{$ad->id}}" class="message-flash-button btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.signale_interet')}}</a>
                            </div>
                            <div class="send-interest-outer sent-message-flash btn-toctoc-first" id="sent-flash-{{$ad->id}}">
                                <a class="sent-message-flash-search btn-more-detail btn-toctoc-recherche" href="javascript:">{{__('searchlisting.interet_signale')}}</a>
                            </div>
                        @endif
                        <div class="div-user-infos" style="text-align:right;">
                            @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->mobile_no))
                                <div class="contact-div"><button type="button" id="userPhone" class="btn btn-primary contact-info userPhone return_handle_button" other-id="{{$ad->id}}" title-info="{{$ad->user->id}}"><span class="glyphicon glyphicon-earphone"></span></button></div>
                            @endif
                            <div class="contact-div"><button data-id="{{ adUrl($ad->id, $id) }}" type="button" class="sendAdMail btn btn-primary contact-info"><span class="glyphicon glyphicon-envelope"></span></button></div>
                        </div>
                        @endif
                        <div class="contact-div div-type-membre-roomate" style="top: 50px;"><p>
                                @if(isset($ad->user))
                                    @if(isUserSubscribed($ad->user->id))<a href="javascript:" class="membre_premium">{{ __('searchlisting.premium') }}</a>@else<a href="javascript:" class="membre_basique">{{ __('searchlisting.basique') }}</a>@endif</p></div>
                                 @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>
@if($ads){{$ads->links('vendor.pagination.new-default')}}@endif
<style>
    @media only screen and (min-width: 765px)
    {
        .grid-view-time
        {
            height: 550px;
        }
        .grid-view-time .grillage
      {
        flex-grow:1;
        display: inline-block;
        padding: 20px;margin-left: 15px;
        padding-right:15px;
        vertical-align: middle;
      }
    }

</style>

