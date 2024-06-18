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
                        <div class="col-sm-2 col-md-2 col-xs-4">
                            <div class="datepicker-outer">
                                <div class="custom-datepicker">
                                    <input class="form-control date_field" type="text" id="date_report" name="date_report" readonly value="{{$date_report}}" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" id="btn-add" value="Search" class="btn btn-info">
                        </div>
                        </div>
                    </div>
                     </form>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>Profile</th>
                                <th>Task</th>
                                <th>Value</th>
                            </tr>
                            @if(!empty($reports))
                            @foreach($reports as $key => $report)
                            <tr>
                            <td>{{$report->login}}</td>
                            <td>{{$report->label}}</td>
                            <td>{{$report->value}}</td>
                            </tr>
                            @endforeach
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
<style>
    .bootstrap-select
    {
        max-width: 100%;
    }
</style> 
<script>
     $(document).ready(function() {
        $("#date_report").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate : new Date()
        });
    });
</script> 
@endsection

