@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>

    <script src="{{ asset('js/admin/manageusers.js') }}"></script>
    <script src="{{ asset('js/admin/manageads.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#signal_table').DataTable({
                "order": [[ 1, "desc" ]],
                pageLength: 25,
                "paging" : false
            });

            $('.filter_signal_loue').on('click', function(){
                table
                .order( 1, 'desc' )
                .draw();
            });
            $('.filter_signal_no_phone').on('click', function(){
                table
                .order( 2, 'desc' )
                .draw();
            });
            $('.filter_signal_no_fb').on('click', function(){
                table
                .order( 3, 'desc' )
                .draw();
            });
        } );
    </script>
@endpush
<style>
    .ad-descripion{
        max-width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Traking Ads
            <small>Manage Ads</small>
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
                <form id="searchForm" method="GET">
                    <div class="box-header">
                        
                        <h3 class="box-title">Filter by number of:</h3>
                        </div> 
                        &nbsp;&nbsp;&nbsp;

                        <label class="radio-inline filter_signal_loue"><input type="radio" checked name="optradio">Loué</label>

                        <label class="radio-inline filter_signal_no_phone"><input type="radio" name="optradio">No Phone</label>

                        <label class="radio-inline filter_signal_no_fb"><input type="radio" name="optradio">No FB</label>
                       
                    </div>
                     </form>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover" id="signal_table">
                            <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Loué</th>
                                <th>No phone respond</th>
                                <th>No FB respond</th>
                                <th>City</th>
                                <th>Price</th>
                                <th>Created at</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($dataToSort))
                                @foreach($dataToSort as $key => $ad)
                                <tr>
                                    <td>
                                        <a title="View" target="_blank" class="action-toggle-on" href="{{ adUrl($ad['id']) }}">{{$ad['title']}}</a>
                                    </td>
                                    <td>
                                        {{ $ad['total_loue'] }}
                                    </td>
                                    <td>
                                        {{ $ad['total_no_phone_respond'] }}
                                    </td>
                                    <td>
                                        {{ $ad['total_no_fb_respond'] }}
                                    </td>
                                    <td>
                                        {{$ad['address']}}
                                    </td>
                                    <td>
                                        {{ isset($ad['min_rent']) ? '&euro;'.$ad['min_rent'] : ''}}
                                    </td>
                                    <td>
                                        {{date('d M Y - H:i:s', strtotime($ad['created_at']))}}
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="8">{{'No record found'}}</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div> 
                    <div class="pull-right">
                        @if($adList) 
                        {{ $adList->links('vendor.pagination.bootstrap-4') }}
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
</style> 
@endsection