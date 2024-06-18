@extends( ($layout == 'inner') ? 'layouts.appinner' : 'layouts.app' )

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.min.css') }}" rel="stylesheet">
     <link href="/css/percent.min.css" rel="stylesheet">
    {{-- <link href="/css/compiledstyles/default.css" rel="stylesheet"> --}}
    <link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1650374776/Bailti/css/default_qtvmoo.css" rel="stylesheet">
     @if($desactive)
     <link href="/css/desactive.css" rel="stylesheet">
     @endif

@endpush

<script>
    var current_user_id = {{!empty($user_id) ? $user_id : 0}};
    var ad_user_id = {{$ad->user->id}};
    var appSettings = {};
    appSettings['url_subscription'] = "{{ route('subscription_plan') }}";
    var TXT_COMMENT_SENT = "{{__('Comment sent successfully')}}";
    @if(!empty($ad->latitude) && !empty($ad->longitude))
        appSettings['lat'] = {{$ad->latitude}};
        appSettings['long'] = {{$ad->longitude}};
        appSettings['address'] = '{{$ad->address}}';
    @endif
    appSettings['layout'] = '{{$layout}}';
    appSettings['ad_premium'] = '{{$ad_premium}}';
    @if(!empty($user_premium))
        appSettings['user_premium'] = '{{$user_premium}}';
    @else
        appSettings['user_premium'] = 'no'
    @endif
</script>

<script type="text/javascript">
    var ad_id = {{$ad->id}};
</script>

<!-- Push a script dynamically from a view -->
@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/leaflet/1/leaflet.js"></script>
    <script src="{{ asset('js/ad_detail_third_fourth.min.js') }}"></script>
    <script src="{{ asset('js/questions.min.js') }}"></script>
    <script src="/js/return_handler.min.js"></script>
    <script src="/js/contact_annonceur.min.js"></script>
    <script src="/js/signal_ad.js"></script>
    <script src="/js/sugg.js"></script>
