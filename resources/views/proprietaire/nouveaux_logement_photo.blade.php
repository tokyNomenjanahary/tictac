<div class="card ">
    <div class="card-header" style="color: #4C8DCB">
        Photo du logement :
    </div>
    <div class="card-body">
        <input type="text" name="file_ids" id="file_ids" hidden>
        <div class="form-group">
            <label class="control-label">{{ __('property.upload_photos') }}</label>
            <div class="upload-photo-outer">
                <div class="file-loading">
                    <input id="file-photos" type="file" multiple class="file" data-overwrite-initial="false"  data-browse-on-zone-click="true" name="file_photos[]" accept="image/*">
                </div>
            </div>
            <div class="upload-photo-listing">
                <p>**{{ __('property.upload_photos_message') }}</p>
            </div>
        </div>
    </div>
</div>
<div class="card" style="margin-top: 5px">
    <div class="card-body" style="margin-top: -5px">
        <div class="row">
            <div class="col-md-12">
                <div class="float-start">

                </div>
                <div class="float-end">
                    <button type="button" class="btn btn-primary" id="precedentInfoComplementaire"> Précédent </button>
                    <button type="button" class="btn btn-primary" id="suivantContrat"> Suivant </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        $("#precedentInfoComplementaire").click(function() {
            $('#profile-tab').tab('show');
        });

        $("#suivantContrat").click(function() {
            $('#contrat-tab').tab('show');
        });
    </script>

@endpush
