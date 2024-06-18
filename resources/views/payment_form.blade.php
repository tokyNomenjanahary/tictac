@extends('layouts.appinner')
@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    {{-- <script src="{{ asset('js/payment.min.js') }}"></script> --}}
    <script src="https://res.cloudinary.com/dl7aa4kjj/raw/upload/v1652871704/Bailti/js/payment.min_cxkcpq.js"></script>
@endpush
@section('content')
    <section class="inner-page-wrap section-center">
        <div class="container">

            <div class="row payment-form">
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger fade in alert-dismissable" style="margin-top:20px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close"
                           title="{{ __('close') }}">×</a>
                        {{ $message }}
                    </div>
                @endif

                <ul class="nav nav-tabs">
                    <li class="active bg-eee" style="margin-right: 2px;">
                        <a data-toggle="tab" href="#login-tab">
                            <i class="fa fa-credit-card"
                            aria-hidden="true"></i> {{ __('subscription.carte_bancaire') }}
                        </a>
                    </li>

                    @if (isStripeCheckout()&& !isset($adId))
                        <li style="cursor: pointer;"><a data-toggle="tab" sub-id="{{ $package->id }}" class="checkout-button" >
                                <i class="fa fa-cc-stripe" aria-hidden="true"></i>
                                {{ __('payment.stripe') }}</a>
                        </li>
                    @else
                        <li style="cursor: pointer;">
                            <a data-toggle="tab"  class="checkout-button-boost bg-eee" >
                            <i class="fa fa-cc-stripe" aria-hidden="true"></i>
                            {{ __('payment.stripe') }}</a>
                        </li>
                    @endif

                    @if(isActivePayPal())

                    <li class="bg-eee"  >
                        <div id="paypal-button" style="padding:2px; margin-top: 4px"></div>
                        {{-- <a data-toggle="tab" style="font-weight: bolder">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14">
                                <path fill="#003087" d="M19.148 1.81C18.037.543 16.028 0 13.458 0H5.999c-.525 0-.973.382-1.055.901L1.84 20.598a.64.64 0 0 0 .633.74h4.605l1.156-7.335-.036.23c.081-.518.527-.9 1.051-.901h2.188c4.299 0 7.664-1.746 8.647-6.797.029-.149.054-.295.076-.437.292-1.867-.002-3.137-1.012-4.288z"/>
                                <path fill="#003087" d="M19.148 1.81C18.037.543 16.028 0 13.458 0H5.999c-.525 0-.973.382-1.055.901L1.84 20.598a.64.64 0 0 0 .633.74h4.605l1.156-7.335-.036.23c.081-.518.527-.9 1.051-.901h2.188c4.299 0 7.664-1.746 8.647-6.797.029-.149.054-.295.076-.437.292-1.867-.002-3.137-1.012-4.288z"/>
                                <path fill="#003087" d="M9.475 6.123a.934.934 0 0 1 .922-.788h5.847c.692 0 1.338.045 1.929.139a8 8 0 0 1 .956.214c.357.1.702.238 1.03.41.293-1.867-.001-3.137-1.011-4.289C18.036.543 16.028 0 13.458 0H5.999c-.525 0-.972.382-1.054.901L1.839 20.597a.64.64 0 0 0 .632.74h4.605l1.156-7.336 1.243-7.878z"/>
                                <path fill="#009CDE" d="M20.16 6.098c-.023.146-.048.292-.076.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.97.383-1.05.901l-1.121 7.105-.319 2.015a.56.56 0 0 0 .554.647h3.882c.459 0 .85-.335.922-.788l.038-.198.732-4.636.046-.256a.934.934 0 0 1 .923-.788h.581c3.76 0 6.704-1.527 7.565-5.946.358-1.846.173-3.388-.777-4.47a3.707 3.707 0 0 0-1.063-.82z"/>
                                <path fill="#012169" d="M19.13 5.688a6.984 6.984 0 0 0-.464-.119 8.636 8.636 0 0 0-.493-.093 12.088 12.088 0 0 0-1.929-.141h-5.847a.931.931 0 0 0-.922.789l-1.243 7.88-.036.229c.08-.518.526-.9 1.05-.901h2.189c4.299 0 7.664-1.746 8.647-6.797.029-.149.054-.294.076-.437-.26-.136-.53-.25-.809-.341a4.027 4.027 0 0 0-.219-.069z"/>
                            </svg>
                            <span style="color:#003087">Pay</span><span style="color: #009CDE">Pal</span>
                        </a> --}}
                    </li>
                    @endif
                </ul>

                <form method="post" id="paypal-form" action="{!! URL::route('paypal') !!}">
                    {{ csrf_field() }}
                    <input type="hidden" name="{{ isset($adId)?'adId':'packageId' }}" value="@if(isset($adId)){{ base64_encode($adId) }}@else{{ base64_encode($package->id) }}@endif" >
                    @if(isset($adId))<input type="hidden" name="ups" id="ups" value="{{ base64_encode($ups) }}"/>@endif
                </form>

                <form method="post" id="paypal-handle" action="{!! URL::route('paypal-return') !!}">
                    {{ csrf_field() }}
                    <input type="hidden" name="orderID" id="orderID" >
                    <input type="hidden" name="order_uid" id="order_uid" >

                </form>

                <div class="tab-content">
                    <div id="login-tab" class="tab-pane fade in active">
                        <div class=" user-visit-main-outer creditly-wrapper">
                            <form method="post" id="payment-form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="col-xs-12 col-md-12 payment-main-div">
                                    <!-- CREDIT CARD FORM STARTS HERE -->
                                    <div class="panel panel-default credit-card-box">

                                        <div class="panel-body">
                                            <div id="payment-error"
                                                 class="alert alert-danger fade in alert-dismissable">
                                            </div>
                                            <form role="form" id="payment-form">
                                                <div class="row">
                                                    <p class="tva-payment">
                                                        @php

                                                        @endphp
                                                        @if (isset($adId))
                                                            <span>{{ __('payment.amount_with_tva', ['amount' => number_format($amount, 2, ',', ''),'tva' => number_format(amountTVA($amount), 2, ',', ''),'current' => $currency]) }}</span>
                                                        @else
                                                            @php
                                                                $moinTva = $package->amount;
                                                            @endphp
                                                            <span>{{ __('payment.amount_with_tva', ['amount' => number_format($moinTva, 2, ',', ''),'tva' => number_format(amountTVA($moinTva), 2, ',', ''),'current' => $currency]) }}</span>
                                                        @endif

                                                    </p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div id="card-element" class="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="nameOnCard">{{ __('payment.name_on_card') }}</label>
                                                            <input id="cardholder-name" type="text"
                                                                   class="form-control billing-address-name"
                                                                   name="nameOnCard"
                                                                   required maxlength="20"
                                                                   placeholder="{{ __('payment.name_on_card') }}"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if (!isset($adId))
                                                    <input type="hidden" name="packageId" id="packageId"
                                                           value="{{ base64_encode($package->id) }}"/>
                                                @else
                                                    <input type="hidden" name="packageId" id="packageId" value="0"/>
                                                    <input type="hidden" name="adId" id="adId"
                                                           value="{{ base64_encode($adId) }}"/>
                                                    <input type="hidden" name="ups" id="ups"
                                                           value="{{ base64_encode($ups) }}"/>
                                                @endif
                                                <input type="hidden" name="intent_secret" id="intent_secret"
                                                       value="{{ $intent->client_secret }}">
                                                <input type="hidden" name="intent_id" id="intent_id"
                                                       value="{{ $intent->id }}">
                                                <input type="hidden" id="apiKey" name="apiKey"
                                                       value='{{ config('customConfig.stripePublicApiKey') }}'>

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <button class="btn btn-success btn-lg btn-block" type="submit"
                                                                data-secret="{{ $intent->client_secret }}"
                                                                id="card-button"
                                                                href="javascript:void(0);">
                                                            {{ __('subscription.pay') }} @if (isset($adId))
                                                                {{ number_format($amount, 2, ',', '') }}
                                                            @else

                                                                {{ number_format($moinTva, 2, ',', '') }}
                                                            @endif    {{$currency}}<span id="showAmount"></span></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <p class="security-payment">
                                            <span><i class="fa fa-lock" aria-hidden="true"></i>
                                                {{ __('payment.secure_payment') }}</span>
                                        </p>
                                        <div class="security-payment message-security">
                                            {{ __('payment.secure_payment_message') }}
                                        </div>
                                        <div class="security-payment image-card">
                                            <div>
                                                <img src="/images/creditcards.png"
                                                     alt="{{ __('payment.image_carte_bancaire') }}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- CREDIT CARD FORM ENDS HERE -->

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>


        </div>
        </div>
    </section>
    <script>
        var messages = {
            "security_code_message": "{{ __('payment.security_code_message') }}",
            "number_message": "{{ __('payment.number_message') }}",
            "expiration_message": decodeHtml("{{ __('payment.expiration_message') }}"),
            "nom_message": "{{ __('payment.nom_message') }}"
        };

        function decodeHtml(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        }
    </script>

