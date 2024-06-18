@extends('layouts.adminappinner')
@push('scripts')
<script src="{{ asset('js/admin/manageusers.js') }}"></script>
<script src="{{ asset('js/admin/manageads.js') }}"></script>
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
                        <label class="radio-inline"><a href="{{route('admin.trakingAd',['type'=>'clic'])}}"><input type="radio" onclick="window.location='{{route('admin.trakingAd',['type'=>'clic'])}}';" {{ ($type == 'clic') ? 'checked' : '' }} name="optradio">Vue</a></label>
                        <label class="radio-inline"><a href="{{route('admin.trakingAd',['type'=>'message'])}}"><input type="radio" onclick="window.location='{{route('admin.trakingAd',['type'=>'message'])}}';" {{ ($type == 'message') ? 'checked' : '' }} name="optradio">Message</a></label>
                        <label class="radio-inline"><a href="{{route('admin.trakingAd',['type'=>'toc_toc'])}}"><input type="radio" onclick="window.location='{{route('admin.trakingAd',['type'=>'toc_toc'])}}';" {{ ($type == 'toc_toc') ? 'checked' : '' }} name="optradio">Toc toc</a></label>
                        <label class="radio-inline"><a href="{{route('admin.trakingAd',['type'=>'phone'])}}"><input type="radio" onclick="window.location='{{route('admin.trakingAd',['type'=>'phone'])}}';" {{ ($type == 'phone') ? 'checked' : '' }} name="optradio">Phone</a></label>
                       
                    </div>
                     </form>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>

                                <th>Vue</th>                               
                                <th>Message</th>
                                <th>Toc Toc</th>
                                <th>Phone</th>
                                <th>Ville</th>
                            </tr>
                            @if(!empty($adList))
                                @foreach($adList as $key => $ad)
                                <tr>
                                    <td>
                                        {{ (isset($ad->total_clic) ? $ad->total_clic : 0) }}
                                    </td>
                                    <td>
                                        {{(isset($ad->total_message) ? $ad->total_message : 0)}}
                                    </td>
                                    <td>
                                        {{(isset($ad->total_toc_toc) ? $ad->total_toc_toc : 0)}}
                                    </td>
                                    <td>
                                        {{(isset($ad->total_phone) ? $ad->total_phone : 0)}}
                                    </td>
                                    <td>
                                        {{$ad->address}}
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr><td colspan="8">{{'No record found'}}</td></tr>
                            @endif
                        </table>
                    </div>     
                    <div class="pull-right">
                        @if(!empty($adList))
                            @if(!empty($select_ad))
                                @if(!empty($shorting))
                                {{ $adList->appends($select_ad)->appends($shorting)->links('vendor.pagination.bootstrap-4') }}
                                @else
                                {{ $adList->appends($select_ad)->links('vendor.pagination.bootstrap-4') }}
                                @endif
                            @else
                                @if(!empty($shorting))
                                {{ $adList->appends($shorting)->links('vendor.pagination.bootstrap-4') }}
                                @else
                                {{ $adList->links('vendor.pagination.bootstrap-4') }}
                                @endif
                            @endif
                            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel"></h4>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="modal-btn-yes">Yes</button>
                                            <button type="button" class="btn btn-default" id="modal-btn-no">No</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>               
                </div>
            </div>
        </div>
    </section>
</div>  


<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="deactive-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Deactive Ad?</h4>
            </div>
            <div class="modal-body">
                <div class="div-form-modal">
                    <label class="control-label" for="property_type">Reason *</label>
                    <div>
                        <select id="raison-deactive">
                            <option value="Interdit de fournir vos coordonnées dans l'annonce">Interdit de fournir vos coordonnées dans l'annonce</option>
                            <option value="Annonce erronée">Annonce erronée</option>
                            <option value="Annonce en double">Annonce en double</option>
                            <option value="Annonce Spam">Annonce Spam</option>
                            <option value="Annonce invalide">Annonce invalide</option>
                            <option value="-1">Autre</option>
                        </select>
                    </div>
                </div> 
                <div class="div-form-modal">
                   <textarea id="autre-reason" name="autre_reason" class="form-control" placeholder="Reason" rows="6">
                   </textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" href="" id="modal-deactive-btn-yes">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@include('admin.ads.delete_ad')
@include('admin.ads.contact_annonceur')
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