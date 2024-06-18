@section('file-input')
  <!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
  {{-- <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css"> --}}

  <!-- bootstrap 5.x or 4.x is supported. You can also use the bootstrap css 3.3.x versions -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">

  <!-- default icons used in the plugin are from Bootstrap 5.x icon library (which can be enabled by loading CSS below) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">

  <!-- alternatively you can use the font awesome icon library if using with `fas` theme (or Bootstrap 4.x) by uncommenting below. -->
  <!-- link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" crossorigin="anonymous" -->

  <!-- the fileinput plugin styling CSS file -->
  <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
  <style>
    .kv-file-rotate, .kv-file-upload {
      display: none !important;
    }
  </style>
@endsection
@if (isset($location))
<form id="sdqsd" method="POST">
  @csrf
@endif
  <label for="input-doc" class="w-100">
    <div class="file-loading">
      <input id="input-doc" name="input-doc[]" type="file" multiple>
    </div>
    {{-- **{{ __('property.upload_photos_message') }} --}}
  </label>
@if (isset($location))
<input type="text" name="location_doc[]" id="location_doc" hidden>
<div class="card" style="margin-top: 5px">
  <div class="row">
      <div class="col-md-12" style="padding: 15px;">
          <div class="float-end">
              <a href="" class="btn btn-secondary">Annuler</a>
              <button id="okok" class="btn btn-primary"> {{__('location.enregistrer')}} </button>
          </div>
      </div>
  </div>
</div>
</form>
@endif

@push('script')
  <!-- buffer.min.js and filetype.min.js are necessary in the order listed for advanced mime type parsing and more correct
  preview. This is a feature available since v5.5.0 and is needed if you want to ensure file mime type is parsed
  correctly even if the local file's extension is named incorrectly. This will ensure more correct preview of the
  selected file (note: this will involve a small processing overhead in scanning of file contents locally). If you
  do not load these scripts then the mime type parsing will largely be derived using the extension in the filename
  and some basic file content parsing signatures. -->
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/buffer.min.js" type="text/javascript"></script>
  {{-- <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/filetype.min.js" type="text/javascript"></script> --}}

  <!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
      wish to resize images before upload. This must be loaded before fileinput.min.js -->
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/piexif.min.js" type="text/javascript"></script>

  <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
      This must be loaded before fileinput.min.js -->
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/sortable.min.js" type="text/javascript"></script>

  <!-- bootstrap.bundle.min.js below is needed if you wish to zoom and preview file content in a detail modal
      dialog. bootstrap 5.x or 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> --}}

  <!-- the main fileinput plugin script JS file -->
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/fileinput.min.js"></script>

  <!-- following theme script is needed to use the Font Awesome 5.x theme (`fa5`). Uncomment if needed. -->
  <!-- script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/themes/fa5/theme.min.js"></script -->

  <!-- optionally if you need translation for your language then include the locale file as mentioned below (replace LANG.js with your language locale) -->
  <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/locales/LANG.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.5.0/js/locales/fr.min.js" integrity="sha512-IzzZlYpScPi/cBy0PyW7EIyFeZr6Uwxl7M3UCu2oDvI00xbBC2Qc+S/lwtE3hlKxXNxd7owqwIuIvz6g9rGVeg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    var plugin
    $(document).ready(function () {
      $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
                }
      });
      let initialPreviewArray = []
      let initialPreviewConfigArray = []
      @if (isset($location))
        var locationFiles = {!! json_encode($location->files) !!};
        var locationId = {!! json_encode($location->id) !!};
        var img_file = ["docx","doc","pdf","txt","odt","rtf","pptx","ppt","xlsx","xls"]
        for (let i = 0; i < locationFiles.length; i++) {
          var doc = locationFiles[i].image
          var extension = doc.split('.')
          if(img_file.includes(extension[extension.length - 1])) {
            initialPreviewArray.push("<i class='bi-file-earmark-fill'></i>")
            initialPreviewConfigArray.push({type: "file", caption:"location_file", width:'120px', url:"{{ route('delete-location-files') }}", id: 0, extra : {id:locationFiles[i].id, type:"deleted", file_name: locationFiles[i].image, etat: locationId}});
          } else {
            initialPreviewArray.push('/storage/' + locationFiles[i].image)
            initialPreviewConfigArray.push({type: "image", caption:"location_img", width:'120px', url:"{{ route('delete-location-files') }}", id: 0, extra : {id:locationFiles[i].id, type:"deleted", file_name: locationFiles[i].image, etat: locationId}});
          }
        }
      @endif
      let docFile = $("#input-doc").fileinput({
          language: '{{__('location.anglais')}}',
          initialPreview: initialPreviewArray,
          initialPreviewAsData: true,
          initialPreviewConfig: initialPreviewConfigArray,
          uploadUrl: "{{ route('upload-location-files') }}",
          deleteUrl: "{{ route('delete-location-files') }}",
          altId: "doc-files-update",
          overwriteInitial: false,
          allowedFileExtensions: ["docx","doc","pdf","txt","odt","rtf","pptx","ppt","xlsx","xls","jpg", "jpeg", "png"],
          maxFileCount: 8,
          showUpload: false,
          uploadAsync: true,
          showRemove: false,
          showCancel: false,
          showDrag: false,
          maxFileSize: 5120,
      })

      docFile.on("filebatchselected", function (event, files) {
        $("#input-doc").fileinput("upload");
      });
      docFile.on("filebatchuploadcomplete", function (event) {
        plugin = $('#input-doc').data('fileinput');
      });

      $('#okok').on('click', function (e) {
        e.preventDefault()
        let formData = new FormData()
        formData.append('id', locationId)
        if (plugin) {
          var ids = []
            for (let index = 0; index < plugin.initialPreviewConfig.length; index++) {
                if (plugin.initialPreviewConfig[index].id != "0") {
                  formData.append('location_doc[]', plugin.initialPreviewConfig[index].id);
                  @if (isset($location))
                    ids.push(plugin.initialPreviewConfig[index].id);
                  @endif
                }
            }
            @if (isset($location))
              $("#location_doc").val(ids)
            @endif
        }
        $.ajax({
          type: "POST",
          url: "{{route('modification.doc')}}",
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
              window.location = "{{ redirect()->route('location.index')->getTargetUrl() }}";
          }
      });
      })

    })
  </script>
@endPush