<script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency={{ get_data_currency_code() }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            //paypal submite

            // $('#paypal').on('click',function(){
            //     $('#paypal-form').submit()
            // })

            //-----------fin paypal---------------------
            // Create an instance of the Stripe object with your publishable API key
            var stripe = Stripe("{{ config('customConfig.stripePublicApiKey') }}");
            $('.checkout-button').on('click', function () {
                var package_id = $(this).attr('sub-id');
                stripeCheckout(package_id);
            });

            $('.checkout-button-boost').on('click', function () {
                stripeCheckoutBoost();
            });

            ///create-checkout-session-boost

            function stripeCheckout(package_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/create-checkout-session',
                    type: "POST",
                    dataType: "json",
                    data: {
                        "package_id": package_id
                    }
                }).done(function (session) {
                    return stripe.redirectToCheckout({
                        sessionId: session.id
                    });

                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            }



            function stripeCheckoutBoost() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                $.ajax({
                    url: '/create-checkout-session-boost',
                    type: "POST",
                    dataType: "json",
                    data: {
                       adId: $('#adId').val(),
                       ups:  $("#ups").val(),
                       packageId : '0'

                    }
                }).done(function (session) {
                    return stripe.redirectToCheckout({
                        sessionId: session.id
                    });

                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            }
        });


        loadPaypalButton()


            function loadPaypalButton() {
                paypal.Buttons({
                    env: "{{ config('paypal')['settings']['mode'] }}",
                    
                    style: {
                        layout:  'horizontal',
                        color:   'silver',
                        shape:   'rect',
                        label:   'paypal',
                        height :   27,
                    },
                    createOrder: function() {

                       @php 
                        $data=[];
                        $data['_token']=csrf_token();
                        $data['order_uid']="123";
                        if(isset($adId))
                        {
                            $data['adId']=base64_encode($adId);
                            $data['ups']=base64_encode($ups);
                        }
                        else
                        {
                            $data['packageId']=base64_encode($package->id);
                        }

                        echo("var data_=".json_encode($data));
                        @endphp

                        
                        
                        return fetch("{{ url('/payment/paypal') }}", {
                            method: 'post',
                            body: JSON.stringify(data_),
                            headers: {
                                'content-type': 'application/json',
                                'accept': 'application/json'
                            }
                        }).then(function(res) {
                            
                            return Promise.all([res.status, res.json()]);
                            
                        }).then(function([status, data]) {
                            if(status == 200 || status == 201)
                                return data.result.id;
                            else if(status != 201 && data.message)
                                alert(data.message);
                            else
                                alert('Something went wrong');
                        }).catch(function(err) {
                            alert('Something went wrong');
                        });
                    },
                    onApprove: function(data) {
                        // return fetch("{{ url('payment/paypal/return') }}", {
                        //     method: 'post',
                        //     headers: {
                        //         'content-type': 'application/json',
                        //         'accept': 'application/json'
                        //     },
                        //     body: JSON.stringify({
                        //         orderID: data.orderID,
                        //         order_uid: "123",
                        //         _token:"{{ csrf_token() }}"
                        //     })
                        // })

                        $('#orderID').val(data.orderID)
                        $('#order_uid').val("123")

                        $('#paypal-handle').submit()


                    }
                    
                }).render('#paypal-button');
            }
        
    </script>
@endsection


@push('styles')
    <style>
        .bg-eee
        {
            background-color:#eee;
            border-radius: 4px 4px 0 0;
        }
    </style>
@endpush
