@extends('layouts.app')

@section('content')
@push("scripts")
<script type="text/javascript" src="/js/popup-forgot-password.js"></script>
@endpush

@push("styles")
    <style>
        #btn-email:hover{
            text-decoration: underline;
            color: blue;
            margin-bottom: 20px;
        }

        #btn-email{
            text-decoration: underline;
            color: blue;
            margin-bottom: 20px;
        }

        .mt-20{
            margin-top: 20px;
        }
        #userAsCat {
            display: none;
        }
    </style>
@endpush



<section class="inner-page-wrap">

    <div style="display: none" id="div-text"
         data-txt_click="{{ __('forgotpassword.txt_click') }}"
         data-span_click="{{ __('forgotpassword.span_click') }}"></div>

    <div id="popup-modal-body-login" class="login-form-x rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
        <div class="popup-social-icons text-center">
            <div class="entete-login">
                <h2 class="bjr">{{ __('reset.reset_pass') }}</h2>
                <h5 class="login-h5"></h5>
            </div>
        </div>

        <form id="verification-email-form" action="{{ route('verificationUser') }}" method="POST" style="display: none;">
            @csrf
            <input type="text" style="display: none;" name="email" id="email" data-url="{{ route('ajax_resend_verification') }}">
        </form>

            <div id="modal-password-modal" class="rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
                @if ($message = Session::get('success'))
                    <div id="data-message-alert" class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                        {{ $message }}
                    </div>
                @endif

                    {{-- @if ($message = Session::get('email_verif'))
                        <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                            {{ $message }}
                        </div>
                    @endif --}}

                @if ($message = Session::get('error'))
                <div id="data-message-alert">
                    <div class="alert alert-danger fade in alert-dismissable" style="margin-top:20px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                        {{ $message }}
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('verificationUser') }}" id="btn-email">{{ __('forgotpassword.txt_click') }} </a><span style="color: black;">{{ __('forgotpassword.span_click') }}</span>
                    </div>
                </div>

                @endif
		      <form id="forgot-password" class="mt-20" method="POST" action="{{ route('password.email') }}">

                    {{ csrf_field() }}
                    <div class="">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email_address" class="control-label">{{ __('reset.email_adresse') }} <sup>*</sup></label>
                            <input required class="form-control" placeholder="{{ __('reset.email_adresse') }}" type="email"
                                   name="email" id="email_address"
                                   @if(!empty($email))
                                    value="{{ $email }}"
                                   @else
                                    value="{{ old('email') }}"
                                   @endif autofocus
                                   data-url="{{ route('ajax_resend_verification') }}" />

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div id="becomeCat" style="max-width: 252px;" class="submit-btn-1 save-nxt-btn btn-reset-psswd">
                            <button style="max-width: 252px;" class="submit-btn-1 save-nxt-btn btn-reset-psswd" id="catTimer" onclick="transformCat()">
                                <input type="submit" id="btn-forgot-password" name="resetpassword" value="{{ __('reset.button_send_link') }}" />
                            </button>
                        </div>
                    </div>
                  <div id="userAsCat">
                      <h6 class="hide_register">{{__('register.hide_send_mail')}}</h6>
                  </div>
                </form>
            </div>
    </div>
</section>
@endsection

@push('scripts')
    <script defer type="text/javascript">
        var transformCat = function() {
            if($('#email').val() != ""){
                document.getElementById("userAsCat").style.display = "block";
                document.getElementById("becomeCat").style.display = "none"
                setTimeout(function() {
                    document.getElementById("userAsCat").style.display = "none";
                    document.getElementById("becomeCat").style.display = "block"
                }, 4000);
            }
        };
    </script>
    <script>
        $(document).ready(function() {
            $('#btn-forgot-password').on('click', function(){
                $('#email').val($('#email_address').val());
                console.log('#btn-forgot-password', $('#email_address').val(), $('#email').val())
            })

            $('#btn-email').on('click', function(event){
                console.log('Redirection', this.href);
                window.location.href(this.href + "?email=" + $('#email_address').val());
            })

        })
    </script>
@endpush

