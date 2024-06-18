@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')

@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="modal fade" id="add_email_error" tabindex="-1" role="dialog" aria-labelledby="add_email_errorLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 1rem;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="add_email_errorLabel"><center> Ajouter un email pour recevoir l'erreur</center></h4>
                </div>
                
                <form method="POST" action="{{ route('admin.saveEmailError') }}" id="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-panel">
                            <div class="form-horizontal tasi-form">
                                {{ csrf_field() }}
                                <hr>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label col-lg-2">Email</label>
                                    <div class="col-sm-8">
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <center>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary" id="">Enregistrer</button>
                        </center>
                        
                    </div>
                </form>
            
            </div>
        </div>
    </div>
    <!-- Content Header (Page header) <?php ?> -->
    <section class="content-header">
        <h1>
            Les emails pour recevoir les erreurs sur le site
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        @if ($notif = Session::get('error'))
        <div class="alert alert-danger fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $notif }}
        </div>
        @endif

        @if ($notif = Session::get('succes'))
        <div class="alert alert-success fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $notif }}
        </div>
        @endif

        @if ($notif = Session::get('update'))
        <div class="alert alert-success fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $notif }}
        </div>
        @endif

        @if ($notif = Session::get('error_update'))
        <div class="alert alert-danger fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $notif }}
        </div>
        @endif

        @if ($notif = Session::get('delete'))
        <div class="alert alert-success fade in alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            {{ $notif }}
        </div>
        @endif

        <button type="button" class="btn bg_blue" data-toggle="modal" data-target="#add_email_error"><i class="glyphicon glyphicon-plus"></i>Ajouter l'email </button>
        <hr>
        <div class="mui-body" cellpadding="0" cellspacing="0" border="0">
            <div class="box-body table-responsive no-padding db-table-outer">
                <table class="table table_wt">
                    
                    <tbody>
                        @foreach ($getEmail as $l)
                        <tr>
                            <th style="font-size: 20px;">{{ $l->email }}</th>
                            <td>
                                <div class="top-10px">
                                    <div class="modal fade" id="updateEmail_{{ $l->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                        
                                        <div class="modal-dialog">
                                            <div class="modal-content round">
                                                <div class="modal-header black">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">Mofication de l'email</h4>
                                                </div>
                                                <form method="POST" action="{{ route('admin.updateEmailError', [$l->id]) }}" class="black">
                                                    {{ csrf_field() }}
                                                    <div class="content" style="min-height: auto;">
                                                        <div class="form-panel">
                                                            <div class="form-horizontal tasi-form">
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label col-lg-2">Email</label>
                                                                    <div class="col-lg-8">
                                                                        <input type="email" class="form-control" id="" name="email_{{ $l->id }}" value="{{ $l->email }}">
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
                                        <div class="modal fade" id="deleteEmail_{{ $l->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content round">
                                                    <div class="modal-header black">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel"><center>Suppression de l'email</center></h4>
                                                    </div>
                                                    <form action="">
                                                        <div class="modal-body black">
                                                            <center>
                                                                <h4>Vous le vous supprimer vraiment cet email</h4>
                                                                <h2>{{ $l->email }}</h2>
                                                            </center>
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                            <center>
                                                                <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                                                                <a href="{{ route('admin.deletEmailError', $l->id) }}" type="button" class="btn btn-round btn-danger">Delete {{ $l->id }}</a>
                                                            </center>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <center>
                                            <button type="button" class="btn btn-primary btn-xs larg" id="" data-toggle="modal" data-target="#updateEmail_{{ $l->id }}"><i class="fa fa-pencil"></i></button>
                                            <button type="button" class="btn btn-primary btn-xs larg" id="{{ $l->id }}" data-toggle="modal" data-target="#deleteEmail_{{ $l->id }}"><i class="fa fa-trash-o"></i></button>
                                        </center>
                                        
                                </div>
                            </td>
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
        width: 8rem;
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
        border-radius: 1rem;
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

        .btn-moyenne{
            width: 8rem;
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