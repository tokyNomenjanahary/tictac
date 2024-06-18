@extends('layouts.adminappinner')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Manage Upselling
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
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Package Listing</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>Label</th>
                                <th>Fr title</th>
                                <th>En title</th>
                                <th>Description en Français</th>
                                <th>Description en Anglais</th>
                                <th>Tarifs</th>
                                <th>Action</th>
                            </tr>
                            @if(!empty($upsels))
                            @foreach($upsels as $key => $upsel)
                            <tr>
                                <td>{{$upsel->label}}</td>
                                <td>{{$upsel->fr_title}}</td>
                                <td>{{$upsel->en_title}}</td>
                                <td>{{$upsel->fr_description}}</td>
                                <td>{{$upsel->en_description}}</td>
                                <td>
                                    @if(count($upsel->tarifs) > 0)
                                    <select>
                                        @foreach($upsel->tarifs as $key2 => $tarif)
                                        <option>{{$tarif->price}}€ / {{$tarif->duration}} {{$tarif->unit}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin.new_upselling') . '?id=' . $upsel->id}}" class="btn btn-info btn-sm" title ="Edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="javascript:" data-id="{{$upsel->id}}"  class="btn btn-danger btn-sm remove-upsel" title ="Remove"><span class="glyphicon glyphicon-remove" aria-hidden="true" ></span></a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="6">{{'No record found'}}</td></tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete upselling?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modal-delete-btn-yes">Yes</button>
                <button type="button" class="btn btn-default" id="modal-delete-btn-no">No</button>
            </div>
        </div>
    </div>
</div>
    </section>
</div>  

<script>
    $(document).ready(function(){
        $('.remove-upsel').on('click', function(){
            $('#modal-delete-btn-yes').attr("data-id", $(this).attr("data-id"));
            $("#delete-modal").modal("show");
        });

        $('#modal-delete-btn-yes').on('click', function(){
            location.href = "?id=" + $(this).attr('data-id');
        });

        $('#modal-delete-btn-no').on('click', function(){
            $("#delete-modal").modal("hide");
        });
        
    });
</script>  
@endsection