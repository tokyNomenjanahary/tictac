@push('scripts')
<script src="/js/contact-validator.js"></script>
@endpush

<div id="contact-information-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">{{ __('document.continuer') }}</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="modal-information-text" ></div>
                <div class="pr-poup-ftr">
                    <div class="submit-btn-2"><input id="continueNo" data-dismiss="modal" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('document.no') }}"></div>
                    <div class="submit-btn-2"><input id="continueYes" type="button" class="submit-btn-2 reg-next-btn" value="{{ __('document.yes') }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>