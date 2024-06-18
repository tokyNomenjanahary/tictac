@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
<!--    <link href="{{ asset('css/admin/datatables.net-bs/dataTables.bootstrap.min.css') }}" rel="stylesheet">-->
@endpush
<!-- Push a script dynamically from a view -->
@push('scripts')
<!--    <script src="{{ asset('js/admin/datatables.net/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/admin/datatables.net-bs/dataTables.bootstrap.min.js') }}"></script>-->

<script src="{{ asset('js/admin/manageusers.js') }}"></script>
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Tracking click sur les emails Toctoc
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
        <div class="row" style="background: white;">
            <div class="col-xs-12">
                    <div class="row">
                        <div class="box box-primary filtre-box">
                            <div class="col-sm-2 col-md-2 col-xs-4 filtre-col" style="margin-bottom: 20px;">
                                <label>Date : </label>
                                <div class="datepicker-outer">
                                    <div class="custom-datepicker">
                                        <input class="form-control date_field" type="text" id="date_report" name="date" readonly value="@if(isset($date_report)) {{$date_report}} @else {{date('d/m/Y')}} @endif" placeholder="dd/mm/yyyy">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2 col-xs-4 filtre-col" style="margin-bottom: 20px;">
                                <label>Total : </label>
                                <div>
                                    <input class="form-control" type="text" disabled="" name="" value="{{$users->count()}}">
                                </div>
                            </div>
                             
                        </div>
                </div>
                <div class="row">
                    <div class="box box-primary">
                    <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding db-table-outer">
                            <table class="table table-hover">
                                <tr>
                                    <th>S. No.</th>
                                    <th> 
                                        User
                                    </th>
                                    <th> 
                                        Date
                                    </th>
                                </tr>
                                @if(!empty($users))
                                @foreach($users as $key => $user)
                                <tr>
                                    <td>{{$users->firstItem() + $key}}</td>
                                    <td><a href="{{url(getConfig('admin_prefix') . '/user_profile/'.base64_encode($user->user_id))}}">{{$user->first_name}}@if(!empty($user->last_name)){{' '. $user->last_name}}@endif</a></td>
                                    <td>{{date('d M Y - H:i:s', strtotime(adjust_gmt($user->date)))}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>{{'No record found'}}</tr>
                                @endif
                            </table>
                        </div>
                        <div class="pull-right">
                            @if($users) 
                            @if(!empty($search_date)) 
                            {{ $users->appends($search_date)->links('vendor.pagination.bootstrap-4') }}
                            @else
                            {{ $users->links('vendor.pagination.bootstrap-4') }}
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
</div>    
<script type="text/javascript">
    $(document).ready(function() {
        $("#date_report").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate : new Date()
        });
        $("#date_report").on('change', function(){
            executeFiltre();
        });

        function executeFiltre()
        {
            var params = "?date=" + $("#date_report").val();
            location.href = params;
        }
    });


</script>
@endsection