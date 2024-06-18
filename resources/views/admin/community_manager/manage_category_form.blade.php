

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if(!empty($id)){{'Edit Report'}}@else{{'Daily report field'}}@endif
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
                            <h3 class="box-title">Report Info</h3>
                        </div>
                        <div class="box-body">
                             <form id="categoryForm" class="form-horizontal" action="{{ route('admin.save-category')}}" method="POST" role="form">
                             {{ csrf_field() }}
                            <input type="hidden" id="id" value="@if(isset($category)) $category->id @endif" name="id"/>
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">Label *</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="label" class="form-control" id="label_category" placeholder="Label" value="@if(isset($category)) $category->label @endif">
                                        @if ($errors->has("label"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("label") }}</strong>
                                        </span>
                                        @endif                                   
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
                                <th>Label</th>
                                <th>Action</th>
                            </tr>
                            @if(isset($categories) && !empty($categories))
                            @foreach($categories as $key => $cat)
                            <tr>

                                <td id="cat-{{$cat->id}}" class="profil-report">{{$cat->label}}</td>
                                <td class="action-td">
                                    <a title="Edit" data-id="{{$cat->id}}" class="action-toggle-on edit-categ" href="javascript:"><i class="fa fa-pencil"></i></a>
                                    <a title="delete" data-id="{{$cat->id}}" class="action-toggle-on delete-categ" href="javascript:" data-id=""><i class="fa fa-trash"></i></a>
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
            $('#label_category').val($('#cat-' + id).text());
            $("#id").val(id);
        });

        $(".delete-categ").on('click', function(){
            var id = $(this).attr('data-id');
            $('#modal-delete-btn-yes').attr("data-id", id);
            $('#delete-modal').modal('show');
        });

    });
</script>
@endsection