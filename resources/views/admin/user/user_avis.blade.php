@extends('layouts.adminappinner')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Avis
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
            <div class="col-xs-12 show-message">
                <div class="box box-primary">

                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">

                            <tr>

                                <th>User</th>                                
                                <th>Note</th>
                                <th>Comments</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            @if(!empty($avis))
                            @foreach($avis as $key => $value)
                            <tr>
                                <td>{{$value->first_name}} {{$value->last_name}}</td>
                                <td>{{$value->notes}}</td>
                                <td>{{$value->comments}}</td>
                                <td>{{adjust_gmt($value->date)}}</td>
                                <td>
                                    @if($value->admin_approve == 1)
                                    <a title="Disapprove" class="action-toggle-on deactive_ad" href="{{route('admin.activeAvis') . '?id=' . $value->id . '&status=0'}}"><i class="fa fa-toggle-on"></i></a>
                                    @else 
                                    <a title="Approve" class="action-toggle-off" href="{{route('admin.activeAvis') . '?id=' . $value->id . '&status=1'}}"><i class="fa fa-toggle-off"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td colspan="8">{{'No record found'}}</td></tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div> 
@endsection