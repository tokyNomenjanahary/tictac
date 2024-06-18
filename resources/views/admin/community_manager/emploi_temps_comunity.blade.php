@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
<!--    <link href="{{ asset('css/admin/datatables.net-bs/dataTables.bootstrap.min.css') }}" rel="stylesheet">-->
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Ajouter un emploi du temps</h4>
                </div>
                <!--form action="" id="register_emploi_temps"  method="POST" enctype="multipart/form-data"-->
                <form action="{{ route('admin.saveEmploiTemps') }}" id="register_emploi_temps"  method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-panel">
                            <div class="form-horizontal tasi-form">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-sm-2 control-label col-lg-2">User</label>
                                    <div class="col-lg-10">
                                        <select name="id_user" class="form-control">
                                            @foreach ($lists_users as $i)
                                            <option value="{{ $i->id }}" id="user_{{ $i->id }}">{{ $i->first_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label col-lg-2">Jour</label>
                                    <div class="col-lg-10">
                                        <select name="id_jours" class="form-control">
                                            @foreach ($jours as $j)
                                            <option value="{{ $j->id_jour }}">{{ $j->jour }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label col-lg-2">H Deb 1</label>
                                    <div class="col-lg-4">
                                        <input type="time" step="2" class="form-control" id="" name="h_deb_1">
                                    </div>
                                    <label class="col-sm-2 control-label col-lg-2">H Fin 1</label>
                                    <div class="col-lg-4">
                                        <input type="time" step="2" class="form-control" id="" name="h_fin_1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label col-lg-2">H Deb 2</label>
                                    <div class="col-lg-4">
                                        <input type="time" step="2" class="form-control" id="" name="h_deb_2">
                                    </div>
                                    <label class="col-sm-2 control-label col-lg-2">H Fin 2</label>
                                    <div class="col-lg-4">
                                        <input type="time" step="2" class="form-control" id="" name="h_fin_2">
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit_emploi_temps">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modif_heure" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel2">Modifier emploi du temps</h4>
                </div>
                <!--form action="" id="register_emploi_temps"  method="POST" enctype="multipart/form-data"-->
                <form action="{{ route('admin.saveEmploiTemps') }}" id="register_emploi_temps2"  method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-panel">
                            <div class="form-horizontal tasi-form">
                                {{ csrf_field() }}
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label col-lg-2">Jour</label>
                                    <div class="col-lg-10">
                                        <select name="id_jours" class="form-control">
                                            @foreach ($jours as $j)
                                            <option value="{{ $j->id_jour }}">{{ $j->jour }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label col-lg-2">H Deb 1</label>
                                    <div class="col-lg-4">
                                        <input type="time" step="2" class="form-control" id="" name="h_deb_1">
                                    </div>
                                    <label class="col-sm-2 control-label col-lg-2">H Fin 1</label>
                                    <div class="col-lg-4">
                                        <input type="time" step="2" class="form-control" id="" name="h_fin_1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label col-lg-2">H Deb 2</label>
                                    <div class="col-lg-4">
                                        <input type="time" step="2" class="form-control" id="" name="h_deb_2">
                                    </div>
                                    <label class="col-sm-2 control-label col-lg-2">H Fin 2</label>
                                    <div class="col-lg-4">
                                        <input type="time" step="2" class="form-control" id="" name="h_fin_2">
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-round btn-danger" id="submit_emploi_temps">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Content Header (Page header) <?php ?> -->
    <section class="content-header">
        <h1>
            Empoi du temps Community
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
        <button type="button" class="btn bg_blue" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-plus"></i> Add user</button>
        <br><br>
        <div class="mui-body" cellpadding="0" cellspacing="0" border="0">
            <div class="box-body table-responsive no-padding db-table-outer">
                <table class="table table_wt">
                    
                    <tbody>
                        @foreach ($data_emploi_temps as $l)
                        <tr>
                            <th><center style="margin-top: 6rem; font-size: 20px;">{{ $l['first_name'] }}</center></th>
                            @foreach ($l['employs_temps'] as $e)
                            <td>
                                <center>
                                    <div class="heure">
                                        {{ $e['jour'] }}
                                    </div>
                                    <div class="heure top-10px">
                                        {{ $e['h_deb_1'] }} - {{ $e['h_fin_1'] }}
                                    </div>
                                    <div class="heure top-10px">
                                        {{ $e['h_deb_2'] }} - {{ $e['h_fin_2'] }}
                                    </div>
                                    <div class="top-10px">
                                        
                                        <div class="modal fade" id="updateHeure{{ $e['id_heure'] }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content round">
                                                    <div class="modal-header black">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="updateHeure{{ $e['id_heure'] }}">Mofication de l'emploi du temps</h4>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.updateEmploiTemps', [$e['id_heure']]) }}" class="black">
                                                        {{ csrf_field() }}
                                                        <div class="content">
                                                            <div class="form-panel">
                                                                <div class="form-horizontal tasi-form">
                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label col-lg-2">Jour</label>
                                                                        <div class="col-lg-10">
                                                                            <select name="id_jours" class="form-control">
                                                                                @foreach ($jours as $j)
                                                                                <option value="{{ $j->id_jour }}">{{ $j->jour }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label col-lg-2">H Deb 1</label>
                                                                        <div class="col-lg-4">
                                                                            <input type="time" step="2" class="form-control" id="" name="h_deb_1">
                                                                        </div>
                                                                        <label class="col-sm-2 control-label col-lg-2">H Fin 1</label>
                                                                        <div class="col-lg-4">
                                                                            <input type="time" step="2" class="form-control" id="" name="h_fin_1">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label col-lg-2">H Deb 2</label>
                                                                        <div class="col-lg-4">
                                                                            <input type="time" step="2" class="form-control" id="" name="h_deb_2">
                                                                        </div>
                                                                        <label class="col-sm-2 control-label col-lg-2">H Fin 2</label>
                                                                        <div class="col-lg-4">
                                                                            <input type="time" step="2" class="form-control" id="" name="h_fin_2">
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="modal-footer">
                                                                        <center>
                                                                            <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-round btn-primary">Update</button>
                                                                        </center>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="modal fade" id="deleteHeure{{ $e['id_heure'] }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content round">
                                                    <div class="modal-header black">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel{{ $e['id_heure'] }}">Suppression de l'emploi du temps</h4>
                                                    </div>
                                                    <form action="">
                                                        <div class="modal-body black">
                                                            <h3>Vous le vous supprimer vraiment cette emploi du temps</h3>
                                                            <div class="">
                                                                {{ $e['h_deb_1'] }} - {{ $e['h_fin_1'] }}
                                                            </div>
                                                            <div class="top-10px">
                                                                {{ $e['h_deb_2'] }} - {{ $e['h_fin_2'] }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <center>
                                                                <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                                                                <a href="{{ route('admin.delete_emploi_temps', [$e['id_heure']]) }}" type="button" class="btn btn-round btn-danger">Delete</a>
                                                            </center>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="button" class="btn btn-primary btn-xs larg" id="update_heure_{{ $e['id_heure'] }}" data-toggle="modal" data-target="#updateHeure{{ $e['id_heure'] }}"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-primary btn-xs larg" id="delet_heure_{{ $e['id_heure'] }}" data-toggle="modal" data-target="#deleteHeure{{ $e['id_heure'] }}"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </center>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
        
    </section>

</div>    
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
    
    .larg{
        width: 4rem;
        height: 2.5rem;
    }
    
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
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', '#submit_emploi_temps', function(e) {
            
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                header: $('meta[name="csrf-token"]').attr('content'),
                data: $("#register_emploi_temps").serialize(),
                dataType: 'json',
                success: function(data) {
                    
                },
                error: function(data) {
                    
                }
            });
            
        });
        
    });
    
</script>
@endsection