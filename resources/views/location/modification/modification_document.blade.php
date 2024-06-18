<!DOCTYPE html>
<html lang="en">
<head>
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css" media="all"
        rel="stylesheet" type="text/css" />
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" media="all"
        rel="stylesheet" type="text/css" /> --}}
    <style type="text/css">
    .fileinput-upload{
        display: none;
    }
    .kv-fileinput-error {
        display: none !important;
    }
    </style>
</head>
<body>
    <div class="file-loading" style="height: 300px !important">
        <input id="file-156" type="file" name="file" multiple class="file" data-min-file-count="2">
    </div>
    <input type="hidden" id="location_id" value="{{$location->id}}">
    <div class="card" style="margin-top: 5px">
        <div class="row">
            <div class="col-md-12" style="padding: 15px;">
                <div class="float-end">
                    <a href="" class="btn btn-secondary">Annuler</a>
                    <button id="okok" class="btn btn-primary"> Sauvegarder </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.js" type="text/javascript">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js"
        type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        var a = <?php echo json_encode($location->files); ?>;
        var url = a.map(element => {
            return '{{ asset('storage/files_location') }}' + '/' + element.image;
        });
        var tt = a.map(element => {
            return {
                key: element.id,
                filename: element.image,
                caption: element.image,
                url: "/site/file-delete",
                extra: {
                    file: element.image
                }
            };
        });
        $("#file-156").fileinput({
            theme: 'fa',
            initialPreview: url,
            initialPreviewConfig: tt,
            initialPreviewAsData: true,
            overwriteInitial: false,
            maxFileSize: 100,
            msgErrorClass: 'hide-error',
            ajaxSettings: { headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            },
            ajaxDeleteSettings : { headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
        });

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
                }
            });
            $('#okok').on('click',function(e){
            e.preventDefault()
            var location_id = $("#location_id").val()
            let formData = new FormData()
            let data = $('#file-156')[0].files
            for (let i = 0; i < data.length; i++) {
                formData.append('file[]', data[i])
            }
            formData.append('location_id',location_id)
            $.ajax({
                type: "POST",
                url: "{{route('modification.doc')}}",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function (response) {
                    window.location = "{{ redirect()->route('location.index')->getTargetUrl() }}";
                }
            });
        })

        });



    </script>
</body>
</html>
