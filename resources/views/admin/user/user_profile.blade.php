@extends('layouts.adminappinner')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 1170.3px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Profile
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{route('admin.users')}}">User List</a></li>
            <li class="active">User profile</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" @if(!empty($userDetail->user_profiles->profile_pic)) src="{{URL::asset('uploads/profile_pics/' . $userDetail->user_profiles->profile_pic)}}" @else src="{{URL::asset('images/profile_avatar.jpeg')}}" @endif alt="User profile picture">
                        <h3 class="profile-username text-center">
                            {{$userDetail->first_name.' '.$userDetail->last_name}}
                        </h3>
                        <p class="text-muted text-center">
                            @if(!empty($userDetail->user_profiles->study_level->study_level))
                            {{$userDetail->user_profiles->study_level->study_level}}
                            @endif
                        </p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Smoker</b>
                                <a class="pull-right">
                                    @if(empty($userDetail->user_profiles->smoker))
                                    {{'Yes'}}
                                    @elseif(!empty($userDetail->user_profiles->smoker) && $userDetail->user_profiles->smoker==1)
                                    {{'No'}}
                                    @elseif(!empty($userDetail->user_profiles->smoker) && $userDetail->user_profiles->smoker==2)
                                    {{'Indifferent'}}
                                    @endif
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Professional category</b>
                                <a class="pull-right">
                                    @if(!empty($userDetail->user_profiles->professional_category) && $userDetail->user_profiles->professional_category==1)
                                    {{'Freelancer'}}
                                    @elseif(!empty($userDetail->user_profiles->professional_category) && $userDetail->user_profiles->professional_category==2)
                                    {{'Salaried'}}
                                    @else
                                    {{'Student'}}
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">About Me</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <strong><i class="fa fa-book margin-r-5"></i> School</strong>
                        <p class="text-muted">
                            @if(!empty($userDetail->user_profiles->school))
                            {{$userDetail->user_profiles->school}}
                            @else
                            {{'--'}}
                            @endif
                        </p>
                        <hr>
                        <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                        <p class="text-muted">
                            @if(!empty($userDetail->user_profiles->city->city_name))
                            {{$userDetail->user_profiles->city->city_name}},
                            @endif
                            @if(!empty($userDetail->user_profiles->country->country_name))
                            {{$userDetail->user_profiles->country->country_name}},
                            @endif
                            @if(!empty($userDetail->user_profiles->postal_code))
                            {{$userDetail->user_profiles->postal_code}}
                            @endif
                        </p>
                        <hr>
                        <strong><i class="fa fa-file-text-o margin-r-5"></i> About me</strong>
                        <p>
                            @if(!empty($userDetail->user_profiles->about_me))
                            {{$userDetail->user_profiles->about_me}}
                            @else
                            {{'--'}}
                            @endif
                        </p>
                        <p>
                            <a id="login-with-account" href="{{route('admin.superAdmin', [$userDetail->id])}}">Se connecter avec ce compte</a>
                        </p>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Other Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-md-3">Email</label>
                            <p class="col-sm-9">{{$userDetail->email}}</p>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Mobile number</label>
                            <p class="col-sm-9">
                                @if(!empty($userDetail->user_profiles->mobile_no))
                                {{$userDetail->user_profiles->mobile_no}}
                                @else
                                {{'--'}}
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Sex</label>
                            <p class="col-sm-9">
                                @if(empty($userDetail->user_profiles->sex))
                                {{'Male'}}
                                @else
                                {{'Female'}}
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">DOB</label>
                            <p class="col-sm-9">
                                @if(!empty($userDetail->user_profiles->birth_date))
                                {{$userDetail->user_profiles->birth_date}}
                                @else
                                {{'--'}}
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Interests</label>
                            <p class="col-sm-9">
                                @if(!empty($userDetail->user_social_interests) && count($userDetail->user_social_interests)>0)
                                @foreach($userDetail->user_social_interests as $interest)

                                {{$interest->social_interest->interest_name.', '}}
                                @endforeach
                                @else
                                {{'--'}}
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Lifestyle</label>
                            <p class="col-sm-9">
                                @if(!empty($userDetail->user_lifestyles) && count($userDetail->user_lifestyles)>0)
                                @foreach($userDetail->user_lifestyles as $lifestyle)
                                {{$lifestyle->lifestyle->lifestyle_name.', '}}
                                @endforeach
                                @else
                                {{'--'}}
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">FB Link</label>
                            <p class="col-sm-9">
                                @if(!empty($userDetail->user_profiles->fb_profile_link))
                                {{$userDetail->user_profiles->fb_profile_link}}
                                @else
                                {{'--'}}
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">LinkedIn Link</label>
                            <p class="col-sm-9">
                                @if(!empty($userDetail->user_profiles->in_profile_link))
                                {{$userDetail->user_profiles->in_profile_link}}
                                @else
                                {{'--'}}
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Date de derniere connexion</label>
                            <p class="col-sm-9">
                            {{ $end_date_conx  }} : @if($jours==0) Aujoud'hui @else il y a {{ $jours }} jours  @endif
                            </p>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

<style type="text/css">
    #login-with-account
    {
        padding: 10px;
        color: white;
        background-color: rgb(40,146,245);
        border-radius: 4px;
    }
</style>
@endsection
