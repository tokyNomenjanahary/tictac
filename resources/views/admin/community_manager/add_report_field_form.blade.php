

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
                <form class="form-horizontal" action="{{ route('admin.save-report-field') }}" method="POST" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Task Info</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">Label *</label>
                                    <div class="col-sm-6">
                                        <input type="hidden" name="id" class="form-control" id="id_field" >
                                        <input type="hidden" name="old_label_field" class="form-control" id="old_label_field" >
                                        <input type="text" name="label_field" class="form-control" id="label_field" >
                                        @if ($errors->has("label_field"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("label_field") }}</strong>
                                        </span>
                                        @endif                                    
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-sm-2 control-label">Sla</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="sla" class="form-control" id="sla" >
                                        @if ($errors->has("sla"))
                                        <span class="help-block">
                                            <strong>{{ $errors->first("sla") }}</strong>
                                        </span>
                                        @endif                                    
                                    </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Type *</label>
                                <div class="col-sm-6">
                                    <select name="type_field" id="type_field">
                                        <option value="int(11)">int</option>
                                        <option value="varchar(255)">string</option>
                                    </select>                                   
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Category *</label>
                                <div class="col-sm-6">
                                    <select name="category_field" id="category_field">
                                    @foreach($categories as $key => $category)
                                    <option value="{{$category->id}}">{{$category->label}}</option>
                                    @endforeach
                                    </select>                                   
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Required *</label>
                                <div class="col-sm-6">
                                    <input type="checkbox" checked="" name="required_field" id="required_field"/>                                  
                                </div>
                            </div>
                            <div class="">
                                <div class="col-sm-2"></div>
                                <div class="box-footer col-sm-10">
                                    <a href="" class="btn btn-default">Cancel</a>
                                    <input type="submit" id="btn-add" value="Add" class="btn btn-info">
                                </div>   
                            </div>
                        </div>
                    </div>
                </form>
            </div> 

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>Field name</th>
                                <th>Type</th>
                                <th>Sla</th>
                                <th>Required</th>
                                <th>Category</th>
                                <th>Action</th>
                                
                            </tr>
                            @if(!empty($fields))
                            @foreach($fields as $key => $field)
                            <tr>

                                <td id="field-name-{{$field->id}}">{{$field->title}}</td>
                                <td id="field-type-{{$field->id}}">{{$field->type}}</td>
                                <td id="field-sla-{{$field->id}}">{{$field->sla}}</td>
                                <td id="field-required-{{$field->id}}">@if($field->required==1){{"Yes"}}@else{{"No"}} @endif</td>
                                <td id="field-category-{{$field->id}}" data-id="{{$field->category_id}}">{{$field->label}}</td>
                                <td class="action-td">
                                    <a title="Edit" data-id="{{$field->id}}" class="action-toggle-on edit-field" href="javascript:"><i class="fa fa-pencil"></i></a>
                                    <a title="delete" data-id="{{$field->id}}" class="action-toggle-on delete-field" href="javascript:" data-id=""><i class="fa fa-trash"></i></a>
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
                <h4 class="modal-title" id="myModalLabel">Delete ad?</h4>
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
        $('.edit-field').on('click', function(){
            var key = $(this).attr("data-id");
            var field_name = $('#field-name-' + key).text();
            var type = $('#field-type-' + key).text();
            var required = $('#field-required-' + key).text();
            var sla = $('#field-sla-' + key).text();
            var category = $('#field-category-' + key).attr("data-id");
            $('#id_field').val(key);
            $('#label_field').val(field_name);
            $('#old_label_field').val(field_name);
            $('#type_field').val(type);
            $('#sla').val(sla);
            $('#btn-add').val('Edit');
            $('#category_field').val(category);
            if(required == "Yes") {
                $('#required_field').prop('checked', true);
            } else {
                $('#required_field').prop('checked', false);
            }
        });

        $('.delete-field').on('click', function(){
            var key = $(this).attr("data-id");
            $('#delete-modal').modal('show');
            $('#modal-delete-btn-yes').attr("data-type", "task");
            $('#modal-delete-btn-yes').attr('data-id', key);
        });

        $('#modal-delete-btn-yes').on('click', function() {
            $('#delete-modal').modal('hide');
            if($(this).attr("data-type") == "task") {
              location.href = "?column_delete=" + $(this).attr('data-id');  
            } else {
                $('#reportDataForm').submit();
            }
            
        });
        $('#modal-delete-btn-no').on('click', function() {
            $('#report_delete_id').val("");
            $('#delete-modal').modal('hide');
        });

        $('#btn-add-report').on('click', function() {
            if($(this).text() == "Add" && isExistReportProfil($('#profil_id').val())) {
                alert("this profil has already a report, please choose another profil");
                return;
            }
            var tasks = getCheckedTask();
            $('#report_tasks').val(JSON.stringify(tasks));
            if(tasks.length > 0)
            {
                $('#reportDataForm').submit();
            } else {
                alert("choose the tasks for the report");
            }
        });

    });

    function isExistReportProfil(profil_id)
    {
        var result = false;
        $('.profil-report').each(function(){
            var id = $(this).attr('data-id');
            if(id==profil_id) {
                result = true;
            }
        });
        return result;
    }

    function getCheckedTask()
    {
        var data = [];
        $('.chooseTask').each(function(){
            if($(this).is(":checked")){
                data.push($(this).attr("data-id"));
            }
        });
        return data;
    }

</script>
@endsection