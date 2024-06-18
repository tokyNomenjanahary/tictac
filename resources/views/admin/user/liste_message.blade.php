@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
<!--    <link href="{{ asset('css/admin/datatables.net-bs/dataTables.bootstrap.min.css') }}" rel="stylesheet">-->
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
<!--    <script src="{{ asset('js/admin/datatables.net/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/admin/datatables.net-bs/dataTables.bootstrap.min.js') }}"></script>-->

<script src="{{ asset('js/admin/manageusers.js') }}"></script>
<script src="{{ asset('js/admin/manageads.js') }}"></script>
<script src="/js/metier_autocompletion.js"></script>
<script src="/js/school_autocomplete.js"></script>
<script src="/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.reload-page').on('click', function(){
        document.location.reload();
    });
});
</script>
@endpush

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Message de désactivation de compte
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
                        
                        <!-- <div class="box-tools">
                            <div clas="col-md-6">
                            <form id="searchForm" method="GET">
                                <input type="hidden" id="treaty" name="treaty" value="0">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search_name" value="" class="form-control pull-right" placeholder="Search">
                                           <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>

                                </div>
                                
                                
                            </form>
                            </div>
                        </div> -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>Message</th>
                                <th>Email</th>
                                <th>Date</th>
                            </tr>
                            @if(!empty($users))
                            @foreach($users as $key => $user)
                            <tr>
                                <td @if($user->vu == 0) class="non-vu" @endif>{{$user->message}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->date}}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>{{'No record found'}}</tr>
                            @endif
                        </table>
                    </div>
                    <div class="pull-right">
                        @if($users) 
                        {{ $users->links('vendor.pagination.bootstrap-4') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div> 

<style type="text/css">
    .div-form-modal
    {
        margin-bottom: 15px;
    }

    #autre-reason
    {
        display: none;
    }

    .easy-autocomplete.eac-square
    {
        width: 90% !important;
    }

    .easy-autocomplete
    {
        position: relative;
    }

    .easy-autocomplete-container
    {
        width: 100% !important;
        box-shadow: 0 10px 30px 0 rgba(50,50,50,.35);
        z-index: 99999;
    }

    .easy-autocomplete-container li
    {
        padding: 5px;
        color: black;
        background-color: white;
        border-bottom: solid #d2d1d1 0.1px;
        cursor: pointer;
        list-style: none;
    }

    .easy-autocomplete-container li:hover
    {
        background-color: rgb(239,239,239);
    }

    .non-vu
    {
        font-weight: bold;
    }

</style>

@endsection
