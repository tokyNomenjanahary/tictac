@extends('layouts.app')

@section('content')
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
@endpush
@push('scripts')
    <script src="/js/contacter-annonce.js"></script>
@endpush
<section class="section-connexion inner-page-wrap section-center">
    <div class="container container-connexion">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
                <div class="rent-property-form-hdr">
                    <div class="rent-property-form-heading">
                        <h6>{{ __('addetails.contact') }} @if(is_null($prenom)) {{$ad->user->first_name}} @else {{$prenom}} @endif</h6>
                    </div>
                </div>
                <div class="rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
                    <div id="successSend"></div>
                    <form id="sendMessageForm" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input type="hidden" name="ad_id" value="{{base64_encode($ad->id)}}">
                        <input type="hidden" name="sender_ad_id" value="">
                        @if(!is_null($prenom))
                        <input type="hidden" name="prenom" value="{{$prenom}}">
                        @endif
                        <div class="form-group">
                            <label class="control-label" for="note">{{ __('addetails.your_message') }}</label>
                            <textarea id="message" name="message" class="form-control" placeholder="{{ __('Message') }}" rows="6"></textarea>
                        </div>
                        <div class="ad-detail-ftr"><p>{{ __('addetails.max_message') }}</p></div>
                        <div class="pr-poup-ftr">
                            <div class="submit-btn-2"><a href="{{adUrl($ad->id)}}">{{ __('addetails.cancel') }}</a></div>
                            <div class="submit-btn-2 reg-next-btn"><a href="javascript:void(0);" id="submit-send-message">{{ __('addetails.send_message') }}</a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    var messages = {'must_auth' : "{{__('addetails.must_auth')}}", 'sent_message' : "{{__('messages.sent_message')}}", 'sent_request' : "{{__('addetails.sent_request')}}", "add_favourite" : "{{__('addetails.add_favourite')}}", "remove_favourite" : "{{__('addetails.remove_favourite')}}", "edit_question" : "{{__('addetails.edit_question')}}", "delete_question" : "{{__('addetails.delete_question')}}", "respond_question" : "{{__('addetails.respond_question')}}"};
</script>
@endSection


