@extends('layouts.adminappinner')

@push('scripts')
<script src="{{ asset('js/admin/manageblogs.js') }}"></script>
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Blogs</h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Blogs List</li>
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
                        <h3 class="box-title">All Blogs</h3>
                        <div class="col-sm-2 pull-right">
                            <a href="{{route('admin.addnewblog')}}" class="btn btn-block btn-warning">Add Blog</a>
                        </div>
                    </div>
                    
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th width="2%">No.</th>
                                <th width="10%">
                                    @if($sort == "blog_title")
                                    <a href="{{getConfig('admin_prefix')}}/blog_list" class="sort-column">Title<i class="fa fa-sort-desc"></i></a>
                                    @else 
                                    <a href="javascript:" class="sort-column" data-sort="blog_title" data-type="ASC">Title</a>
                                    @endif
                                </th>
                                <th width="13%">
                                    @if($sort == "meta_title")
                                    <a href="{{getConfig('admin_prefix')}}/blog_list" class="sort-column">Meta title<i class="fa fa-sort-desc"></i></a>
                                    @else 
                                    <a href="javascript:" class="sort-column" data-sort="meta_title" data-type="ASC">Meta title</a>
                                    @endif
                                </th>
                                <th width="21%">
                                    @if($sort == "blog_description")
                                    <a href="{{getConfig('admin_prefix')}}/blog_list" class="sort-column">Description<i class="fa fa-sort-desc"></i></a>
                                    @else 
                                    <a href="javascript:" class="sort-column" data-sort="blog_description" data-type="ASC">Description</a>
                                    @endif
                                </th>
                                <th width="21%">
                                    @if($sort == "meta_description")
                                    <a href="{{getConfig('admin_prefix')}}/blog_list" class="sort-column">Meta description<i class="fa fa-sort-desc"></i></a>
                                    @else 
                                    <a href="javascript:" class="sort-column" data-sort="meta_description" data-type="ASC">Meta description</a>
                                    @endif
                                </th>
                                <th width="10%">
                                    @if($sort == "users.first_name")
                                    <a href="{{getConfig('admin_prefix')}}/blog_list" class="sort-column">Added By<i class="fa fa-sort-desc"></i></a>
                                    @else 
                                    <a href="{{getConfig('admin_prefix')}}/blog_list?sort=users.first_name&foreign=users&foreign_id=user_id&type=ASC" class="sort-column" data-sort="users.first_name"  data-type="ASC">Added By</a>
                                    @endif
                                </th>
                                <th width="6%">
                                    @if($sort == "created_at")
                                    <a href="{{getConfig('admin_prefix')}}/blog_list" class="sort-column">Created at<i class="fa fa-sort-desc"></i></a>
                                    @else 
                                    <a href="javascript:" class="sort-column" data-sort="created_at" data-type="DESC">Created at</a>
                                    @endif
                                </th>
                                <th width="6%">
                                    @if($sort == "updated_at")
                                    <a href="{{getConfig('admin_prefix')}}/blog_list" class="sort-column">Modif at<i class="fa fa-sort-desc"></i></a>
                                    @else 
                                    <a href="javascript:" class="sort-column" data-sort="updated_at" data-type="DESC">Modif at</a>
                                    @endif
                                </th>
                                <th width="3%">Mark as featured</th>
                                <th width="15%">Actions</th>
                            </tr>
                            @if(!empty($all_blogs) && count($all_blogs) > 0)
                            @foreach($all_blogs as $key => $blog)
                            <tr>
                                @if(!empty($blog->user))
                                <td>{{$all_blogs->firstItem() + $key}}</td>
                                <td>{{str_limit($blog->blog_title, 30, '...')}}</td>
                                <td>{{str_limit($blog->meta_title, 30, '...')}}</td>
                                <td>{{str_limit(strip_tags($blog->blog_description), 60, '...')}}</td>
                                <td>{{str_limit($blog->meta_description, 60, '...')}}</td>
                                <td>{{$blog->user->first_name}}</td>
                                <td>{{adjust_gmt($blog->created_at)}}</td>
                                <td>{{adjust_gmt($blog->updated_at)}}</td>
                                <td class="text-center">
                                    <input class="custom-checkbox" id="si-checkbox-{{$blog->id}}" type="checkbox" @if(!empty($blog->is_featured)){{"checked=checked"}}@endif value="{{base64_encode($blog->id)}}">
                                </td>
                                <td>
                                    <a href="{{route('admin.editblog',[base64_encode($blog->id)])}}" class="btn btn-info btn-sm" title ="Edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    @if($blog->is_active == '1')
                                    <a href="{{route('admin.blogstatus',[base64_encode($blog->id),base64_encode('0')])}}"  class="btn btn-danger btn-sm" title ="De-activate"><span class="glyphicon glyphicon-remove" aria-hidden="true" ></span></a>
                                    @else
                                    <a href="{{route('admin.blogstatus',[base64_encode($blog->id), base64_encode('1')])}}" class="btn btn-success btn-sm" title ="Activate"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
                                    @endif
                                    <a href="{{route('admin.deleteblog',[base64_encode($blog->id)])}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete ?');" title ="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                </td>
                                @endif
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
                        @if(!empty($all_blogs))
                        {{ $all_blogs->links('vendor.pagination.bootstrap-4') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>    
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.sort-column').on('click',function(){
            var sort = $(this).attr('data-sort');
            var type = $(this).attr('data-type');
            location.href = "?sort=" + sort + "&type=" + type;
        });
    });
</script>
@endsection