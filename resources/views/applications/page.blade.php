@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/applications.js') }}"></script>
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

                    <li @if($type == 'validated') class="active" @endif>
                    	<a href="{{ url('/candidatures-annonce/valide/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{ __('application.accepted') }} </a>
                    </li>
                    <li @if($type == 'waiting') class="active" @endif>
                    	<a href="{{ url('/candidatures-annonce/en-attente/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{ __('application.waiting') }}</a>
                    </li>
                    <li @if($type == 'declined') class="active" @endif>
                    	<a href="{{ url('/candidatures-annonce/refuse/' . str_slug($ad->title,'-') . '-' . $ad->id) }}">{{__('application.declined') }} </a>
                    </li>
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

                @if(!empty($apps) && count($apps) > 0)
                <div class="tab-content">
                    <div id="vistiTab-2" class="tab-pane fade in active">
                        <div class="visitTab-cont visitTab-cont2">
                            <div class="visitTab-cont-hdr m-t-2">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-8 col-md-8">
                                        <h6>{{count($apps)}} {{ __('application.results') }}</h6>
                                    </div>
                                </div>
                            </div>
                                @include('applications.list')
                        </div>
                    </div>
                </div>
                <div class="pull-right">
                    @if(!empty($apps))
                    {{ $pagination }}
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
