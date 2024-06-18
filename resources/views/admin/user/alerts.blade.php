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
            Alert
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

                                <th>Prénom</th>                              
                                <th>Age</th>
                                <th>Sexe</th>
                                <th>Profession</th>
                                <th>Filtres</th>
                                <th>Date</th>
                            </tr>
                            @if(!empty($alerts))
                            @foreach($alerts as $key => $alert)
                            <tr>
                                <td>
                                    <a href="{{url(getConfig('admin_prefix') . '/user_profile/'.base64_encode($alert->user_id))}}">
                                        {{$alert->first_name}} 
                                    </a>
                                </td>
                                <td>
                                    {{Age($alert->birth_date)}} ans
                                </td>
                                <td>
                                    @if($alert->sex == 0)
                                    Male
                                    @else
                                    Female
                                    @endif
                                </td>
                                <td>
                                    {{$alert->profession}} 
                                </td>
                                <td>
                                    {{buildFilters($alert->filtres)}}
                                </td>
                                <td>
                                    {{adjust_gmt($alert->date)}}
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