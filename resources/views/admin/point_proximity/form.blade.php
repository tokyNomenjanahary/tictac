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
            Point of Proximity
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">
                Point of Proximity
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
                <form id="formPointProximity" action="{{ route('proximity.store') }}" method="POST" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Point Proximity</h3>
                        </div>
                        <div class="box-body">
                            @if(count($proximities)>0)
                                @foreach($proximities as $proximity)
                                    <div class="row" style="margin-bottom: 10px">
                                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10"> 
                                            <input type="text" class="form-control" placeholder="" name="point_proximity[]" value="{{ $proximity->title }}"/>
                                            <input type="text" class="form-control hidden proximity_id" placeholder="" name="id[]" value="{{ $proximity->id }}"/>
                                        </div>
                                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                            <button type="button" class="btn btn-danger remove_proximity"> - </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10"> 
                                    <input type="text" class="form-control" placeholder="" name="point_proximity[]" value=""/>        
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                    <button type="button" class="btn btn-success add_proximity"> + </button>
                                </div>
                            </div>   
                            <input type="text" id="deleted_id" class="hidden" name="deleted_id">                         
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-right" id="featured-submit-btn">Submit</button>
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
<script src="{{ asset('js/admin/point_proximity.js') }}"></script>
@endpush