@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
<!--    <link href="{{ asset('css/admin/datatables.net-bs/dataTables.bootstrap.min.css') }}" rel="stylesheet">-->
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Aproove/Disaproove Details
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

                                <input class="filtre form-control" type="text" id="daterange" readonly name="daterange" @if(isset($start_date)) value="{{$start_date}}-{{$end_date}}" @endif />
                                <input type="hidden" id="date_debut" @if(isset($start_date)) value="{{$start_date}}" @else 
                                value="{{date('d/m/Y')}}" @endif>
                                <input type="hidden" id="date_limit" @if(isset($end_date)) value="{{$end_date}}" @else 
                                value="{{date('d/m/Y')}}" @endif>
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="box box-primary">
                    
                    <!-- /.box-header -->
                        <input type="hidden" id="short" name="">
                        <input type="hidden" id="desc" name="">
                        <div class="box-body table-responsive no-padding db-table-outer">
                            <table class="table table-hover">
                                <tr>
                                    <th>Ad</th>
                                    <th> 
                                        User
                                    </th>
                                    <th> 
                                       Type
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                    
                                </tr>
                                @if(!empty($data))
                                @foreach($data as $key => $d)
                                <tr>
                                    <td><a href="{{adUrl($d->ad_id)}}">{{$d->title}}</a></td>
                                    <td>{{$d->first_name}}</td>
                                    <td>@if($d->type == 0) Désapprouve @else Approuve @endif</td>
                                    <td>{{$d->date}}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>{{'No record found'}}</tr>
                                @endif
                            </table>
                        </div>
                        <div class="pull-right">
                            @if($data) 
                            @if(!empty($search_query)) 
                            {{ $data->appends($search_query)->links('vendor.pagination.bootstrap-4') }}
                            @else
                            {{ $data->links('vendor.pagination.bootstrap-4') }}
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    </section>
</div>    
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
    .input-edit
    {
        width: 100%;
    }
    .action-edit-button
    {
        display: none;
    }
    .full
    {
        width: 100%;
    }

    .filtre-col
    {
        margin-left: 10px;
    }

    .Total
    {    
        float: right;
        margin-right: 20px;
        font-size: 25px;
        font-weight: bold;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('#daterange').daterangepicker({
            opens: 'left',
            locale: {
              format: 'DD/MM/YYYY'
            },
            startDate : $('#date_debut').val(),
            endDate : $('#date_limit').val()
          }, function(start, end, label) {
                executeFiltre();
                
          });
       
        $(".filtre").on('change', function(){
            executeFiltre();
        });

        function executeFiltre()
        {
            var drp = $('#daterange').data('daterangepicker');
            console.log(drp.startDate.format('DD/MM/YYYY'));
            var start = drp.startDate.format('YYYY-MM-DD');
            var end = drp.endDate.format('YYYY-MM-DD');
            var status = $('#sel-status').val();
            var user = $('#sel-user').val();
            var params = "?start=" + start + "&end=" + end;

            location.href = params;
        }
    });

</script>
@endsection