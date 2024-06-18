@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
<!--    <link href="{{ asset('css/admin/datatables.net-bs/dataTables.bootstrap.min.css') }}" rel="stylesheet">-->
@endpush

@section('content')

<div class="content-wrapper">
  <section class="content">
  <div class="container">
<div class="row">
    <div class="col-sm-12">
        <h1 class="display-3">MAINTENANCE</h1>
        <div>
     
        </div>     
        <form method="post" action="{{ route('admin.updatemailmaintenance') }} ">
          {{ csrf_field() }}
          <div class="form-group">

              <label for="stock_name">Mail:</label>
              <input type="text" class="form-control" name="mail" value="{{ $mails }}" />
          </div>

         
          <button type="submit" class="btn btn-primary">Update</button>
      </form>
    <div>
    </div>
    </div>
  </section>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
    
    /*.btn-round{
        /*#ce0a0a
        border-radius: 50%;
    }*/
    
    .black{
        color: black;
    }
    
    .bg_blue{
        color: #fff;
        background-color: #3c8dbc;
    }
    
    .heure{
        border-radius: 1rem;
        background-color: #3c8dbc;
        height: 3rem;
        font-size: 16px;
    }
    
    .round{
        border-radius: 2rem;
    }
    
    .btn-round{
        -webkit-border-radius: 20px;
    }
    
    .top-10px{
        margin-top: 10px;
    }
    
    .top-2{
        margin-top: 2rem;
    }
    
    .top-4{
        top: 4rem;
    }
    
    .top-neg-4{
        margin-top: -4rem;
    }
    
    .border-profil{
        /*border: 1px solid;*/
        border-radius: 1rem;
        background: #ffffff;
        box-shadow: 0px 3px 2px #aab2bd;
        padding-bottom: 5px;
    }
    
    .centre{
        text-align: center;
    }
    
    .user-image{
        border-radius: 50%;
    }
    
    .table_wt{
        background: #34495E;
        color: #fff;
        border-radius: .4em;
        overflow: hidden;
        tr {
            border-color: lighten(#34495E, 10%);
        }
        
        th, td {
            margin: .5em 1em;
            @media (min-width: $breakpoint-alpha) { 
                padding: 1em !important; 
            }
        }
        
        th, td:before {
            color: #fff;
        }
        td{
            color: #fff;
        }
        
    }
</style>
@endsection