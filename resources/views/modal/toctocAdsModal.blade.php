@push("scripts")
{{-- <script src="{{ asset('js/message_flash.js') }}"></script> --}}
<script src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1649163065/js/message_flash_cb80hf.js"></script>
@endpush
<div id="toctocAdModal" class="modal fade alert-modal" role="dialog">
    <div class="modal-dialog">
        <a href="javascript:" class="closeModalBtn" data-dismiss="modal"><span>x</span></a>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="alrt-modal-body">
                <h4>TocToc</h4>
                <div class="row content-left div-type-location">
                    <div class="form_group">
                      <label class="control-label" for="address">{{ __('filters.choix_ad') }} </label>
                      <div class="custom-selectbx">
                        <select id="toctocAds" name="toctocAds" title="{{__('filters.no_selected')}}" class="selectpicker">
                            
                        </select>
                    </div>
                    </div>
                </div>
                <div class="porject-btn-1">
                    <a id="save-toctoc-btn" href="javascript:">
                        <img width="20" height="20" src="/img/icons/toctoc.png">
                        {{ __('filters.send') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="toctocAdModalPostAd" class="modal fade alert-modal" role="dialog">
    <div class="modal-dialog">
        <a href="javascript:" class="closeModalBtn" data-dismiss="modal"><span>x</span></a>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="alrt-modal-body">
                <h4>{{ __('filters.annonce_interet_toctoc') }}</h4>
                <div class="porject-btn-1">
                    <a id="save-toctoc-btn" href="/publiez-annonce">
                        {{ __('filters.pub_annonce') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>