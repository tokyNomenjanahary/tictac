@extends('layouts.adminappinner')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Indicators
      </h1>
<!--      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>-->
    </section>
    
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
                <div class="box box-primary filtre-box">
                    <div class="col-sm-2 col-md-2 col-xs-4 filtre-col">
                        <label>Date : </label>
                        <div class="datepicker-outer">
                            <div class="custom-datepicker">
                                <input class="form-control date_field" type="text" id="date_report" name="date_report" readonly value="@if(isset($date_report)) {{$date_report}} @else {{date('d/m/Y')}} @endif" placeholder="dd/mm/yyyy">
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{$unique_users}}</h3>

                        <p>Visiteurs</p>
                        <sub>Unique</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{route('admin.adList')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{$tauxRegistration}}</h3>

                        <p>Taux d'inscription</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{$tauxAds}}</h3>

                        <p>Taux de création d'annonce</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <!-- ./col -->
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{$ads_count_sc_1}}</h3>

                        <p>Logement entier</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{route('admin.adList')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{$ads_count_sc_2}}</h3>

                        <p>Partager un logement</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{route('admin.adList')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-xs-3">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>{{$users_sc1}}</h3>

                        <p>Inscription</p>
                        <sub>Cherche à louer un logement</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-3">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>{{$users_sc2}}</h3>

                        <p>Inscription</p>
                        <sub>Cherche un logment à partager</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-3">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>{{$users_sc3}}</h3>

                        <p>Inscription</p>
                        <sub>Louer son logement</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-3">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>{{$users_sc4}}</h3>

                        <p>Inscription</p>
                        <sub>Partager son logement</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-3">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>{{$users_sc5}}</h3>

                        <p>Inscription</p>
                        <sub>Monter une colocation</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-xs-4">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Nombre vue: {{$showForm1}}</p>
                        <p>Nombre validation: {{$validateForm1}}</p>
                        <h3>{{$tauxForm1}}</h3>

                        <p>1er formulaire d'inscription</p>
                        <p>/connexion-popup</p>
                        <sub>Taux de validation</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-4">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Nombre vue: {{$showForm2}}</p>
                        <p>Nombre validation: {{$validateForm2}}</p>
                        <h3>{{$tauxForm2}}</h3>

                        <p>2ème formulaire d'inscription</p>
                        <p>/creer-compte/etape/2</p>
                        <sub>Taux de validation</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-4">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Nombre vue: {{$showForm3}}</p>
                        <p>Nombre validation: {{$validateForm3}}</p>
                        <h3>{{$tauxForm3}}</h3>

                        <p>Formulaire profil de recherche</p>
                        <p>/inscritnormal3</p>
                        <sub>Taux de validation</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-4">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Nombre vue: {{$showForm4}}</p>
                        <p>Nombre validation: {{$validateForm4}}</p>
                        <h3>{{$tauxForm4}}</h3>

                        <p>Adresse d'une annonce</p>
                        <sub>Taux de validation</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-xs-4">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Nombre vue: {{$showForm5}}</p>
                        <p>Nombre validation: {{$validateForm5}}</p>
                        <h3>{{$tauxForm5}}</h3>

                        <p>Création express d'une annonce</p>
                        <p>/publiez-annonce (les scénarios)</p>
                        <sub>Taux de validation</sub>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 show-message">
                <div class="box box-primary">

                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">

                            <tr>

                                <th>Url</th>                                
                                <th>Nombre de visite</th>
                            </tr>
                            @if(!empty($urls))
                            @foreach($urls as $key => $url)
                            <tr>
                                <td>{{$url->url}}</td>
                                <td>{{$url->nb}}</td>
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
<script>
     $(document).ready(function() {
        $("#date_report").datepicker({
            format: "dd/mm/yyyy",
            minDate: "-0d",
            setDate : new Date()
        });

        $("#date_report").on('change', function(){
            location.href = "?date_report=" + $(this).val();
        });
    });
</script> 
@endsection