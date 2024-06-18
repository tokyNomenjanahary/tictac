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
            @if(!empty($id)){{'Edit Page'}}@else{{'Add New Page'}}@endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{route('admin.pagelisting')}}">Page List</a></li>
            <li class="active">
                @if(!empty($id)){{'Edit Page'}}@else{{'Add New Page'}}@endif
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
                <form class="form-horizontal" @if(!empty($id)) action="{{ route('admin.editpage', [base64_encode($id)]) }}" @else action="{{ route('admin.addnewpage') }}" @endif id="page-add-edit-form" method="POST" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Page Info</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group{{ $errors->has('title') || $errors->has('url_slug') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Page <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Page Title" @if(!empty($id)) value="{{old('title', $page->title)}}" @else value="{{old('title')}}" @endif>
                                    
                                    @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    @endif
                                    @if ($errors->has('url_slug'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url_slug') }}</strong>
                                    </span>
                                    @endif                                    
                                </div>
                            </div>
                            <!-- <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Description <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <textarea name="description" rows="10" id="page_description" class="form-control" placeholder="Page Description">@if(!empty($id)){{old('description', $page->description)}}@else{{old('description')}}@endif</textarea>
                                    
                                    @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> -->
                            <div class="form-group{{ $errors->has('meta_title') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Meta Title <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" name="meta_title" class="form-control" id="meta_title" placeholder="Meta Title" @if(!empty($id)) value="{{old('meta_title', $page->meta_title)}}" @else value="{{old('meta_title')}}" @endif>
                                    
                                    @if ($errors->has('meta_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('meta_title') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('meta_description') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Meta Description</label>
                                <div class="col-sm-10">
                                    <textarea name="meta_description" rows="6" id="meta_description" class="form-control" placeholder="Meta Description">@if(!empty($id)){{old('meta_description', $page->meta_description)}}@else{{old('meta_description')}}@endif</textarea>
                                    
                                    @if ($errors->has('meta_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('meta_description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <!-- <div class="form-group{{ $errors->has('meta_keywords') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Meta Keywords</label>
                                <div class="col-sm-10">
                                    <textarea name="meta_keywords" rows="6" id="meta_keywords" class="form-control" placeholder="Meta Keywords">@if(!empty($id)){{old('meta_keywords', $page->meta_keywords)}}@else{{old('meta_keywords')}}@endif</textarea>
                                    <div>
                                        <p>(Use comma separated values for keywords).</p>
                                    </div>
                                    
                                    @if ($errors->has('meta_keywords'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('meta_keywords') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> -->
                            <!-- <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Sort Order</label>
                                <div class="col-sm-10">
                                    <input type="text" name="sort_order" class="form-control" id="sort_order" placeholder="Sort Order" @if(!empty($id)) value="{{old('sort_order', $page->sort_order)}}" @else value="{{old('sort_order')}}" @endif>
                                    
                                    @if ($errors->has('sort_order'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('sort_order') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> -->
                            <div class="">
                                <div class="col-sm-2"></div>
                                <div class="box-footer col-sm-10">
                                    <a href="{{route('admin.pagelisting')}}" class="btn btn-default">Cancel</a>
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