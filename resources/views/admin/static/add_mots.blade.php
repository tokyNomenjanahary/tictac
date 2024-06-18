@extends('layouts.adminappinner')

@push('scripts')
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script src="{{ asset('js/admin/add_edit_page.js') }}"></script>
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           {{'Add New Mot clés'}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{route('admin.mot_cles')}}">List Mots clés</a></li>
            <li class="active">
                New mots clés
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
                <form class="form-horizontal" action="{{ route('admin.add_new_mot_cles') }}" id="page-add-edit-form" method="POST" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Mots Clés</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group{{ $errors->has('title') || $errors->has('url_slug') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Mots Clés <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Mots Clés">
                                    
                                    @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    @endif                                   
                                </div>
                            </div>
                            <div class="">
                                <div class="col-sm-2"></div>
                                <div class="box-footer col-sm-10">
                                    <a href="{{route('admin.mot_cles')}}" class="btn btn-default">Cancel</a>
                                    <input type="submit" value="Submit" class="btn btn-info">
                                </div>   
                            </div>
                        </div>
                    </div>
                </form>
            </div>            
        </div>
    </section>
</div>
@endsection