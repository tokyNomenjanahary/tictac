<div class="card">
    <a data-extension="pdf" class="handleDown">
        {{-- <a data-href="{{ route('locataire.download-pdf', $locataire->id) }}" data-extension="pdf" id="downloadPdf"> --}}
        <div class="card-body" >
            <h5 class="card-title text-center mb-0" style="font-size:40px;"><i class="fa-regular fa-file-pdf" style="color:#9f1318"></i></h5>
            <h6 class="card-title text-center mb-0 mt-1">{{__('inventaire.pdf')}}</h6>
        </div>
    </a>
</div>
<div class="card mt-2 ">
    <a data-extension="docx" class="handleDown">
        {{-- <a href="{{ route('locataire.download-docx', $locataire->id) }}" data-extension="docx"> --}}
        <div class="card-body">
            <h5 class="card-title text-center mb-0" style="font-size:40px;"><i class="fa-regular fa-file-word" style="color:#4C8DCB"></i></h5>
            <h6 class="card-title text-center mb-0 mt-1">{{__('inventaire.word')}}</h6>
        </div>
    </a>
</div>
<script>
    $('.handleDown').on('click', function (e) {
        e.preventDefault();
        let type = $('.card.type').attr('data-file-type')
        let extension = $(this).attr('data-extension')
        let url = '/locataire/' + {{ $locataire->id }} + '/type-modele/' + type + '/' + extension
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
            }
        });

        $.ajax({
            type:'GET',
            url: url,
            xhrFields: {
                responseType: 'blob'
            },
            success:function(response, status, xhr){
                var url = window.URL.createObjectURL(response);
                var dispositionHeader = xhr.getResponseHeader('Content-Disposition');
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(dispositionHeader);
                var filename = matches != null && matches[1] ? matches[1].replace(/['"]/g, '') : 'file.pdf';
                var link = document.createElement('a');
                link.href = url;
                link.download = filename;
                link.click();
                window.URL.revokeObjectURL(url);
                $('#TelechargerModele').modal('hide');
            },
            error: function(data) {

            }
        });
    })
</script>