@extends('layouts.adminappinner')
@push('styles')
<link href="{{ asset('bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
<link href="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Featured City
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">
                Add Featured City
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
                <form id="addFeaturedLocation" method="POST" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add City</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Country <sup>*</sup></label>
                                        <select class="form-control" name="country" id="country">
                                            @if(!empty($countries))
                                            @foreach($countries as $data)
                                            @if(!empty($data->country))
                                            <option value="{{$data->id}}">{{$data->country}}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>City <sup>*</sup></label>
                                        <select class="form-control" name="city" id="city">
                                            @if(!empty($cities))
                                            @foreach($cities as $data)
                                            @if(!empty($data->city))
                                            <option selected value="{{$data->id}}">{{$data->city}}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="featured-location-image">Featured City Image <sup>*</sup></label>
                                        <div class="file-loading">
                                            <input id="featured-location-image" type="file" data-overwrite-initial="true" name="image" accept="image/*" required>
                                        </div>
                                        <div class="upload-photo-listing">
                                            <p>(Image supported - .jpg, .jpeg, .png, .gif).</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-footer">
                                        <button type="button" class="btn btn-primary" id="featured-submit-btn">Submit</button>
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
    var messagess = {"browse" : "{{__('profile.browse')}}","cancel" : "{{__('profile.cancel')}}","remove" : "{{__('profile.remove')}}","upload" : "{{__('profile.upload')}}"}
</script>
<script src="{{ asset('bootstrap-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.js') }}"></script>
<script src="{{ asset('bootstrap-fileinput/themes/fa/theme.min.js') }}"></script>
<script src="{{ asset('js/admin/featured_city.js') }}"></script>
@endpush