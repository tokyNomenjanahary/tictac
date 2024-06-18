@extends('layouts.app')

@section('content')
<section class="inner-page-wrap section-center">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
                <div class="rent-property-form-hdr">
                    <div class="rent-property-form-heading">
                        <h6>{{ __('reset.reset_pass') }}</h6>
                    </div>
                </div>
                <div class="rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
                    {{-- @if ($message = Session::get('success'))

                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                        {{ $message }}
                    </div>

                    @endif --}}
                    @if ($message = Session::get('error'))

                    <div class="alert alert-danger fade in alert-dismissable" style="margin-top:20px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                        {{ $message }}
                    </div>

                    @endif
                    <form  method="POST" action="{{ route('password.resetForm') }}">
                        {{ csrf_field() }}

                        <div class="">
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email_address" class="control-label">{{ __('reset.email_adresse') }} <sup>*</sup></label>
                                <input class="form-control" placeholder="{{ __('E-Mail Address') }}" type="text" name="email" id="email_address" value="{{ old('email') }}" autofocus />

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="control-label">{{ __('reset.password') }} <sup>*</sup></label>
                                <input id="password" type="password" class="form-control" name="password" placeholder="{{ __('Password') }}">

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password-confirm" class="control-label">{{ __('reset.confirm_password') }} <sup>*</sup></label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('reset.confirm_password') }}">

                                @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="submit-btn-1 save-nxt-btn" style="max-width: 220px;"><input type="submit" name="resetpassword" value="{{ __('reset.reset_pass') }}" /></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

