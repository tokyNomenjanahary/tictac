@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')

@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <h1>
            Facture
        </h1>
        <div class="container">
            <div class="row">
                <h3>Generation de facture de l'inscription avec email</h3>
                <div class="box box-primary">

                    @if ($notif = Session::get('succes'))
                        <div class="alert alert-success fade in alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ $notif }}
                        </div>
                    @endif
                    @if ($notif = Session::get('error'))
                        <div class="alert alert-error fade in alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ $notif }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            Vous devez remplire le champ en bas
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.facture') }}" id="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-panel">
                                <div class="form-horizontal tasi-form">
                                    {{ csrf_field() }}
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-sm-4 radio">
                                            <label><input type="radio" name="optradio" value="0" id="user_check">User seleument</label>
                                        </div>
                                        <div class="col-sm-4 radio">
                                            <label><input type="radio" name="optradio" value="1" id="admin_check">Admin seulement</label>
                                        </div>
                                        <div class="col-sm-4 radio">
                                            <label><input type="radio" name="optradio" value="2" id="deux_check" checked>Les deux</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <div class="col-md-6">
                                        <label class="col-sm-4 control-label col-lg-2">Ordre de</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="ordre_de" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-sm-4 control-label col-lg-2">Email User</label>
                                        <div class="col-sm-8">
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="email_admin">
                                        <label class="col-sm-4 control-label col-lg-2">Email Admin</label>
                                        <div class="col-sm-8">
                                            <input type="email" name="email_admin" class="form-control">
                                            @if ($errors->any())
                                                <p class="alert alert-danger">
                                                    @foreach ($errors->all() as $error)
                                                        {{ $error }}
                                                    @endforeach
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <center>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary" id="">Envoyer</button>
                            </center>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <h3>Generation de facture de l'inscription avec facebook sans email</h3>
                <div class="box box-primary">

                    @if ($notif = Session::get('success'))
                        <div class="alert alert-success fade in alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ $notif }}
                        </div>
                    @endif
                    @if ($notif = Session::get('errorSansUser'))
                        <div class="alert alert-error fade in alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ $notif }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            Vous devez remplire le champ en bas
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.searchUserSansmail') }}" id="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-panel">
                                <div class="form-horizontal tasi-form">
                                    {{ csrf_field() }}
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="col-sm-4 control-label col-lg-3">Nom de l'user</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="user_name" class="form-control" required placeholder="Ajouter ici le nom de l'user">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <center>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary" id="">Chercher</button>
                            </center>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">

    .card-facture
    {
        border: 2px solid #3c8dbc;
        border-radius: 12px;
    }

    .larg{
        width: 8rem;
        height: 2.5rem;
    }

</style>
<script type="text/javascript">

    $('#user_check').change(function() {
        $('#email_admin').hide();
    });
    $('#admin_check').change(function() {
        $('#email_admin').show();
    });
    $('#deux_check').change(function() {
        $('#email_admin').show();
    });


</script>
@endsection
