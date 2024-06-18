@extends( ($layout == 'inner') ? 'layouts.appinner' : 'layouts.app' )

<!-- fb pixel view content code -->
@push('scripts')
    <script src="{{ asset('js/jquery.flexslider.js') }}"></script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUBhW1coDqA6E5JdXruNEMwfVNY7fhL_4&libraries=places" async defer></script> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/leaflet/1/leaflet.js"></script>
    <script src="{{ asset('js/ad_detail_first_sec.js') }}"></script>
    <script src="{{ asset('js/questions.js') }}"></script>
    <script src="{{ asset('js/save_docs.js') }}"></script>
    <script src="/js/return_handler.js"></script>
    <script src="/js/contact_annonceur.js"></script>
    <script src="/js/slick-img.js"></script>
    <script src="/js/signal_ad.js"></script>
@endpush
<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="/css/percent.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/awesome-bootstrap-checkbox/1.0.1/awesome-bootstrap-checkbox.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link href="/css/compiledstyles/default.css" rel="stylesheet">
    <link href="/css/compiledstyles/account/ad-details.css" rel="stylesheet">
    <link href="/css/compiledstyles/account/ad-details-responsive.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
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
    @endif
</script>

<script type="text/javascript">
    var ad_id = {{$ad->id}};
</script>

<!-- Push a script dynamically from a view -->

