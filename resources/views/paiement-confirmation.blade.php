@extends('layouts.appinner')
<!-- Push a script dynamically from a view -->
@push('styles')
<link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
<link href="/css/subscription.css" rel="stylesheet" type="text/css">

@endpush
@section('content')
<section class="inner-page-wrap section-subscription">
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
                        <div class="alert alert-success alert-confirmation fade in alert-dismissable"><span class="icon-ok">{{__("subscription.email", array("nom_pass" => $packageDetail['packageTitle']))}}</span></div>
                    </div>
                    <div class="row">
                        <div class="detail-paiement"><strong>{{__('subscription.detail_paiement')}} : </strong></div>
                        <div class="detail-paiement">{{__('subscription.nom_plan')}}  : {{$packageDetail['packageTitle']}}</div>
                        <div class="detail-paiement">{{__('subscription.duree_plan')}}  : {{$packageDetail['packageDuration']}} {{traduct_info_bdd($packageDetail['unite'])}}</div>
                        <div class="detail-paiement">{{__('subscription.plan_valide')}} {{__('subscription.from')}}  {{formatDate($packageDetail['packageStartDate'])}} {{__('subscription.to')}} {{formatDate($packageDetail['packageEndDate'])}}</div>
                        <div class="detail-paiement">{{__('subscription.amount_paid')}}  : {{$packageDetail['packageAmount']/100}} {{get_current_symbol()}}</div>
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
