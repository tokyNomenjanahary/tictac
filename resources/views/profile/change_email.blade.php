@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
@endpush

@section('content')

    <section class="inner-page-wrap section-center">
        <div class="container">

            <div class="row">
                <div
                    class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 rent-property-form-outer rent-property-step-1">
                    @if ($message = Session::get('message'))
                        <div class="alert {{Session::get('alert-class')}} fade in alert-dismissable"
                             style="margin-top:20px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                            {{ $message }}
                        </div>
                    @endif

                    <div class="edit-pro-hdr text-center">
                        <h4>{{ __('profile.manage_profile') }}</h4>
                        <p>{{ __('profile.keep_personal') }}</p>
                    </div>
                    <div class="rent-property-form-hdr">
                        <div class="rent-property-form-heading">
                            <h6>{{ __('profile.edit_profile') }}</h6>
                        </div>

                        <div class="rent-property-form-step-listing edit-pro-step-listing">
                            <ul>
                                <li class="rent-property-menu"><a href="{{ route('edit.profile') }}"><span><i
                                                class="fa fa-user" aria-hidden="true"></i></span>
                                        <h6>{{ __('Personal Info') }}</h6></a></li>
                                <li class="property-feature-menu active"><a href="javascript:void(0);"><span><i
                                                class="fa fa-envelope" aria-hidden="true"></i></span>
                                        <h6>{{ __('Change Email') }}</h6></a></li>
                                <li class="visiting-menu"><a href="{{ route('edit.changepwd') }}"><span><i
                                                class="fa fa-eye" aria-hidden="true"></i></span>
                                        <h6>{{ __('Change Password') }}</h6></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="rent-property-form-content project-form white-bg m-t-20">
                        <form id="changeEmail" action="{{ route('edit.changeemail') }}" method="POST"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="change-em-block">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
                                        <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <div class="col-md-4"><label>{{ __('profile.curent_mail') }}
                                                    <sup>*</sup></label></div>
                                            <div class="col-md-8">
                                                <input type="email" class="form-control" name="email"
                                                       placeholder="{{ __('profile.enter_curent_mail') }}"
                                                       value="{{ old('email') }}"/>
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                    {{ $errors->first('email') }}                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row{{ $errors->has('newemail') ? ' has-error' : '' }}">
                                            <div class="col-md-4"><label>{{ __('profile.new_mail') }}
                                                    <sup>*</sup></label></div>
                                            <div class="col-md-8">
                                                <input type="email" class="form-control" name="newemail"
                                                       placeholder="{{ __('profile.enter_new_mail') }}"
                                                       value="{{ old('newemail') }}"/>
                                                @if ($errors->has('newemail'))
                                                    <span class="help-block">
                                                    {{ $errors->first('newemail') }}                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="submit-btn-1 save-nxt-btn">
                                                <!--                                            <a href="javascript:void(0);">Update</a>-->
                                                <input type="submit" value="{{ __('profile.Update') }}"/>
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

