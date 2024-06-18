@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')

@endpush

@section('content')
    <div class="content-wrapper">
        {{-- Modal pour l'envoi de facture --}}
        <div class="modal fade" id="envoiFacture" tabindex="-1" role="dialog" aria-labelledby="add_email_errorLabel" aria-hidden="true" style="display: none;">
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

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-body table-responsive no-padding db-table-outer">
                            <table class="table table-hover">
                                <tr>
                                    <th>User Name</th>
                                    <th>Created date</th>
                                    <th><center>Action</center></th>
                                </tr>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            <div class="modal fade" id="userSansMail_{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content round">
                                                        <div class="modal-header black">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">Mofication de l'email</h4>
                                                        </div>
                                                        <form method="POST" action="{{ route('admin.factureSansMail')}}" class="black">
                                                            {{ csrf_field() }}
                                                            <div class="content" style="min-height: auto;">
                                                                <div class="form-panel">
                                                                    <div class="form-horizontal tasi-form">
                                                                        <input name="user_id" type="hidden" value="{{ $user->id }}">

                                                                        <div class="form-group">
                                                                            <div class="col-sm-12">
                                                                                <label class="col-sm-4 control-label col-lg-2">Envoyé au</label>
                                                                            </div>
                                                                            <div class="col-sm-4 radio">
                                                                                <label><input type="radio" name="optradio" value="0" id="user_check">User seleument</label>
                                                                            </div>
                                                                            <div class="col-sm-4 radio">
                                                                                <label><input type="radio" name="optradio" value="1" id="admin_check">Admin seulement</label>
                                                                            </div>
                                                                            <div class="col-sm-4 radio">
                                                                                <label><input type="radio" name="optradio" value="2" id="deux_check" checked>Les deux</label>
                                                                            </div>
                                                                        </div><hr>

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
                                                                                    <input type="email" name="email_user" class="form-control" required>
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

                                                                        <div class="modal-footer">
                                                                            <center>
                                                                                <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Fermer</button>
                                                                                <button type="submit" class="btn btn-round btn-primary">Envoyer</button>
                                                                            </center>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <center>
                                                <button type="button" class="btn btn-primary btn-xs larg" id="{{ $user->id }}" data-toggle="modal" data-target="#userSansMail_{{ $user->id }}">Facturer</button>
                                            </center>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
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
