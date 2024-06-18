@extends('layouts.app')

@section('content')
@push('styles')
	<link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
@endpush
<script src='https://www.google.com/recaptcha/api.js'></script>

<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
                <div class="rent-property-form-hdr">
                    <div class="rent-property-form-heading">
                        <h6>{{ __("captcha.captcha_message") }}</h6>
                    </div>
                </div>
                <div class="rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
                    @if ($message = Session::get('success'))

                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                        {{ $message }}
                    </div>

                    @endif
                    @if ($message = Session::get('error'))

                    <div class="alert alert-danger fade in alert-dismissable" style="margin-top:20px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                        {{ $message }}
                    </div>

                    @endif
                    <form method="POST" enctype="multipart/form-data" action="{{ route('validate_recaptcha') }}">
                        {{ csrf_field() }}
                        <div class="g-recaptcha" data-sitekey="6LdHlWUUAAAAALf_U5Z-ilLduo-cdbcprc_PyfQ-"></div>
						<div class="submit-btn-1 save-nxt-btn"><input type="submit" name="Login" value="{{ __('captcha.valid') }}" /></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
