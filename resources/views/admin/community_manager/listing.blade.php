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
            {{__("admin.bot")}}
            <small>{{__("admin.list_bot")}}</small>
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
                <div class="form-group">
                    <div class="custom-selectbx" style="display: block; max-width: 250px !important;">
                        <select id="Community-manager" name="property_type" class="selectpicker">
                            <option selected value="">Community Manager</option>
                            @foreach($comunity as $data)
                             <option selected value="{{$data->id}}">{{$data->first_name . ' ' . $data->last_name}}</option>
                            @endforeach
                            
                        </select>
                    </div>
                </div>
                <div class="box box-primary">

                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>Propriétaire de l'annonce</th>
                                <th>Lien profil facebook</th>
                                <th>Titre</th>                                
                                <th>Description</th>
                                <th>Adresse</th>
                                <th>Loyer</th>
                                <th>Community Manager</th>
                                <th>Action</th>
                                
                            </tr>
                            @if(!empty($ads))
                            @foreach($ads as $key => $ad)
                            <tr>

                                <td>{{$ad->prenom_prop . ' ' . $ad->nom_prop}}</td>
                                <td>{{$ad->fb_link}}</td>
                                <td>{{$ad->title}}</td>
                                <td>{{$ad->description}}</td>
                                <td>{{$ad->address}}</td>
                                <td>{{$ad->min_rent}}</td>
                                <td>{{$ad->prenom_comunity . ' ' . $ad->nom_comunity}}</td>
                                <td><a title="View" class="action-toggle-on" href="{{ route('view.ad', [str_slug($ad->property_type),$ad->url_slug . '-' . $ad->id]) }}"><i class="fa fa-eye"></i></a>
                                <a title="View fb" target="_blank" href="{{$ad->fb_ad_link}}" class="action-toggle-on" href="javascript:"><i class="fa fa-facebook"></i></a>
                                 <a title="Edit" class="action-toggle-on" href="{{route('admin.create-ad') . '?ad_id=' . $ad->id}}"><i class="fa fa-pencil"></i></a>
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