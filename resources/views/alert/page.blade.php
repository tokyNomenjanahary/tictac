@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
	<link href="{{ asset('css/documents.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alert.css') }}" rel="stylesheet">

@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/alert.js') }}"></script>
@endpush

@section('content')
<section class="inner-page-wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 user-visit-main-outer">
                <ul class="nav second-tab-menu m-t-2">
                    <li class="active">
                        <a href="{{ url('/my-document') }}">{{ __('alert.title') }} ({{count($savedAlerts)}})</a>
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

                <div class="tab-content">
                    <div id="vistiTab-2" class="tab-pane fade in active">
                        <div class="visitTab-cont visitTab-cont2">
                                @include('alert.list-selected')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="delete-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">{{ __('alert.delete_alert_title') }}</h4>
            </div>
            <div class="modal-body">
				<div><p>{{ __('document.delete_document_message') }}</p></div>
                <div class="pr-poup-ftr">
					<div class="submit-btn-2"><input data-dismiss="modal" id="nodeleteDocs" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('document.no') }}"></div>
					<div class="submit-btn-2"><input id="yesdeleteDocs" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('document.yes') }}"></div>
				</div>
            </div>
        </div>
    </div>
</div>

@endsection
