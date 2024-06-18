@extends('layouts.adminappinner')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Modifier texte</h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Pages List</li>
        </ol>
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
        <div class="row">
            <div class="col-md-12 show-message">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Pages</h3>
                        <!-- <div class="col-sm-2 pull-right">
                            <a href="{{route('admin.addnewpage')}}" class="btn btn-block btn-warning">Add Page</a>
                        </div> -->
                        <div style="display: inline-block; margin-left : 15px;">
                            <select id="choosePage" class="selectpicker">
                                @foreach($pages as $key => $page)
                                <option @if($page->page==$page_show) selected="" @endif value="{{$page->page}}">
                                    @if(!empty($page->url))
                                    {{$page->url}}
                                    @else
                                    {{$page->page}}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                            <button id="add-btn" type="submit" class="btn btn-default"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="box-tools">
                            <form id="searchForm" method="GET">
                                <div class="input-group input-group-sm bx-inline">
                                    <input type="text" id='search_name' name="search_name" @if(!empty($search_name)) value="{{$search_name}}" @endif class="form-control pull-right" placeholder="Search">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>


                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover" id="table" data-admin="{{ getConfig('admin_prefix') }}">
                            <tr>
                                <th width="3%">Ordre</th>
                                <th width="30%">Text fr</th>
                                <th width="30%">Text en</th>
                                <th width="10%">index</th>
                                <th width="10%">Page</th>
                                <th width="12%">Status</th>
                                <th width="20%">Actions</th>
                            </tr>
                            @if(!empty($all_pages) && count($all_pages) > 0)

                            @foreach($all_pages as $key => $item)
                            <tr>
                                <td class="order-{{$item->id}}">@if($item->ordre < 1000){{$item->ordre}}@endif</td>
                                <td class="fr-{{$item->id}}">{{$item->text_fr}}</td>
                                <td class="en-{{$item->id}}">{{$item->text_en}}</td>
                                <td>{{$item->index}}</td>
                                <td>{{$item->page}}</td>

                                @if($item->valide == '1')
                                    <td class="validation-{{$item->id}}">VALIDE</td>
                                @elseif($item->valide == '2')
                                    <td class="validation-{{$item->id}}">EN COURS</td>
                                @elseif($item->valide == '3')
                                    <td class="validation-{{$item->id}}">ANNULE</td>
                                @else
                                    <td class="validation-{{$item->id}}">INVALIDE</td>
                                @endif

                                <td>
                                    <a href="javascript:" data-id="{{$item->id}}" class="edit-item edit-item-{{$item->id}} btn btn-info btn-sm" title ="Edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="javascript:" data-id="{{$item->id}}" class="show-item show-item-{{$item->id}} btn btn-info btn-sm" title ="Voir"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a>

                                    @if($item->valide == '2')
                                        <a href="{{ route('admin.traduction.cancel.traduction', ['id' => $item->id]) }}"data-id="{{$item->id}}"
                                           class="cancel-pending-item btn btn-danger btn-sm action-edit-button-{{$item->id}}"
                                           title="Annuler en cours"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                    @endif

                                    <a href="javascript:" data-id="{{$item->id}}" class="action-edit-button save-item btn btn-success btn-sm action-edit-button-{{$item->id}}" title ="Valider"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
                                    <a href="javascript:" data-id="{{$item->id}}" class="action-edit-button cancel-item btn btn-danger btn-sm action-edit-button-{{$item->id}}" title ="Annuler"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="bg-info">
                                <td colspan="6">Record(s) not found.</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

            {{-- Modal d'ajout --}}
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="add-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        Nouveau Texte
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="form-add" method="POST" action="{{route('admin.save-texte')}}">
                        {{csrf_field()}}
                        <div class="row">
                        <div class="col-sm-4 col-md-4 col-xs-6">
                            <label>Ordre</label>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-xs-6">
                                <input type="text" name="ordre" id="add-ordre">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-xs-6">
                                <label>Text Fr à proposer</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 full">
                                <textarea class="full" name="text_fr" id="add-text-fr" rows="5">

                                </textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-xs-12">
                                <label>Text En à proposer</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 full">
                                <textarea class="full" name="text_en" id="add-text-en" rows="5">

                                </textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-xs-12">
                                <label>Index</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 full">
                                <input class="full" type="text" name="index" id="add-index">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12">
                                <label>Url</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 full">
                                <input class="full" type="text" name="url" id="add-url">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-xs-12">
                                <label>Page</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xs-12 full">
                                <input  class="full" type="text" name="page" id="add-page">
                            </div>
                        </div>
                    </form>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-save-texte">Enregistrer</button>
                    <button type="button" class="btn btn-default" id="modal-btn-no" data-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>

            {{-- Modal de Show --}}
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="show-modal">
                <div class="modal-dialog">
                    <div class="modal-content" style="width: 900px;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">
                                Consulter la traduction
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form id="form-add" method="POST" action="{{route('admin.save-texte')}}">
                                {{csrf_field()}}

                                <div class="row">
                                    <div class="col-sm-4 col-md-4 col-xs-4">
                                        <label>Status</label>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-xs-4">
                                        <label>Ordre</label>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-xs-4">
                                        <label>Index</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4 col-xs-4">
                                        <input type="text" name="status" id="show-status">
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-xs-4">
                                        <input type="text" name="ordre" id="show-ordre">
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-xs-4">
                                        <input type="text" name="index" id="show-index">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-xs-6">
                                        <label>Url</label>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-xs-6">
                                        <label>Page</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-xs-6">
                                        <input type="text" name="url" id="show-url">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-xs-6">
                                        <input type="text" name="page" id="show-page">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-xs-6">
                                        <label>Text Fr Actuel</label>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-xs-6">
                                        <label>Text Fr à Proposer</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-xs-6">
                                        <textarea class="full" name="text_fr_actuel" id="show-text-fr-actuel" rows="5"></textarea>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-xs-6">
                                        <textarea class="full" name="text_fr_proposer" id="show-text-fr-proposer" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-xs-6">
                                        <label>Text En Actuel</label>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-xs-6">
                                        <label>Text En Proposer</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-xs-6 ">
                                        <textarea class="full" name="text_en_actuel" id="show-text-en-actuel" rows="5"></textarea>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-xs-6 ">
                                        <textarea class="full" name="text_en" id="show-text-en-proposer" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4 col-md-4 col-xs-6">
                                        <label>Commentaire</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-xs-12 full">
                                        <textarea class="full" name="commentaire" id="show-commentaire" rows="5"></textarea>
                                    </div>
                                </div>
                            </form>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" id="modal-show-btn" data-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </div>
            </div>


    </section>
</div>
<style type="text/css">
    .input-edit
    {
        width: 100%;
    }
    .action-edit-button
    {
        display: none;
    }
    .full
    {
        width: 100%;
    }
</style>
<script>
    var is_admin={{ getUserAdmin()->user_type_id}}
    $(document).ready(function(){

        $('#btn-save-texte').on('click', function(){
            $('#form-add').submit();
        });

        $('#add-btn').on('click', function(){
            $('#add-modal').modal('show');
        });

        $('.show-item').on('click', function(){
            getTraduction($(this).attr('data-id'))

            $('#show-modal').modal('show');
        });

        $("#choosePage").on('change', function(){
            var value = $("#choosePage").val();
            location.href = "?page_show=" + value;
        });

        $('.cancel-pending-item').on('click', function(){
            console.log('.cancel-pending-item click')

            let id = $(this).attr('data-id');


        });

        $('.edit-item').on('click', function(){
            var id = $(this).attr('data-id');
            var fr_text = $('.fr-' + id).text();
            var en_text = $('.en-' + id).text();
            var order = $('.order-' + id).text();
            $('.fr-' + id).html('<textarea rows="4" class="input-edit" id="input-fr-'+ id +'">'+ escapeDoubleQuote(fr_text) +'</textarea>');
            $('.en-' + id).html('<textarea rows="4" class="input-edit" id="input-en-'+ id +'">'+ escapeDoubleQuote(en_text) +'</textarea>');
            $('.order-' + id).html('<input class="input-edit" id="input-order-"'+ id +' type="text" value="'+ order +'"/>');
            $('#input-order-' + id).focus();
            $('.action-edit-button-' + id).show();
            $(this).hide();
        });

        $('.cancel-item').on('click', function(){
            var id = $(this).attr('data-id');
            var fr_text = $('.fr-' + id + ' textarea').val();
            var en_text = $('.en-' + id + ' textarea').val();
            var order = $('.order-' + id + ' input').val();
            $('.fr-' + id).html(fr_text);
            $('.en-' + id).html(en_text);
            $('.order-' + id).html(order);

            $('.action-edit-button-' + id).hide();

            $('.edit-item-' + id).show();
        });

        $('.save-item').on('click', function(){
            var id = $(this).attr('data-id');
            var fr_text = $('.fr-' + id + ' textarea').val();
            var en_text = $('.en-' + id + ' textarea').val();
            var order = $('.order-' + id + ' input').val();
            $('.fr-' + id).html(fr_text);
            $('.en-' + id).html(en_text);
            $('.order-' + id).html(order);

            $('.action-edit-button-' + id).hide();

            $('.edit-item-' + id).show();

            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                url: 'edit_page_text',
                type: 'post',
                data : {'id' : id,'text_fr' : fr_text, 'text_en' : en_text, 'order' : order, "page" : $('#choosePage').val()}
            }).done(function(result){
              if(result == 'order') {

                location.reload();
              }

                let element = '.validation-' + id

                $(element).text((is_admin==2)?'VALIDE':'EN COURS')

                console.log(element, $(element).text())
            }).fail(function (jqXHR, ajaxOptions, thrownError){

            });

        });

        $('.page-link').each(function(){
            var href = $(this).attr('href') + "&page_show=" + $('#choosePage').val();
            var search_name = $('#search_name').val();
            if(search_name != "") {
                href += "&search_name=" + search_name;
            }
            $(this).attr("href", href);
        });
    });

    function escapeDoubleQuote(str)
    {
        return str.replace(/"/g, '&quot;');
    }

    function getTraduction(id) {
        let admin = $('#table').data('admin')

        console.log('#table', admin)

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: 'get-traduction/' + id,
            type: 'get'
        }).done(function(result){
            if(result) {
                console.log('getTraduction', result.text_fr_proposition, result.text_en_proposition)

                if (result.valide == 1) {
                    $('#show-status').val('VALIDE')
                } else if(result.valide == 2) {
                    $('#show-status').val('EN COURS')
                } else if(result.valide == 3) {
                    $('#show-status').val('ANNULE')
                } else {
                    $('#show-status').val('INVALIDE')
                }

                $('#show-ordre').val(result.ordre)
                $('#show-index').val(result.index)
                $('#show-url').val(result.url)
                $('#show-page').val(result.page)
                $('#show-text-fr-actuel').val(result.text_fr)
                $('#show-text-fr-proposer').val(result.text_fr_proposition)
                $('#show-text-en-actuel').val(result.text_en)
                $('#show-text-en-proposer').val(result.text_en_proposition)
                $('#show-commentaire').val(result.commentaire)
            }
        }).fail(function (jqXHR, ajaxOptions, thrownError){

        });
    }

    /*function cancelPending(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: 'cancel-traduction/' + id,
            type: 'get'
        }).done(function(result){
            if(result) {
                console.log('getTraduction', result.text_fr_proposition, result.text_en_proposition)

                if (result.status == '1') {
                    $('#show-status').val('VALIDE')
                } else {
                    $('#show-status').val('NON VALIDE')
                }
            }
        }).fail(function (jqXHR, ajaxOptions, thrownError){

        });
    }*/
</script>
@endsection
