<!-- Push a stye dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper clearfix">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @if (!empty($user))
                    Edit Profile
                @else
                    Add User Detail
                @endif
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.users') }}">User List</a></li>
                <li class="active">
                    @if (!empty($user))
                        Edit Profile
                    @else
                        Add User Detail
                    @endif
                </li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            @if ($message = Session::get('error'))
                <div class="alert alert-danger fade in alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    {{ $message }}
                </div>
            @endif
            @if ($message = Session::get('status'))
                <div class="alert alert-success fade in alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    {{ $message }}
                </div>
            @endif
            <div class="row">
                <!-- left column -->
                <div class="col-md-12 show-message">
                    <!-- general form elements -->
                    <form id="editProfile" method="POST" enctype="multipart/form-data" role="form">
                        {{ csrf_field() }}
                        <div class="box box-primary ">
                            <div class="box-header with-border">
                                <h3 class="box-title">Personal Info</h3>
                            </div>
                            <div class="box-body">
                                @if (!empty($user))
                                    <input type="hidden" name="id"
                                        @if (!empty($user->id)) value="{{ base64_encode($user->id) }}" @endif />
                                @else
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label>Email <sup>*</sup></label>
                                                <input type="email" class="form-control" placeholder="Email" name="email"
                                                    id="email" />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>First name <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Fist name"
                                                name="first_name" id="first_name"
                                                @if (!empty($user->first_name)) value="{{ $user->first_name }}" @endif />
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Last name <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Last name"
                                                name="last_name" id="last_name"
                                                @if (!empty($user->last_name)) value="{{ $user->last_name }}" @endif />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Sex <sup>*</sup></label>
                                            <select class="form-control" name="sex" id="sex">
                                                <option @if (!empty($user) && count($user->user_profiles->get()) > 0 && $user->user_profiles->sex == '0') selected @endif value="0">Male
                                                </option>
                                                <option @if (!empty($user) && count($user->user_profiles->get()) > 0 && $user->user_profiles->sex == '1') selected @endif value="1">Female
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Mobile number <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Enter valid mobile no."
                                                name="mobile_no" id="mobile_no"
                                                @if (!empty($user->user_profiles) && !empty($user->user_profiles->mobile_no)) value="{{ $user->user_profiles->mobile_no }}" @endif />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Postal code <sup>*</sup></label>
                                            <input type="text" class="form-control" placeholder="Enter valid postal code"
                                                name="postal_code" id="postal_code"
                                                @if (!empty($user->user_profiles) && !empty($user->user_profiles->postal_code)) value="{{ $user->user_profiles->postal_code }}" @endif />
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Date of birth <sup>*</sup></label>
                                            <div class="datepicker-outer">
                                                <div id="datepicker-1" class="custom-datepicker">
                                                    <input class="form-control datepicker" type="text"
                                                        placeholder="mm/dd/yyyy" readonly name="birth_date" id="birth_date"
                                                        @if (!empty($user->user_profiles) && !empty($user->user_profiles->birth_date)) value="{{ date('m/d/Y', strtotime($user->user_profiles->birth_date)) }}" @endif>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box box-primary ">
                            <div class="box-header with-border">
                                <h3 class="box-title">Social Info</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="file-profile-photo">Upload your photo</label>
                                            <div class="file-loading">
                                                <input id="file-profile-photo" type="file" data-overwrite-initial="true"
                                                    name="file_profile_photos" accept="image/*">
                                            </div>
                                            <div class="upload-photo-listing">
                                                <p>Upload your photo and make your profile stand out (Image supported -
                                                    .jpg, .jpeg, .png, .gif).</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="about_me">About me</label>
                                            <textarea id="about_me" name="about_me" class="form-control"
                                                placeholder="Write something about your self" rows="3">
    @if (!empty($user->user_profiles) && !empty($user->user_profiles->about_me))
    {{ $user->user_profiles->about_me }}
    @endif
    </textarea>
                                        </div>
                                        <div class="ad-detail-ftr">
                                            <p>Max. 500 characters are allowed</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>School</label>
                                            <input type="text" class="form-control" placeholder="which school you did?"
                                                name="school_name" id="school_name"
                                                @if (!empty($user->user_profiles) && !empty($user->user_profiles->school)) value="{{ $user->user_profiles->school }}" @endif />
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Professional category</label>
                                            <select class="form-control selectpicker" name="professional_category"
                                                id="professional_category">
                                                <option @if (!empty($user) && count($user->user_profiles->get()) > 0 && $user->user_profiles->professional_category == '0') selected @endif value="0">Student
                                                </option>
                                                <option @if (!empty($user) && count($user->user_profiles->get()) > 0 && $user->user_profiles->professional_category == '1') selected @endif value="1">
                                                    Freelancer</option>
                                                <option @if (!empty($user) && count($user->user_profiles->get()) > 0 && $user->user_profiles->professional_category == '2') selected @endif value="2">
                                                    Salaried</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Level of studies</label>
                                            <div class="custom-selectbx">
                                                <select id="lvl_of_study" name="lvl_of_study"
                                                    class="selectpicker form-control">
                                                    @if (!empty($studyLevels))
                                                        @foreach ($studyLevels as $data)
                                                            @if (!empty($user->user_profiles) && count($user->user_profiles->get()) > 0 && $user->user_profiles->study_level_id == $data->id)
                                                                <option selected value="{{ $data->id }}">
                                                                    {{ $data->study_level }}</option>
                                                            @else
                                                                <option value="{{ $data->id }}">
                                                                    {{ $data->study_level }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box box-primary ">
                            <div class="box-header with-border">
                                <h3 class="box-title">Social Info+</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Interests</label>
                                            <ul class="property-feature-check-listing db-property-listing">
                                                @if (!empty($socialInterests))
                                                    @foreach ($socialInterests as $data)
                                                        <li>
                                                            @if (!empty($social_interests_array) && in_array($data->id, $social_interests_array))
                                                                <input class="custom-checkbox"
                                                                    id="si-checkbox-{{ $data->id }}" type="checkbox"
                                                                    name="social_interests[]" checked="checked"
                                                                    value="{{ $data->id }}">
                                                            @else
                                                                <input class="custom-checkbox"
                                                                    id="si-checkbox-{{ $data->id }}" type="checkbox"
                                                                    name="social_interests[]" value="{{ $data->id }}">
                                                            @endif
                                                            <label
                                                                for="si-checkbox-{{ $data->id }}">{{ $data->interest_name }}</label>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Smoker</label>
                                            <select class="selectpicker form-control" name="smoker" id="smoker">
                                                <option @if (!empty($user) && count($user->user_profiles->get()) > 0 && $user->user_profiles->smoker == '0') selected @endif value="0">Yes
                                                </option>
                                                <option @if (!empty($user) && count($user->user_profiles->get()) > 0 && $user->user_profiles->smoker == '1') selected @endif value="1">No
                                                </option>
                                                <option @if (!empty($user) && count($user->user_profiles->get()) > 0 && $user->user_profiles->smoker == '2') selected @endif value="2">
                                                    Indifferent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Origin country</label>
                                            <select class="selectpicker form-control" name="country" id="country">
                                                @if (!empty($countries))
                                                    @foreach ($countries as $data)
                                                        @if (!empty($user->user_profiles) && $user->user_profiles->country_id == $data->id)
                                                            <option selected value="{{ $data->id }}">
                                                                {{ $data->country_name }}</option>
                                                        @else
                                                            <option value="{{ $data->id }}">{{ $data->country_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Origin city</label>
                                            <select class="selectpicker form-control" name="city" id="city">
                                                @if (!empty($cities))
                                                    @foreach ($cities as $data)
                                                        @if (!empty($user->user_profiles) && $user->user_profiles->city_id == $data->id)
                                                            <option selected value="{{ $data->id }}">
                                                                {{ $data->city_name }}</option>
                                                        @else
                                                            <option value="{{ $data->id }}">{{ $data->city_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label>Lifestyle</label>
                                            <ul class="property-feature-check-listing db-property-listing">
                                                @if (!empty($userLifestyles))
                                                    @foreach ($userLifestyles as $data)
                                                        <li>
                                                            @if (!empty($user_lifestyles_array) && in_array($data->id, $user_lifestyles_array))
                                                                <input class="custom-checkbox"
                                                                    id="ul-checkbox-{{ $data->id }}" type="checkbox"
                                                                    name="user_lifestyles[]" checked="checked"
                                                                    value="{{ $data->id }}">
                                                            @else
                                                                <input class="custom-checkbox"
                                                                    id="ul-checkbox-{{ $data->id }}" type="checkbox"
                                                                    name="user_lifestyles[]" value="{{ $data->id }}">
                                                            @endif
                                                            <label
                                                                for="ul-checkbox-{{ $data->id }}">{{ $data->lifestyle_name }}</label>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label><i class="fa fa-facebook-square" aria-hidden="true"
                                                    style="color:#3B5998;"></i>&nbsp;Link to Facebook profile</label>
                                            <input type="text" class="form-control"
                                                placeholder="Paste your Facebook profile URL" name="fb_profile_link"
                                                id="fb_profile_link"
                                                @if (!empty($user->user_profiles) && !empty($user->user_profiles->fb_profile_link)) value="{{ $user->user_profiles->fb_profile_link }}" @endif />
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label><i class="fa fa-linkedin-square" aria-hidden="true"
                                                    style="color:#0077B5;"></i>&nbsp;Link to LinkedIn profile</label>
                                            <input type="text" class="form-control"
                                                placeholder="Paste your LinkedIn profile URL" name="in_profile_link"
                                                id="in_profile_link"
                                                @if (!empty($user->user_profiles) && !empty($user->user_profiles->in_profile_link)) value="{{ $user->user_profiles->in_profile_link }}" @endif />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-footer">
                                            <button type="button" class="btn btn-primary"
                                                id="edit-profile-step-3">Submit</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->
            </div>
            <!--/.col (right) -->
        </section>
    </div>
@endsection
<!-- /.row -->
@push('scripts')
    <script>
        var messagess = {
            "browse": "{{ __('profile.browse') }}",
            "cancel": "{{ __('profile.cancel') }}",
            "remove": "{{ __('profile.remove') }}",
            "upload": "{{ __('profile.upload') }}"
        }
    </script>
    <script src="{{ asset('bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('js/admin/user/edit_profile.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.js') }}"></script>
    <script src="{{ asset('bootstrap-fileinput/themes/fa/theme.min.js') }}"></script>
@endpush
