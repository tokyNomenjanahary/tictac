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
            Edit page content
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{route('admin.pagelisting')}}">Page List</a></li>
            <li class="active">
                Edit page content
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
            <div class="box-header">
                <h3 class="box-title">Pages</h3>
                <!-- <div class="col-sm-2 pull-right">
                    <a href="{{route('admin.addnewpage')}}" class="btn btn-block btn-warning">Add Page</a>
                </div> -->
                <div style="display: inline-block; margin-left : 15px;">
                    <select id="choosePage" class="selectpicker">
                        @foreach($pages as $key => $p)
                        <option value="{{$p->id}}" @if($p->id==$page) selected @endif>{{$p->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- left column -->
            <div class="col-md-12 show-message">
                <!-- general form elements -->
                <form class="form-horizontal" action="{{route('admin.saveStatic')}}" id="page-add-edit-form" method="POST" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$elem->id}}"/>
                    <div class="box box-primary ">
                        <div class="box-body">
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <div class="col-sm-12">
                                    <textarea name="description" rows="10" id="page_description" class="form-control" placeholder="Page Description"><?php echo $elem->description;?></textarea>
                                </div>
                            </div>
                            <div class="">
                                <div class="box-footer col-sm-10">
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
<script>
    $(document).ready(function(){
        $('#choosePage').on('change', function(){
            location.href = "?page_show=" + $(this).val();
        });
    });
</script>
@endsection