@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/request-to-visit.js') }}"></script>
@endpush

@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 user-visit-main-outer">
                <div class="user-visit-main-hdr">
                    <h4>@if(!empty($ad->title)){{$ad->title}} @endif</h4>
                </div>

                <ul class="nav second-tab-menu m-t-2">
                    @if($ad->scenario_id == '5')
                    <li @if($type == 'sent') class="active" @endif><a href="{{ url('/demandes/envoyes/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{ __('request.request_sent') }} </a></li>
                    <li @if($type == 'received') class="active" @endif><a href="{{ url('/demandes/recu/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{ __('request.request_received') }}</a></li>
                    <li @if($type == 'accepted') class="active" @endif><a href="{{ url('/demandes/accepte/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{ __('request.accepted_request') }} </a></li>
                    <li @if($type == 'declined') class="active" @endif><a href="{{ url('/demandes/refuse/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{ __('request.declined_request') }} </a></li>
                    @else
                    <li @if($type == 'sent') class="active" @endif><a href="{{ url('/demandes/envoyes/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{ __('request.request_sent') }} </a></li>
                    <li @if($type == 'visit') class="active" @endif><a href="{{ url('/demandes/visite/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{ __('request.request_to_visit') }}</a></li>
                    <li @if($type == 'accepted') class="active" @endif><a href="{{ url('/demandes/accepte/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{ __('request.accepted_request_to_visit') }} </a></li>
                    <li @if($type == 'declined') class="active" @endif><a href="{{ url('/demandes/refuse/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{ __('request.declined_request_to_visit') }} </a></li>
                    @endif
                </ul>
                @if ($message = Session::get('error'))

                <div class="alert alert-danger fade in alert-dismissable" style="margin-top:20px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                    {{ $message }}
                </div>

                @endif

                @if ($message = Session::get('status'))

                <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                    {{ $message }}
                </div>

                @endif

                @if(!empty($requests) && count($requests) > 0)
                <div class="tab-content">
                    <div id="vistiTab-2" class="tab-pane fade in active">
                        <div class="visitTab-cont visitTab-cont2">
                            <div class="visitTab-cont-hdr m-t-2">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-8 col-md-8">
                                        <h6>{{$requests->total()}} {{ __('request.result') }}</h6>
                                    </div>
                                </div>
                            </div>
                            @if($type == 'sent')
                                @include('requests.request-sent')
                            @else
                                @include('requests.request-to-visit')
                            @endif
                        </div>
                    </div>
                </div>
                {{ $requests->links('vendor.pagination.default') }}

                <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel"></h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="modal-btn-yes">{{ __('request.yes') }}</button>
                                <button type="button" class="btn btn-default" id="modal-btn-no">{{ __('request.no') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

