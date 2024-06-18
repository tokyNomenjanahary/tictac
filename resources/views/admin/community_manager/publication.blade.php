@extends('layouts.adminappinner')
@section('content')
<style type="text/css">
    .alert-message
    {
        display: none;
    }
    .row-phrase
    {
        padding : 15px;
    }
    textarea
    {
        display: block;
        width: 100%;
    }
</style> 
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Publications
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="alert alert-success fade in alert-dismissable success-message alert-message">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            Enregistré avec succès
        </div>
        <div class="alert alert-danger fade in alert-dismissable error-message alert-message">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            Erreur lors de l'enregistrement
        </div>

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
        <div class="row">
            <div class="col-md-12 show-message">
                
                    
                    <div class="box box-primary ">
                        <div class="box-header with-border">
                            <h3 class="box-title">Publications le {{date('d/m/Y')}}<br><br>Total Publications : <span id="nbPubJournalier">{{$nbJournalier}}</span></h3>
                        </div>
                        <form id="edit-pixel-id" method="POST"  action="{{ route('admin.save_all_publication_comunity') }}" role="form">
                        {{ csrf_field() }}
                            <div class="box-body" id="content-list-phrase">
                                @for($i=0; $i < $nb; $i++)
                                <div class="row row-phrase" id="pub-{{$i}}">
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-12 col-md-4">
                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <label>Lien <sup>*</sup></label>
                                                <input type="text" id="link-{{$i}}" class="form-control" placeholder="Lien" name="lien[]" id="p-title" value=""/>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-12 col-md-4">
                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <label>Texte <sup>*</sup></label>
                                                <textarea name="text[]" rows="4" id="text-{{$i}}">@if(count($phrases) > 0) {{trim($phrases[$i])}} @endif</textarea>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-12 col-md-4">
                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <label>Status <sup>*</sup></label>
                                                <div>
                                                    <select name="status[]" id="status-{{$i}}" class="selectpicker" >
                                                        <option value="Visible">
                                                            Visible
                                                        </option>
                                                        <option value="Attente validation">
                                                            Attente validation
                                                        </option>
                                                    </select>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-12 col-md-4">
                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <label>Login</label>
                                                <input type="text" id="login-{{$i}}" class="form-control" placeholder="Login" name="login[]" id="p-title" value=""/>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-12 col-md-4">
                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <label>mdp</label>
                                                <input type="text" id="mdp-{{$i}}" class="form-control" placeholder="Mot de passe" name="mdp[]" id="p-title" value=""/>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-sm-12 col-md-4">
                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <label>Proxy</label>
                                                <input type="text" id="proxy-{{$i}}" class="form-control" placeholder="Proxy" name="proxy[]" id="p-title" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-1 col-md-1">
                                            <label> </label>
                                            <div>
                                                <button type="submit" data-id={{$i}} class="btn btn-info btn-valid-pub">Valider</button>
                                                <!-- <a href="javascript:" class="btn btn-default btn-remove-phrase">Valider</a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                                <!-- <div class="row">
                                    <div class="col-xs-6 col-sm-1 col-md-1">
                                        <label> </label>
                                        <div>
                                            <button type="submit" class="btn btn-info">Enregistrer Tout</button>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </form>
                    </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div> 
<script type="text/javascript">
    $(document).ready(function(){
        $(".btn-valid-pub").on('click', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var lien = $("#link-" + id).val();
            var text = $("#text-" + id).val();
            var status = $("#status-" + id).val();
            var proxy = $("#proxy-" + id).val();
            var login =  $("#login-" + id).val();
            var mdp =  $("#mdp-" + id).val();

            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                url: 'save_publication_comunity',
                type: 'post',
                data : {'lien' : lien,'text' : text, 'status' : status, 'proxy' : proxy, "login" : login, "mdp" : mdp}
            }).done(function(result){
                if (result.error == 'yes') {
               // console.log(result);
                $('.error-message').show();
                $('.error-message').prepend('<div class="alert alert-danger fade in alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Cet lien est déja enregistré, Merci de vérifier</div>');

            } else {
                var nb = $('#nbPubJournalier').text();
                $('#nbPubJournalier').text(parseInt(nb) + 1);

                $('#pub-' + id).remove();
                $('.success-message').show();
                setTimeout(function(){ $('.alert-message').hide(); }, 3000);

            }

            }).fail(function (jqXHR, ajaxOptions, thrownError){
                // $('.error-message').show();
                // setTimeout(function(){ $('.alert-message').hide(); }, 3000);
            });
        });
    });
</script> 
 
@endsection