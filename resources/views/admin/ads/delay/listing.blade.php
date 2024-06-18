@extends('layouts.adminappinner')



@push('styles')
    <style>
        .no-display {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            console.log('load', $('#table').data('count_exp'))
            $('.count-ad').text($('#table').data('count_exp'))

            console.log('load', $('#table').data('count_des'))
            $('#count-ad-desactive').text($('#table').data('count_des'))

            console.log('load', $('#table').data('admin'))
            let admin = $('#table').data('admin')

            $('#modal-btn-envoi-mail-expire').click(function () {

                console.log('#modal-btn-envoi-mail-expire')

                let btn = $(this);
                btn.prop('disabled', true);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "GET",
                    url: `/${admin}/delay-annonces-mail/all?expire=1`,
                    success: function(data) {
                        console.log('#confirmation-modal', data.statut)

                        if (data.statut) {
                            $('#alert-success').removeClass( "d-none no-display" );
                            btn.removeAttr("disabled");
                            btn.prop('disabled', false);

                            // $("#confirmation-modal").modal('hide');
                        }else {
                            $('#alert-error').removeClass( "d-none no-display" );
                        }
                    },
                    error: function(data) {
                        $('#alert-error').removeClass( "d-none no-display" );
                    }
                });
            })

            $('#modal-btn-envoi-mail-desactive').click(function () {

                console.log('#modal-btn-envoi-mail-desactive')

                let btn = $(this);
                btn.prop('disabled', true);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "GET",
                    url: `/${admin}/delay-annonces-mail/all?expire=0`,
                    success: function(data) {
                        console.log('#confirmation-modal', data.statut)

                        if (data.statut) {
                            $('#alert-success').removeClass( "d-none no-display" );
                            btn.removeAttr("disabled");
                            btn.prop('disabled', false);

                            // $("#confirmation-modal").modal('hide');
                        }else {
                            $('#alert-error').removeClass( "d-none no-display" );
                        }
                    },
                    error: function(data) {
                        $('#alert-error').removeClass( "d-none no-display" );
                    }
                });
            })

            $('#btn-envoi-mail-expire').click(function (event) {
                $("#confirmation-modal").modal('show');

                event.preventDefault();
            });


            $('#btn-envoi-mail-desactive').click(function (event) {
                $("#confirmation-modal").modal('show');

                event.preventDefault();
            });

        })
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
            <div class="row">
                <div class="col-6">
                    <h1>
                        Ads
                        <small>Manage Ads</small>
                    </h1>
                </div>
                <div class="col-6">
                    <a href="{{ route('delay.annonce.mail.all', ['expire' => 1]) }}" style="margin: 20px;"
                       id="btn-envoi-mail-expire" class="btn btn-primary">Envoi Mail Expire</a>
                    <a href="{{ route('delay.annonce.mail.all', ['expire' => 0]) }}" style="margin: 20px;"
                       id="btn-envoi-mail-desactive" class="btn btn-primary">Envoi Mail Desactive</a>
                </div>
            </div>
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
                            <table class="table table-hover" id="table"
                                   data-admin="{{ getConfig('admin_prefix') }}"
                                   data-count_exp="{{ $count_expire }}"
                                   data-count_des="{{ $count_desactve }}">
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Ad Title</th>
                                        <th>Ad Description</th>
                                        <th>Posted By</th>
                                        <th>State</th>
                                        <th>Updated Date</th>

                                        {{--<th>Action</th>--}}
                                    </tr>
                                </thead>

                                <tbody>

                                    @if(!empty($adList))

                                        @foreach($adList as $key => $ad)

                                            {{--@if($ad->updated_at->addDays($daysToAdd) < $now)--}}
                                                <tr>
                                                    <td>{{$ad->id}} | {{$ad->status}}</td>
                                                    <td><a title="View" class="action-toggle-on" href="{{ adUrl($ad->id) }}">{{$ad->title}}</a></td>
                                                    <td class="ad-descripion">@if(!empty($ad->description)){{$ad->description}}@endif</td>
                                                    <td>@if(!empty($ad->user))<a href="{{url(getConfig('admin_prefix') . '/user_profile/'.base64_encode($ad->user->id))}}">{{$ad->user->first_name}}@if(!empty($ad->user->last_name)){{' ' . $ad->user->last_name}}@endif</a>@endif</td>
                                                    <td>
                                                        <span class="label label-warning">Expire</span>

                                                        {{--@if($dateExp > $ad->updated_at)
                                                            <span class="label label-warning">Expire</span>
                                                        @elseif($dateExp < $ad->created_at)
                                                            <span class="label label-success">Not Expire</span>
                                                        @else
                                                            <span class="label label-info">Now</span>
                                                        @endif--}}
                                                    </td>
                                                    <td>{{date('d M Y - H:i:s', strtotime(adjust_gmt($ad->updated_at)))}}</td>

                                                    {{--<td class="action-td">
                                                        <a title="Envoyer Message" class="action-toggle-on send_message" href="javascript:" data-id="{{$ad->id}}"><i class="fa fa-envelope"></i></a>
                                                    </td>--}}
                                                </tr>
                                            {{--@endif--}}
                                        @endforeach
                                    @else
                                        <tr><td colspan="8">{{'No record found'}}</td></tr>
                                    @endif
                                </tbody>

                            </table>
                        </div>

                        <div class="pull-right">
                            {{ $adList->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <div class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true" id="confirmation-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p> Vous voulez envoyer un mail à tous les <span class="count-ad" id="count-ad"></span> Annonces Expires ? </p>

                    <div id="alert-success"  class="alert alert-success fade in alert-dismissable d-none no-display">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        Envoi du Mail est successé
                    </div>

                    <div id="alert-error" class="alert alert-danger fade in alert-dismissable d-none no-display">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        Envoi du Mail est erroné
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="modal-btn-envoi-mail-expire">
                        (<span class="count-ad" id="count-ad"></span>) Envoyer Mail Expire</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-envoi-mail-desactive">
                        (<span id="count-ad-desactive"></span>) Envoyer Mail Desactive</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>

@endsection


