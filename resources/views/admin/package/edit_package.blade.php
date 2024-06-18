@extends('layouts.adminappinner')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit Package<small>Manage Package</small>
        </h1>
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
            <div class="col-md-12 show-message">
                <!-- general form elements -->
                <form id="edit-package" method="POST" @if(!empty($id)) action="{{ route('admin.editPackage', [base64_encode($id)]) }}" @endif enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Package</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-5 col-md-5">
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label>Title <sup>*</sup></label>
                                        <input type="text" class="form-control" placeholder="Title" name="title" id="p-title" @if(!empty($id)) value="{{old('title', $package->title)}}" @endif/>
                                        @if ($errors->has('title'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-2 col-md-2">
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label>Popular <sup>*</sup></label>
                                        <div>
                                        <input type="checkbox" @if(!empty($id) && $package->popular == 1) checked @endif name="popular">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-5 col-md-5">
                                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                        <label>Description </label>
                                        <textarea class="form-control" placeholder="Description" name="description" id="p-description" />@if(!empty($id)){{old('description', $package->description)}}@endif</textarea>
                                        @if ($errors->has('description'))
                                        <span class="help-block">{{ $errors->first('description') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                                        <label>Amount <sup>*</sup></label>
                                        <input type="text" class="form-control" placeholder="Amount" name="amount" id="p-amount" @if(!empty($id)) value="{{old('amount', $package->amount)}}" @endif/>
                                        @if ($errors->has('amount'))
                                        <span class="help-block">{{ $errors->first('amount') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-3 col-md-3">
                                    <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                                        <label>Duration<sup>*</sup></label>
                                        <input type="text" class="form-control" placeholder="Duration" name="duration" id="p-duration" @if(!empty($id)) value="{{old('duration', $package->duration)}}" @endif/>
                                               @if ($errors->has('duration'))
                                               <span class="help-block">{{ $errors->first('duration') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-6 col-sm-3 col-md-3">
                                    <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                                        <label>Unit (day, month, year)<sup>*</sup></label>
                                        <input type="text" class="form-control" placeholder="Unit" name="unite" id="p-duration" @if(!empty($id)) value="{{old('unite', $package->unite)}}" @endif/>
                                               @if ($errors->has('unit'))
                                               <span class="help-block">{{ $errors->first('unit') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-footer">
                                    <a href="{{route('admin.packageList')}}" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="btn btn-info" id="edit-profile-step-3">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>    
@endsection