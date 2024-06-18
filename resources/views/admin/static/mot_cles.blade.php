@extends('layouts.adminappinner')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Mot Clés</h1>
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
                        <div class="col-sm-2 pull-right">
                            <a href="{{route('admin.add_new_mot_cles')}}" class="btn btn-block btn-warning">Add Mots clés</a>
                        </div>
                        
                        
                    </div>

                    
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>Mots Clés</th>
                                <th>Action</th>
                            </tr>
                            @if(!empty($mots) && count($mots) > 0)
                            @foreach($mots as $key => $item)
                            <tr>
                                <td class="fr-{{$item->id}}">{{$item->mot_cles}}</td>
                                <td>
                                    <a href="javascript:" data-id="{{$item->id}}" class="edit-item edit-item-{{$item->id}} btn btn-info btn-sm" title ="Edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="javascript:" data-id="{{$item->id}}" class="action-edit-button save-item btn btn-success btn-sm action-edit-button-{{$item->id}}" title ="Valider"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
                                    <a href="javascript:" data-id="{{$item->id}}" class="action-edit-button cancel-item btn btn-danger btn-sm action-edit-button-{{$item->id}}" title ="Annuler"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                    <a href="?id={{$item->id}}" class="btn btn-danger btn-sm" title ="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                    <div class="pull-right">
                        @if(!empty($mots))
                        {{$mots->links('vendor.pagination.bootstrap-4') }}
                        @endif
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
</style>
<script>
    $(document).ready(function(){
        $("#choosePage").on('change', function(){
            var value = $("#choosePage").val();
            location.href = "?page_show=" + value;
        });

        $('.edit-item').on('click', function(){
            var id = $(this).attr('data-id');
            var fr_text = $('.fr-' + id).text();
            var en_text = $('.en-' + id).text();
            $('.fr-' + id).html('<input class="input-edit" id="input-fr-"'+ id +' type="text" value="'+ fr_text +'"/>');
            $('.action-edit-button-' + id).show();
            $(this).hide();
        });

        $('.cancel-item').on('click', function(){
            var id = $(this).attr('data-id');
            var fr_text = $('.fr-' + id + ' input').val();
            $('.fr-' + id).html(fr_text);
            $('.action-edit-button-' + id).hide();
            $('.edit-item-' + id).show();
        });

        $('.save-item').on('click', function(){
            var id = $(this).attr('data-id');
            var fr_text = $('.fr-' + id + ' input').val();
            $('.fr-' + id).html(fr_text);
            $('.action-edit-button-' + id).hide();
            $('.edit-item-' + id).show();

            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                url: 'add_new_mot_cles',
                type: 'post',
                data : {'id' : id,'mot_cles' : fr_text}
            }).done(function(result){ 
              
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


</script>
@endsection