@extends('layouts.app')
@push('styles')
{{-- <link href="/css/choosescen.css" rel="stylesheet"> --}}
<link href="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1649933303/Bailti/css/choosescen_v8unsl.css" rel="stylesheet">
@endpush
@section('content')
<section class="inner-page-wrap section-login">
<div id="popup-modal-body-login" class="register-form-x rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
    <div id="chooseSearchScenarioModal">
        <div class="modal-header">
            <div class="modal-title modal-title-annonce text-center">{{ __('ad_modal.post_ad_titre') }}</div>
        </div>
        <div class="ad-senario-popup">
                <div class="fadeIn home-searching-sec blue-bg text-center">
                    <ul>
                        <li class="ad-type-1">
                            <a  @if ($message = Session::get('type')) data-id="3" class="logement-scenario" @endif id="rent-a-prop-search-loc" rel='nofollow'  href="{{ route('step-address-annonce') }}/louer-une-propriete" data-toggle="tooltip" data-placement="top" title="{{__('ad_modal.rent_property_tooltip')}}"><div class="home-search-option-bx">
                                    <div class="home-search-option-bx-inside">
                                        <div class="search-icon ad_type">
                                            <img class="without-hover" src="/images/search-option-icon-1-new.png" alt="search-option-icon-1.png" />
                                            <img class="with-hover" src="/img/search-option-icon-1-hover.png" alt="search-option-icon-1-hover.png" width="57" height="51" />
                                        </div>
                                        <h3 class="h4-ads">{{ __('ad_modal.ad_rent_property') }}</h3>
                                    </div>
                                </div>
                            </a>
                        </li>
                <li class="ad-type-2"><a  @if ($message = Session::get('type')) data-id="4" class="logement-scenario" @endif id="share-an-accom-search-loc" rel='nofollow'  href="{{ route('step-address-annonce') }}/partager-un-logement" data-toggle="tooltip" data-placement="top" title="{{__('ad_modal.share_tooltip')}}"><div class="home-search-option-bx">
                            <div class="home-search-option-bx-inside">
                                <div class="search-icon ad_type">
                                    <img class="without-hover" src="/images/search-option-icon-2.png" alt="search-option-icon-2.png" width="57" height="51" />
                                    <img class="with-hover" src="/images/search-option-icon-3-hover.png" alt="search-option-icon-3-hover.png" width="57" height="51" />
                                </div>
                                <h3 class="h4-ads">{{ __('ad_modal.ad_share_accomodation') }}</h3>
                            </div>
                        </div>
                    </a>
                </li>
                        @if(!($message = Session::get('type')))
                        <li class="ad-type-3"><a id="seek-rent-a-prop-search-loc" rel='nofollow'  href="{{ route('step-address-annonce') }}/chercher-a-louer-une-propriete" data-toggle="tooltip" data-placement="top" title="{{__('ad_modal.seek_rent_tooltip')}}"><div class="home-search-option-bx">
                                    <div class="home-search-option-bx-inside">
                                        <div class="search-icon ad_type">
                                            <img class="without-hover" src="/images/search-option-icon-3.png" alt="search-option-icon-3.png" width="57" height="51"/>
                                            <img class="with-hover" src="/images/search-option-icon-3-hover.png" alt="search-option-icon-3-hover.png" width="57" height="51"/>
                                        </div>
                                        <h3 class="h4-ads">{{ __('ad_modal.ad_seek_rent_property') }}</h3>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="ad-type-4"><a id="seek-share-an-accom-search-loc" rel='nofollow'  href="{{ route('step-address-annonce') }}/chercher-a-partager-un-logement" data-toggle="tooltip" data-placement="top" title="{{__('ad_modal.seek_share_tooltip')}}"><div class="home-search-option-bx">
                                    <div class="home-search-option-bx-inside">
                                        <div class="search-icon ad_type">
                                            <img class="without-hover" src="/images/search-option-icon-4.png" alt="search-option-icon-4.png" width="57" height="51" />
                                            <img class="with-hover" src="/images/search-option-icon-4-hover.png" alt="search-option-icon-4-hover.png" width="57" height="51" />
                                        </div>
                                        <h3 class="h4-ads">{{ __('ad_modal.ad_seek_share_accomodation') }}</h3>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="ad-type-5"><a id="seek-comp-a-search-loc" rel='nofollow' href="{{ route('step-address-annonce') }}/chercher-ensemble-une-propriete" data-toggle="tooltip" data-placement="top" title="{{__('ad_modal.monter_colocation_tooltip')}}"><div class="home-search-option-bx">
                                    <div class="home-search-option-bx-inside">
                                        <div class="search-icon ad_type">
                                            <img class="without-hover" src="/images/search-option-icon-5.png" alt="earch-option-icon-5.png" width="57" height="51" />
                                            <img class="with-hover" src="/images/search-option-icon-5-hover.png" alt="search-option-icon-5-hover.png" width="57" height="51" />
                                        </div>
                                        <h3 class="h4-ads">{{ __('ad_modal.ad_seek_someone_search_property') }}</h3>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
        </div>
    </div>
</div>
</section>

@endsection