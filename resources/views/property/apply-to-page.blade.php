@extends('layouts.app')

@section('content')
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
@endpush
@push('scripts')
    <script src="/js/applyTo.js"></script>
    <script src="/js/save_docs.js"></script>
@endpush
@include("common.fileInputMessages")
<section class="section-connexion inner-page-wrap section-center">
    <div class="container container-connexion">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2">
                <div class="rent-property-form-hdr">
                    <div class="rent-property-form-heading">
                        <h6>{{ __('addetails.contact_to_ad') }}</h6>
                    </div>
                </div>
                <div class="rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">
                    <div id="successSend"></div>
                    <form id="applyToForm" method="post" enctype="multipart/form-data" action="/application/add" data-message="{{__('addetails.applySuccess')}}" class="super-form">
                    {{ csrf_field() }}
                    <input type="hidden" name="sender_ad_id" value="">
                    <input type="hidden" name="ad_id" value="{{base64_encode($ad->id)}}">
                    <input type="hidden" id="save_doc" name="save_doc" value="0">
                    <div class="form-group">
                        <label class="control-label" for="apply-motivation">{{ __('addetails.motivation') }}</label>
                        <textarea id="motivation" name="motivation" class="form-control" placeholder="{{ __('Motivation') }}"></textarea>
                    </div>

                    @foreach($ad_documents as $ad_document)
                        <div class="form-group" id="document-{{$ad_document->id}}">
                            <div>
                                <label class="control-label">{{ $ad_document->document_name }} @if($ad_document->document_required == 1)
                                    *
                                    @endif
                                </label>
                                @if(isset($savedDocuments) && !empty($savedDocuments) && count($savedDocuments)>0)
                                    <a data-id="{{$ad_document->id}}" class="saved_doc-button" style="float:right;" href="javascript:void(0);">
                                        <i class="glyphicon glyphicon-floppy-disk icon-size"></i>
                                        <span>{{ __('document.saved_docs') }}<span class="nb_saved_doc" style="display:none;color:red;">(<span class="number_saved_doc"></span>{{ __('document.selected') }})</span></span>
                                        <div class="saved_doc_selected"></div>
                                    </a>
                                @endif
                                
                           </div>
                            <div class="upload-file-outer">
                                <div class="file-loading">
                                    <input id="file-{{$ad_document->id}}" data-doc="{{$ad_document->id}}" type="file" class="application-documents" 
                                    @if($ad_document->document_required == 1)
                                    data-min-file-count="0"
                                    @endif
                                    name="document_{{$ad_document->id}}" accept="image/*">
                                </div>
                            </div>
                            
                        </div>
                        <span id="key-file-{{$ad_document->id}}"></span>
                    @endforeach
                    <div class="pr-poup-ftr">
                        <div class="submit-btn-2"><a data-dismiss="modal" href="{{adUrl($ad->id)}}">{{ __('addetails.cancel') }}</a></div>
                        <div class="submit-btn-2"><input id="submitApply" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('addetails.apply') }}"></div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <div id="save_doc-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">{{ __('document.save_docs') }}</h4>
                </div>
                <div class="modal-body">
                    <div><p>{{ __('document.save_docs_message') }}</p></div>
                    <div class="pr-poup-ftr">
                        <div class="submit-btn-2"><input id="noSaveDocs" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('document.no') }}"></div>
                        <div class="submit-btn-2"><input id="yesSaveDocs" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('document.yes') }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="saved_doc-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">{{ __('document.saved_docs_title') }}</h4>
                </div>
                <div class="modal-body" id="modal_body_saved_doc">
                    @if(isset($savedDocuments) && !empty($savedDocuments))
                        @foreach($savedDocuments as $savedDocument)
                            <a href="javascript:void(0);" class="saved_document">
                                <div class="card" style="margin-bottom:10px;width:28rem;display:inline-block;">
                                  <div style="position:relative;">
                                        <span class="badge shoppingBadge shoppingBadge-custom"style="background-color:rgb(51,122,183);position:absolute;right:5px;top:0px;">{{$savedDocument->type}}</span>
                                        <input id="doc-{{$savedDocument->id}}" data-id="{{$savedDocument->id}}" type="checkbox" class="checkDocument" style="display:none;width:20px;height:20px;position:absolute;left:5px;top:0px;"/>
                                        <img class="card-img-top" width="280" height="150;" src="{{URL::asset('uploads/tempfile/' . $savedDocument->name)}}" alt="Card image cap">
                                 </div>
                                  <div class="card-body" style="margin-top:5px;">
                                    <a target="_blank" href="{{URL::asset('uploads/tempfile/' . $savedDocument->name)}}" class="btn btn-primary"> {{ __('document.view_doc') }}</a>
                                  </div>
                                </div>
                            </a>
                        @endforeach
                    @endif
                    <div class="pr-poup-ftr">
                        <div class="submit-btn-2"><a data-dismiss="modal" href="javascript:void(0);">{{ __('document.cancel') }}</a></div>
                        <div class="submit-btn-2"><input data-id="" id="SavedDocSelectButton" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('document.select') }}"></div>
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

