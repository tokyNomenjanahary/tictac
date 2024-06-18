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

            <div id="alert-invalide" class="alert alert-info fade in alert-dismissable no-display">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                Une Notification est envoyé au traducteur concerne
            </div>

        @if ($message = Session::get('status'))
            <div class="alert alert-success fade in alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                {{ $message }}
            </div>
        @endif

            <div class="row">
                <div class="col-md-12 show-message">
                    <div class="box box-info">

                        <div class="box-body table-responsive no-padding db-table-outer">
                            <table class="table table-hover" id="table" data-admin="{{ getConfig('admin_prefix') }}">
                                <tr>
                                    <th width="3%">Ordre</th>
                                    <th width="30%">Text fr à proposer</th>
                                    <th width="30%">Text en à proposer</th>
                                    <th width="10%">index</th>
                                    <th width="10%">Page</th>
                                    <th width="20%">Actions</th>
                                </tr>
                                @if(!empty($pages) && count($pages) > 0)

                                    @foreach($pages as $key => $item)
                                        <tr>
                                            <td class="order-{{$item->id}}">@if($item->ordre < 1000){{$item->ordre}}@endif</td>
                                            <td class="fr-{{$item->id}}">{{$item->text_fr_proposition}}</td>
                                            <td class="en-{{$item->id}}">{{$item->text_en_proposition}}</td>
                                            <td>{{$item->index}}</td>
                                            <td>{{$item->page}}</td>

                                            <td>
                                                <a href="javascript:" data-id="{{$item->id}}" class="show-item show-item-{{$item->id}} btn btn-info btn-sm" title ="Voir"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="bg-info">
                                        <td colspan="6">Record(s) not found.</td>
                                    </tr>
                                @endif
                            </table>
                            <div class="pull-right">
                                @if(!empty($pages))
                                    {{ $pages->links('vendor.pagination.bootstrap-4') }}
                                @endif
                            </div>
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
                                Validation
                            </h4>
                        </div>
                        <form id="form-validation" method="POST" action="{{route('admin.traduction.validation')}}">
                        <div class="modal-body">
                                {{csrf_field()}}

                                <input type="hidden" name="id" id="show-id">
                                <input type="hidden" name="traducteur" id="show-traducteur">

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
                                        <label>Text En à Proposer</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-xs-6 ">
                                        <textarea class="full" name="text_en_actuel" id="show-text-en-actuel" rows="5"></textarea>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-xs-6 ">
                                        <textarea class="full" name="text_en_proposer" id="show-text-en-proposer" rows="5"></textarea>
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
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="modal-btn-valide">Valide</button>
                            <button type="button" class="btn btn-danger"  id="modal-btn-invalide">Invalide</button>
                            <button type="button" class="btn btn-default" id="modal-show-btn" data-dismiss="modal">Annuler</button>
                        </div>

                        </form>
                    </div>
                </div>
            </div>

    </section>
</div>
@endsection

@push('styles')
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
        .no-display {
            display: none;
        }
    </style>
@endpush

@push('scripts')

    <script type="text/javascript">
        $(document).ready(function(){
            $('.show-item').on('click', function(){
                getTraduction($(this).attr('data-id'))

                $('#show-modal').modal('show');
            });


            $('#modal-btn-valide').on('click', function () {

                ('#form-validation').submit()

                let btn = $(this);
                btn.prop('disabled', true);

                setTimeout(function(){
                    btn.removeAttr("disabled");
                    btn.prop('disabled', false);
                }, 10000);
            })

            $('#modal-btn-invalide').on('click', function () {

                console.log('#modal-btn-invalide', $('#show-id').val(), $('#show-traducteur').val(), $('#show-commentaire').val())

                let btn = $(this);
                btn.prop('disabled', true);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: 'invalide-traduction',
                    type: 'post',
                    data : {
                        'id'          : $('#show-id').val(),
                        'commentaire' : $('#show-commentaire').val(),
                        'traducteur'  : $('#show-traducteur').val()
                    }
                }).done(function(result){
                    console.log('#modal-btn-invalide success')

                    $('#show-modal').modal('hide');

                    btn.removeAttr("disabled");
                    btn.prop('disabled', false);

                    // alert-invalide
                    $('#alert-invalide').removeClass("no-display")
                }).fail(function (jqXHR, ajaxOptions, thrownError){

                    $('#show-modal').modal('hide');
                    btn.removeAttr("disabled");
                    btn.prop('disabled', false);
                });
            })

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

                        if (result.status == '1') {
                            $('#show-status').val('VALIDE')
                        } else {
                            $('#show-status').val('NON VALIDE')
                        }

                        $('#show-id').val(result.id)
                        $('#show-traducteur').val(result.id_traducteur)
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
        })

    </script>

@endpush
