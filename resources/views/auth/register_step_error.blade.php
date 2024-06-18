@extends('layouts.app')

@section('content')
@push('styles')
	<link href="/css/register.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/sumoselect.min.css">
    <link href="/css/intlTelInput/intlTelInput.min.css" rel="stylesheet">
    <link href="/css/country/countrySelect.css" rel="stylesheet">
@endpush
@push('scripts')
<script src="/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script>
<script src="/js/register.js"></script>
<script src="/js/date-naissance.min.js"></script>
<script src="/js/intlTelInput/intlTelInput.min.js"></script>
<script src="/js/jquery.sumoselect.min.js"></script>
<script src="/js/country/countrySelect.js"></script>
<script src="/js/mask.js"></script>
<script src="/js/jquery.validate.js"></script>
<script>
    jQuery.extend(jQuery.validator.messages, {
        required: "{{__('validator.required')}}",
        email: "{{__('validator.email')}}",
        number : "{{__('validator.number')}}"
      });
    jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
    }, "{{__('validator.required')}}");
</script>
<script>
var messages_error = {
    "error_phone"        : "{{__('login.error_phone')}}",
    "error_occured"      : "{{__('backend_messages.error_occured')}}",
    "invalid_phone"      : "{{__('login.invalid_phone')}}",
    "rectify_message"    : "{{__('backend_messages.rectify_message')}}",
    "error_contact"      : "{{__('backend_messages.error_contact')}}",
    "email_confirmation" : "{{__('backend_messages.email_confirmation')}}"
};
var messages = {"registration_successful" : "{{__('login.registration_successful')}}"};

</script>
@endpush
    @if ($message = Session::get('success'))

        <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
            {{ $message }}
        </div>

    @endif
<section class="inner-page-wrap section-center section-login">
    <div id="popup-modal-body-login" class="register-form-x rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
        <div class="modal-body">
            <div class="pop-hdr">
            <h6 class="fb-registration-message register-to-search">

            <div class="pop-hdr" style="font-size: 20px; color: #ff4545;">
                {{ __('register.has_error') }}
            </div>
            <div class="pop-hdr l1" style="color:black;">
            {{ __('register.refresh_inscription') }}
            </div>
            </div>

  

            <!-- <div class="pop-hdr">
                <h6 class="fb-registration-message">{{__('login.register_fb_message')}}</h6>
            </div> -->


            <ul class="nav nav-tabs">
            </ul>

            <div class="tab-content">
                <div id="login-tab" class="tab-pane fade in active">

                    <form id="registerStep" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="register-second-content">
                        </div>
                                <!-- <div class="row">
                                    <div id="register-school" class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{__('login.school')}}</label>
                                            <input type="text" class="form-control" placeholder="{{__('login.school')}}" name="school" id="school" />
                                        </div>
                                    </div>
                                    <div id="register-profession" class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('profile.profession') }} <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="{{ __('profile.profession_placeholder') }}" name="profession" id="profession"/>
                                        </div>
                                    </div>
                                </div> -->

                            </div>
                            <div class="form-group">
                                <label>{{__('register.mail')}} <sup>*</sup></label>
                                <input class="form-control" placeholder="{{__('register.mail_placeholder')}}" type="text" name="email" id="email_register" />
                            </div>
          

                        </div>

                            <div class="pr-poup-ftr">
                                <div class="submit-btn-2 reg-next-btn"><a href="javascript:void(0);" id="submit-reg-step-error">Continuer</a></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="information-modal" class="modal ">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-body" >
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 style="font-size : 1.2em;" id="modal-information-text" class="modal-title text-center"> {{__('register.error_link_term_condition')}} </h5>
            </div>
        </div>
    </div>
</div>


@endsection
<style>
    .hide
    {
        display: none;
    }
    .rent-property-form-content {
    padding-bottom: 61px !important;
    /* margin: 72
px
 !important; */
}
</style>

 @if(Session::get('stepAccount') == 2)
 @push('scripts')
 <script type="text/javascript">

$(document).ready(function(){ /*code here*/
    $('.sumo-select').SumoSelect();

        if ($("#registerStep").length >= 0) {
            $("#registerStep").validate({

            rules: {
            email: {
                required: true,
                email: true
            }
            },
            messages: {
            email: {
                required:  "{{__('validator.required')}}",
                email: "{{__('validator.email')}}"
            }

            }
        })
    }
})

</script>
 @endpush
 @endif

