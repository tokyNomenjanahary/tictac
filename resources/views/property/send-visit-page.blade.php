@extends('layouts.app')

@section('content')
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
@endpush
@push('scripts')
    <script src="/js/sendVisit.js"></script>
@endpush
<section class="section-connexion inner-page-wrap section-center">
    @if(!is_null(getParameter("visit_id")))
    <input type="hidden" id="visit_id" value="{{getParameter('visit_id')}}">
    @endif
    <div class="container container-connexion">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
                <div class="rent-property-form-hdr">
                    <div class="rent-property-form-heading">
                        <h6>{{ __('addetails.choose_time_slot') }}</h6>
                    </div>
                </div>
                <div class="rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
                    <div id="successSend"></div>
                    <input type="hidden" id="current_sender_ad_id" name="sender_ad_id" value="">
                    <input type="hidden" id="current_ad_id"  name="ad_id" value="{{$ad->id}}">
                    <div id="sendRequestToVisitModel" class="model-body-visit">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var messages = {'must_auth' : "{{__('addetails.must_auth')}}", 'sent_message' : "{{__('messages.sent_message')}}", 'sent_request' : "{{__('addetails.sent_request')}}", "add_favourite" : "{{__('addetails.add_favourite')}}", "remove_favourite" : "{{__('addetails.remove_favourite')}}", "edit_question" : "{{__('addetails.edit_question')}}", "delete_question" : "{{__('addetails.delete_question')}}", "respond_question" : "{{__('addetails.respond_question')}}"};
</script>
@endSection

