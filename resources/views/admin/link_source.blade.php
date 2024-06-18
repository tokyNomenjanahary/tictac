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
            Lien source page de Vente
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
                                <label>Payment</label>
                                <div>
                                    <input @if($search_query['payment']==1) checked="" @endif type="checkbox" id="payment-chk">
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2 col-xs-4 filtre-col" style="margin-bottom: 20px;">
                                <label>Date : </label>

                                <input class="form-control" type="text" id="daterange" readonly name="daterange" @if(isset($start_date)) value="{{$start_date}}-{{$end_date}}" @endif />
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
                        <div class="box-body table-responsive no-padding db-table-outer">
                            <table class="table table-hover">
                                <tr>
                                    <th>S. No.</th>
                                    <th>Device</th>
                                    <th> 
                                        User
                                    </th>
                                    <th> 
                                        Lien Source
                                    </th>
                                    <th>
                                        Comunity
                                    </th>
                                    <th> 
                                        Action
                                    </th>
                                    <th>Date</th>
                                    <th>Referer</th>
                                    <th>First Page</th>
                                </tr>
                                @if(!empty($users))
                                @foreach($users as $key => $user)
                                <tr>
                                    <td>{{$users->firstItem() + $key}}</td>
                                    <td>{{deviceName($user->device)}}</td>
                                    <td><a href="{{url(getConfig('admin_prefix') . '/user_profile/'.base64_encode($user->user_id))}}">{{$user->first_name}}</a></td>
                                    <td><a href="{{$user->link}}">{{$user->link}}</a></td>
                                    <td>
                                        @if(!is_null(getComunityIdFromUrl($user->link))) 
                                        Yes
                                        @else
                                        No
                                        @endif
                                    </td>
                                    <td>{{$user->action}}</td>
                                    <td>{{$user->date}}</td>
                                    <?php $refInfos = getPageReferer($user->user_id, $user->date); ?>
                                    @if(!is_null($refInfos))
                                    <td><a href="{{$refInfos['referer']}}">{{$refInfos['referer']}}</a></td>
                                    <td><a href="{{$refInfos['first_page']}}">{{$refInfos['first_page']}}</a></td>
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr>{{'No record found'}}</tr>
                                @endif
                            </table>
                        </div>
                        <div class="pull-right">
                            @if($users) 
                            @if(!empty($search_query)) 
                            {{ $users->appends($search_query)->links('vendor.pagination.bootstrap-4') }}
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
       
        $("#date_report").on('change', function(){
            executeFiltre();
        });
        $("#payment-chk").on('change', function(){
            executeFiltre();
        });

        function executeFiltre()
        {
            var drp = $('#daterange').data('daterangepicker');
            console.log(drp.startDate.format('DD/MM/YYYY'));
            var start = drp.startDate.format('YYYY-MM-DD');
            var end = drp.endDate.format('YYYY-MM-DD');
            var params = "?start=" + start + "&end=" + end;
            if($("#payment-chk").is(":checked")) {
                params += "&payment=1";
            } else {
                params += "&payment=0";
            }
            location.href = params;
        }
    });


</script>

@endsection