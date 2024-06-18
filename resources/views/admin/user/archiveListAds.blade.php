@extends('layouts.adminappinner')

<!-- Push a script dynamically from a view -->
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
<style>
.dataTables_empty           
{                           
 display: none !important;  
}                           
.dataTables_info{           
 display: none !important;  
}
.dataTables_length{
    display: none !important;  
} 
.dataTables_filter{
    margin-right: 15px;
}                           
</style>
@endpush


@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h3>
            Historique d'archivage des annonces
            </h3>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header mt-20">
                        <ul>
                            <li>            
                                <h3 class="box-title"> <a href="{{route('admin.archivage')}}">Users archivées : {{count($UserArchives)}}</a> </h3>
                            </li>
                            <li class="mt-15">            
                                <h3 class="box-title"> <a href="{{route('admin.archivageAds')}}">Ads archivées : {{count($adsArchives)}}</a> </h3>
                            </li>
                            <li>            
                                <h3 class="box-title"> <a href="{{route('admin.archivageProfile')}}">Users Profiles archivées : {{count($UserProfileArchives)}}</a> </h3>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover" id="archive">
                            <thead>
                                <tr>
                                    <th>User_Id</th>
                                    <th>address</th>
                                    <th>title</th>
                                    <th>phone</th>
                                    <th>created_at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adsArchives as $adsArchive )
                                <tr>
                                    <td>{{$adsArchive->user_id}}</td>
                                    <td>{{$adsArchive->address}}</td>
                                    <td>{{$adsArchive->title}}</td>
                                    <td>{{$adsArchive->phone}}</td>
                                    <td>{{$adsArchive->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if ($adsArchives->isEmpty())
                            <tr>
                                <td colspan="5"> <h3>Pas d'annonces archivées</h3> </td>    
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
<!-- Push a script dynamically from a view -->
@push('scripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function(){
        var archive = $('#archive').DataTable({
            "pageLength": 20,
            "language": {
                "paginate": {
                "previous": "&lt;", // Remplacer "previous" par "<"
                "next": "&gt;" // Remplacer "next" par ">"
            },
                "lengthMenu": "Filtre _MENU_ ",
                "zeroRecords": "Pas de recherche corespondant",
                "info": "Affichage _PAGE_ sur _PAGES_",
                "infoEmpty": "Pas de recherche corespondant",
                "infoFiltered": "(filtered from _MAX_ total records)"
            },
    })
});
</script>
@endpush
