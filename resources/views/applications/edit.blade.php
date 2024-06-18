@extends($layout == 'inner' ? 'layouts.appinner' : 'layouts.app')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="{{ asset('css/intlTelInput/intlTelInput.css') }}" rel="stylesheet">
@endpush
<script>
    var appSettings = {};
    @if (!empty($ad_details))
        @if (!empty($ad_details->latitude) && !empty($ad_details->longitude))
            appSettings['long'] = {{ $ad_details->latitude }};
            appSettings['lat'] = {{ $ad_details->longitude }};
            appSettings['address'] = '{{ $ad_details->address }}';
        @endif
        @if (!empty($ad_details->nearby_facilities) && count($ad_details->nearby_facilities) > 0)
            appSettings['nearby'] = [];
            @foreach ($ad_details->nearby_facilities as $nearby)
                appSettings.nearby.push(
                    "{{ $nearby->latitude . '#' . $nearby->longitude . '#' . $nearby->name . '#' . $nearby->nearbyfacility_type }}"
                    );
            @endforeach
        @endif
    @endif
    @if (!empty($AdInfo))
        @if (!empty($AdInfo->latitude) && !empty($AdInfo->longitude))
            appSettings['long'] = {{ $AdInfo->latitude }};
            appSettings['lat'] = {{ $AdInfo->longitude }};
            appSettings['address'] = '{{ $AdInfo->address }}';
        @endif
    @endif
    @if (!empty($address) && !empty($latitude) && !empty($longitude))
        appSettings['long'] = {{ $latitude }};
        appSettings['lat'] = {{ $longitude }};
        appSettings['address'] = '{{ $address }}';
    @endif
</script>

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/intlTelInput/intlTelInput.js') }}"></script>
    <script src="{{ asset('js/seekcompasearch.js') }}"></script>
    <script src="{{ asset('js/edit-application.js') }}"></script>
@endpush

@section('content')
    <section class="inner-page-wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 rent-property-form-outer">
                    <div class="rent-property-form-content edit-application project-form rp-step-1-content white-bg m-t-20">
                        <form id="applyToForm" method="post" enctype="multipart/form-data"
                            action="{{ url('/application/edit') }}" class="super-form">
                            {{ csrf_field() }}
                            <input type="hidden" name="app_id" value="{{ $application->id }}">
                            <input type="hidden" name="ad_id" value="{{ base64_encode($application->ad_id) }}">
                            <input type="hidden" name="sender_ad_id" value="{{ base64_encode($application->sender_id) }}">
                            <div class="heading-underline">
                                <h6>{{ __('Motivation') }}</h6>
                            </div>
                            <div class="form-group">
                                <textarea id="motivation" name="motivation" class="form-control" placeholder="{{ __('Motivation') }}"
                                    value="{{ $application->motivation }}">{{ $application->motivation }}</textarea>
                            </div>
                            @foreach ($ad_docs as $ad_document)
                                <div class="heading-underline">
                                    <h6>{{ $ad_document->element->document_name }} @if ($ad_document->element->document_required == 1)
                                            *
                                        @endif
                                    </h6>
                                </div>
                                <div class="form-group">
                                    <div class="upload-file-outer">
                                        <div class="file-loading">
                                            <input id="file-{{ $ad_document->element->id }}"
                                                data-doc="{{ $ad_document->element->id }}" type="file"
                                                class="application-documents" data-url="{{ $ad_document->content }}"
                                                data-name="{{ $ad_document->element->document_name }}"
                                                @if ($ad_document->element->document_required == 1) data-min-file-count="0" @endif
                                                name="document_{{ $ad_document->element->id }}" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <span id="key-file-{{ $ad_document->element->id }}"></span>
                            @endforeach
                            <div class="pr-poup-ftr">
                                <div class="submit-btn-2"><a data-dismiss="modal" class="form-cancel"
                                        href="javascript:void(0);">{{ __('Cancel') }}</a></div>
                                <div class="submit-btn-2"><input type="submit" class="submit-btn-2 reg-next-btn"
                                        value="{{ __('Save') }}"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
