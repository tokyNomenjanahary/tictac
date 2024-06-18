@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/my-comments.js') }}"></script>
@endpush

@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 user-visit-main-outer">
                <ul class="nav second-tab-menu m-t-2">
                    <li @if($type == 'posted') class="active" @endif>
                        <a href="{{ url('/mes-commentaires/poste') }}">{{ __('comments.posted') }}</a>
                    </li>
                    <li @if($type == 'received') class="active" @endif>
                        <a href="{{ url('/mes-commentaires/' ) }}">{{ __('comments.received') }}</a>
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

                @if(!empty($cs) && count($cs) > 0)
                <div class="tab-content">
                    <div id="vistiTab-2" class="tab-pane fade in active">
                        <div class="visitTab-cont visitTab-cont2">
                            <div class="visitTab-cont-hdr m-t-2">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-8 col-md-8">
                                        <h6>{{count($cs)}} {{ __('comments.results') }}</h6>
                                    </div>
                                </div>
                            </div>
                                @include('comments.list-selected')
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

