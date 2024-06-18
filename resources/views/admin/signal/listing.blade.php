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
            Signal
            <small>Manage Signal</small>
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
                        <h3 class="box-title">Signal Listing</h3>
                        <div style="display: inline-block; margin-left : 15px;">
                                <select id="treatySelect" class="selectpicker">
                                    <option @if($treaty == 0)  selected @endif value="0">Unchecked</option>
                                    <option @if($treaty == 1)  selected @endif value="1">Checked</option>
                                    </select>
                                </div>
                        <div class="box-tools">
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


                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding db-table-outer">
                        <table class="table table-hover">
                            <tr>
                                <th>S. No.</th>
                                <th>
                                    @if($sort == 'user_id' && $order == 'asc')
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=user_id&foreign=users&order=desc&ffield=first_name') }}">User name <i class="fa fa-sort-asc"></i></a>
                                    @elseif($sort == 'user_id' && $order == 'desc')
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=user_id&foreign=users&order=asc&ffield=first_name') }}">User name <i class="fa fa-sort-desc"></i></a>
                                    @elseif($sort == 'user_id' && $order != 'asc' && $order != 'desc')
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=user_id&foreign=users&order=desc&ffield=first_name') }}">User name <i class="fa fa-sort-asc"></i></a>
                                    @else
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=user_id&foreign=users&order=desc&ffield=first_name') }}">User name <i class="fa fa-sort"></i></a>
                                    @endif
                                </th>
                                <th>
                                    Signal User
                                </th>
                                <th>
                                    @if($sort == 'ad_id' && $order == 'asc')
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=ad_id&foreign=ads&order=desc&ffield=title') }}">Ad <i class="fa fa-sort-asc"></i></a>
                                    @elseif($sort == 'ad_id' && $order == 'desc')
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=ad_id&foreign=ads&order=asc&ffield=title') }}">Ad <i class="fa fa-sort-desc"></i></a>
                                    @elseif($sort == 'ad_id' && $order != 'asc' && $order != 'desc')
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=ad_id&foreign=ads&order=desc&ffield=title') }}">Ad <i class="fa fa-sort-asc"></i></a>
                                    @else
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=ad_id&foreign=ads&order=desc&ffield=title') }}">Ad <i class="fa fa-sort"></i></a>
                                    @endif
                                </th>
                                <th>
                                    <a href="javascript: ">Comment </a>
                                </th>
                                <th>
                                    <a href="javascript: ">Tag </a>
                                </th>
                                <th>
                                    @if($sort == 'created_at' && $order == 'asc')
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=created_at&order=desc') }}">Date <i class="fa fa-sort-asc"></i></a>
                                    @elseif($sort == 'created_at' && $order == 'desc')
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=created_at&order=asc') }}">Date <i class="fa fa-sort-desc"></i></a>
                                    @elseif($sort == 'created_at' && $order != 'asc' && $order != 'desc')
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=created_at&order=desc') }}">Date <i class="fa fa-sort-asc"></i></a>
                                    @else
                                    <a href="{{ url(getConfig('admin_prefix') . '/signal?sort=created_at&order=desc') }}">Date <i class="fa fa-sort"></i></a>
                                    @endif
                                </th>
                                <th>
                                    <a href="javascript: ">Activate or desactivate Ad </a>
                                </th>
                                <th>
                                    <a href="javascript: ">Deactivate Comment</a>
                                </th>
                                 <th>
                                    <a href="javascript: ">Checked</a>
                                </th>
                                <th>Done</th>
                                <th>Action</th>
                            </tr>
                            @if(!empty($list))
                            @foreach($list as $key => $s)
                            @if(!is_null($s->ad))
                            <tr>
                                <td>{{$s->id}}</td>
                                <td><a href="{{url(getConfig('admin_prefix') . '/siteusers/user_profile/'.base64_encode($s->ad_user->id))}}">{{$s->ad_user->first_name}}@if(!empty($s->ad_user->last_name)){{' '. $s->ad_user->last_name}}@endif</a></td>
                                <td>@if(isset($s->user))
                                {{$s->user->first_name}}@if(!empty($s->user->last_name)){{' '. $s->user->last_name}}@endif
                                @endif</td>
                                <td><a href="{{ adUrl($s->ad->id) }}" class="reload-page" target="_blank">{{$s->ad->title}}</a></td>
                                <td>{{$s->commentaire}}</td>
                                <td>
                                    <ul style="list-style: none">
                                    @if($s->tags)
                                    @foreach($s->tags as $tag)
                                        <li>
                                            {{ $tag->tag->name }}
                                        </li>
                                    @endforeach
                                    @endif
                                    </ul>
                                </td>
                                <td>
                                    {{ adjust_gmt($s->created_at) }}
                                </td>

                                <td class="action-td">
                                    @if(!empty($s->ad->admin_approve))
                                    <a title="Disapprove" class="action-toggle-on deactive_ad" href="{{url(getConfig('admin_prefix') . '/siteusers/activeDeactiveAd/'.base64_encode($s->ad->id).'/0') . '?signal=true'}}" data-id="{{$s->ad->id}}"><i class="fa fa-toggle-on approve-button"></i></a>
                                    @else
                                    <a title="Approve" class="action-toggle-off approve-button"" href="{{url(getConfig('admin_prefix') . '/siteusers/activeDeactiveAd/'.base64_encode($s->ad->id).'/1') . '?signal=true'}}"><i class="fa fa-toggle-off approve-button"></i></a>
                                    @endif
                                </td>
                                 <td class="action-td">
                                     @if((is_null($s->ad->admin_approve) || $s->ad->admin_approve==0) && !empty($s->deactive_comment))
                                        {{$s->deactive_comment}}
                                     @endif

                                 </td>
                                <td class="action-td">
                                    @if($s->treaty == 1)
                                    <a title="Untreat" class="action-toggle-on" href="{{url(getConfig('admin_prefix') . '/treatSignalAd/'. $s->id .'/0')}}"><i class="fa fa-toggle-on"></i></a>
                                    @else
                                    <a title="Treat" class="action-toggle-off" href="{{url(getConfig('admin_prefix') . '/treatSignalAd/'. $s->id .'/1')}}"><i class="fa fa-toggle-off"></i></a>
                                    @endif
                                </td>
                                <td>
                                    @if($s->done == 1)
                                    Done
                                    @else
                                    Not Done
                                    @endif
                                </td>
                                <td class="action-td">
                                    @if(!empty($s->ad->ad_details->property_type))
                                    <a title="View" data-id="{{$s->ad->id}}" class="action-toggle-on" href="{{ route('view.ad', [str_slug($s->ad->ad_details->property_type->property_type),$s->ad->url_slug . '-' . $s->ad->id]) }}"><i class="fa fa-eye"></i></a>
                                    @endif
                                     <a title="Edit" class="action-toggle-on" href="{{route('admin.create-ad') . '?ad_id=' . $s->ad->id}}"><i class="fa fa-pencil"></i></a>
                                     <a title="delete" class="action-toggle-on delete" href="javascript:" data-id="?ad_id={{$s->ad->id}}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @else
                            <tr>{{'No record found'}}</tr>
                            @endif
                        </table>
                    </div>
                    <div class="pull-right">
                        @if($list)
                        {{ $list->links('vendor.pagination.bootstrap-4') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete ad?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modal-delete-btn-yes">Yes</button>
                <button type="button" class="btn btn-default" id="modal-delete-btn-no">No</button>
            </div>
        </div>
    </div>
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
@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('.delete').on('click', function() {
            $('#delete-modal').modal('show');
            $('#modal-delete-btn-yes').attr('data-id', $(this).attr('data-id'));
        });
        $('#modal-delete-btn-yes').on('click', function() {
            $('#delete-modal').modal('hide');
            location.href = $(this).attr('data-id');
        });
        $('#modal-delete-btn-no').on('click', function() {
            $('#delete-modal').modal('hide');
        });
        $('#treatySelect').on("change", function(){
            $('#treaty').val($('#treatySelect').val());
            $("#searchForm").submit();
        });

        /*$(".disapprove-button").on("click", function(){
            var ad_id = $(this).attr("data-id");
            $('#ad_id').val(ad_id);
            $('#modalDisapproveComment').modal('show');
        })*/

        /*$('#submitApproveDisapprove').on('click', function(){
            $('#formApprove').submit();
        });*/

        $('#comment').on('keyup', function(){
            if($(this).val() != "") {
                $('#submitApproveDisapprove').attr('disabled', false);
            } else {
                 $('#submitApproveDisapprove').attr('disabled', true);
            }
        });
    });
</script>
@endpush
@endsection
