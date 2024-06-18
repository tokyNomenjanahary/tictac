@extends('layouts.appinner')

<!-- Push a script dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/custom_seek.css') }}" rel="stylesheet">
    <link href="{{ asset('css/documents.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" crossorigin="anonymous"> --}}
@endpush

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src="{{ asset('js/documents.js') }}"></script>
@endpush

@section('content')
    <section class="inner-page-wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 user-visit-main-outer">
                    <ul class="nav second-tab-menu m-t-2">
                        <li class="active">
                            <a href="">{{ __('document.title') }} ({{ count($savedDocuments) }})</a>
                        </li>
                    </ul>
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger fade in alert-dismissable" style="margin-top:20px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close"
                                title="{{ __('close') }}">×</a>
                            {{ $message }}
                        </div>
                    @endif

                    @if ($message = Session::get('status'))
                        <div class="alert alert-success fade in alert-dismissable" style="margin-top:20px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close"
                                title="{{ __('close') }}">×</a>
                            {{ $message }}
                        </div>
                    @endif

                    <div class="tab-content">
                        <div id="vistiTab-2" class="tab-pane fade in active">
                            <div class="visitTab-cont visitTab-cont2">
                                <div class="visitTab-cont-hdr m-t-2">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-8 col-md-8">
                                            <a href="javascript:void(0);" id="remove_document" class="btn btn-danger"
                                                style="display:inline-block;"><i class="fa-solid fa-trash"
                                                    style="color:white;font-size:15px;padding:0px"></i></a>
                                            <a href="javascript:void(0);" id="add_document" class="btn btn-primary"
                                                style="display:inline-block;"><span class="glyphicon glyphicon-plus"
                                                    style="font-size:15px;;"></span></a>

                                        </div>
                                    </div>
                                </div>

                                @include('documents.list-selected')
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
                    <h4 class="modal-title text-center">{{ __('document.delete_document_title') }}</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <p>{{ __('document.delete_document_message') }}</p>
                    </div>
                    <div class="pr-poup-ftr">
                        <div class="submit-btn-2"><input data-dismiss="modal" id="nodeleteDocs" type="button"
                                class="submit-btn-2 reg-next-btn" value="{{ __('document.no') }}"></div>
                        <div class="submit-btn-2"><input id="yesdeleteDocs" type="button" class="submit-btn-2 reg-next-btn"
                                value="{{ __('document.yes') }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="documentModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">{{ __('document.new_document_title') }}</h4>
                </div>
                <div class="modal-body">
                    <form id="new-document-form" method="post" enctype="multipart/form-data"
                        action="{{ url('/add_document') }}" class="super-form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label" for="apply-motivation">{{ __('document.type') }} *</label>
                            <input type="text" id="type_document" name="type_document" class="form-control"
                                placeholder="{{ __('document.document_type') }}">
                            <div id="error-type_document" class="error alert-danger">{{ __('document.error_required') }}
                            </div>
                        </div>
                        <div class="form-group" id="">
                            <div class="upload-file-outer">
                                <div class="file-loading">
                                    <input id="documents_file" name="documents_file" type="file" class="file">
                                </div>
                            </div>
                            <div id="error-documents_file" class="error alert-danger">
                                {{ __('document.error_one_document') }}</div>


                        </div>
                        <div class="pr-poup-ftr">
                            <div class="submit-btn-2"><a data-dismiss="modal"
                                    href="javascript:void(0);">{{ __('document.cancel') }}</a></div>
                            <div class="submit-btn-2"><input id="submitDocument" type="submit"
                                    class="submit-btn-2 reg-next-btn" value="{{ __('document.save') }}"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div id="documentEditModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center">{{ __('document.edit_document_title') }}</h4>
                </div>
                <div class="modal-body">
                    <form id="edit-document-form" method="post" enctype="multipart/form-data"
                        action="{{ url('/edit_document') }}" class="super-form">
                        {{ csrf_field() }}
                        <input type="hidden" id="id_doc" name="id_doc" value="0">
                        <div class="form-group">
                            <label class="control-label" for="apply-motivation">{{ __('document.type') }} *</label>
                            <input type="text" id="edit_type_document" name="edit_type_document" class="form-control"
                                placeholder="{{ __('document.document_type') }}">
                            <div id="error-edit_type_document" class="error alert-danger">
                                {{ __('document.error_required') }}</div>
                        </div>
                        <div class="form-group" id="">
                            <div class="upload-file-outer">
                                <div class="file-loading">
                                    <input id="edit-documents_file" name="edit-documents_file" type="file"
                                        class="file">
                                </div>
                            </div>
                            <div id="error-edit-documents_file" class="error alert-danger">
                                {{ __('document.error_one_document') }}</div>


                        </div>
                        <div class="pr-poup-ftr">
                            <div class="submit-btn-2"><a data-dismiss="modal"
                                    href="javascript:void(0);">{{ __('document.cancel') }}</a></div>
                            <div class="submit-btn-2"><input id="submitEditDocument" type="submit"
                                    class="submit-btn-2 reg-next-btn" value="{{ __('document.save') }}"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
