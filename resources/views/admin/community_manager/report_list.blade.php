@extends('layouts.adminappinner')
@push('scripts')
<script src="{{ asset('js/admin/manageusers.js') }}"></script>
@endpush
<style>
    .ad-descripion{
        max-width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Report
            <small>Comunity manager Daily report</small>
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
            <div class="col-xs-12 show-message">
                <div class="box box-primary">
                <form id="searchForm" method="GET">
                    <div class="box-header">
                        <div class="row">
                        <input type="hidden" id="report_id" name="report_id">
                        <input type="hidden" id="sort" name="sort">
                        <input type="hidden" id="sort_direction" name="sort_direction">
                        <div class="col-sm-2 col-md-1 col-xs-4">
                            <label class="col-sm-4 control-label">Hors Sla</label>
                            <div class="col-sm-1"><input style="margin-top: 13px; margin-left: 15px;" type="checkbox" @if($hors_sla) checked="" @endif name="hors_sla"></div>
                        </div>
                        <div class="col-sm-2 col-md-2 col-xs-4">
                            <select id="task" name="task" class="filter selectpicker">
                                <option value="">Filter by task</option>
                                @foreach($tasks as $key => $task)
                                <option value="{{$task->id}}" @if($task->id == $tache) selected @endif>{{$task->label}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1 col-md-1 col-xs-2">
                            <div class="datepicker-outer">
                                <div class="custom-datepicker">
                                    <input class="form-control date_field" type="text" id="date_report" name="date_report" readonly value="{{$date_report}}" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1 col-md-1 col-xs-2">
                            <input type="submit" id="btn-add" value="Search" class="btn btn-info">
                        </div>
                        </div>
                    </div>
                     </form>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th sort-data="first_name" class="entete-tri @if($sort=='first_name'){{$sort_direction}}@endif">
                                    <a href="javascript:">User</a>
                                </th>
                                <th sort-data="login" class="entete-tri @if($sort=='login'){{$sort_direction}}@endif">
                                    <a href="javascript:">Profile</a>
                                </th>
                                <th sort-data="label" class="entete-tri @if($sort=='label'){{$sort_direction}}@endif">
                                    <a href="javascript:">Tache</a>
                                </th>
                                <th sort-data="value" class="entete-tri @if($sort=='value'){{$sort_direction}}@endif">
                                    <a href="javascript:">Value</a>
                                </th>
                                <th>Action</th>
                            </tr>
                            @if(!empty($reportsList))
                            <tr>
                            @foreach($reportsList as $key => $report)
                            <tr>
                            <td>{{$report->first_name}}/{{$report->email}}</td>
                            <td>{{$report->login}}</td>
                            <td>{{$report->label}}</td>
                            <td>{{$report->value}}</td>
                            <td class="action-td">
                                @if($report->user_id == getUserAdmin()->id)
                                 <a title="Edit" class="action-toggle-on" href="{{route('admin.new_daily_report') . '?id=' . $report->id_report}}"><i class="fa fa-pencil"></i></a>
                                  <a title="delete" class="action-toggle-on delete" href="javascript:" data-id="{{$report->id_report}}"><i class="fa fa-trash"></i></a>
                                @endif
                            </td>
                            </tr>
                            @endforeach
                            </tr>
                            @else
                            <tr><td colspan="{{count($entetes)}}">{{'No record found'}}</td></tr>
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
                <button type="button" class="btn btn-primary" id="modal-delete-btn-yes">Yes</button>
                <button type="button" class="btn btn-default" id="modal-delete-btn-no">No</button>
            </div>
        </div>
    </div>
</div>
<style>
    .bootstrap-select
    {
        max-width: 100%;
    }

    .entete-tri
    {
        padding-left : 20px !important;
    }
    .entete-tri.up
    {
        background-image: url('../img/pull-up-triangle.png') !important;
        background-repeat: no-repeat !important;
        background-size: 10px 10px !important;
        background-position: 5px 50% !important;
    }

    .entete-tri.down
    {
        background-image: url('../img/pull-down-triangle.png') !important;
        background-repeat: no-repeat !important;
        background-size: 10px 10px !important;
        background-position: 5px 50% !important;
    }
</style> 
<script>
    $(document).ready(function() {
        $("#date_report").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate : new Date()
        });
        $('.delete').on('click', function() {
            $('#delete-modal').modal('show');
            $('#modal-delete-btn-yes').attr('data-id', $(this).attr('data-id'));
        });
        $('#modal-delete-btn-yes').on('click', function() {
            $('#delete-modal').modal('hide');
            $('#report_id').val($(this).attr('data-id'));
            $('#searchForm').submit();
        });
        $('#modal-delete-btn-no').on('click', function() {
            $('#delete-modal').modal('hide');
        });
        $('#userSelect').on('change', function() {
            updateProfilesList();
        });

        $('.entete-tri').on('click', function(){
            var sort = $(this).attr("sort-data");
            $('#sort').val(sort);
            if(!$(this).hasClass("up") && $(this).hasClass("down"))
            {
               $('.entete-tri').removeClass("up");
               $('.entete-tri').removeClass("down");
               $(this).addClass("up"); 
               $('#sort_direction').val("asc");
            } else if($(this).hasClass('up')) {
               $('.entete-tri').removeClass("up");
               $('.entete-tri').removeClass("down");
               $(this).addClass("down");
                $('#sort_direction').val("desc");
            } else {
               $('.entete-tri').removeClass("up");
               $('.entete-tri').removeClass("down");
               $(this).addClass("up");
               $('#sort_direction').val("asc");
            }

            $('#searchForm').submit();
        });

    });

    function updateProfilesList()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: '',
            data : {id_new_user : $('#userSelect').val()},
            dataType: 'html',
            success: function (data) {
                if(data != "") {
                    $('#profileSelect').html(data);
                    $('.selectpicker').selectpicker('refresh')
                }     
            }
        });
    }
    
</script> 
@endsection

