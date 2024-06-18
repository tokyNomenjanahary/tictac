@extends('layouts.appinner')
<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="/css/custom_checkbox.css" rel="stylesheet">
    <link href="/css/custom_radio.css" rel="stylesheet">
@endpush

<script>
    <?php
    echo "var currency_symbol ='$currency_symbol';";
    ?>
</script>

@push('scripts')
    <script src="/js/boost.js"></script>
@endpush
@section('content')
    <section class="inner-page-wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 ad-details-outer">
                    <div class="user-visit-main-hdr">
                        <h4>{{ __('boost.boost_the_ad') }}</h4>
                    </div>
                    <div class="boost-benifits">
                        <div class="row">
                            <div class="white-bg">
                                <div class="dis-tble">
                                    <div class="dis-tble-cell">
                                        <div class="myad-bx-pic">
                                            @if($ad->scenario_id == 1 || $ad->scenario_id == 2)
                                                <figure class="brdr-rect">
                                                    <img @if($ad->ad_files && count($ad->ad_files) > 0) class="pic_available" src="{{URL::asset('uploads/images_annonces/' . $ad->ad_files[0]->filename)}}" alt="{{$ad->ad_files[0]->user_filename}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif/>
                                                </figure>
                                            @elseif($ad->scenario_id == 3 || $ad->scenario_id == 4 || $ad->scenario_id == 5)
                                                <figure class="brdr-radi">
                                                    <img @if(!empty(Auth::user()->user_profiles) && !empty(Auth::user()->user_profiles->profile_pic)) class="pic_available" src="{{URL::asset('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic)}}" alt="{{Auth::user()->user_profiles->profile_pic}}" @else class="no_pic_available" src="{{URL::asset('images/room_no_pic.png')}}" alt="{{ __('no pic') }}" @endif/>
                                                </figure>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="dis-tble-cell">
                                        <h4>{{$ad->title}}</h4>
                                        <h6>@if(!empty($ad->address)){{$ad->address}}@endif - <strong>@if(!empty($ad) && !empty($ad->min_rent)){{$currency_symbol.$ad->min_rent}}@endif</strong>@if(!empty($ad) && !empty($ad->max_rent)) {{ __('to') }} <strong>{{$currency_symbol.$ad->max_rent}}</strong>@endif {{ __('boost.per_month') }}</h6>
                                        <p>@if($ad->scenario_id == 1 || $ad->scenario_id == 2) {{traduct_info_bdd($ad->ad_details->property_type->property_type)}} @else {{ __('seeking_for') . ' - ' . traduct_info_bdd($ad->ad_details->property_type->property_type)}} @endif</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="boost-ad-second">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 boost-ad-price">
                                <div class="row title-upselling">
                                    {{__("boost.ameliorer_annonce")}}
                                </div>
                                <div class="row">
                                    @foreach($upsels as $key => $upsel)
                                        @if(!($upsel->label == "annonce_acceuil" && !isUpsAcceuilDispo($ad->scenario_id)))
                                            <?php $is_label = "is_" . $upsel->label; $date_label = "date_" . $upsel->label  ?>
                                            @if(!($ad->$is_label == 1 && !isOldDate($ad->$date_label)))
                                                <div class="white-bg text-center upselling-div">
                                                    <div class="inside-upsel-div">
                                                        <div class="description-upsel">
                                                            <label class="container-checkbox">
                                                                {{ $upsel->$lang_title }}
                                                                <input type="checkbox" id="check-upsel-{{$upsel->id}}"  value="{{$upsel->id}}" class="check-upsel" name="upselling[]">
                                                                <span class="checkmark"></span>
                                                            </label>

                                                            <!-- <img src="/img/checked.png" class="icone-checked" width="20" height="20"> -->

                                                        </div>
                                                        <div class="description-upsel-custom">
                                                            <?php echo $upsel->$lang_description; ?>
                                                        </div>
                                                        <div id="tarifs-upsel-{{$upsel->id}}" class=" tarifs-upsel">
                                                            @foreach($upsel->tarifs as $key2 => $tarif)
                                                                <div>
                                                                    <label class="container-radio">
                                                                        {{Conversion_devise($tarif->price)}} {{$currency_symbol}} {{__('boost.pendant')}} {{$tarif->duration}} {{traduct_info_bdd($tarif->unit)}}
                                                                        <input class="choose-tarifs tarifs-upsel-{{$upsel->id}}" data-upsel="{{$upsel->id}}" type="radio" data-id="{{Conversion_devise($tarif->price)}}" name="tarifs-{{$upsel->id}}" value="{{$tarif->id}}">
                                                                        <span class="checkmark-radio"></span>
                                                                    </label>

                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="row white-bg boost-button-container">
                            @if(!$isAdmin)
                                <div class="porject-btn-1" id="div-button-payer">
                                    <a id="button-payer" href="javascript:" data-url="{{route('payment', [0]) . '?email=' . Auth::user()->email . '&adId=' . $ad->id . '&EncryptedKey=' . generateKey()}}" class="" ad-id="{{base64_encode($ad->id)}}" boost-ad-amount="">{{ __('boost.pay_now') }} <span id="amount-text"></span></a>
                                </div>
                            @else
                                <div class="porject-btn-1" id="div-button-payer">
                                    <a id="button-boost-admin" href="javascript:" data-url="{{route('payment', [0]) . '?admin=true&email=' . Auth::user()->email . '&adId=' . $ad->id . '&EncryptedKey=' . generateKey()}}" class="" ad-id="{{base64_encode($ad->id)}}" boost-ad-amount="">{{ __('boost.boost') }}</a>
                                </div>
                            @endif
                            <div class="porject-btn-2">
                                <a href="{{$url_return}}" boost-ad-amount="">{{ __('boost.annuler') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="alert-modal" class="modal ">
            <div class="modal-dialog ">
                <div class="modal-content ">
                    <div class="modal-body" >
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h5 id="modal-news-text" class="modal-title text-center">{{ __("boost.choose_plan") }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
