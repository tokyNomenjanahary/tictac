

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Profile
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Profile info</h3>
                        </div>
                        <div class="box-body">
                             <form id="categoryForm" class="form-horizontal" action="{{ route('admin.save-profile')}}" method="POST" role="form">
                             {{ csrf_field() }}
                            <input type="hidden" id="id" value="" name="id"/>
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">Login *</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="login" class="form-control" id="login_profile" placeholder="Login" value="">
                                        @if ($errors->has("login"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("label") }}</strong>
                                        </span>
                                        @endif                                   
                                    </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Category *</label>
                                <div class="col-sm-6">
                                    <select name="category[]" class="selectpicker" id="category_field" multiple>
                                    @foreach($categories as $key => $category) 
                                    <option @if($key == 0) selected @endif value="{{$category->id}}">{{$category->label}}</option>
                                    @endforeach
                                    </select>                                   
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">User *</label>
                                <div class="col-sm-6">
                                    <select name="user" id="user_field">
                                    @foreach($users as $key => $user)
                                    <option value="{{$user->id}}">{{$user->email}}/{{$user->first_name}}</option>
                                    @endforeach
                                    </select>                                   
                                </div>
                            </div>
                            <div class="">
                                <div class="col-sm-2"></div>
                                <div class="box-footer col-sm-10">
                                    <a href="" class="btn btn-default">Cancel</a>
                                    <input type="submit" id="btn-add" value="Add" class="btn btn-info">
                                </div>   
                            </div>
                            </form>
                        </div>
                    </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>Login</th>
                                <th>User</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                            @if(isset($profiles) && count($profiles) > 0)
                            @foreach($profiles as $key => $profile)
                            <tr>
                                <td id="profile-{{$profile->id}}" class="profil-report">{{$profile->login}}</td>
                                <td id="profile-user-{{$profile->id}}" class="profil-report" data-id="{{$profile->user}}">{{$profile->email}}/{{$profile->first_name}}</td>
                                <td id="profile-category-{{$profile->id}}" data-id="{{json_encode(unserialize($profile->category))}}" class="profil-report">
                                    <select id="select">
                                    @foreach($profile->categories as $key2 => $categ)
                                        @if(!is_null($categ))
                                        <option value="{{$categ->id}}">
                                        {{$categ->label}}
                                        </option>
                                        @endif
                                    @endforeach
                                    </select>
                                </td>
                                <td class="action-td">
                                    <a title="Edit" data-id="{{$profile->id}}" class="action-toggle-on edit-categ" href="javascript:"><i class="fa fa-pencil"></i></a>
                                    <a title="delete" data-id="{{$profile->id}}" class="action-toggle-on delete-categ" href="javascript:" data-id=""><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="8">{{'No record found'}}</td></tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" data-type="" class="btn btn-primary" id="modal-delete-btn-yes">Yes</button>
                <button type="button" class="btn btn-default" id="modal-delete-btn-no">No</button>
            </div>
        </div>
    </div>
</div> 
<script>
    $(document).ready(function(){

        $('#modal-delete-btn-yes').on('click', function() {
            location.href = "?id=" + $(this).attr('data-id'); 
        });
        $('#modal-delete-btn-no').on('click', function() {
            $('#delete-modal').modal('hide');
        });

        $('.edit-categ').on('click', function(){
            $('#btn-add').val('Edit');
            var id = $(this).attr('data-id');
            $('#login_profile').val($('#profile-' + id).text());
            $("#id").val(id);
            $('#category_field').val(JSON.parse($("#profile-category-" + id).attr("data-id")));
            $('.selectpicker').selectpicker('refresh');
            $('#user_field').val($("#profile-user-" + id).attr("data-id"));
        });

        $(".delete-categ").on('click', function(){
            var id = $(this).attr('data-id');
            $('#modal-delete-btn-yes').attr("data-id", id);
            $('#delete-modal').modal('show');
        });

    });
</script>
@endsection