@extends('layouts.adminappinner')
@push('scripts')
<script src="{{ asset('js/admin/manageusers.js') }}"></script>
@endpush
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Promo Code
            <small>List Promo Code</small>
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
                                <th>Label</th>
                                <th>Code</th>
                                <th>Type</th>                                
                                <th>Commentaire</th>
                                <th>Value</th>
                                <th>End Validity</th>
                                <th>Action</th>
                                
                            </tr>
                            @if(!empty($promotions))
                            @foreach($promotions as $key => $promo)
                            <tr>

                                <td>{{$promo->libelle}}</td>
                                <td>{{$promo->code}}</td>
                                <td>{{$promo->libelle_type}}</td>
                                <td>{{$promo->commentaire}}</td>
                                <td>{{$promo->value . ' ' . $promo->unite}}</td>
                                <td>{{date("Y/d/m", strtotime($promo->end_date_validity))}}</td>
                                <td>
                                 <a title="Edit" class="action-toggle-on" href="{{route('admin.add-code-promo') . '?promo_id=' .  $promo->id}}"><i class="fa fa-pencil"></i></a>
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