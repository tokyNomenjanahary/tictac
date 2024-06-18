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
                    <h4>{{ __('subscription.confirmation') }}</h4>
                </div>

                <div class="subscription-listing white-bg m-t-2">
                    <div class="row">
                        <div class="alert alert-success alert-confirmation fade in alert-dismissable"><span class="icon-ok">{{__("subscription.message_confirm_boost", ['title' => $boostedAds[0]->title])}}</span></div>
                    </div>
                    <div class="row">
                        <div class="detail-paiement"><strong>{{__('subscription.detail_paiement')}} : </strong></div>
                        <?php $totalAmount = $payment->amount/100; ?>
                        <div class="detail-paiement">{{__('subscription.amount_paid')}}  : {{$payment->amount/100}} {{$current_symbol}}</div>
                        <div class="detail-paiement">{{__('payment.amount_with_tva', ['amount' => $totalAmount, 'tva' => amountTVA(reverseAmount($totalAmount)),'current'=> $current_symbol])}}</div>
                        <div class="detail-paiement"><strong>{{__('subscription.detail_boost')}} : </strong></div>
                        @foreach($boostedAds as $boost)
                        <div class="detail-paiement"><strong><span class="span-boost-title">{{$boost->fr_title}}</span></strong></div>
                        <div class="detail-paiement">{{__('subscription.duration')}} : {{$boost->duration}} {{traduct_info_bdd($boost->unit)}}</div>
                        <div class="detail-paiement">{{__('subscription.boost_valide')}} {{__('subscription.from')}}  {{date('d/m/Y')}} {{__('subscription.to')}} {{formatDate($boost->expiry_date)}}</div>
                        <div class="detail-paiement">{{ __('subscription.montant') }}  : {{Conversion_devise($boost->price)}} {{$current_symbol}}</div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="detail-paiement">
                        <?php $link = generatePaymentReturnUrl(); $action = getAction($link);?>
                        @if(!isDashboardLink($link))
                        <a href="{{$link}}" class="btn-acceuil">
                        @if(!empty($action))
                        <?php echo __('subscription.return_' . $action); ?>
                        @else{{__('subscription.return')}}@endif</a>
                        @endif
                        <a href="/" class="btn-acceuil">{{__('subscription.acceuil')}}</a>
                        <a href="{{route('user.dashboard')}}" class="btn-acceuil">{{__('subscription.dashboard')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include("common.premium_modal")
@endsection