@include("common.fileInputMessages")
@section('content')
    <section class="inner-page-wrap">
        <div class="container container-ad-details">
            <div class="row d-flex">
                <div class="container-promo-btn">
                    <div class="upgrade-btn custum-upgrade-btn">
                        {{--<a id="code_promo_header" href="{{getSearchUrl()}}?return=true">
                            <i class="fa fa-arrow-left"></i>
                        </a>--}}

                        <a id="code_promo_header" class="link-back" href="{{getSearchUrl()}}?return=true">
                            <i class="fa fa-arrow-left"></i>
                            <span class="none-displa text-primary">{{ __('addetails.retour_aux') }} </span>
                            <span class="text-primary">{{ __('addetails.result') }}</span>
                        </a>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 ad-details-outer">

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


                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-8">
                            <div class="user-visit-main-hdr annonce-title">
                                <h1>{{$ad->title}}</h1>
                                <div class="ad-location d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img class="location-home" src="/img/annonce/icons8-home-address-96-black.png" alt="location home">
                                        <span class="location underline">{{$ad->address}}</span>
                                    </div>
                                    <span class="duration"> {{translateDuration($ad->updated_at)}}</span>
                                </div>
                            </div>

                            <div class="ad-det-pro-slider white-bx-shadow d-flex flex-wrap @if(sizeOf($ad->ad_files)==0) no-vertical-slide @endif">
                                <div class="ad-det-slider-bx flex-one d-flex align-items-center justify-content-center">

                                    @if(!empty($ad->ad_files) && count($ad->ad_files) > 0 && File::exists(storage_path('/uploads/images_annonces/' . $ad->ad_files[0]->filename)))
                                        <div class="slick-slider" id="slick-slider-room-{{$ad->id}}">
                                            @foreach($ad->ad_files as $key => $img)
                                                <div>
                                                    <img alt="{{$ad->title}}" class="ad-image-slick active-custom-carousel" src="{{'/uploads/images_annonces/' . $img->filename}}" >
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="no-pic-border d-flex justify-content-center align-items-center">
                                            <img class="no_pic_available" src="/images/room_no_pic.png" alt="{{ __('no pic') }}" />
                                        </div>
                                    @endif
                                    @if(count($ad->ad_files) > 1)
                                        <div class="custom-carousel-prev-wrapper d-flex align-items-center">
                                            <div class='custom-carousel-prev d-flex justify-content-center align-items-center' data-id="slick-slider-room-{{$ad->id}}">
                                                <img src="/img/navigation-arrow-left-gray.png"/>
                                            </div>
                                        </div>
                                        <div class="custom-carousel-next-wrapper d-flex align-items-center">
                                            <div class='custom-carousel-next d-flex justify-content-center align-items-center' data-id="slick-slider-room-{{$ad->id}}">
                                                <img src="/img/navigation-arrow-right-gray.png"/>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- ************** -->
                                </div>
                                <div class="ad-det-right-detail d-flex flex-column align-items-center justify-content-between">
                                    <div class="ad-det-right-price d-flex justify-content-center flex-wrap">
                                        @if(!empty($ad->min_rent) && $ad->min_rent != 0)
                                            <h2 class="text-center">{{Conversion_devise($ad->min_rent)}} {{get_current_symbol()}} <span>{{__("addetails.unit_rent_par_month")}}</span></h2>
                                        @else
                                            <h2 class="text-center d-flex align-items-center">{{__('searchlisting.a_negocier')}}</h2>
                                        @endif

                                    </div>

                                    <div class="vertical-slide d-flex justify-content-center @if(sizeOf($ad->ad_files)==0) flex-one @endif">

                                        @if(!empty($ad->ad_files) && count($ad->ad_files) > 0 && File::exists(storage_path('/uploads/images_annonces/' . $ad->ad_files[0]->filename)))
                                            @if(count($ad->ad_files) > 2)
                                                <button data-id="slider-slick-vertical" class="custom-carousel-prev d-flex justify-content-center align-items-center" data-id="slick-slider-vertical">
                                                    <img class="" src="/img/icons/arrow_up_grey.png">
                                                </button>
                                                <button data-id="slider-slick-vertical" class="custom-carousel-next d-flex justify-content-center align-items-center" data-id="slick-slider-vertical">
                                                    <img class="" src="/img/icons/arrow_down_grey.png">
                                                </button>
                                            @endif
                                            <div class="slick-slider-vertical" id="slider-slick-vertical">

                                                @foreach($ad->ad_files as $ad_file)
                                                    <div class="slider-vertical-item">
                                                        <img src="{{'/uploads/images_annonces/' . $ad_file->filename}}" alt="{{$ad_file->user_filename}}" />
                                                    </div>
                                                @endforeach

                                                <!-- <div class="slider-vertical-item">
                                                <img class="" src="/img/bg-payment.jpg">
                                            </div> -->
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex flex-wrap">
                                        @if($ad->scenario_id == 1 || $ad->scenario_id==2)
                                            <div class="snd-interst @if(!isUserallowedToContact($ad->user->id)) grey-button-glyph @endif">
                                                <a href="javascript:void(0);" class="return_handle_button full-width d-flex align-items-center" data-id="{{$ad->id}}" ad_id="{{$ad->id}}" sender_ad_id="{{$searched_id}}" id="send_request_to_visit">
                                                    <img src="/img/annonce/calendar.png">
                                                    <span class="flex-one"><?php echo __('addetails.send_request_to_visit'); ?></span>
                                                </a>
                                            </div>
                                        @endif
                                        <div class="snd-interst">
                                            <a href="javascript:void(0);" ad_id="{{base64_encode($ad->id)}}" ad_search_id="{{base64_encode($searched_id)}}" class="return_handle_button d-flex align-items-center" id="add_to_favorites">
                                                @if(in_array($ad->id, $favourites))
                                                    <img src="/img/full-icon-star.png"> <span class="flex-one"><?php echo __('addetails.remove_favourite'); ?></span>
                                                @else
                                                    <img src="/img/empty-icon-star.png"> <span class="flex-one"><?php echo __('addetails.add_favourite'); ?></span>
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ad-det-bottom-detials m-t-2">
                                <div class="heading-underline">
                                    <h6><img src="/img/icons/comment_info.png">{{ __('addetails.details') }}</h6>
                                </div>
                                <div class="ad-det-bottom-detials-in white-bx-shadow">
                                    <div class="ad-bottom-detials-bx">
                                        <div class="icon-hdd d-flex flex-wrap">
                                            <div class="ad-bottom-detials-tittle d-flex">
                                                <div class="icon-left-hdd">
                                                    <i class="fa fa-home" aria-hidden="true"></i>
                                                </div>
                                                <strong class="about-sub-tittle">@if($ad->scenario_id == 1){{  __('addetails.about_property') }} @else {{  __('addetails.about_room') }} @endif</strong>
                                            </div>
                                            <div class="ad-detials-sumary d-flex flex-wrap">
                                                <div class="colored-info is-furnished d-flex align-items-center">@if($ad->ad_details->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif</div>
                                                @if(isDisponible($ad->available_date))
                                                    <div class="colored-info is-available d-flex align-items-center">{{ __('addetails.dispo') }}</div>
                                                @endif
                                                @if(!empty($ad->ad_details) && !empty($ad->ad_details->bedrooms))
                                                    <div class="colored-info total-bedroom d-flex align-items-center">
                                                        <img src="/img/annonce/icons8-insomnia-filled-100.png"> &nbsp;{{$ad->ad_details->bedrooms}}
                                                    </div>
                                                @endif
                                                @if(!empty($ad->ad_details) && !empty($ad->ad_details->bathrooms))
                                                    <div class="colored-info total-bathroom d-flex align-items-center">
                                                        <img src="/img/annonce/icons8-bath-100.png">&nbsp;{{$ad->ad_details->bathrooms}}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 ad-db-abt-pro">
                                                <ul class="list-with-dot">

                                                    @if(!empty(getPropertyTypeById($ad->ad_details->property_type_id)))
                                                        <li><strong>
                                                                {{traduct_info_bdd(getPropertyTypeById($ad->ad_details->property_type_id))}}
                                                            </strong></li>
                                                    @endif

                                                    @if(!empty($ad) && !empty($ad->ad_details->min_surface_area))
                                                        <li><strong>{{$ad->ad_details->min_surface_area}}&nbsp;</strong>m<sup>2</sup></li>
                                                    @endif
                                                    @if($ad->ad_details->kitchen_separated == 1)
                                                        <li><strong>{{ __('addetails.cuisine') }}</strong></li>
                                                    @endif
                                                    @if(!empty($ad->ad_details) && !empty($ad->ad_details->bedrooms))
                                                        <li class="underline">
                                                            {{ __('addetails.chambre_couche') }}
                                                            <div class="colored-info total-bedroom d-flex align-items-center justify-content-center">
                                                                <img src="/img/annonce/icons8-insomnia-filled-100.png">&nbsp;  {{$ad->ad_details->bedrooms}}
                                                            </div>
                                                        </li>
                                                    @endif
                                                    @if(!empty($ad->ad_details) && !empty($ad->ad_details->bathrooms))
                                                        <li class="underline">
                                                            {{ __('addetails.salles_bain') }}
                                                            <div class="colored-info total-bathroom d-flex align-items-center justify-content-center">
                                                                <img src="/img/annonce/icons8-bath-100.png">&nbsp; {{$ad->ad_details->bathrooms}}
                                                            </div>
                                                        </li>
                                                    @endif
                                                    @if(!empty($ad->ad_details) && !empty($ad->ad_details->partial_bathrooms))
                                                        <li class="underline">
                                                            {{ __('addetails.salles_bain_partielle') }}
                                                            <div class="colored-info total-partial-bathroom d-flex align-items-center justify-content-center">
                                                                <img src="/img/annonce/icons8-shower-filled-100.png"> {{$ad->ad_details->partial_bathrooms}}
                                                            </div>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 ad-db-abt-pro">
                                                <ul class="list-with-dot">
                                                    <li>
                                                        <div class="colored-info is-furnished">@if($ad->ad_details->furnished == 0) {{__("searchlisting.furnished")}} @else  {{__("searchlisting.not_furnished")}} @endif
                                                        </div>
                                                    </li>
                                                    @if(isDisponible($ad->available_date))
                                                        <li class="d-flex align-items-center">
                                                            <span class="underline">{{ __('addetails.disponibilite') }}</span>
                                                            <div class="colored-info is-available">{{ __('addetails.dispo') }}</div>
                                                            <span class="gray">{{ __('addetails.depuis') }}&nbsp;{{$ad->available_date}}</span>
                                                        </li>
                                                    @endif

                                                    <li class="">
                                                        <span class="underline">{{ __('addetails.min_durre') }}</span>&nbsp;{{ __('addetails.sejour') }}:<span class="gray">&nbsp;@if(!empty($ad->ad_details->minimum_stay) && $ad->ad_details->minimum_stay>0)&nbsp;{{ __('addetails.jours') }} @else  {{ __('addetails.aucune') }} @endif</span>
                                                    </li>
                                                    @if(!empty($ad->ad_details) && !empty($ad->ad_details->deposit_price))
                                                        <li>
                                                            <span class="underline">{{ __('addetails.prix_depot') }}:</span>&nbsp;<strong>{{get_current_symbol()}}&nbsp;{{Conversion_devise($ad->ad_details->deposit_price)}}</strong>
                                                        </li>
                                                    @endif
                                                    @if(!empty($ad->ad_details) && !empty($ad->ad_details->broker_fees))
                                                        <li>
                                                            <span class="underline">{{ __('addetails.Frais_courtage') }}:</span>&nbsp;<strong>{{get_current_symbol()}}&nbsp;{{Conversion_devise($ad->ad_details->broker_fees)}}</strong>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ad-bottom-detials-bx m-t-20 d-flex flex-wrap">
                                        @if(!empty($ad->ad_details) && !empty($ad->ad_details->no_of_roommates))
                                            <div class="details-item number-living-person d-flex flex-one">
                                                <div class="details-item-tittle">
                                                    <div class="details-item-tittle-content d-flex align-items-center">
                                                        <i class="fa fa-users" aria-hidden="true"></i>
                                                        <h6>{{ __('addetails.person_living_here') }}</h6>
                                                    </div>
                                                </div>
                                                <div class="summary-info flex-one d-flex">
                                                    <div class="colored-info total-living-person d-flex align-items-center">
                                                        <i class="fa fa-users" aria-hidden="true"></i>{{$ad->ad_details->no_of_roommates}}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="details-item ideal-roommate-sumary d-flex flex-wrap flex-one">
                                            <div class="details-item-tittle">
                                                <div class="details-item-tittle-content d-flex align-items-center">
                                                    <img src="/img/annonce/user-ideal.png">
                                                    <h6>{{ __('addetails.ideal_roommate') }}</h6>
                                                </div>
                                            </div>
                                            <div class="summary-info d-flex">
                                                <div class="ideal-roommate-info">
                                                    <span class="info-label">{{ __('addetails.gender') }}</span>
                                                    <div class="colored-info preferred-gender d-flex justify-content-center align-items-center">
                                                        @if($ad->ad_details->preferred_gender == 0) {{ __('addetails.man') }}@elseif($ad->ad_details->preferred_gender == 1) {{ __('addetails.woman') }}
                                                        @else
                                                            {{ __("addetails.dont_matter") }}
                                                        @endif
                                                    </div>
                                                </div>

                                                @if(!empty($ad->ad_details->age_range_from) && $ad->ad_details->age_range_from != 0)
                                                    <div class="ideal-roommate-info">
                                                        <span class="info-label">{{ __('addetails.age') }}</span>
                                                        <div class="colored-info age-range d-flex justify-content-center align-items-center">
                                                            @if(!empty($ad->ad_details->age_range_to) && $ad->ad_details->age_range_to != 0 && $ad->ad_details->age_range_to > $ad->ad_details->age_range_from)
                                                                {{ __('addetails.from') }}
                                                            @else
                                                                {{ __('addetails.a_partir_de') }}
                                                            @endif
                                                            {{$ad->ad_details->age_range_from}}

                                                            @if(!empty($ad->ad_details->age_range_to) && $ad->ad_details->age_range_to != 0 && $ad->ad_details->age_range_to > $ad->ad_details->age_range_from)
                                                                {{ __('addetails.to') }} {{$ad->ad_details->age_range_to}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="ideal-roommate-info">
                                                    <span class="info-label">{{ __('addetails.occupation') }}</span>
                                                    <div class="colored-info preferred-occupation d-flex justify-content-center align-items-center">
                                                        @if($ad->ad_details->preferred_occupation == 0) {{ __('addetails.student') }} @elseif($ad->ad_details->preferred_occupation == 1) {{ __('addetails.salaried') }} @else {{ __("addetails.dont_matter") }} @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if(!empty($ad->ad_to_property_features) && count($ad->ad_to_property_features) > 0)
                                            <div class="details-item room-feature flex-one">
                                                <div class="details-item-tittle">
                                                    <div class="details-item-tittle-content d-flex align-items-center">
                                                        <i class="fa fa-th" aria-hidden="true"></i>
                                                        <h6>@if($ad->scenario_id == 1){{ __('addetails.property_features') }} @else{{ __("addetails.room_features") }} @endif </h6>
                                                    </div>
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
                                            <div class="details-item building-amneties flex-one">
                                                <div class="details-item-tittle">
                                                    <div class="details-item-tittle-content d-flex align-items-center">
                                                        <i class="fa fa-sort" aria-hidden="true"></i>
                                                        <h6>{{ __('addetails.building_amneties') }}</h6>
                                                    </div>
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
                                            <div class="details-item property-rules flex-one">
                                                <div class="details-item-tittle">
                                                    <div class="details-item-tittle-content d-flex align-items-center">
                                                        <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                        <h6>{{ __("addetails.property_rules") }}</h6>
                                                    </div>

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
                                            <div class="details-item guarantee-asked flex-one">
                                                <div class="details-item-tittle">
                                                    <div class="details-item-tittle-content d-flex align-items-center">
                                                        <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                                        <h6>{{__("addetails.guarantee_asked")}}</h6>
                                                    </div>

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

                                        @if(!empty($ad->ad_visiting_details) && count($ad->ad_visiting_details) > 0)
                                            <div class="details-item visiting-details flex-one">
                                                <div class="details-item-tittle">
                                                    <div class="details-item-tittle-content d-flex align-items-center">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                        <h6>{{ __("addetails.visiting_details") }}</h6>
                                                    </div>

                                                </div>
                                                @foreach($ad->ad_visiting_details as $ad_visiting_detail)
                                                    <div class="ad-bd-vist-detl p-a-15">
                                                        <div class="ad-bd-vist-bx">
                                                            <div class="ad-bd-vist-hdd">
                                                                <div class="ad-bd-vist-hdd-icon">
                                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                                </div>
                                                                <h6>{{date('jS M. Y', strtotime($ad_visiting_detail->visiting_date))}}</h6>
                                                                <span class="ad-bd-vist-tm"><strong>{{ __("addetails.time") }}:</strong> @if(!empty($ad_visiting_detail->end_time)){{ __("addetails.between") }} {{date("h:i a", strtotime($ad_visiting_detail->start_time))}} {{ __("addetails.to") }} {{date("h:i a", strtotime($ad_visiting_detail->end_time))}}@else{{ __("addetails.from") }} {{date("h:i a", strtotime($ad_visiting_detail->start_time))}} @endif</span>
                                                            </div>
                                                            @if(!empty($ad_visiting_detail->notes))
                                                                <p>{{$ad_visiting_detail->notes}}</p>
                                                            @endif
                                                        </div>
                                                        @if($ad->user_id != Auth::id())
                                                            <div>
                                                                <input type="button" class="send-visit-request-date btn-send-visit" visit_id="{{$ad_visiting_detail->id}}" data-id="{{$ad->id}}" value="{{__('addetails.demander_visite_date')}}">
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="details-item room-description">
                                            <div class="details-item-tittle">
                                                <div class="details-item-tittle-content d-flex align-items-center">
                                                    <img src="/img/icons/black_list.png">
                                                    <h6>@if($ad->scenario_id == 1){{ __('addetails.property_description') }} @else{{ __('addetails.room_description') }} @endif </h6>
                                                </div>
                                            </div>
                                            <div class="ad-bd-desc">
                                                <!--                                        <h6>Title Lorem Ipsume</h6>-->
                                                <p>{!! nl2br($ad->description) !!}</p>
                                                <div class="cache-description"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            @if(count($suggestion_ads)>0)
                                <div class="ad-det-bottom-detials m-t-2">
                                    <div class="ad-det-bottom-detials-in white-bx-shadow pdt-suggestion">
                                        <div class="ad-bottom-detials-bx m-t-20 d-flex flex-wrap">

                                            <div class="details-item room-description">
                                                <div class="details-item-tittle">
                                                    <div class="details-item-tittle-content d-flex align-items-center">
                                                        <img src="/img/icons/black_list.png">
                                                        <h6> {{ __("addetails.similar_ad") }} </h6>
                                                    </div>
                                                </div>
                                                <div class="center-align-bloc d-flex flex-wrap justify-content-between m-t-20">
                                                    @foreach($suggestion_ads as $suggestion)
                                                        @if($suggestion->scenario_id == 1 || $suggestion->scenario_id == 2)
                                                            @if(!empty($suggestion->ad_files) && count($suggestion->ad_files) > 0 && File::exists(storage_path('/uploads/images_annonces/' . $suggestion->ad_files[0]->filename)))
                                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                                    <div class="d-flex justify-content-center align-items-center suggestion_image">
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
                                                                        <p class="address-suggestion-premium">{{ $suggestion->address }}</p>
                                                                    </div>
                                                                </a>
                                                            @else
                                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                                    <div class="no-pic-border d-flex justify-content-center align-items-center suggestion_image_no_pic" style="border-radius: 0px !important;">
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
                                                                        <p class="address-suggestion-premium">{{ $suggestion->address }}</p>
                                                                    </div>
                                                                </a>
                                                            @endif
                                                        @else
                                                            @if(!empty($suggestion->user->user_profiles) && !empty($suggestion->user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $suggestion->user->user_profiles->profile_pic)) && ($suggestion->user->user_profiles->profile_pic != ""))
                                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                                    <div class="d-flex justify-content-center align-items-center suggestion_image_recherche">
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
                                                                        <p class="address-suggestion-premium">{{ $suggestion->address }}</p>
                                                                    </div>
                                                                </a>
                                                            @else
                                                                <a href="{{ adUrl($suggestion->id, null) }}">
                                                                    <div class="no-pic-border d-flex justify-content-center align-items-center suggestion_image_no_pic_recherche">
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
                                                                        <p class="address-suggestion-premium">{{ $suggestion->address }}</p>
                                                                    </div>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif

                            <div class="question-response full-width d-flex flex-wrap justify-content-center m-t-2">
                                <div class="tittle-section full-width d-flex justify-content-center align-items-center">
                                    <img src="/img/annonce/icon-question.png">
                                    <h6><b>{{__("addetails.you_dont_find")}}</b></h6>
                                </div>
                                <div class="ad-det-bottom-detials-in">
                                    <form id="form-add-comment">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                                        <input type="text" name="text" onchange="searchComments()" class="form-control" placeholder="Saisir votre question" id="form-add-comment-text">
                                        <div class="search-or-add d-flex justify-content-center">
                                            <input type="button" id="add-question" value="{{__('addetails.post_question')}}">
                                        </div>
                                    </form>
                                    <ul id="comments-zone" class=""></ul>
                                </div>
                            </div>
                        </div>
                        <div class="right-side-item col-xs-12 col-sm-12 col-md-4">
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
                                    <a href="{{ url('/demandes/visite/' . $ad->url_slug .'-'.$ad->id) }}">
                                        <h6>{{ __('addetails.list_request_visit') }}</h6>
                                        <i class="fa fa-align-left" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="line-with-icon">
                                    <a href="{{ url('/candidatures-annonce/en-attente/' . $ad->url_slug .'-'.$ad->id) }}">
                                        <h6>{{ __('addetails.list_application') }}</h6>
                                        <i class="fa fa-align-left" aria-hidden="true"></i>
                                    </a>
                                </div>
                                @if(!empty($user_id))
                                    <div class="white-bx-shadow text-center ad-dt-right-contact next-white-bx">
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
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">

                                            @if(!empty($ad->user->user_profiles) && !empty($ad->user->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)) && ($ad->user->user_profiles->profile_pic != ""))
                                                <a class="d-flex" target="_blank" href="{{userUrl($ad->user_id)}}">
                                                    <img class="user-profile-search contact-info object-fit-cover" class="pic_available" src="{{URL::asset('uploads/profile_pics/' . $ad->user->user_profiles->profile_pic)}}" alt="{{$ad->user->user_profiles->profile_pic}}" width="40" height="40">
                                                </a>
                                            @else
                                                <a target="_blank" href="{{userUrl($ad->user_id)}}">
                                                    <img class="user-profile-search contact-info object-fit-cover" src="{{URL::asset('/images/profile_avatar.jpeg')}}" alt="avatar" width="40" height="40">
                                                </a>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="content-floue text-left">
                                                @if($ad->user->is_community == 1)
                                                    {{getWord($ad->user->first_name)}}
                                                @else
                                                    {{getFirstWord($ad->user->first_name)}}
                                                @endif

                                            </h6>
                                            @if($ad_premium == 'yes')
                                                <span class="member-type premium">{{ __('addetails.premium_member') }}</span>
                                            @else
                                                <span class="member-type basic">{{ __('addetails.basic_member') }}</span>
                                            @endif
                                            @if($ad->is_logo_urgent && !isOldDate($ad->date_logo_urgent))
                                                <h6>
                                                    <a href="javascript:" class="link-logo-urgent">
                                                        <span class="glyphicon glyphicon-star"></span>
                                                        {{__('searchlisting.urgent')}}
                                                    </a>
                                                </h6>
                                            @endif

                                        </div>
                                    </div>
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

                                    <div class="porject-btn-1 @if(!isUserallowedToContact($ad->user->id)) grey-button @endif">
                                        <a  @if(isUserSubscribed() && !$ad->user->email) href="{{fbContact($ad->user_id)}}" @else href="javascript:void(0);" id="send_message" @endif class="return_handle_button full-width d-flex align-items-center" actionName="message" data-id="{{$ad->id}}">
                                            <img class="" width="30" height="30" src="/img/icons/blue_icon_contact.png">
                                            <!-- <i class="fa comment-alt-lines"></i> -->
                                            <span class="flex-one d-flex justify-content-center">{{ __('addetails.contact_maj') }}</span>
                                        </a>
                                    </div>

                                    <div class="porject-btn-1 @if(!isUserallowedToContact($ad->user->id)) grey-button @endif">
                                        <a href="javascript:void(0);" class="return_handle_button full-width d-flex align-items-center" data-id="{{$ad->id}}" id="apply-to">
                                            <img class="" width="30" height="30" src="/img/icons/blue_paper_plane.png">
                                            <span class="flex-one d-flex justify-content-center">{{ __('addetails.apply') }}</span>
                                        </a>
                                    </div>

                                    @if($ad->scenario_id==2)
                                        <div class="porject-btn-1">
                                            <a class="full-width d-flex align-items-center" href="{{userUrl($ad->user_id)}}">
                                                <i class="glyphicon glyphicon-info-sign about-profile" aria-hidden="true"></i>
                                                <span class="flex-one d-flex justify-content-center">{{ __('addetails.voir_son_profil') }}</span>
                                            </a>
                                        </div>
                                    @endif

                                    @if(Auth::check())
                                        @if(Auth::user()->user_type_id != 1)
                                            <div class="porject-btn-1 comunity-button">
                                                <a href="javascript:void(0);" data-id="{{$ad->id}}" class="contact_annonceur full-width d-flex align-items-center">{{ __('addetails.contact_ad') }}</a>
                                            </div>
                                            <div class="porject-btn-1 comunity-button">
                                                <a href="javascript:void(0);" data-id="{{$ad->id}}" data-type="{{route('deactive-ad-comunity', [$ad->id])}}" class="contact_annonceur full-width d-flex align-items-center">{{ __('addetails.desactivate_ad') }}</a>
                                            </div>
                                            <div class="porject-btn-1 comunity-button">
                                                <a href="javascript:void(0);" data-id="{{$ad->id}}" data-type="{{ url('/modifier-annonce/' . $ad->url_slug .'-'.$ad->id) }}" class="contact_annonceur full-width d-flex align-items-center">{{ __('addetails.update_ad') }}</a>
                                            </div>
                                            <div class="porject-btn-1 comunity-button">
                                                <a href="javascript:void(0);" id="delete-ad" class="full-width d-flex align-items-center">{{ __('addetails.supprimer_ad') }}</a>
                                            </div>
                                        @endif
                                    @endif

                                    @if(isset($ad->user->user_profiles) && isset($ad->user->user_profiles->fb_profile_link) && !empty($ad->user->user_profiles->fb_profile_link))
                                        <div class="porject-btn-1 button-flash-div @if(!isUserallowedToContact($ad->user->id)) grey-button @endif">
                                            <a href="javascript:" class="return_handle_button full-width d-flex align-items-center incrPhone" data-id="{{$ad->user->id}}" data-url="{{ route('showFbContactCount', ['ads_id' => $ad->id]) }}" id="fb-profile-button">
                                                <!-- <img class="img-icon-button" width="30" height="30" src="/img/icons/facebook.png"> -->
                                                <i class="fa fa-facebook-f"></i>
                                                <span class="flex-one d-flex justify-content-center">{{ __('addetails.show_fb_contact') }}</span>
                                            </a>
                                        </div>
                                        <div class="snd-interst" id="show-fb-profile" style="display: none;">
                                            <i class="fa fa-facebook-official" aria-hidden="true"></i> <a id="lien-fb-profile" target="_blank" href=""></a></div>
                                    @endif
                                    @if(!empty($ad->user->user_profiles->mobile_no))
                                        <div data-id="{{$ad->user_id}}" class="return_handle_button porject-btn-1 view-phone-number @if(!isUserallowedToContact($ad->user->id)) grey-button @endif" id="view-phone-number">
                                            <a href="javascript:void(0);" class="full-width d-flex align-items-center incrPhone" data-url="{{ route('showPhoneCount', ['ads_id' => $ad->id]) }}">
                                                <img class="img-icon-button" width="25" height="25" src="/img/icons/icone-phone.png">
                                                <span class="flex-one d-flex justify-content-center">{{ __('addetails.view_phone') }}</span>
                                            </a>
                                        </div>
                                        <div class="snd-interst" style="display: none;"></div>
                                    @endif
                                </div>
                                @if(!empty($user_id))
                                    <div class="white-bx-shadow text-center ad-dt-right-contact next-white-bx">
                                        <h6>{{ __('addetails.signal_bailleur') }}</h6>
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
                                        <div class="porject-btn-1">
                                            <a href="javascript: " id="signal-ad" class="full-width d-flex align-items-center">
                                                <i class="fa fa-exclamation-triangle"></i>
                                                <span class="flex-one d-flex justify-content-center">{{__('addetails.signal')}}</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif



                                @if($ad->messageFlash)
                                    <div style="margin-top : 20px; " class="div-flash-message social_info m-b-1">
                                        <div class="sent-message-flash porject-btn-custom button-flash-div" style="display: block;">
                                            <a href="javascript:" class="return_handle_button"> <img width="60" height="50" class="img-toctoc" src="/img/icons/toctoc.png">{{__('addetails.message_flash_envoye')}}</a>
                                        </div>
                                    </div>
                                @else
                                    <div  style="margin-top : 20px; " class="div-flash-message social_info m-b-1">
                                        <div class="porject-btn-1 button-flash-div">
                                            <a href="javascript:" data-id="{{$ad->user_id}}" class="return_handle_button d-flex align-items-center justify-content-center" data-ad-id="{{$ad->id}}" id="message-flash-button">
                                                <img width="60" height="50" class="img-toctoc" src="/img/annonce/fist-toctoc.png">
                                                <div class="text-toctoc">
                                                    <?php echo __('addetails.message_flash'); ?>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="sent-message-flash porject-btn-custom button-flash-div">
                                            <a href="javascript:" class="return_handle_button" > <img width="60" height="50" class="img-toctoc" src="/img/icons/toctoc.png">{{__('addetails.message_flash_envoye')}}</a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if($ad->scenario_id==2 || $ad->scenario_id==4 || $ad->scenario_id==5)
                                <div style="margin-top:15px;" class="social_info white-bx-shadow m-b-1 carousel" data-ride="carousel">
                                    <div class="ad-bottom-detials-bx">
                                        <div class="icon-hdd">
                                            <h6 class="content-floue about-profile-h6"><a class="view-profile-btn" href="{{userUrl($ad->user_id)}}"><i class="glyphicon glyphicon-info-sign about-profile" aria-hidden="true"></i>{{ __('addetails.voir_profil') }} {{getFirstWord($ad->user->first_name)}}</a></h6>
                                        </div>
                                    </div>
                                    @if(!is_null($userProfiles) && isset($userProfiles->school) || isset($userProfiles->smoker) || isset($userProfiles->profession))
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                @if(!is_null($userProfiles) && isset($userProfiles->school))  <i class="glyphicon glyphicon-education icon-size"></i>
                                                <span>{{$userProfiles->school}}</span>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                @if(!is_null($userProfiles) && isset($userProfiles->profession))  <i class="fi fi-person icon-size"></i>
                                                <span> {{ __('addetails.metier') }} : {{$userProfiles->profession}}</span>
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
                                    <p>{{ __("addetails.your_fb_friends_searching") . ' ' . config('app.name', 'TicTacHouse') }}</p>
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
                            @if($ad->scenario_id!=4 && $ad->scenario_id!=5)
                                <div class="ad-det-nearby">
                                    <div class="ad-det-nearby-map">
                                        <div id="gmap" class="google_map_detail_1"></div>
                                    </div>
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
                            @endif

                            @if(count($ad_proximities) > 0)
                                <div class="ad-det-nearby">
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
                                </div>
                            @endif

                            @if($layout == 'inner' && $user_id != $ad->user->id && (!empty($user_premium) && $user_premium == 'no' ))
                                <div class="rgt-upgrad-bx text-center">
                                    <img class="img-icon-button" width="100" height="100" src="/img/icons/debloquer.png">
                                    <h4>{{ __('addetails.upgrade') }}</h4>
                                    <a href="{{ route('subscription_plan') }}">{{ __('addetails.upgrade_now') }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- ***************************************** -->
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
    @else
        <div id="alertModalContact" class="modal fade alert-modal" role="dialog">
            <div class="modal-dialog">
                <a href="javascript:" class="closeModalBtn" data-dismiss="modal"><span>x</span></a>
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="alrt-modal-body">
                        <h3>{{ __('addetails.become_premium') }}</h3>
                        <p>{{ __('addetails.to_contact') }} "{{getFirstWord($ad->user->first_name)}}" {{ __('addetails.need_become_premium') }}.</p>
                        <div class="porject-btn-1 project-btn-green">
                            <a href="{{ route('subscription_plan') }}">{{__("addetails.upgrade_now_maj")}}</a>
                        </div>
                        <div class="porject-btn-1 project-btn-green" style="margin-left : initial;">
                            <a href="javascript:" data-id="message" class="code_promo_button">{{__("addetails.code_promo")}}</a>
                        </div>
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
                        <h3>{{ __('addetails.become_premium') }}</h3>
                        <p>{{ __('addetails.to_send_visit_request') }} "{{getFirstWord($ad->user->first_name)}}" {{ __('addetails.need_become_premium') }}.</p>
                        <div class="porject-btn-1 project-btn-green"><a href="{{ route('subscription_plan') }}">{{__("addetails.upgrade_now_maj")}}</a></div>
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




    <div id="save_doc-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">{{ __('document.save_docs') }}</h4>
                </div>
                <div class="modal-body">
                    <div><p>{{ __('document.save_docs_message') }}</p></div>
                    <div class="pr-poup-ftr">
                        <div class="submit-btn-2"><input id="noSaveDocs" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('document.no') }}"></div>
                        <div class="submit-btn-2"><input id="yesSaveDocs" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('document.yes') }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="saved_doc-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">{{ __('document.saved_docs_title') }}</h4>
                </div>
                <div class="modal-body" id="modal_body_saved_doc">
                    @if(isset($savedDocuments) && !empty($savedDocuments))
                        @foreach($savedDocuments as $savedDocument)
                            <a href="javascript:void(0);" class="saved_document">
                                <div class="card" style="margin-bottom:10px;width:28rem;display:inline-block;">
                                    <div style="position:relative;">
                                        <span class="badge shoppingBadge shoppingBadge-custom"style="background-color:rgb(51,122,183);position:absolute;right:5px;top:0px;">{{$savedDocument->type}}</span>
                                        <input id="doc-{{$savedDocument->id}}" data-id="{{$savedDocument->id}}" type="checkbox" class="checkDocument" style="display:none;width:20px;height:20px;position:absolute;left:5px;top:0px;"/>
                                        <img class="card-img-top" width="280" height="150;" src="{{URL::asset('uploads/tempfile/' . $savedDocument->name)}}" alt="Card image cap">
                                    </div>
                                    <div class="card-body" style="margin-top:5px;">
                                        <a target="_blank" href="{{URL::asset('uploads/tempfile/' . $savedDocument->name)}}" class="btn btn-primary"> {{ __('document.view_doc') }}</a>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endif
                    <div class="pr-poup-ftr">
                        <div class="submit-btn-2"><a data-dismiss="modal" href="javascript:void(0);">{{ __('document.cancel') }}</a></div>
                        <div class="submit-btn-2"><input data-id="" id="SavedDocSelectButton" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('document.select') }}"></div>
                    </div>
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
                    <form class="super-form" action="/signal/add" data-message="{{__('addetails.signalSuccess')}}" method="post">
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

    <div id="alertModalContactFb" class="modal fade alert-modal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="alrt-modal-body">
                    <h3>{{ __('addetails.become_premium') }}</h3>
                    <p>{{ __('addetails.to_contact') }} "{{getFirstWord($ad->user->first_name)}}" {{ __('addetails.need_become_premium') }}.</p>
                    <div class="porject-btn-1 project-btn-green">
                        <a href="{{ route('subscription_plan') }}">{{__("addetails.upgrade_now_maj")}}</a>
                    </div>
                    <div class="porject-btn-1 project-btn-green" style="margin-left : initial;">
                        <a href="javascript:" data-id="message" class="code_promo_button">{{__("addetails.code_promo")}}</a>
                    </div>
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

            var slickable = true;
            var mainSliderOption = {
                dots: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                prevArrow: false,
                nextArrow: false,
                asNavFor: '#slider-slick-vertical'
            }
            if (window.matchMedia("(min-width: 701px)").matches && parseInt("{{count($ad->ad_files)}}") < 3) {
                slickable=false
                var mainSliderOption = {
                    dots: false,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    prevArrow: false,
                    nextArrow: false
                }
            }

            $('#slick-slider-room-{{$ad->id}}').slick(mainSliderOption)

            console.log(slickable, {{count($ad->ad_files)}}, ' ++++++++++++++++++++++++')
            if (slickable == true) {
                $('#slider-slick-vertical').slick({
                    vertical: true,
                    verticalSwiping: true,
                    dots: false,
                    prevArrow: false,
                    nextArrow: false,
                    centerMode: false,
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    asNavFor: '#slick-slider-room-{{$ad->id}}',
                    responsive: [
                        {
                            breakpoint: 700, //<700
                            settings: {
                                vertical: false,
                                verticalSwiping: false,
                                slidesToShow: 4,
                                slidesToScroll: 4
                            }
                        },
                        {
                            breakpoint: 600, //<700
                            settings: {
                                vertical: false,
                                verticalSwiping: false,
                                slidesToShow: 3,
                                slidesToScroll: 3
                            }
                        },
                        {
                            breakpoint: 500, //<400
                            settings: {
                                vertical: false,
                                verticalSwiping: false,
                                slidesToShow: 2,
                                slidesToScroll: 2
                            }
                        }
                    ]
                });
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

