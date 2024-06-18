@extends('layouts.appinner')
<!-- Push a script dynamically from a view -->
@push('styles')
<link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
<link href="/css/subscription.css" rel="stylesheet" type="text/css">
@endpush
@section('content')
<section class="inner-page-wrap">
    <div class="subscription-header">
        <div class="payment__header--filter"></div>
        <div class="page-header-container">
            <h1 class="payment__pgtitle">{{ __('subscription.pgtitle') }}</h1>
            <h2 class="payment__pgsubtitle">{{ __('subscription.pgsubtitle') }}</h2>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 user-visit-main-outer">
                <div class="user-visit-main-hdr">
                    <h4>{{ __('subscription.title') }}</h4>
                </div>
                <!-- <div class="container-promo-btn">
                    <div class="upgrade-btn custum-upgrade-btn"><a id="code_promo_header" href="javascript:"><img class="img-icon-button img-icon-button-header" width="25" height="25" src="/img/icons/code_promo.png">{{__('header.code_promo_button')}}</a></div>
                </div> -->

                <div class="subscription-listing white-bg m-t-2">
                    <div class="img-ticket-reduction">
                        <img src="/img/ticket-reduction-integre.png"/>
                    </div>
                    <div class="row">
                        @if(!empty($packages) && count($packages) > 0)
                        @foreach($packages as $package)
                        <div class="col-xs-12 col-sm-6 col-md-3 button-show-plan" data-href="/payment/{{$package->id}}?email={{Auth::user()->email}}&EncryptedKey={{generateKey()}}" sub-plan-amount="{{$package->amount}}">
                            <div class="planbox @if($package->popular == 1) mobile-popular @endif">
                                @if($package->popular == 1)<div class="popular">{{__("subscription.most_popular")}}</div>@endif
                                <div class="box-title"><h3>{{$package->title}}</h3></div>
                                <div class="plan-duration"><p>{{$package->duration}} <small>{{ traduct__nfo_bdd($package->unite) }}</small></p></div>
                                <div class="planprice"><p>&euro;{{number_format($package->amount,2)}} </p>
                                    
                                    <!--<small>{{ __('subscription.just') }} &euro;{{round($package->amount/($package->duration * 30),2)}} {{ __('subscription.day') }}!</small>-->
                                </div>
                                <div class="plan-features">
                                    <p><?php echo $package->description; ?></p>
                                </div>
                                <span class="angle-subs"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                                <div class="subscription-link" style="">
                                    <a href="/payment/{{$package->id}}?email={{Auth::user()->email}}&EncryptedKey={{generateKey()}}" sub-plan-amount="{{$package->amount}}">{{ __('subscription.select_plan') }}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>

                <div class="go-premium">
                    <div class="heading-one"><h2>{{ __('subscription.why_premium') }}</h2></div>

                    <div class="white-bg premium-listing">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>{{ __('Basic') }}</th>
                                        <th>{{ __('Premium') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $phrases = getPremiumPhrases();?>
                                    @foreach($phrases as $phrase)
                                    <tr>
                                        <td>{{ $phrase->phrase_fr }}</td>
                                        <td>@if($phrase->type_membre == 0 || $phrase->type_membre == 3)<div class="gray-check"><i class="fa fa-check" aria-hidden="true"></i>@else - @endif</div></td>
                                        <td>@if($phrase->type_membre == 1 || $phrase->type_membre == 3)<div class="green-check"><i class="fa fa-check" aria-hidden="true"></i>@else - @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="users-temoignage">
            <div class="temoignage-title">{{__("subscription.title_temoin")}}</div>
            <div class="temoignage temoignage-left">
                <div class="appreciation impression">
                    {{i18n('temoin_parfait')}}
                </div>
                <div class="appreciation text-temoignage">
                    {{i18n('temoin_1_parole')}}
                </div>

                <div class="etoile">
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <span class="nom-temoignage">{{i18n('nom_temoin_1')}}</span>
                </div>
                <div class="temoignage-quote">
                    <i class="fa fa-quote-left"></i>
                </div>
            </div>
             <div class="temoignage">
                <div class="appreciation impression">
                    {{i18n('temoin_2_resume')}}
                </div>
                <div class="appreciation text-temoignage">
                    {{i18n('temoin_2_parole')}}
                </div>

                <div class="etoile">
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <span class="nom-temoignage">{{i18n('temoin_2_nom')}}</span>
                </div>
                <div class="temoignage-quote">
                    <i class="fa fa-quote-left"></i>
                </div>
            </div>
            <div class="temoignage temoignage-left">
                <div class="appreciation impression">
                    {{i18n('temoin_3_resume')}}
                </div>
                <div class="appreciation text-temoignage">
                    {{i18n('temoin_3_parole')}}
                </div>

                <div class="etoile">
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <span class="nom-temoignage">{{i18n('temoin_3_nom')}}</span>
                </div>
                <div class="temoignage-quote">
                    <i class="fa fa-quote-left"></i>
                </div>
            </div>
            <div class="temoignage">
                <div class="appreciation impression">
                    {{i18n('temoin_4_resume')}}
                </div>
                <div class="appreciation text-temoignage">
                    {{i18n('temoin_4_parole')}}
                </div>
                <div class="etoile">
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <i class="fa fa-star " aria-hidden="true"></i>
                    <span class="nom-temoignage">{{i18n('temoin_4_nom')}} </span>
                </div>
                <div class="temoignage-quote">
                    <i class="fa fa-quote-left"></i>
                </div>
            </div>
        </div>
    </div>
    
</section>
@include('common.code_promo')
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $('.button-show-plan').on('click', function(){
            location.href = $(this).attr('data-href');
        });
    });
</script>
@endpush
