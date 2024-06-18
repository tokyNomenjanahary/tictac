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
            Ads
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

                    <!-- Critere Recherche -->
                <form id="searchForm" method="GET">
                    <div class="box-header">

                        <h3 class="box-title">Annonce alert</h3>
                        <input type="hidden" id="ad_id" name="ad_id">


                        <div style="display: inline-block; margin-left : 15px;width : 200px;">
                                 <input type="text" class="form-control" placeholder="Rechercher par nom" id="nom" name="filter_nom"@if(!empty($select_ad) && !empty($select_ad['filter_nom'])) value="{{$select_ad['filter_nom']}}" @endif autofocus>
                        </div>
                        <div style="display: inline-block; margin-left : 15px;">
                                <select id="treatySelect" name="active" class="filter selectpicker">
                                    <option  @if(!empty($select_ad) && $select_ad['active']==2){{'selected'}}@endif value="2">Select Ad Status</option>
                                    <option @if(!empty($select_ad) && $select_ad['active']==0){{'selected'}}@endif value="0">Inactive</option>
                                    <option  @if(!empty($select_ad) && $select_ad['active']==1){{'selected'}}@endif value="1">Active</option>
                                    </select>
                        </div>
                        <div style="display: inline-block; margin-left : 15px;">
                                <select id="treatySelect" name="real_user" class="filter selectpicker">
                                    <option  @if(!empty($select_ad) && $select_ad['real_user']==2){{'selected'}}@endif value="2">Select Ad creator</option>
                                    <option @if(!empty($select_ad) && $select_ad['real_user']==0){{'selected'}}@endif value="0">Comunity Manager</option>
                                    <option @if(!empty($select_ad) && $select_ad['real_user']==1){{'selected'}}@endif value="1">Real User</option>
                                    </select>
                        </div>
                         <div style="display: inline-block; margin-left : 15px;">
                             <select class="form-control selectpicker" name="ad_type" id="adType" onchange="this.form.submit()">
                                    <option value="0">Select Ad Scenario</option>
                                    <option value="1" @if(!empty($select_ad) && $select_ad['ad_type']==1){{'selected'}}@endif>Rent a property</option>
                                    <option value="2"@if(!empty($select_ad) && $select_ad['ad_type']==2){{'selected'}}@endif>Share an accomodation</option>
                                    <option value="3"@if(!empty($select_ad) && $select_ad['ad_type']==3){{'selected'}}@endif>Seek to rent a property</option>
                                    <option value="4"@if(!empty($select_ad) && $select_ad['ad_type']==4){{'selected'}}@endif>Seek to share an accomodation</option>
                                    <option value="5"@if(!empty($select_ad) && $select_ad['ad_type']==5){{'selected'}}@endif>Seek someone to search together for a property</option>
                                </select>
                         </div>

                    </div>
                     </form>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>

                                <th>S. No.</th>
                                <th>Nombre de vue</th>
                                <th>Pays by IP</th>
                                <th>Ad Title</th>
                                <th>Ad Description</th>
                                <th>Posted By</th>
                                <th>Promotion Type</th>
                                <th>Community Name</th>
                                <th>Short url</th>
                                <th>Date de modification</th>
                                <th>User registered</th>
                                <th>Mark As Featured</th>
                                <th>Checked</th>
                                <th>
                                    @php
                                    if(!empty($select_ad)){
                                        $adTypeLink = 'ad_type=' . $select_ad['ad_type'] . '&';
                                    } else {
                                        $adTypeLink = '';
                                    }
                                    @endphp
                                    @if(!empty($shorting['short']) && $shorting['short']=='date' && !empty($shorting['type']) && ($shorting['type']=='desc' || $shorting['type']=='DESC'))
                                    <a href="{{url(getConfig('admin_prefix') . '/adList?' . $adTypeLink . 'short=date&type=asc')}}">Created Date <i class="fa fa-sort-asc"></i></a>
                                    @elseif(!empty($shorting['short']) && $shorting['short']=='date' && !empty($shorting['type']) && ($shorting['type']=='asc' || $shorting['type']=='ASC'))
                                    <a href="{{url(getConfig('admin_prefix') . '/adList?' . $adTypeLink . 'short=date&type=desc')}}">Created Date <i class="fa fa-sort-desc"></i></a>
                                    @elseif(empty($shorting))
                                    <a href="{{url(getConfig('admin_prefix') . '/adList?' . $adTypeLink . 'short=date&type=asc')}}">Created Date <i class="fa fa-sort-asc"></i></a>
                                    @else
                                    <a href="{{url(getConfig('admin_prefix') . '/adList?' . $adTypeLink . 'short=date&type=asc')}}">Created Date <i class="fa fa-sort"></i></a>
                                    @endif
                                </th>
                                <th>Action</th>
                            </tr>
                            @if(!empty($adList))
                            @foreach($adList as $key => $ad)
                            <tr>

                                <td>{{$adList->firstItem() + $key}}</td>
                                <td>{{$ad->view}}</td>
                                <td>{{$ad->user->ip_country}}</td>
                                <td><a title="View" class="action-toggle-on" href="{{ adUrl($ad->id) }}">{{$ad->title}}</a></td>
                                <td class="ad-descripion">@if(!empty($ad->description)){{$ad->description}}@endif</td>
                                <td>@if(!empty($ad->user))<a href="{{url(getConfig('admin_prefix') . '/user_profile/'.base64_encode($ad->user->id))}}">{{$ad->user->first_name}}@if(!empty($ad->user->last_name)){{' ' . $ad->user->last_name}}@endif</a>@endif</td>
                                <td>
                                    @if(!empty($ad->boosted))
                                    @if($ad->boosted == 1)
                                    <span class="label label-warning">Boosted</span>
                                    @else
                                    <span class="label label-success">Premium</span>
                                    @endif
                                    @else
                                    <span class="label label-info">Basic</span>
                                    @endif
                                </td>
                                <td>{{getComunityName($ad->id)}}</td>
                                <td>{{$ad->short_url}}</td>
                                <td>{{$ad->updated_at}}</td>
                                 <td class="text-center">
                                    <input disabled="true" type="checkbox" @if(!empty($ad->user->email)){{"checked=checked"}}@endif >
                                </td>
                                <td class="text-center">
                                    <input class="custom-checkbox-featured" id="si-checkbox-{{$ad->id}}" @if($ad->scenario_id==3) disabled="true" @endif type="checkbox" @if(!empty($ad->is_featured)){{"checked=checked"}}@endif value="{{base64_encode($ad->id)}}">
                                </td>
                                <td class="action-td">
                                    @if($ad->ad_treaty == 1)
                                    <a title="Mark as not checked" class="action-toggle-on" href="{{url(getConfig('admin_prefix') . '/checkAd/'. $ad->id .'/0')}}"><i class="fa fa-toggle-on"></i></a>
                                    @else
                                    <a title="Mark as checked" class="action-toggle-off" href="{{url(getConfig('admin_prefix') . '/checkAd/'. $ad->id .'/1')}}"><i class="fa fa-toggle-off"></i></a>
                                    @endif
                                </td>
                                <td>{{date('d M Y - H:i:s', strtotime(adjust_gmt($ad->created_at)))}}</td>
                                <td class="action-td">
                                    @if(!empty($ad->ad_details->property_type))
                                    <a title="View" class="action-toggle-on" href="{{ adUrl($ad->id) }}"><i class="fa fa-eye"></i></a>
                                    @endif
                                    @if(!empty($ad->ad_details->property_type))
                                    <a title="Boost" class="action-toggle-on" href="{{getConfig('admin_prefix')}}/boostAd?ad_id={{$ad->id}}"><i class="fa fa-arrow-circle-o-up"></i></a>
                                    @endif

                                    @if(!empty($ad->admin_approve))
                                    <a title="Disapprove" data-id="{{$ad->id}}" class="action-toggle-on deactive_ad" href="{{url(getConfig('admin_prefix') . '/siteusers/activeDeactiveAd/'.base64_encode($ad->id).'/0')}}"><i class="fa fa-toggle-on"></i></a>
                                    @else
                                    <a title="Approve" class="action-toggle-off" href="{{url(getConfig('admin_prefix') . '/siteusers/activeDeactiveAd/'.base64_encode($ad->id).'/1')}}"><i class="fa fa-toggle-off"></i></a>
                                    @endif
                                     <a title="Edit" class="action-toggle-on" href="{{route('admin.create-ad') . '?ad_id=' . $ad->id}}"><i class="fa fa-pencil"></i></a>
                                      <a title="delete" class="action-toggle-on delete" href="javascript:" data-id="{{$ad->id}}"><i class="fa fa-trash"></i></a>
                                      <a title="Envoyer Message" class="action-toggle-on send_message" href="javascript:" data-id="{{$ad->id}}"><i class="fa fa-envelope"></i></a>
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
