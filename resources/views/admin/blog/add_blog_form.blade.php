<!-- Push a stye dynamically from a view -->
@push('styles')
<link href="{{ asset('bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
<link href="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('bootstrap-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.js') }}"></script>
<script src="{{ asset('bootstrap-fileinput/themes/fa/theme.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script src="{{ asset('js/admin/add_edit_blog.js') }}"></script>
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if(!empty($id)){{'Edit Blog'}}@else{{'Add New Blog'}}@endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{route('admin.bloglisting')}}">Blog List</a></li>
            <li class="active">
                @if(!empty($id)){{'Edit Blog'}}@else{{'Add New Blog'}}@endif
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
                <form class="form-horizontal" @if(!empty($id)) action="{{ route('admin.editblog', [base64_encode($id)]) }}" @else action="{{ route('admin.addnewblog') }}" @endif id="blog-add-edit-form" method="POST" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Blog Info</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group{{ $errors->has('blog_title') || $errors->has('url_slug') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Blog Title <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" name="blog_title" class="form-control" id="blog_title" placeholder="Blog Title" @if(!empty($id)) value="{{old('blog_title', $blog->blog_title)}}" @else value="{{old('blog_title')}}" @endif>
                                    
                                    @if ($errors->has('blog_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('blog_title') }}</strong>
                                    </span>
                                    @endif
                                    @if ($errors->has('url_slug'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url_slug') }}</strong>
                                    </span>
                                    @endif                                    
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('blog_meta_title') || $errors->has('url_slug') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Blog Meta Title <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" name="blog_meta_title" class="form-control" id="blog_meta_title" placeholder="Blog Meta title seo" @if(!empty($id)) value="{{old('blog_meta_title', $blog->meta_title)}}" @else value="{{old('blog_meta_title')}}" @endif>
                                    
                                    @if ($errors->has('blog_meta_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('blog_meta_title') }}</strong>
                                    </span>
                                    @endif
                                    @if ($errors->has('url_slug'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url_slug') }}</strong>
                                    </span>
                                    @endif                                    
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('blog_meta_description') || $errors->has('url_slug') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Blog Meta Description <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <input type="text" name="blog_meta_description" class="form-control" id="blog_meta_description" placeholder="Blog Meta Description seo" @if(!empty($id)) value="{{old('blog_meta_description', $blog->meta_description)}}" @else value="{{old('blog_meta_description')}}" @endif>

                                    <!-- <textarea id="blog_meta_description" name="blog_meta_description" class="form-control" placeholder="Blog Meta Description seo" rows="6">@if(!empty($id)) 
                                    {{old('blog_meta_description', $blog->meta_description)}}
                                    @else 
                                    {{old('blog_meta_description')}}
                                    @endif
                                    </textarea> -->
                                    
                                    @if ($errors->has('blog_meta_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('blog_meta_description') }}</strong>
                                    </span>
                                    @endif
                                    @if ($errors->has('url_slug'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url_slug') }}</strong>
                                    </span>
                                    @endif                                    
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('blog_description') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">Description <sup>*</sup></label>
                                <div class="col-sm-10">
                                    <textarea name="blog_description" rows="10" id="blog_description" class="form-control" placeholder="Blog Description">@if(!empty($id)){{old('blog_description', $blog->blog_description)}}@else{{old('blog_description')}}@endif</textarea>
                                    
                                    @if ($errors->has('blog_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('blog_description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Featured Image</label>
                                <div class="col-sm-10">
                                    <div class="file-loading">
                                        <input id="featured_image" type="file" data-overwrite-initial="true" name="featured_image" accept="image/*">
                                    </div>
                                    <div class="upload-photo-listing">
                                        <p>(Image supported - .jpg, .jpeg, .png, .gif).</p>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="col-sm-2"></div>
                                <div class="box-footer col-sm-10">
                                    <a href="{{route('admin.bloglisting')}}" class="btn btn-default">Cancel</a>
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