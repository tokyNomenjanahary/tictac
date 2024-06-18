@extends('layouts.app')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/intlTelInput/intlTelInput.css') }}" rel="stylesheet">
@endpush
<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/intlTelInput.js') }}"></script>
    <script src="/js/jquery.validate.min.js"></script>
@endpush
@include("common.fileInputMessages")
@section('content')

<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 rent-property-form-outer rent-property-step-1">
                <div class="rent-property-form-hdr">

                    <div class="rent-property-form-heading">
                        <h6>{{ __('acceuil.contactez_nous') }}</h6>
                    </div>
                </div>
                <div class="rent-property-form-content project-form rp-step-1-content white-bg m-t-20">
                    <form id="contact-us" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="control-label" for="nom">{{ __('acceuil.nom') }} *</label>
                            <input type="text" class="form-control" id="nom" placeholder="{{ __('acceuil.nom') }}" name="nom" autofocus>
                        </div>
                         <div class="form-group">
                            <label class="control-label" for="email">{{ __('acceuil.mail') }} *</label>
                            <input type="text" class="form-control" id="email" placeholder="{{ __('acceuil.mail') }}" name="email" autofocus>
                        </div>
                         <div class="form-group">
                            <label class="control-label" for="mobile_no">{{ __('acceuil.mobile_no') }}</label>
                            <input type="text" class="form-control" id="mobile_no" placeholder="{{ __('acceuil.mobile_no') }}" name="mobile_no" autofocus>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="message">{{ __('acceuil.message') }} *</label>
                            <textarea id="message" name="message" class="form-control" placeholder="{{ __('acceuil.message') }}" rows="6"></textarea>
                        </div>
                        <div class="pr-form-ftr">
                            <div class="submit-btn-1 save-nxt-btn"><input type="submit" name="Save" value="{{ __('acceuil.send') }}" id="send_button" /></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="newsletter-modal" class="modal ">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-body" >
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 id="modal-news-text" class="modal-title text-center">{{ __("application.common_friend_sender") }}</h5>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var telInput = $("#mobile_no");

        // initialise plugin
        telInput.intlTelInput({
            initialCountry: 'fr',
            nationalMode: false,
            utilsScript: "/js/intlTelInput/utils.js"
        });

        $('#send_button').on('click', function(e) {
            if($('#contact-us').valid()) {
                e.preventDefault();
                insertContact();
            }
        });

        jQuery.extend(jQuery.validator.messages, {
            required: "{{__('validator.required')}}",
            email: "{{__('validator.email')}}"
        });
        jQuery("#contact-us").validate({
            rules: {
                 "nom":{
                    "required": true,
                 },
                 "email": {
                    "required": true,
                    "email": true
                 },
                 "message": {
                    "required": true
                 }
            }
        });
        $("#contact-us").validate();
    });

    function insertContact()
    {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: '/subscribeContact',
            type: 'post',
            data : { "nom" : $("#nom").val(), "mobile" : $("#mobile_no").val(), "mail" : $("#email").val(), "message" : $("#message").val() }
        }).done(function(result){
            $("#nom").val("");
            $("#mobile_no").val("");
            $("#email").val("");
            $("#message").val("");
            $("#modal-news-text").text(result);
            $("#newsletter-modal").modal('show');
        }).fail(function (jqXHR, ajaxOptions, thrownError){
        });
    }


</script>
@endsection
