@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/intlTelInput/intlTelInput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profil.css') }}" rel="stylesheet">
@endpush

@section('content')

    <section class="inner-page-wrap section-center">
        <div class="container">
            <div class="row">
                <div
                    class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 rent-property-form-outer rent-property-step-1">
                    @if ($message = Session::get('status'))
                        <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                            {{ $message }}
                        </div>
                    @endif

                    <div class="edit-pro-hdr text-center">
                        <h4>{{ __('profile.title') }}</h4>
                        <p>{{ __('profile.keep_personal_detail') }}</p>
                    </div>
                    <div class="rent-property-form-hdr">
                        <div class="profil-percent">
                            @if($profilPercent == 100)
                                <div id="completer-profil">
                                    {{__("profile.profil_completed")}}
                                </div>
                            @else
                                <div id="completer-profil">
                                    {{__("profile.completer_profile")}}
                                </div>
                            @endif

                            <div class="pourcentage-profil-mesure">
                                <div class="pourcentage-profil" style="width: {{$profilPercent}}%">

                                </div>
                            </div>
                            <div class="number-precent-profil">
                                {{$profilPercent}}%
                            </div>
                        </div>
                        <div class="rent-property-form-heading">
                            <h6>{{ __('profile.edit_profile') }}</h6>
                        </div>
                        <div class="rent-property-form-step-listing edit-pro-step-listing">
                            <ul>
                                <li class="rent-property-menu"><a href="{{ route('edit.profile') }}"><span><i
                                                class="fa fa-user" aria-hidden="true"></i></span>
                                        <h6>{{ __('profile.personal_info') }}</h6></a></li>
                                <!--                            <li class="property-feature-menu"><a href=""><span><i class="fa fa-envelope" aria-hidden="true"></i></span><h6>{{ __('profile.change_mail') }}</h6></a></li>-->
                                <li class="visiting-menu active"><a href="javascript:"><span><i class="fa fa-eye"
                                                                                                aria-hidden="true"></i></span>
                                        <h6>{{ __('profile.modif_pass') }}</h6></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
                        <form id="changePassword" action="{{ route('edit.changepwd') }}" method="POST"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="change-em-block change-pw-block">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
                                        <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <div class="col-md-4"><label>{{ __('profile.new_password') }}
                                                    <sup>*</sup></label></div>
                                            <div class="col-md-8">
                                                <input type="password" name="password" class="form-control"
                                                       placeholder="{{ __('profile.new_password') }}"/>
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                    {{ $errors->first('password') }}                                                       </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div
                                            class="form-group row{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            <div class="col-md-4"><label>{{ __('profile.verify_new_password') }}
                                                    <sup>*</sup></label></div>
                                            <div class="col-md-8">
                                                <input type="password" name="password_confirmation" class="form-control"
                                                       placeholder="{{ __('profile.verify_new_password') }}"/>
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
                                                {{ $errors->first('password_confirmation') }}
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="submit-btn-1 save-nxt-btn">
                                                <!--                                            <a href="javascript:void(0);">{{ __('profile.Update') }}</a>-->
                                                <input type="submit" value="{{ __('profile.modifier') }}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

