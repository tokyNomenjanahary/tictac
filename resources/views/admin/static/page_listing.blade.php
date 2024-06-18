@extends('layouts.adminappinner')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Static Pages</h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Pages List</li>
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
            <div class="col-md-12 show-message">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Pages</h3>
                        <!-- <div class="col-sm-2 pull-right">
                            <a href="{{route('admin.addnewpage')}}" class="btn btn-block btn-warning">Add Page</a>
                        </div> -->
                    </div>
                    
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th width="5%">No.</th>
                                <th width="5%">Page</th>
                                <th width="5%">url</th>
                                <th width="20%">Title</th>
                                <th width="26%">Description</th>
                                <!-- <th width="10%">Sort Order</th> -->
                                <th width="12%">Created Date</th>
                                <th width="15%">Actions</th>
                            </tr>
                            @if(!empty($all_pages) && count($all_pages) > 0)
                            @foreach($all_pages as $key => $page)
                            <tr>
                                <td>{{$all_pages->firstItem() + $key}}</td>
                                <td>{{str_limit($page->title, 30, '...')}}</td>
                                <td>{{str_limit($page->url_slug, 30, '...')}}</td>
                                <td>{{str_limit($page->meta_title, 30, '...')}}</td>
                                <td>{{str_limit(strip_tags($page->meta_description), 60, '...')}}</td>
                                <!-- <td>{{$page->sort_order}}</td> -->
                                <td class="text-center">
                                    {{date('m-d-Y', strtotime($page->created_at))}}
                                </td>
                                <td>
                                    <a href="{{route('admin.editpage',[base64_encode($page->id)])}}" class="btn btn-info btn-sm" title ="Edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <!-- @if($page->is_active == '1')
                                    <a href="{{route('admin.pagestatus',[base64_encode($page->id),base64_encode('0')])}}"  class="btn btn-danger btn-sm" title ="De-activate"><span class="glyphicon glyphicon-remove" aria-hidden="true" ></span></a>
                                    @else
                                    <a href="{{route('admin.pagestatus',[base64_encode($page->id), base64_encode('1')])}}" class="btn btn-success btn-sm" title ="Activate"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
                                    @endif -->
                                    <!-- <a href="{{route('admin.deletepage',[base64_encode($page->id)])}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete ?');" title ="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a> -->
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="bg-info">
                                <td colspan="6">Record(s) not found.</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="pull-right">
                        @if(!empty($all_pages))
                        {{ $all_pages->links('vendor.pagination.bootstrap-4') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>    
</div>
@endsection