@endpush
@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 ad-details-outer">
                <div class="user-visit-main-hdr annonce-title">
                     <h1>{{$ad->title}}</h1>
                </div>

                @if($desactive)
                <div class="row m-t-2">
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <div class="ad-det-pro-slider white-bx-shadow">


                                <div class="alert alert-danger fade in alert-dismissable" style="margin-top:20px;">
                                    <?php echo __('addetails.active_annonce_fb'); ?>
                                </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- @if($floute)
                $ad->user->budget
                <div class="row m-t-2">
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <div class="ad-det-pro-slider white-bx-shadow">


                                <div class="alert alert-danger fade in alert-dismissable alert-abonner" style="margin-top:20px;">
                                    <?php echo __('addetails.abonnez_vous_alert'); ?>
                                </div>
                        </div>
                    </div>
                </div>
                @endif -->
                @endif

                <div class="container-promo-btn">
                   {{-- <div class="upgrade-btn custum-upgrade-btn">
                        <a id="code_promo_header" href="{{getSearchUrl()}}?return=true">
                            <img class="img-icon-button img-icon-button-header" width="25" height="20"
                                 src="/img/icons/icone-retour.png"> A
                        </a>
                    </div>--}}


                    <a id="code_promo_header" class="link-back" href="{{getSearchUrl()}}?return=true">
                        <i class="fa fa-arrow-left"></i>
                        <span class="none-display">{{ __('addetails.retour_aux') }} </span>{{ __('addetails.result') }}
                    </a>
                </div>

                <div class="row m-t-2">
                    <div class="col-xs-12 col-sm-12 col-md-8">

                        <div class="request-bx-1 ad-details-roommate-bx-1 m-t-2">
                            <div class="row row-roommate">
                                <div class="request-bx-left img-floue">
                                    <figure class="brdr-radi fgr-brdr-radi">
                                        <img @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic)) class="pic_available" src="{{'/uploads/profile_pics/' . $ad->user->user_profiles->profile_pic}}" alt="{{$ad->user->user_profiles->profile_pic}}" @else @if(!empty($ad->user->user_profiles)) @if($ad->user->user_profiles->sex == 0) src="{{URL::asset('images/pdp-homme.jpg')}}" @else  src="{{URL::asset('images/pdp-femme.jpg')}}" @endif @endif alt="{{ __('no pic') }}" @endif/>
                                    </figure>
                                </div>
                                <div class="request-bx-right">
                                    <div class="rb-hdr">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="rb-hdr-left content-floue">
                                                    <h6>
                                                        @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->origin_country_code))
                                                        <a class="iti-flag flag-profil-search {{$ad->user->user_profiles->origin_country_code}}"></a>
                                                        @endif
                                                        <span>@if(!empty($ad->user) && !empty($ad->user->first_name)){{getFirstWord($ad->user->first_name)}} @endif @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->birth_date)){{ ', ' . date_diff(date_create($ad->user->user_profiles->birth_date), date_create('today'))->y}}@endif</span> <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 rs-time text-right">{{ __('Posted') }} {{translateDuration($ad->updated_at)}}.</div>
                                        </div>
                                    </div>
                                    <div class="ad-rmate-mid-detail">
                                        <ul>
                                            <li>
                                                @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->professional_category) && $ad->user->user_profiles->professional_category == 2)
                                                <div class="ad-rmate-mid-icon">
                                                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                                                </div>{{ __('addetails.situation_pro') }} : {{ __('addetails.salaried') }}
                                                @elseif(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->professional_category) && $ad->user->user_profiles->professional_category == 1)
                                                <div class="ad-rmate-mid-icon">
                                                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                                                </div> {{ __('addetails.situation_pro') }} : {{ __('addetails.freelancer') }}
                                                @else
                                                <div class="ad-rmate-mid-icon">
                                                    <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                                </div> {{ __('addetails.situation') }} : {{ __('addetails.student') }}
                                                @endif
                                            </li>
                                            <li>
                                            @if(!empty($ad) && (!is_null($ad->min_rent) || !is_null($ad->max_rent)))

                                                @if(!is_null($ad->min_rent))
                                                <div class="ad-rmate-mid-icon ad-rmate-mid-icon-dolr "></div> {{ __('addetails.budget_from') }} <strong>{{Conversion_devise($ad->min_rent)}} {{get_current_symbol()}} </strong>
                                                @endif

                                                @if(!is_null($ad->min_rent) && !is_null($ad->max_rent))
                                                {{ __('addetails.to') }}
                                                @endif

                                                @if(!is_null($ad->max_rent)){{ __('addetails.budget') }} : <strong>{{Conversion_devise($ad->max_rent)}} {{get_current_symbol()}} </strong>
                                                @endif
                                                {{ __('addetails.per_month') }}

                                            @else
                                            @if($ad->ad_details->budget != 0) {{ __('addetails.budget') }} : <strong>{{Conversion_devise($ad->ad_details->budget)}} {{get_current_symbol()}} </strong>
                                            @else

                                            {{ __('addetails.budget') }} : {{ __('searchlisting.a_negocier') }}
                                             @endif
                                            @endif
                                            </li>
                                            <li><div class="ad-rmate-mid-icon"><span class="look-green"></span></div>{{ __('addetails.date_disponibilite') }} : @if(date_create($ad->available_date) > date_create('today')) {{ __('addetails.looking_from') }} {{date('j M', strtotime($ad->available_date))}} @else {{ __('addetails.looking_now') }} @endif</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->about_me))
                            <div class="rb-ftr-detail">
                                <p>
                                {{ __('addetails.profile_description') }} - {{$ad->user->user_profiles->about_me}}
                                </p>
                            </div>
                            @endif
                        </div>

                        <div class="ad-det-bottom-detials m-t-2">
                            <div class="heading-underline">
                                <h6>{{ __('addetails.details') }}</h6>
                            </div>
                            <div class="ad-det-bottom-detials-in white-bx-shadow">
                                <div class="ad-bottom-detials-bx">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-home" aria-hidden="true"></i>
                                        </div>
                                        <h6>@if($ad->scenario_id == 3){{ __('addetails.looking_for_property') }} @else{{ __('addetails.looking_for_accomodation') }} @endif</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <ul class="list-with-dot">
                                                @if(!is_null($ad->type_location) && !is_null($ad->sous_type_loc)) <li>{{traduct_info_bdd($ad->type_location->label)}}</li> @endif
                                                <li>{{traduct_info_bdd($ad->ad_details->property_type->property_type)}}</li>
                                                @if(!empty($ad->ad_details->min_surface_area) && $ad->ad_details->min_surface_area != 0)
                                                <li>{{$ad->ad_details->min_surface_area}}@if(!empty($ad->ad_details->max_surface_area)) {{ __('addetails.to') }} {{$ad->ad_details->max_surface_area}}@endif {{ __('addetails.square_meters') }}</li>
                                                @endif
                                                @if(!empty($ad->ad_details->bedrooms) && $ad->ad_details->bedrooms != 0)
                                                <li>{{ __('addetails.minimum') }} {{$ad->ad_details->bedrooms}} {{ __('addetails.bedroom') }}</li>
                                                @endif
                                                @if(!empty($ad->ad_details->bathrooms) && $ad->ad_details->bathrooms != 0)
                                                <li>{{ __('addetails.minimum') }} {{$ad->ad_details->bathrooms}} {{ __('addetails.bathroom') }}</li>
                                                @endif
                                                @if(!empty($ad->ad_to_allowed_pets) && count($ad->ad_to_allowed_pets) > 0)
                                                <?php $pets = ''; ?>
                                                @foreach($ad->ad_to_allowed_pets as $key => $allowed_pets)
                                                @if($key == 0)
                                                <?php $pets .= traduct_info_bdd($allowed_pets->allowed_pets->petname); ?>
                                                @else
                                                <?php $pets .= ' ' . __('and') . ' ' . traduct_info_bdd($allowed_pets->allowed_pets->petname); ?>
                                                @endif
                                                @endforeach
                                                <li>{{$pets}} {{ __('addetails.are_allowed') }}</li>
                                                @endif
                                                @if(!empty($ad->ad_details->kitchen_separated))
                                                <li>{{ __('addetails.separated_kitchen') }}</li>
                                                @endif
                                                @if($ad->scenario_id == 3)
                                                <li>{{ __('addetails.accept_property_from') }} @if($ad->ad_details->accept_as == 2){{ __('addetails.agent') }} @elseif($ad->ad_details->accept_as == 1){{ __('addetails.landlord') }} @else {{ __('addetails.roommate') }} @endif</li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 ad-db-abt-pro">
                                            <ul class="list-with-dot">

                                                @if($ad->ad_details->furnished == 0)
                                                <li> {{__("searchlisting.furnished")}} </li>
                                                @elseif($ad->ad_details->furnished == 1)
                                                <li>{{__("searchlisting.not_furnished")}}</li>
                                                @else
                                                <li>{{__("searchlisting.furnished")}} |
                                                {{__("searchlisting.not_furnished")}} </li> @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                @if($ad->scenario_id == 4)
                                @if(!empty($ad->ad_details->no_of_roommates) && $ad->ad_details->no_of_roommates > 0)
                                <div class="ad-bottom-detials-bx m-t-35">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-users" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.person_living_there') }}</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <ul class="list-with-dot">
                                                <li>{{ __('addetails.maximum') }} {{$ad->ad_details->no_of_roommates}} {{ __('addetails.person') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="ad-bottom-detials-bx m-t-35">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.ideal_roommate') }}</h6>
                                    </div>
                                    <div class="row">
                                        <div style="width:100%" class="col-xs-12 col-sm-6 col-md-6">
                                            <ul class="list-with-dot">
                                                <li><b>{{ __('addetails.gender') }}:</b>@if(!is_null($ad->ad_details->preferred_gender) && $ad->ad_details->preferred_gender == 0) {{ __('addetails.man') }}@elseif($ad->ad_details->preferred_gender == 1) {{ __('addetails.woman') }}@else {{ __("addetails.dont_matter") }}@endif
                                                @if(!is_null($ad->ad_details->age_range_from) && $ad->ad_details->age_range_from != 0)
                                                ,
                                                @if(!is_null($ad->ad_details->age_range_to) && $ad->ad_details->age_range_to != 0 && $ad->ad_details->age_range_to > $ad->ad_details->age_range_from)
                                                {{ __('addetails.from') }}
                                                @else
                                                {{ __('addetails.a_partir_de') }}
                                                @endif
                                                {{$ad->ad_details->age_range_from}}

                                                @if(!is_null($ad->ad_details->age_range_to) && $ad->ad_details->age_range_to != 0 && $ad->ad_details->age_range_to > $ad->ad_details->age_range_from)
                                                {{ __('addetails.to') }} {{$ad->ad_details->age_range_to}}
                                                @endif
                                                {{ __('addetails.years_old') }},
                                                @endif <b>
                                                @if(!is_null($ad->ad_details->preferred_occupation))
                                                {{ __('addetails.occupation') }}:</b>@if($ad->ad_details->preferred_occupation == 0) {{ __('addetails.student') }} @elseif($ad->ad_details->preferred_occupation == 1) {{ __('addetails.salaried') }} @else {{ __("addetails.dont_matter") }} @endif
                                                @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($ad->ad_to_property_features) && count($ad->ad_to_property_features) > 0)
                                <div class="ad-bottom-detials-bx m-t-35">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-th" aria-hidden="true"></i>
                                        </div>
                                        <h6>@if($ad->scenario_id == 3){{ __('addetails.property_features') }} @else{{ __('addetails.accomodation_features') }} @endif </h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <ul class="list-with-dot">
                                                @foreach($ad->ad_to_property_features as $ad_to_prop_feature)
                                                <li>{{traduct_info_bdd($ad_to_prop_feature->property_features->feature)}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($ad->ad_to_amenities) && count($ad->ad_to_amenities) > 0)
                                <div class="ad-bottom-detials-bx m-t-35">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-sort" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.building_amneties') }}</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <ul class="list-with-dot">
                                                @foreach($ad->ad_to_amenities as $ad_to_amenity)
                                                <li>{{traduct_info_bdd($ad_to_amenity->amenities->amenity)}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($ad->ad_to_property_rules) && count($ad->ad_to_property_rules) > 0)
                                <div class="ad-bottom-detials-bx m-t-35">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.property_rules') }}</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <ul class="list-with-dot">
                                                @foreach($ad->ad_to_property_rules as $ad_to_prop_rule)
                                                <li>{{traduct_info_bdd($ad_to_prop_rule->property_rules->rules)}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($ad->ad_to_guarantees) && count($ad->ad_to_guarantees) > 0)
                                <div class="ad-bottom-detials-bx m-t-35">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{__("addetails.garantie")}}</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <ul class="list-with-dot">
                                                @foreach($ad->ad_to_guarantees as $ad_to_guarantee)
                                                <li>{{traduct_info_bdd($ad_to_guarantee->guarantees->guarantee)}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="ad-bottom-detials-bx m-t-35">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-file-text" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.ad_description') }}</h6>
                                    </div>
                                    <div class="ad-bd-desc">
                                        <p>
                                            {!! __(nl2br($ad->description),["address"=>$ad->address]) !!}
                                        </p>
                                        <div class="cache-description"></div>
                                    </div>
                                </div>

                                <div class="adb-search-area m-t-35">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-file-text" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.search_area') }}</h6>
                                    </div>
                                    <div class="ad-detail-search-bx">
                                        <h6>{{$ad->address}}</h6>
<!--                                        <div class="ad-detail-search-bx-form">
                                            <form>
                                                <input type="search" class="form-control" placeholder="Search the Locality" />
                                                <input class="ad-detail-serch-btn" type="submit" name="Search" value="Search">
                                            </form>
                                        </div>-->
                                        <div class="ad-det-nearby-map">
                                            <div id="gmap" class="google_map_detail_2"></div>
                                        </div>
                                    </div>
<!--                                    <div class="porject-btn-1 m-t-2"><a href="#">Select this Location</a></div>-->
                                </div>

                            </div>
                        </div>

                        {{-- @if(count($suggestion_ads)>0)
                        <div id="sugg-content" class="ad-det-bottom-detials m-t-2">
                            <div class="heading-underline">
                                <h6> {{ __('addetails.similar_ad') }} </h6>
                            </div>
                            <div class="ad-det-bottom-detials-in white-bx-shadow pdt-suggestion">

                                <div class="ad-bottom-detials-bx">

                                    <div class="center-align-bloc d-flex flex-wrap justify-content-between ad-bd-desc">
                                        @foreach($suggestion_ads as $suggestion)
                                        @if($suggestion->scenario_id == 1 || $suggestion->scenario_id == 2)
                                            @if(!empty($suggestion->ad_files) && count($suggestion->ad_files) > 0 && File::exists(storage_path('/uploads/images_annonces/' . $suggestion->ad_files[0]->filename)))
                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                    <div class="d-flex justify-content-center align-items-center suggestion_image_basic2">
                                                        <img class="no_pic_available" src="{{'/uploads/images_annonces/' . $suggestion->ad_files[0]->filename}}" alt="{{$suggestion->ad_files[0]->filename}}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                    <div class="title_suggestion">
                                                        <p class="top_title">{{ $suggestion->title }} </p>
                                                        <p>
                                                            @if(!empty($ad) && !empty($ad->min_surface_area))
                                                            <span class="label label-success">{{$suggestion->min_surface_area}} {{ __('searchlisting.sq_meter') }}
                                                            </span>
                                                            @endif
                                                            <span class="label label-success">@if($suggestion->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</span>
                                                        </p>
                                                        <p> {{ (isset($suggestion->min_rent)) ? Conversion_devise($suggestion->min_rent).' '.get_current_symbol().'/'.__('addetails.mois') : __('addetails.a_negocier') }} </p>
                                                        <p class="address-suggestion">{{ $suggestion->address }}</p>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                    <div class="no-pic-border d-flex justify-content-center align-items-center suggestion_image_no_pic_basic2" style="border-radius: 0px !important;">
                                                        <img class="no_pic_available" src="/images/room_no_pic.png" alt="no pic">
                                                    </div>
                                                    <div class="title_suggestion">
                                                        <p class="top_title">{{ $suggestion->title }} </p>
                                                        <p>
                                                            @if(!empty($ad) && !empty($ad->min_surface_area))
                                                            <span class="label label-success">{{$suggestion->min_surface_area}} {{ __('searchlisting.sq_meter') }}
                                                            </span>
                                                            @endif
                                                            <span class="label label-success">@if($suggestion->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</span>
                                                        </p>
                                                        <p> {{ (isset($suggestion->min_rent)) ? Conversion_devise($suggestion->min_rent).' '.get_current_symbol().'/'.__('addetails.mois') : __('addetails.a_negocier') }} </p>
                                                        <p class="address-suggestion">{{ $suggestion->address }}</p>
                                                    </div>
                                                </a>
                                            @endif
                                        @else
                                            @if(!empty($suggestion->user->user_profiles) && !empty($suggestion->user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $suggestion->user->user_profiles->profile_pic)) && ($suggestion->user->user_profiles->profile_pic != ""))
                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                    <div class="d-flex justify-content-center align-items-center suggestion_image_basic2_recherche">
                                                        <img class="no_pic_available" src="{{URL::asset('uploads/profile_pics/' . $suggestion->user->user_profiles->profile_pic)}}" alt="{{$suggestion->user->user_profiles->profile_pic}}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                    </div>
                                                    <div class="title_suggestion">
                                                        <p class="top_title">{{ $suggestion->title }} </p>
                                                        <p>
                                                            @if(!empty($ad) && !empty($ad->min_surface_area))
                                                            <span class="label label-success">{{$suggestion->min_surface_area}} {{ __('searchlisting.sq_meter') }}
                                                            </span>
                                                            @endif
                                                            <span class="label label-success">@if($suggestion->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</span>
                                                        </p>
                                                        <p> {{ (isset($suggestion->min_rent)) ? Conversion_devise($suggestion->min_rent).' '.get_current_symbol().'/'.__('addetails.mois') : __('addetails.a_negocier') }} </p>
                                                        <p class="address-suggestion">{{ $suggestion->address }}</p>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                    <div class="no-pic-border d-flex justify-content-center align-items-center suggestion_image_no_pic_basic2_recherche">
                                                        <img class="no_pic_available" src="/images/room_no_pic.png" alt="no pic">
                                                    </div>
                                                    <div class="title_suggestion">
                                                        <p class="top_title">{{ $suggestion->title }} </p>
                                                        <p>
                                                            @if(!empty($ad) && !empty($ad->min_surface_area))
                                                            <span class="label label-success">{{$suggestion->min_surface_area}} {{ __('searchlisting.sq_meter') }}
                                                            </span>
                                                            @endif
                                                            <span class="label label-success">@if($suggestion->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</span>
                                                        </p>
                                                        <p> {{ (isset($suggestion->min_rent)) ? Conversion_devise($suggestion->min_rent).' '.get_current_symbol().'/'.__('addetails.mois') : __('addetails.a_negocier') }} </p>
                                                        <p class="address-suggestion">{{ $suggestion->address }}</p>
                                                    </div>
                                                </a>
                                            @endif
                                        @endif
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                        @endif --}}





                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4">

                        @if($user_id == $ad->user->id)
                        <div  class="social_info white-bx-shadow m-b-1">
                         <div class="profil-percent">
                            @if($ad->is_logo_urgent && !isOldDate($ad->date_logo_urgent))
                            <h6 class="h6-urgent">
                                <a href="javascript:" class="link-logo-urgent">
                                  <span class="glyphicon glyphicon-star"></span>
                                  {{__('searchlisting.urgent')}}
                                </a>
                            </h6>
                            @endif
                            @if(calculAdPercent($ad->id) == 100)
                            <div id="completer-profil">
                                 {{__("addetails.ad_completed")}}
                            </div>
                            @else
                            <div id="completer-profil">
                                {{__("addetails.completer_ad")}}
                            </div>
                            @endif

                            <div class="pourcentage-profil-mesure">
                                <div class="pourcentage-profil" style="width: {{calculAdPercent($ad->id)}}%">

                                </div>
                            </div>
                            <div class="number-precent-profil">
                                {{calculAdPercent($ad->id)}}%
                            </div>
                        </div>
                        </div>
                        <div class="line-with-icon">
                            <a href="{{ url('/modifier-annonce/' . $ad->url_slug .'-'.$ad->id) }}">
                                <h6>{{ __('addetails.edit_ad') }}</h6>
                                <i class="edit-iconright fa fa-pencil" aria-hidden="true"></i>
                            </a>
                        </div>
                        @if(isUserSubscribed($ad->user_id) || isSuperUser())
                        <div class="line-with-icon">
                            <a href="{{ url('/duplicate/' .$ad->id) }}">
                                <h6>{{ __('addetails.duplicate_ads') }}</h6>
                                <i class="edit-iconright fa fa-clone" aria-hidden="true"></i>
                            </a>
                        </div>
                        @endif
                        <div class="line-with-icon">
                            <a href="{{ url('/demandes/recu/' . $ad->url_slug .'-'.$ad->id) }}">
                                <h6>{{ __('addetails.list_request_received') }} </h6>
                                <i class="fa fa-align-left" aria-hidden="true"></i>
                            </a>
                        </div>
                         <div class="line-with-icon">
                            <a href="{{ url('/modifier-profile/') }}">
                                <h6>{{ __('addetails.profil_recherche') }}</h6>
                                <i class="edit-iconright fa fa-user" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div  class="social_info white-bx-shadow m-b-1">
                         <div class="profil-percent">
                            @if(calculProfilPercent() == 100)
                            <div id="completer-profil">
                                 {{__("profile.profil_completed")}}
                            </div>
                            @else
                            <div id="completer-profil">
                                {{__("profile.completer_profile")}}
                            </div>
                            @endif

                            <div class="pourcentage-profil-mesure">
                                <div class="pourcentage-profil" style="width: {{calculProfilPercent()}}%">

                                </div>
                            </div>
                            <div class="number-precent-profil">
                                {{calculProfilPercent()}}%
                            </div>
                        </div>
                        </div>
                        @if($ad->scenario_id != 3 && $ad->scenario_id != 4)
                            <div class="line-with-icon">
                                <a href="{{ url('/candidatures-annonce/en-attente/' . $ad->url_slug .'-'.$ad->id) }}">
                                    <h6>{{ __('addetails.list_application') }}</h6>
                                    <i class="fa fa-align-left" aria-hidden="true"></i>
                                </a>
                            </div>
                        @endif
                        @if(!empty($user_id))
                        <div class="white-bx-shadow text-center ad-dt-right-contact next-white-bx">
                        @if(isUserSubscribed())
                        <div class="signal-info">
                            <span class="
                                span-icon span-signal-info span-signal-info-loue"><i class="fa fa-home fb-nb"></i><span class="icon-x icon-x-info icon-x-loue">{{__('addetails.loue')}}</span>
                                <span class="span-nb-signal">( <span id="badge-nb-signal-loue">{{countSignalAd($ad->id, "ad_loue")}}</span> )</span>
                            </span>
                            <span class="
                                span-icon span-signal-info"><img class="img-icon-button" width="25" height="25" src="/img/icons/icone-phone.png">
                                <span class="icon-x">X</span>
                                <span class="span-nb-signal">( <span id="badge-nb-signal-no-phone">{{countSignalAd($ad->id, "no_phone_respond")}}</span> )</span>
                            </span>
                            <span class="
                                span-icon span-signal-info"><i class="fa fa-facebook-f fb-nb"></i><span class="icon-x icon-x-info">X</span>
                                <span class="span-nb-signal">( <span id="badge-nb-signal-no-fb">{{countSignalAd($ad->id, "no_fb_respond")}} </span>)</span>
                            </span>
                        </div>
                        @if(!isSignaledByUser($ad->id, "no_phone_respond"))
                        <div class="porject-btn-1">
                            <a href="javascript: " id="signal-phone" class="full-width d-flex align-items-center">
                                <span class="
                                span-icon"><img class="img-icon-button" width="25" height="25" src="/img/icons/icone-phone.png">
                                <span class="icon-x">X</span></span>
                                <span class="flex-one d-flex justify-content-center">{{__('addetails.ne_repond_pas_phone')}}</span>

                            </a>
                        </div>
                        @endif
                        @if(!isSignaledByUser($ad->id, "no_fb_respond"))
                        <div class="porject-btn-1">
                            <a href="javascript: " id="signal-fb" class="full-width d-flex align-items-center">
                                <span class="
                                span-icon"><i class="fa fa-facebook-f"></i><span class="icon-x">X</span>
                                </span>
                                <span class="flex-one d-flex justify-content-center">{{__('addetails.ne_repond_pas_fb')}}</span>
                            </a>
                        </div>
                        @endif
                        @if(!isSignaledByUser($ad->id, "ad_loue"))
                        <div class="porject-btn-1">
                            <a href="javascript: " id="btn-ad-loue" class="full-width d-flex align-items-center">
                                <span class="
                                span-icon"><i class="fa fa-home"></i><span class="icon-x">{{__('addetails.loue')}}</span>
                                </span>
                                <span class="flex-one d-flex justify-content-center">{{__('addetails.ad_loue')}}</span>
                            </a>
                        </div>
                        @endif
                        @endif
                        <div class="porject-btn-1">
                            <a href="javascript: " id="signal-ad" class="full-width d-flex align-items-center">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="flex-one d-flex justify-content-center">{{__('addetails.signal')}}</span>
                            </a>
                        </div>
                        </div>
                        @endif
                        @else

                        <div class="white-bx-shadow text-center ad-dt-right-contact">
                            @if($ad_premium == 'yes')
                            <span class="member-type premium">{{ __('addetails.premium_member') }}</span>
                            @else
                            <span class="member-type basic">{{ __('addetails.basic_member') }}</span>
                            @endif
                            @if($ad->is_logo_urgent && !isOldDate($ad->date_logo_urgent))
                            <h6 class="h6-urgent">
                                <a href="javascript:" class="link-logo-urgent">
                                  <span class="glyphicon glyphicon-star"></span>
                                  {{__('searchlisting.urgent')}}
                                </a>
                            </h6>
                            @endif
                            <h6 class="content-floue">{{ __('addetails.contact') }} {{getFirstWord($ad->user->first_name)}}</h6>
                            @if(Auth::check())
                            @if(isUserSubscribed() || isUserSubscribed($ad->user_id))
                            <div class="signal-info">
                                <span class="
                                    span-icon span-signal-info span-signal-info-loue"><i class="fa fa-home fb-nb"></i><span class="icon-x icon-x-info icon-x-loue">{{__('addetails.loue')}}</span>
                                    <span class="span-nb-signal">( <span id="badge-nb-signal-loue">{{countSignalAd($ad->id, "ad_loue")}} </span>)</span>
                                </span>
                                <span class="
                                    span-icon span-signal-info"><img class="img-icon-button" width="25" height="25" src="/img/icons/icone-phone.png">
                                    <span class="icon-x">X</span>
                                    <span class="span-nb-signal">( <span id="badge-nb-signal-no-phone">{{countSignalAd($ad->id, "no_phone_respond")}} </span>)</span>
                                </span>
                                <span class="
                                    span-icon span-signal-info"><i class="fa fa-facebook-f fb-nb"></i><span class="icon-x icon-x-info">X</span>
                                    <span class="span-nb-signal">( <span id="badge-nb-signal-no-fb">{{countSignalAd($ad->id, "no_fb_respond")}} </span>)</span>
                                </span>
                            </div>
                            @endif
                            @if(Auth::user()->user_type_id != 1)
                            <div class="porject-btn-1 comunity-button">
                                <a href="javascript:void(0);" data-id="{{$ad->id}}" class="contact_annonceur">{{ __('addetails.contact_ad') }}</a>
                            </div>
                            <div class="porject-btn-1 comunity-button">
                                <a href="javascript:void(0);" data-id="{{$ad->id}}" data-type="{{route('deactive-ad-comunity', [$ad->id])}}" class="contact_annonceur">{{ __('addetails.desactivate_ad') }}</a>
                            </div>
                            <div class="porject-btn-1 comunity-button">
                                <a href="javascript:void(0);" data-id="{{$ad->id}}" data-type="{{ url('/modifier-annonce/' . $ad->url_slug .'-'.$ad->id) }}" class="contact_annonceur">{{ __('addetails.update_ad') }}</a>
                            </div>
                            <div class="porject-btn-1 comunity-button">
                                <a href="javascript:void(0);" id="delete-ad">{{ __('addetails.supprimer_ad') }}</a>
                            </div>
                            @if(isNoIndexAd($ad->id))
                            <div class="porject-btn-1 comunity-button">
                                <a href="{{route('indexe.ad', [$ad->id])}}">{{ __('addetails.index_ad') }}</a>
                            </div>
                            @else
                            <div class="porject-btn-1 comunity-button">
                                <a href="{{route('desindexe.ad', [$ad->id])}}">{{ __('addetails.unindex_ad') }}</a>
                            </div>
                            @endif
                            @endif
                            @endif


                            <div class="porject-btn-1 @if(!isUserallowedToContact($ad->user->id)) grey-button @endif">
                                <a  @if(isUserSubscribed() && !$ad->user->email) href="{{fbContact($ad->user_id)}}" @else href="javascript:void(0);" id="send_message" @endif class="return_handle_button" data-id="{{$ad->id}}" ><img class="img-icon-button" width="30" height="30" src="/img/icons/contacter.png"><span class="middle-text">{{ __('addetails.contact_maj') }}</span></a>
                            </div>
                             @if(isset($ad->user->user_profiles) && isset($ad->user->user_profiles->fb_profile_link) && !empty($ad->user->user_profiles->fb_profile_link))
                            <div class="porject-btn-1 button-flash-div @if(!isUserallowedToContact($ad->user->id)) grey-button @endif">
                              <a href="javascript:" class="incrPhone" data-id="{{$ad->user->id}}" data-url="{{ route('showFbContactCount', ['ads_id' => $ad->id]) }}" id="fb-profile-button"> <img class="img-icon-button" width="30" height="30" src="/img/icons/facebook.png">{{ __('addetails.show_fb_contact') }}</a>
                            </div>
                            <div class="snd-interst" id="show-fb-profile" style="display: none;"><i class="fa fa-facebook-official" aria-hidden="true"></i> <a id="lien-fb-profile" target="_blank" href=""></a></div>
                            @endif
                            @if(!empty($ad->user->user_profiles->mobile_no))
                            <div data-id="{{$ad->user_id}}" class="return_handle_button porject-btn-1 view-phone-number" id="view-phone-number @if(!isUserallowedToContact($ad->user->id)) grey-button @endif">
                                <a href="javascript:void(0);" class="incrPhone" data-url="{{ route('showPhoneCount', ['ads_id' => $ad->id]) }}">
                                <img class="img-icon-button" width="25" height="25" src="/img/icons/icone-phone.png">
                                {{ __('addetails.view_phone') }}
                                </a>
                            </div>
                            <div class="snd-interst" style="display: none;"></div>
                            @endif
                            @if($ad->scenario_id != 3 && $ad->scenario_id != 4)
                                <div class="porject-btn-1 @if(!isUserallowedToContact($ad->user->id)) grey-button @endif">
                                    <a href="javascript:void(0);" class="return_handle_button" data-id="{{$ad->id}}" id="apply-to"><img class="img-icon-button" width="30" height="30" src="/img/icons/postuler.png"><span class="middle-text">{{ __('addetails.apply') }}</span></a>
                                </div>
                            @endif

                            <div class="snd-interst @if(!isUserallowedToContact($ad->user->id)) grey-button-glyph @endif">
                                <a href="javascript:void(0);" ad_id="{{base64_encode($ad->id)}}" sender_ad_id="{{base64_encode($searched_id)}}" class="return_handle_button" data-id="{{$ad->id}}" id="send_request_to_visit">
                                    <i class="fa fa-calendar" aria-hidden="true"></i> {{ __('addetails.send_request_to_visit') }}
                                </a>
                            </div>
                            <div class="snd-interst">
                                <a href="javascript:void(0);" ad_id="{{base64_encode($ad->id)}}" ad_search_id="{{base64_encode($searched_id)}}" class="return_handle_button" id="add_to_favorites">
                                    @if(in_array($ad->id, $favourites))
                                    <i class="fa fa-heart" aria-hidden="true"></i> {{ __('addetails.remove_favourite') }}
                                    @else
                                    <i class="fa fa-heart-o" aria-hidden="true"></i> {{ __('addetails.add_favourite') }}
                                    @endif
                                </a>
                            </div>
                        </div>
                        @if(!empty($user_id))
                        <div class="white-bx-shadow text-center ad-dt-right-contact next-white-bx">
                        @if(isUserSubscribed() || isUserSubscribed($ad->user_id))
                        <h6>{{ __('addetails.signal_bailleur') }}</h6>
                        @if(!isSignaledByUser($ad->id, "no_phone_respond"))
                        <div class="porject-btn-1">
                            <a href="javascript: " id="signal-phone" class="full-width d-flex align-items-center">
                                <span class="
                                span-icon"><img class="img-icon-button" width="25" height="25" src="/img/icons/icone-phone.png">
                                <span class="icon-x">X</span></span>
                                <span class="flex-one d-flex justify-content-center txt-btn-signal-basic">{{__('addetails.ne_repond_pas_phone')}}</span>

                            </a>
                        </div>
                        @endif
                        @if(!isSignaledByUser($ad->id, "no_fb_respond"))
                        <div class="porject-btn-1">
                            <a href="javascript: " id="signal-fb" class="full-width d-flex align-items-center">
                                <span class="
                                span-icon"><i class="fa fa-facebook-f"></i><span class="icon-x icon-x-basic">X</span>
                                </span>
                                <span class="flex-one d-flex justify-content-center txt-btn-signal-basic">{{__('addetails.ne_repond_pas_fb')}}</span>
                            </a>
                        </div>
                        @endif
                        @endif
                        <div class="porject-btn-1">
                            <a href="javascript: " id="signal-ad" class="full-width d-flex align-items-center">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="flex-one d-flex justify-content-center">{{__('addetails.signal')}}</span>
                            </a>
                        </div>
                        </div>
                        @endif
                        @if($ad->messageFlash)
                        <div  style="margin-top : 20px; " class="div-flash-message social_info white-bx-shadow m-b-1">
                          <div class="porject-btn-custom button-flash-div">
                          <a href="javascript:" class="return_handle_button" > <img width="60" height="50" class="img-toctoc" src="/img/icons/toctoc.png">
                            <div class="text-center">{{__('addetails.message_flash_envoye')}}</div></a>
                          </div>
                        </div>
                        @else
                        <div  style="margin-top : 20px; " class="div-flash-message social_info white-bx-shadow m-b-1">
                          <div class="porject-btn-1 button-flash-div">
                          <a href="javascript:" data-id="{{$ad->user_id}}" class="return_handle_button" data-ad-id="{{$ad->id}}" id="message-flash-button"> <img width="60" height="50" class="img-toctoc" src="/img/icons/toctoc.png"><div class="text-center"><?php echo __('addetails.message_flash') ?></div></a>
                          </div>
                          <div class="sent-message-flash porject-btn-custom button-flash-div">
                          <a href="javascript:" class="return_handle_button" > <img width="60" height="50" class="img-toctoc" src="/img/icons/toctoc.png"><div class="text-center">{{__('addetails.message_flash_envoye')}}</div></a>
                          </div>
                        </div>
                        @endif
                        @endif
                        @if($ad->scenario_id==2 || $ad->scenario_id==4 || $ad->scenario_id==5)
                         <div style="margin-top:15px;" class="social_info white-bx-shadow m-b-1">
							<div class="ad-bottom-detials-bx">
                                    <div class="icon-hdd">
                                        <h6 class="content-floue about-profile-h6"><a class="view-profile-btn" href="{{userUrl($ad->user_id)}}"><i class="glyphicon glyphicon-info-sign about-profile" aria-hidden="true"></i>{{ __('addetails.voir_profil') }} {{getFirstWord($ad->user->first_name)}}</a></h6>
                                    </div>
							</div>
							@if(!is_null($userProfiles) && isset($userProfiles->school) || isset($userProfiles->smoker) || isset($userProfiles->profession))
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    @if(isset($userProfiles) && isset($userProfiles->school))  <i class="glyphicon glyphicon-education icon-size"></i>
                                        <span>{{$userProfiles->school}}</span>
                                    @endif

                                </div>
                            </div>
                             <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    @if(!is_null($userProfiles) && isset($userProfiles->profession))  <i class="fi fi-person icon-size"></i>
                                        <span>{{ __('addetails.metier') }} : {{$userProfiles->profession}}</span>
                                    @endif

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <ul class="list-with-dot">
                                         @if(!is_null($userProfiles) && isset($userProfiles->smoker) && $userProfiles->smoker==0) <li>{{ __('addetails.smoker') }}</li>@endif
                                    </ul>
                                </div>
                            </div>
                            @endif
                            @if(!is_null($userProfiles) && isset($userProfiles->city_name) || isset($userProfiles->country_name))
                            <div style="margin-top:35px;" class="ad-bottom-detials-bx">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-globe" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.origin') }}</h6>
                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <ul class="list-with-dot">
                                         @if(!is_null($userProfiles) && isset($userProfiles->city_name)) <li>{{ __('addetails.from') . " " . $userProfiles->city_name }} / @if(isset($userProfiles->country_name)) {{$userProfiles->country_name}} @endif</li>@endif
                                    </ul>
                                </div>
                            </div>
                            @endif

							@if(!is_null($socialInfo) && count($socialInfo) > 0)
							<div style="margin-top:35px;" class="ad-bottom-detials-bx">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.interests') }}</h6>
                                    </div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<ul class="list-with-dot">
										 @foreach($socialInfo as $interest)
											<li>{{ traduct_info_bdd($interest->interest_name) }}</li>
										 @endforeach
									</ul>
								</div>
							</div>
							@endif
							<!-- @if(!is_null($lifeStyles) && count($lifeStyles) > 0)
							<div style="margin-top:35px;" class="ad-bottom-detials-bx">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-street-view" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.lifestyles') }}</h6>
                                    </div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<ul class="list-with-dot">
										 @foreach($lifeStyles as $lifeStyle)
											<li>{{ traduct_info_bdd($lifeStyle->lifestyle_name) }}</li>
										 @endforeach
									</ul>
								</div>
							</div>
							@endif -->
                            @if(!is_null($typeMusics) && count($typeMusics) > 0)
                            <div style="margin-top:35px;" class="ad-bottom-detials-bx">
                                    <div class="icon-hdd">
                                        <div class="icon-left-hdd">
                                            <i class="fa fa-music" aria-hidden="true"></i>
                                        </div>
                                        <h6>{{ __('addetails.music') }}</h6>
                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <ul class="list-with-dot">
                                         @foreach($typeMusics as $music)
                                            <li>{{ traduct_info_bdd($music->label) }}</li>
                                         @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
						</div>
						@endif
                        @if(!empty($friend_ads_arr) && count($friend_ads_arr) > 0)
                        <div class="fb-frnd-list white-bx-shadow m-b-1">
                            <div class="fb-list-icon">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </div>
                            <p>{{ __("addetails.your_fb_friends_renting") . ' ' . config('app.name', 'TicTacHouse') }}</p>
                            <ul>
                                @foreach($friend_ads_arr as $friends_ads)
                                <li>
                                    <figure>
                                        @if(!empty($friends_ads['user_profile']['profile_pic']) && File::exists(public_path('uploads/profile_pics/' .$friends_ads['user_profile']['profile_pic'])))
                                        <img src="{{URL::asset('uploads/profile_pics/' . $friends_ads['user_profile']['profile_pic'])}}" title="{{$friends_ads['user_profile']['first_name']}}" alt="{{$friends_ads['user_profile']['first_name']}}" />
                                        @else
                                        <img src="{{URL::asset('images/profile_avatar.jpeg')}}" title="{{$friends_ads['user_profile']['first_name']}}" alt="{{$friends_ads['user_profile']['first_name']}}" />
                                        @endif
                                    </figure>
                                    <h5>{{getFirstWord($friends_ads['user_profile']['first_name'])}}@if(!empty($friends_ads['user_profile']['last_name'])){{ ' ' . $friends_ads['user_profile']['last_name'] }} @endif</h5>
                                    @foreach($friends_ads['ads'] as $friend_adds)
                                    <p>
                                        <a href="{{ adUrl($friend_adds['ad_id']) }}">{{$friend_adds['ad_title']}}</a> {{ __('addetails.at') }} <u><i>{{$friend_adds['ad_location']}}</i></u>
                                    </p>
                                    @endforeach
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
						@if(isset($friendsFb) && count($friendsFb)>0)

							<div id="carouselExampleControls" style="margin-top:15px;" class="fb-frnd-list white-bx-shadow m-b-1 carousel" data-ride="carousel">
								<p><a href="#"><i style="font-size : 1.5em;color:rgb(66,103,178);;" class="fa fa-facebook-official"></i><span class="">{{ __("addetails.common_friend") }}</span></a></p>

							  <div class="carousel-inner">
								@foreach($friendsFb as $key => $fbFriend)
									@if($key==0)
										<div class="item active">
									@elseif($key%10==0 && $key != count($friendsFb))
										</div>
										<div class="item">
									@endif
									<div style="display:inline-block;"><img style="border-radius:50%;display:block;margin:auto;" width="40" height="40" src="{{$fbFriend->pdp}}" /><p style="font-size:0.7em;">{{$fbFriend->fb_friend_name}}</p></div>
								@endforeach
								  </div>
								</div>
							</div>
						@endif
						@if(isset($friendsFb) && count($friendsFb)==0)
							<div style="margin-top:15px;" class="fb-frnd-list white-bx-shadow m-b-1" data-ride="carousel">
								<p><a href="#"><i style="font-size : 1.5em;color:rgb(66,103,178);;" class="fa fa-facebook-official"></i><span class="">{{ __("addetails.no_common_friend") }}</span></a></p>
							</div>
						@endif

                        <div class="ad-det-nearby">
                            @if(!empty($ad->nearby_facilities) && count($ad->nearby_facilities) > 0)
                            <div class="ad-det-nearby-list white-bx-shadow">
                                <h5>{{ __('addetails.near_by_facilities') }}</h5>
                                <ul>
                                    @foreach($ad->nearby_facilities as $nearby_facility)
                                    <li>
<!--                                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>-->
                                        {{$nearby_facility->name}}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>

                        <div class="ad-det-nearby">
                            @if(count($ad_proximities) > 0)
                            <div class="ad-det-nearby-list white-bx-shadow">
                                <h5>{{ __('addetails.point_proxim') }}</h5>
                                <ul>
                                    @foreach($ad_proximities as $ad_proximity)
                                    <li>
                                        {{is_null($ad_proximity->proximity)?'':$ad_proximity->proximity->title}}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                        @if($layout == 'inner' && $user_id != $ad->user->id && (!empty($user_premium) && $user_premium == 'no' ))
                        <div class="rgt-upgrad-bx text-center">
                            <img class="img-icon-button" width="100" height="100" src="/img/icons/debloquer.png">
                            <h4>{{ __('addetails.upgrade') }}</h4>
                            <a href="{{ route('subscription_plan') }}">{{ __('addetails.upgrade_now') }}</a>
                        </div>
                        @endif
                        <div id="mobile-sugg-content" class="white-bx-shadow text-center ad-dt-right-contact">

                        </div>
                    </div>
                </div>
                {{-- locataire rate --}}
                @if (isset($ratings))
                    @if (strpos(url()->current(), "/location-logement/") && !$ratings->isEmpty())
                        <style>
                            .l-row {
                                display: flex;
                            }

                            .l-wrap {
                                flex-wrap: wrap;
                            }

                            .l-align-items-center {
                                align-items: center;
                            }

                            .rating-container {
                                margin-top: 1.5rem;
                                padding: 1.5rem;
                            }

                            .ptop {
                                padding: 1.5rem;
                            }
                            .col-rating {
                                width: 85%; 
                            }
                            .col {
                                width: 13%;
                                min-width: 95px;
                            }
                            .l-mb-20 {
                                margin-bottom: 20px;
                                margin-right: 10px;
                                font-weight: 900;
                            }
                            div.col-rating > div.progress:last-child, div.col-rating-w > div.per:last-child {
                                margin-bottom: 0px;

                            }
                            .bg-success{
                                background: #ffe500;
                            }
                            .l-color {
                                color: #ffe500;
                            }
                            @media only screen and (max-width: 720px)  {
                                .col-rating, .col {
                                    width: 95%; 
                                }

                                .l-justify-content-center {
                                    justify-content: center;
                                }
                            }

                            @media only screen and (max-width: 410px)  {
                                .col-rating, .col {
                                    width: 90%; 
                                }
                            }

                        </style>
                        <div class="l-row l-wrap l-align-items-center l-justify-content-center rating-container col-xs-12 col-sm-12 col-md-12 bg-white">
                            <div class="col-rating-w">
                                <div class="l-mb-20 per">
                                    5
                                </div>
                                <div class="l-mb-20 per">
                                    4
                                </div>
                                <div class="l-mb-20 per">
                                    3
                                </div>
                                <div class="l-mb-20 per">
                                    2
                                </div>
                                <div class="l-mb-20 per">
                                    1
                                </div>
                            </div>
                            <div class="col-rating">
                                @foreach ($count_per_rate as $key => $item)
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" aria-label="Success example" style="width: {{$item}}%" aria-valuenow="{{$item}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col">
                                <div style="text-align: center;">
                                    <h1>{{ $average / 5 }}</h1>
                                </div>
                                <div style="text-align: center;">
                                    <i class="fa fa-star l-color" aria-hidden="true" style="font-size: 5rem;"></i>
                                </div>
                                <div style="text-align: center; margin-top: 10px;">
                                    <h6 style="color: #757577;">{{$total}} notes</h6>
                                </div>
                            </div>
                        </div>
                        @if (!$ratings_comments->isEmpty())
                            @foreach ($ratings_comments as $ratings_comment)
                                <div class="l-row bg-white ptop" style="border-bottom: solid 1px #e9e2e2;">
                                    <div class="w-avatar">
                                        <img src="{{ URL::asset('images/profile_avatar.jpeg') }}" width="50" style="border-radius: 50%;">
                                    </div>
                                    <div class="w-review" style="padding-left: 20px;">
                                        <div>
                                            @for ($i = 0; $i < $ratings_comment->Note; $i++)
                                            <i class="fa fa-star l-color" aria-hidden="true" style="font-size: 2rem;"></i>
                                            @endfor
                                        </div>
                                        <div>
                                            {{ $ratings_comment->Avis }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endif
                @endif

                <div class="col-xs-12 col-sm-12 col-md-12">
                    @if(count($suggestion_ads)>0)
                        <div id="sugg-content" class="ad-det-bottom-detials m-t-2">
                            <div class="heading-underline">
                                <h6> {{ __('addetails.similar_ad') }} </h6>
                            </div>
                            <div class="ad-det-bottom-detials-in white-bx-shadow pdt-suggestion">

                                <div class="ad-bottom-detials-bx">

                                    <div class="center-align-bloc d-flex flex-wrap justify-content-between ad-bd-desc">
                                        @foreach($suggestion_ads as $suggestion)
                                        @if($suggestion->scenario_id == 1 || $suggestion->scenario_id == 2)
                                            @if(!empty($suggestion->ad_files) && count($suggestion->ad_files) > 0 && File::exists(storage_path('/uploads/images_annonces/' . $suggestion->ad_files[0]->filename)))
                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                    <div class="d-flex justify-content-center align-items-center suggestion_image_basic2">
                                                        <img class="no_pic_available" src="{{'/uploads/images_annonces/' . $suggestion->ad_files[0]->filename}}" alt="{{$suggestion->ad_files[0]->filename}}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                    <div class="title_suggestion">
                                                        <p class="top_title">{{ $suggestion->title }} </p>
                                                        <p>
                                                            @if(!empty($ad) && !empty($ad->min_surface_area))
                                                            <span class="label label-success">{{$suggestion->min_surface_area}} {{ __('searchlisting.sq_meter') }}
                                                            </span>
                                                            @endif
                                                            <span class="label label-success">@if($suggestion->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</span>
                                                        </p>
                                                        <p> {{ (isset($suggestion->min_rent)) ? Conversion_devise($suggestion->min_rent).' '.get_current_symbol().'/'.__('addetails.mois') : __('addetails.a_negocier') }} </p>
                                                        <p class="address-suggestion">{{ $suggestion->address }}</p>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                    <div class="no-pic-border d-flex justify-content-center align-items-center suggestion_image_no_pic_basic2" style="border-radius: 0px !important;">
                                                        <img class="no_pic_available" src="/images/room_no_pic.png" alt="no pic">
                                                    </div>
                                                    <div class="title_suggestion">
                                                        <p class="top_title">{{ $suggestion->title }} </p>
                                                        <p>
                                                            @if(!empty($ad) && !empty($ad->min_surface_area))
                                                            <span class="label label-success">{{$suggestion->min_surface_area}} {{ __('searchlisting.sq_meter') }}
                                                            </span>
                                                            @endif
                                                            <span class="label label-success">@if($suggestion->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</span>
                                                        </p>
                                                        <p> {{ (isset($suggestion->min_rent)) ? Conversion_devise($suggestion->min_rent).' '.get_current_symbol().'/'.__('addetails.mois') : __('addetails.a_negocier') }} </p>
                                                        <p class="address-suggestion">{{ $suggestion->address }}</p>
                                                    </div>
                                                </a>
                                            @endif
                                        @else
                                            @if(!empty($suggestion->user->user_profiles) && !empty($suggestion->user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $suggestion->user->user_profiles->profile_pic)) && ($suggestion->user->user_profiles->profile_pic != ""))
                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                    <div class="d-flex justify-content-center align-items-center suggestion_image_basic2_recherche">
                                                        <img class="no_pic_available" src="{{URL::asset('uploads/profile_pics/' . $suggestion->user->user_profiles->profile_pic)}}" alt="{{$suggestion->user->user_profiles->profile_pic}}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                    </div>
                                                    <div class="title_suggestion">
                                                        <p class="top_title">{{ $suggestion->title }} </p>
                                                        <p>
                                                            @if(!empty($ad) && !empty($ad->min_surface_area))
                                                            <span class="label label-success">{{$suggestion->min_surface_area}} {{ __('searchlisting.sq_meter') }}
                                                            </span>
                                                            @endif
                                                            <span class="label label-success">@if($suggestion->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</span>
                                                        </p>
                                                        <p> {{ (isset($suggestion->min_rent)) ? Conversion_devise($suggestion->min_rent).' '.get_current_symbol().'/'.__('addetails.mois') : __('addetails.a_negocier') }} </p>
                                                        <p class="address-suggestion">{{ $suggestion->address }}</p>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                    <div class="no-pic-border d-flex justify-content-center align-items-center suggestion_image_no_pic_basic2_recherche">
                                                        <img class="no_pic_available" src="/images/room_no_pic.png" alt="no pic">
                                                    </div>
                                                    <div class="title_suggestion">
                                                        <p class="top_title">{{ $suggestion->title }} </p>
                                                        <p>
                                                            @if(!empty($ad) && !empty($ad->min_surface_area))
                                                            <span class="label label-success">{{$suggestion->min_surface_area}} {{ __('searchlisting.sq_meter') }}
                                                            </span>
                                                            @endif
                                                            <span class="label label-success">@if($suggestion->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</span>
                                                        </p>
                                                        <p> {{ (isset($suggestion->min_rent)) ? Conversion_devise($suggestion->min_rent).' '.get_current_symbol().'/'.__('addetails.mois') : __('addetails.a_negocier') }} </p>
                                                        <p class="address-suggestion">{{ $suggestion->address }}</p>
                                                    </div>
                                                </a>
                                            @endif
                                        @endif
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                        @endif
                </div>
                <div class="ad-det-bottom-detials m-t-2">
                    <div class="heading-underline">
                        <h6>{{ __('addetails.questions') }}</h6>
                    </div>
                    <div class="ad-det-bottom-detials-in white-bx-shadow">
                        <form id="form-add-comment">
                            {{ csrf_field() }}
                            <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                            <input type="text" name="text" onchange="searchComments()" class="form-control" placeholder="{{__('addetails.search_question')}}" id="form-add-comment-text">
                            <div class="search-or-add">
                                <br>
                                {{__("addetails.you_dont_find")}} <input type="button" id="add-question" value="{{__('addetails.post_question')}}">
                            </div>
                        </form>
                        @if(Auth::check() && Auth::id() == $ad->user->id)  @endif
                        <ul id="comments-zone" class=""></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="information-modal-flash" class="modal ">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-body" >
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 style="font-size : 1.2em;" id="modal-information-text" class="modal-title text-center"> {{__('addetails.messageFlashSuccess', ["user" => getFirstWord($ad->user->first_name)])}} </h5>
            </div>
        </div>
    </div>
</div>

@if($layout == 'outer')
<div id="alertModalContact" class="modal fade alert-modal" role="dialog">
    <div class="modal-dialog">
        <a href="javascript:" class="closeModalBtn" data-dismiss="modal"><span>x</span></a>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="alrt-modal-body">
                <h3>{{ __('addetails.post_ad_first') }}</h3>
                <p>{{ __('addetails.to_contact') }} "{{getFirstWord($ad->user->first_name)}}" {{ __('addetails.need_post_ad_first') }}.</p>
                <div class="porject-btn-1"><a href="/creer-compte/etape/1">{{__("addetails.post_your_ad")}}</a></div>
            </div>
        </div>
    </div>
</div>
<div id="alertModalRequestToVisit" class="modal fade alert-modal" role="dialog">
    <div class="modal-dialog">
        <a href="javascript:" class="closeModalBtn" data-dismiss="modal"><span>x</span></a>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="alrt-modal-body">
                <h3>{{ __('addetails.post_ad_first') }}</h3>
                <p>{{ __('addetails.to_send_visit_request') }} "{{getFirstWord($ad->user->first_name)}}" {{ __('addetails.need_post_ad_first') }}.</p>
                <div class="porject-btn-1"><a href="/creer-compte/etape/1">{{__("addetails.post_your_ad")}}</a></div>
            </div>
        </div>
    </div>
</div>
<div id="alertModalAddToFav" class="modal fade alert-modal" role="dialog">
    <div class="modal-dialog">
        <a href="javascript:" class="closeModalBtn" data-dismiss="modal"><span>x</span></a>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="alrt-modal-body">
                <h3>{{ __('addetails.post_ad_first') }}</h3>
                <p>{{ __('addetails.need_post_ad_first') }}.</p>
                <div class="porject-btn-1"><a href="/creer-compte/etape/1">{{__("addetails.post_your_ad")}}</a></div>
            </div>
        </div>
    </div>
</div>
@endif



<div id="comment-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name">{{ __('addetails.question_title') }}</h4>
            </div>
            <div class="modal-body">
                <div id="comment-content-to-view"></div>
            </div>
        </div>
    </div>
</div>


<div id="comment-view-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name">{{ __('addetails.edit_question') }}</h4>
            </div>
            <div class="modal-body">
                <form class="question-form" action="/comment/edit" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="comment-id" id="comment-view-modal-action">
                    <div class="form-group">
                        <textarea name="text" placeholder="{{__('Comment')}}" class="form-control" id="comment-view-modal-text"></textarea>
                    </div>
                    <div style="height: 80px;">
                        <div class="submit-btn-2" >
                            <input type="submit" value="{{__('addetails.submit')}}" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="response-edit-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name">{{ __('addetails.edit_response') }}</h4>
            </div>
            <div class="modal-body">
                <form class="question-form" action="/my-comment/response/edit" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="action" id="response-edit-modal-action">
                    <input type="hidden" name="id" id="response-edit-modal-id">
                    <div class="form-group">
                        <textarea name="text" placeholder="{{__('addetails.response_label')}}" class="form-control" id="response-edit-modal-text"></textarea>
                    </div>
                    <div style="height: 80px;">
                        <div class="submit-btn-2" >
                            <input type="submit" value="{{__('addetails.submit')}}" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="response-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name">{{ __('addetails.response') }}</h4>
            </div>
            <div class="modal-body">
                <form class="question-form" action="/my-comment/response/add" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="action" id="response-edit-modal-action">
                    <input type="hidden" name="comment_id" id="response-modal-comment-id">
                    <div class="form-group">
                        <textarea name="text" placeholder="{{__('addetails.response_label')}}" class="form-control" id="response-edit-modal-text"></textarea>
                    </div>
                    <div style="height: 80px;">
                        <div class="submit-btn-2" >
                            <input type="submit" value="{{__('addetails.submit')}}" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="signal-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="application-modal-name">{{ __('addetails.signal_ad') }}</h4>
            </div>
            <div class="modal-body">
                <form class="super-form" id="signalForm" data-message="{{__('addetails.signalSuccess')}}" action="/signal/add" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="ad_id" id="signal-modal-ad-id" value="{{$ad->id}}">
                    <input type="hidden" name="user_id" id="signal-modal-user-id" value="{{!empty($user_id) ? $user_id : 0}}">
                    <ul class="signal-tag-list">
                        @foreach($signal_tags as $tag)
                            <li>
                                <input id="signal-tag-{{ $tag->id }}" class="select-tag alone-selected-tag" type="checkbox" value="{{ $tag->id }}" name="tags[]" style="display: none">
                                <label for="signal-tag-{{ $tag->id }}">
                                    <span class="select-tag-name">{{ __($tag->name) }}</span>
                                </label>
                            </li>
                        @endforeach
                        <li>
                            <input class="select-tag" type="checkbox" id="signal-tag--1" value="-1" style="display: none">
                            <label for="signal-tag--1">
                                <span class="select-tag-name" id="select-other">{{ __('addetails.other') }}</span>
                            </label>
                        </li>
                    </ul>
                    <div class="form-group">
                        <textarea name="comment" placeholder="{{__('addetails.comment')}}" class="form-control" id="signal-modal-comment" style="display: none"></textarea>
                    </div>
                    <div style="height: 80px;">
                        <div class="submit-btn-2" id="submit-signal" style="display: none">
                            <input type="submit" value="{{__('addetails.submit')}}" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@if(isset($isVillePage))
<input type="hidden" id="temp_prenom" value="{{$ad->user->first_name}}">
@endif
<input type="hidden" id="is_connected" @guest value="false" @else value="true" @endguest>
@if(Auth::check())
@if(Auth::user()->user_type_id != 1)
@include('admin.ads.contact_annonceur')
@include('admin.ads.delete_ad')
@endif
@endif

@endsection
@push("scripts")
<script>
        history.replaceState
            ? history.replaceState(null, null, window.location.href.split("#")[0])
            : window.location.hash = "";

</script>
<script type="text/javascript">
    var messages = {'must_auth' : "{{__('addetails.must_auth')}}", 'sent_message' : "{{__('messages.sent_message')}}", 'sent_request' : "{{__('addetails.sent_request')}}", "add_favourite" : "{{__('addetails.add_favourite')}}", "remove_favourite" : "{{__('addetails.remove_favourite')}}", "edit_question" : "{{__('addetails.edit_question')}}", "delete_question" : "{{__('addetails.delete_question')}}", "respond_question" : "{{__('addetails.respond_question')}}"};
    $(document).ready(function(){
        if("{{checkActionPromo()}}" == "message") {
            $('#sendMessageModel').modal("show");
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.incrPhone').on('click', function(){
            var url = $(this).attr('data-url');
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                url: url,
                type: 'get'
            }).done(function(result){
                console.log(result);
            });
        });
    });
</script>
@endpush

