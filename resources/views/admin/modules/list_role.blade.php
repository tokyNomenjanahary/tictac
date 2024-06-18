@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
<!--    <link href="{{ asset('css/admin/datatables.net-bs/dataTables.bootstrap.min.css') }}" rel="stylesheet">-->
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Rôles
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
        <div class="row" style="background: white;">
            <div class="col-xs-12">
                <div class="row">
                    <div class="box box-primary">
                    
                    <!-- /.box-header -->
                        <input type="hidden" id="short" name="">
                        <input type="hidden" id="desc" name="">
                        <div class="box-body table-responsive no-padding db-table-outer">
                            <table class="table table-hover">
                                <tr>
                                    <th>Nom</th>
                                    <th> 
                                        Action
                                    </th>
                                </tr>
                                @if(!empty($roles))
                                @foreach($roles as $key => $d)
                                <tr>
                                    <td id="date-{{$d->id}}">{{$d->designation}}</td>
                                    <td>
                                        <a href="{{route('admin.add_role', [$d->id])}}" class="edit-item edit-item-{{$d->id}} btn btn-info btn-sm" title ="Edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                        <a href="{{route('admin.delete_role', [$d->id])}}" class="delete-item btn btn-danger btn-sm" title ="Annuler"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>{{'No record found'}}</tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
</div> 
@endsection