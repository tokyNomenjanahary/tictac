@extends('layouts.adminappinner')

@section('content')
<style type="text/css">
    .filtre-box
    {
        height: 90px;
    }

    .filtre-col
    {
        margin-top: 5px;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
<!--      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>-->
    </section>
    
    <!-- Main content -->
    <section class="content">
        <div class="row">
                <div class="box box-primary filtre-box">
                    <div class="col-sm-2 col-md-2 col-xs-4 filtre-col">
                        <label>Annonces Commnuity</label>
                        <div>
                            <input type="checkbox" @if(!isset($adComunity) || $adComunity) checked="" @endif id="adComunity" name="">
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2 col-xs-4 filtre-col">
                        <label>Date : </label>
                        <div class="datepicker-outer">
                            <div class="custom-datepicker">
                                <input class="form-control date_field" type="text" id="date_report" name="date_report" readonly value="@if(isset($date_report)) {{$date_report}} @else {{date('d/m/Y')}} @endif" placeholder="dd/mm/yyyy">
                            </div>
                        </div>
                    </div>
                     <br>
                     <div style="float: right; display: flex;margin-right: 15px;">
                        <div class="btn-group" role="group" id="btn-group-show" data-show="{{$show}}" aria-label="Basic example" style="display: flex; margin-right: 20px;">
                          <button type="button" class="btn btn-primary @if($show=='D') active @endif" id="filter-day">Jours</button>
                          <button type="button" class="btn btn-primary @if($show=='W') active @endif" id="filter-week">Semaines</button>
                          <button type="button" class="btn btn-primary @if($show=='M') active @endif" id="filter-month">Mois</button>
                        </div>
                        <input class="form-control" type="text" id="daterange" readonly name="daterange" value="{{$date_report_debut}} - {{$date_report_limit}}" />
                        <input type="hidden" id="date_debut" value="{{$date_report_debut}}">
                        <input type="hidden" id="date_limit" value="{{$date_report_limit}}">
                    </div>
                </div>
        </div>
        <!-- Small boxes (Stat box) -->
       
  
        <div id="container-h" style="width:100%; height:550px;"></div>
        <hr>
        <div id="container-2" class="mt-5" style="width:100%; height:550px;"></div>
        <hr>
        <div id="containerRegister" class="mt-5" style="width:100%; height:550px;"></div>

    </section>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
    function dashChartData()
    {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: 'dashboard-chart-data',
            data : {"date_debut" : $('#date_debut').val(), "date_limit" : $('#date_limit').val()},
            type: 'post',
            dataType: 'json'
        }).done(function(result){
        
           
            Highcharts.chart('container-3', {

                title: {
                    text: 'Taux de vente'
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
                    name: 'Taux de vente',
                     data: result.subscription
                },
                {
                    name : "Taux de vue page de vente",
                    data : result.nbViewSubscription
                },
                {
                    name : "Taux de vue page d'achat",
                    data : result.nbViewAchat
                }],

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
   // dashChartData();

    function dashChartData2()
    {
    	$.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: 'dashboard-chart-registration',
            data : {"date_debut" : $('#date_debut').val(), "date_limit" : $('#date_limit').val()},
            type: 'post',
            dataType: 'json'
        }).done(function(result){
           Highcharts.chart('container-2', {

                title: {
                    text: 'Indicators evolution'
                },

                subtitle: {
                    text: 'Bailti'
                },

                yAxis: {
                    tickInterval: 20,
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
                    name: 'User registrations',
                     data: result.userRegistration
                }, {
                    name: 'Ads  by comunity manager',
                    data: result.comunityAds
                }, {
                    name: 'Comment by community',
                    data: result.comment
                }, {
                    name: 'Ads rate',
                    data: result.tauxAds
                }, {
                    name : 'User unique',
                    data : result.userUnique
                }],

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

    function dashChartDatah()
    {
    	$.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: 'dashboard-chart-registrationh',
            data : {"date_debut" : $('#date_debut').val(), "date_limit" : $('#date_limit').val(), "date_report" : $('#date_report').val()},
            type: 'post',
            dataType: 'json'
        }).done(function(result){
           Highcharts.chart('container-h', {

                title: {
                    text: 'Comment  by comunity manager'
                },

                subtitle: {
                    text: 'Bailti'
                },

                yAxis: {
                    tickInterval: 20,
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
                    name: 'Comment by comunity manager',
                     data: result.cadcom
                }],

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
    dashChartDatah();
    dashChartData2();

    function dashChartRegisterData()
    {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: 'dashboard-chart-register',
            data : {"date_debut" : $('#date_debut').val(), "date_limit" : $('#date_limit').val()},
            type: 'post',
            dataType: 'json'
        }).done(function(result){
           Highcharts.chart('containerRegister', {

                title: {
                    text: 'Indicators register form'
                },

                subtitle: {
                    text: 'Bailti'
                },

                tooltip: {
                    valueDecimals: 2
                },

                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: ''
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

                series: [
                {
                    name: '1er formulaire d\'inscription',
                    data: result.formulaire_inscription1
                },
                {
                    name: '2ème formulaire d\'inscription',
                    data: result.formulaire_inscription2
                },
                {
                    name: 'Formulaire profil de recherche',
                    data: result.formulaire_inscription3
                },
                {
                    name: 'Adresse d\'une annonce',
                    data: result.formulaire_inscription4
                },
                {
                    name: 'Création express d\'une annonce',
                    data: result.formulaire_inscription5
                },
                {
                    name: 'Taux de validation total des forms',
                    data: result.taux_validation_total
                },
                {
                    name: 'Taux de validation de la dernier formulaire',
                    data: result.taux_validation_totalF3
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
    dashChartRegisterData();

    function dashVilleChartData()
    {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: 'dashboard-chart-data-ville',
            data : {"date_debut" : $('#date_debut').val(), "date_limit" : $('#date_limit').val()},
            type: 'post',
            dataType: 'json'
        }).done(function(result){
           Highcharts.chart('containerVille', {

                title: {
                    text: 'Ville inscription'
                },

                subtitle: {
                    text: 'Tictachouse'
                },

                yAxis: {
                    tickInterval: 1,
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

                series: result.data,

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
    //dashVilleChartData();

    function dashComunityChartData()
    {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: 'dashboard-chart-comunity-data',
            data : {"date_debut" : $('#date_debut').val(), "date_limit" : $('#date_limit').val()},
            type: 'post',
            dataType: 'json'
        }).done(function(result){
           Highcharts.chart('container2', {

                title: {
                    text: 'Comunity manager Ad Creation'
                },

                subtitle: {
                    text: 'Tictachouse'
                },

                yAxis: {

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

                series: result.data,

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

    /*function dashpubClickData()
    {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: 'dashboard-chart-pub-click',
            data : {"date_debut" : $('#date_debut').val(), "date_limit" : $('#date_limit').val()},
            type: 'post',
            dataType: 'json'
        }).done(function(result){
           Highcharts.chart('container-pub', {

                title: {
                    text: 'Click on Pub'
                },

                subtitle: {
                    text: 'Tictachouse'
                },

                yAxis: {

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

                series: result.data,

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
    dashpubClickData();*/
    //dashComunityChartData();
</script>
<script>
     $(document).ready(function() {
        $(function() {
          $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            locale: {
              format: 'DD/MM/YYYY'
            }
          }, function(start, end, label) {
                //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                var mydate = new Date();
                const month = mydate.getMonth()+1;
                location.href = "?debut=" + start.format('YYYY-MM-DD') + '&limit=' + end.format('YYYY-MM-DD') + "&adComunity=" + isAdsComunity();;
          });
        });
        $("#date_report").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate : new Date()
        });

        $("#date_report").on('change', function(){
            location.href = "?date_report=" + $(this).val() + "&adComunity=" + isAdsComunity();
        });

        $("#filter-day").on('click', function(){
            var mydate = new Date();
            const month = mydate.getMonth()+1;
            location.href = "?date_report=" + mydate.getDate() + '/' + (month < 10 ? '0' + month : month)  + '/' + mydate.getFullYear()+"&show=D&adComunity=" + isAdsComunity();
        });

        $("#filter-week").on('click', function(){
            var mydate = new Date();
            const month = mydate.getMonth()+1;
            location.href = "?show=W&adComunity=" + isAdsComunity();
        }); 

        $("#filter-month").on('click', function(){
            var mydate = new Date();
            const month = mydate.getMonth()+1;
            location.href = "?date_report=" + mydate.getDate() + '/' + (month < 10 ? '0' + month : month)  + '/' + mydate.getFullYear()+"&show=M&adComunity=" + isAdsComunity();
        });

        $("#adComunity").on('change', function(){
            var queryParams = new URLSearchParams(window.location.search);

            // Set new or modify existing parameter value. 
            queryParams.set("adComunity", isAdsComunity());

            history.pushState(null, null, "?"+queryParams.toString());
            location.href = window.location.search;
        });
         

        function isAdsComunity()
        {
            if($('#adComunity').is(':checked')) {
                return true;
            } else return false;
        }
    });
</script>
@endsection
