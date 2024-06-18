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
            Taux de statut vérifié
        </h1>
    </section>
    <!-- Main content -->
    {{-- main --}}
    <section class="content">
        @if ($message = Session::get('error'))
        <div class="alert alert-danger fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $message }}
        </div>

        @endif
        <div class="alert-status">

        </div>
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

                             <div class="col-sm-2 col-md-2 col-xs-4 filtre-col" style="margin-bottom: 20px;">
                                <label>User : </label>

                               <select id="sel-user" class="filtre selectpicker">
                                    <option value="0"></option>
                                    @foreach($users as $user)
                                    <option @if(isset($currentUser) && $currentUser == $user->id) selected @endif value="{{$user->id}}">{{$user->first_name . " " . $user->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2 col-md-2 col-xs-4 filtre-col" style="margin-bottom: 20px;">
                                <label>Status : </label>

                                <select id="sel-status" class="filtre selectpicker">
                                    <option value="0"></option>
                                    <option  @if(isset($status) && $status == "Visible") selected @endif value="Visible">Visible</option>
                                    <option @if(isset($status) && $status == "En attente de validation") selected @endif value="En attente de validation">En attente de validation</option>
                                </select>
                            </div>
                             
                        </div>
                    </div>
                    
                    <div class="row">
                    
                        <div style="margin-bottom: 20px;">                           
                            <div >
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filtre status
                                    <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="about-us">
                                    <li><a href="{{ route('admin.pub_community_list_check') }}">tous</a></li>
                                    <li><a href="{{ route('admin.listStatActive') }}?start={{ date_format(date_create(implode('-',array_reverse(explode("/",$start_date)))), 'Y-m-d') }}&end={{ date_format(date_create(implode('-',array_reverse(explode("/",$end_date)))), 'Y-m-d') }}">status vérifié</a></li>
                                    <li><a href="{{ route('admin.listStatRouge') }}?start={{ date_format(date_create(implode('-',array_reverse(explode("/",$start_date)))), 'Y-m-d') }}&end={{ date_format(date_create(implode('-',array_reverse(explode("/",$end_date)))), 'Y-m-d') }}">status rouge</a></li>
                                    <li><a href="{{ route('admin.courbeStatActive') }}?start={{ date_format(date_create(implode('-',array_reverse(explode("/",$start_date)))), 'Y-m-d') }}&end={{ date_format(date_create(implode('-',array_reverse(explode("/",$end_date)))), 'Y-m-d') }}">courbe taux status vérifié</a></li>
                                    </ul>
                                    </div>
                            </div>
                           
                        </div>
                </div>
                <div class="row">
                    <hr>
                    <div id="container-3" style="width:100%; height:550px;"></div>
                </div>
                
            </div>
        </div>
        
    </section>
</div>    
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
    .input-edit
    {
        width: 100%;
    }
    .fa {
  font-size: 25px;
  margin-left: 20px;

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
    margin-right: 11px;
    font-size: 16px;
    font-weight: bold;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {


        function dashChartData()
    {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: 'community-chart-data-status',
            data : {"date_debut" : $('#date_debut').val(), "date_limit" : $('#date_limit').val(),"user":$('#sel-user').val(),"status":$('#sel-status').val()},
            type: 'post',
            dataType: 'json'
        }).done(function(result){
        
           
            Highcharts.chart('container-3', {

                title: {
                    text: 'Taux de status vérifié'
                },

                subtitle: {
                    text: 'Bailti'
                },

                yAxis: {
                    tickInterval: 5,
                    title: {
                        text: 'Number'
                    }
                },
                 xAxis: {
                        categories: result.xAxis
                    },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    series: {
                        label: {
                            connectorAllowed: false
                        }
                    }
                },

                series: [{
                    name: 'taux de status vérifié',
                     data: result.statut
                }
               ],

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }

            });
            
        }).fail(function (jqXHR, ajaxOptions, thrownError){
             
        });
    }
    dashChartData();


        $('#btn-save-pub').on('click', function(){
            $('#form-edit').submit();
        });
        $(".ko-classe").on("click", function(){
        var id = $(this).attr("id");
        var etat = -1;
        setStatu(etat, id);
        });

        $(".ok-classe").on("click", function(){
        var id = $(this).attr("id");
        var etat = 1;
        setStatu(etat, id);
        });
        $('.edit-item').on('click', function(){
            var id = $(this).attr('data-id');
            var lien = $('#lien-' + id).text();
            var texte = $('#texte-' + id).text();
            var status = $('#status-' + id).text();
            var login = $('#login-' + id).text();
            var mdp = $('#mdp-' + id).text();
            var proxy = $('#lien-' + id).text();
            $('#id-edit').val(id);
            $('#lien-edit').val(lien);
            $('#texte-edit').val(texte);
            $('#status-edit').val(status);
            $('#login-edit').val(login);
            $('#mdp-edit').val(mdp);
            $('#proxy-edit').val(proxy);
            $('#edit-modal').modal('show');
        });
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
        $(".stati").on('change', function(){
            executeFiltre();
        });

        $('.col-short').on('click', function(){
            var short = $(this).attr("data-sort");
            var type = $(this).attr("data-type-sort");
            $('#short').val(short);
            $('#desc').val(type);
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
            if(user != 0) {
                params += '&user=' + user;
            }

            if(status != 0) {
                params += '&status=' + status;
            }

            location.href = params;
        }

        function setStatu(etat, id) {

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: '/admin2021/activeDeactiveAds',
            type: "post",
            datatype: "json",
            data: {"AdsId": id, "status": etat},
            beforeSend: function (){
               // $(".loader-icon").show();
            }
        }).done(function (data){
           if(etat == -1){
            $("#statu-" + id).empty().html('<a title="Enable" class="action-toggle-off ko-classe" id="'+ id +'" ><i class="fa fa-circle"></i></a> <a title="Enable" class="action-toggle-on ok-classe" id="'+ id +'"><i class="fa fa-circle-o"></i></a>');
            $('.alert-status').empty().html('<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>'+data.msg+'</div>');

           }else{
            $("#statu-" + id).empty().html('<a title="Enable" class="action-toggle-off ko-classe" id="'+ id +'" ><i class="fa fa-circle-o"></i></a> <a title="Enable" class="action-toggle-on ok-classe" id="'+ id +'"><i class="fa fa-circle"></i></a>');
            $('.alert-status').empty().html('<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>'+data.msg+'</div>');
               
           }
        $(".ko-classe").on("click", function(){
        var id = $(this).attr("id");
        var etat = -1;
        setStatu(etat, id);
        });

        $(".ok-classe").on("click", function(){
        var id = $(this).attr("id");
        var etat = 1;
        setStatu(etat, id);
        });
            

        }).fail(function (jqXHR, ajaxOptions, thrownError){
            //alert('No response from server');
        });
           
        }
        
    });

</script>
@endsection