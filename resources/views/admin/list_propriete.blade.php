@extends('layouts.adminappinner')

@section('content')
                  <?php
                    $message = Session::get('message');
                    $message1 = Session::get('message1');
                   ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Variable domaines
      </h1>
    </section>
    
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->

        <!-- date -->

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

        <!-- fin date -->
        <div class="row text-center " style="border: 2px solid gray;">
            <h2 class="text-center"> Tous les proprietes</h2>
        </div>
        <br>
        @if ($message)
             <p class="alert alert-danger">
                <?php
                    echo $message;
                    Session::put('message', null);
                ?>
            </p>
        @endif
        @if ($message1)
             <p class="alert alert-info">
                <?php
                    echo $message1;
                    Session::put('message1', null);
                ?>
            </p>
        @endif
          <br>  
        <div class="row" style="margin: top 20px ;">
            <div class="col-lg-offset-2 col-lg-8" style="border:2px solid gray;">
            <table class="table table-hover text-center">
                <tr>
                    <th style="font-size:20px;">valeur</th>
                    <th style="font-size:20px;">action</th>
                </tr>
                
            @foreach ($props as $prop )
                <tr style="height: 20px;">
                  <td style="color:blue;font-weight:bold;font-size:25px;">{{$prop->nom}}</td>
                  <td><button class="btn btn-info" style="margin-right:5px;">
                    <a href="{{route('editer_propriete', ['id' => $prop->id ])}}" style="color:white;" id="delete">Modifier</a></button>
                    <button class="btn btn-danger" onclick="return confirm('êtes vous sûr de vouloir supprimer cet élément ?')"><a href="{{ route('delete_property',['id' => $prop->id]) }}"style="color:white;"  id="delete">Supprimer</a></button></td>
                </tr>
            @endforeach
            </table>
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