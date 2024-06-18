@extends('layouts.master')


@section('content')
    <form id="registerStep" method="POST" enctype="multipart/form-data" style="display:none;">
        {{ csrf_field() }}
        <div class="form-group">
            <label>{{__('register.mail')}} <sup>*</sup></label>
            <input class="form-control" placeholder="{{__('register.mail_placeholder')}}" type="text" name="email"
                   value="test@gmail.com" id="email_register"/>
        </div>
        @if(!empty($numero))
            <input type="text" class="form-control" name="phone_no" id="number" value="{{ $numero }}"
                   placeholder="(Code) *******">
        @else
            <input type="text" class="form-control" name="phone_no" id="number" value="" placeholder="(Code) *******">
        @endif
    </form>



    <section class="validOTPForm">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            @if(empty($bug))
                                <h7 class="text-center">{{ __('register.envoie_code') }} </h7>
                            @else
                                @if($bug == 1)
                                    <h7 class="text-center2">{{ __('register.signal_save') }} </h7>
                                @endif
                                @if($bug == 2)
                                    <h7 class="text-center2"
                                        style="color:#ff0000;">{{ __('register.save_demande') }} </h7>
                                @endif
                            @endif

                        </div>


                        <div class="card-body">
                            <div class='loader-icon' style="display:none;"><img src='/images/rolling-loader.apng'></div>
                            <form>
                                <div class="form-group">
                                    <label for="phone_no">{{ __('register.numero_phone') }} </label>
                                    @if(!empty($numero))
                                        <input type="text" class="form-control" name="phone_no" id="number"
                                               value="{{ $numero }}" placeholder="(Code) *******">
                                    @else
                                        <input type="text" class="form-control" name="phone_no" id="number" value=""
                                               placeholder="(Code) *******">

                                    @endif

                                    <div class="text-danger" id="error"
                                         style="display:none;">{{ __('backend_messages.number_phone_exist') }}</div>
                                    <div class="text-success" id="success_phone"
                                         style="display:none;">{{ __('register.envoie_code') }}</div>

                                </div>
                                <div id="recaptcha-container"></div>
                                <a href="#" id="getcode"
                                   class="btn btn-dark btn-sm">{{ __('register.renvoyer_code') }} </a>

                                <div class="form-group mt-4">
                                    <input type="text" name="" id="codeToVerify" name="getcode" class="form-control"
                                           placeholder="Entrer le code ici">
                                </div>

                                <a href="#" class="btn btn-dark btn-sm btn-block"
                                   id="verifPhNum">{{ __('register.verifier_code') }} </a>

                                <h5 class="text-center">


                                    {{ __('register.no_code') }} <a href="{{ url('/signaler') }}"
                                                                    class="clique">{{ __('register.click') }}</a>

                                    <div class="mt-2" style="text-decoration: underline">
                                        {{ __('register.change_phone_number') }}
                                    </div>
                                </h5>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        h5.text-center {
            margin-top: 20px;
            font-size: 11px;
        }

        .clique {
            color: #0260c1;
            font-weight: bold;
        }


        .bg-dark {
            background-color: #ffffff !important;
        }

        .btn-dark {
            color: #fff;
            background-color: #2a8bb7;
            border-color: #fff;
        }

        h7.text-center {
            vertical-align: inherit;
            color: #2f2828;
        }

        h7.text-center2 {
            vertical-align: inherit;
            color: #59d07b;
        }

    </style>

@endsection

@push('scripts')

    @php
   $key_original = App\firebase_key::where('id',1)->first()->key;
   $key1 = App\firebase_key::where('id',2)->first()->key;
   $key2 = App\firebase_key::where('id',3)->first()->key;
    @endphp

    <script>
        var env = "{{ env('APP_ENV') }}";
        var key_original ="{{$key_original}}";
        var key1 ="{{$key1}}";
        var key2 ="{{$key2}}";

        var messages = {
            "ivalid": "{{__('register.ivalid')}}",
            "verifie": "{{__('register.verifie_phone')}}",
            "invalid_phone": "{{__('login.invalid_phone')}}",
            "rectify_message": "{{__('backend_messages.rectify_message')}}",
            "error_contact": "{{__('backend_messages.error_contact')}}",
            "email_confirmation": "{{__('backend_messages.email_confirmation')}}"
        };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.0.1/firebase.js"></script>
    <script src="{{ asset('/js/firebase.js') }}"></script>

@endpush





