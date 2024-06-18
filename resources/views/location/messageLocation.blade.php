@extends('proprietaire.index')

@section('contenue')
<style>
    @media only screen and (max-width: 600px) {
        .lab-mob{
            float:  left !important;
        }
        .card{
            box-shadow: none !important;
        }
    }
</style>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<div class="container">
    <div class="row" style="margin-top: 30px;">
        <div class="row tete">
            <div class="col-lg-4 col-sm-4 col-md-4 titre">
                <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>{{__('location.nouveauxMessage')}}</h3>
            </div>
        </div>
    </div>


    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #ffff">
        <div class="card" id="ttt" style="margin-top: 5px">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                {{__('location.DESTINATAIRE')}}
            </div>
            <form action="{{route('location.envoyerMessage')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body" style="margin-top: px;">
                <div class="row align-middle mt-3">
                    <div class=" col-md-2 text-end  col-sm-12" style="margin-top:5px">
                        <label for="" class=" lab-mob form-label">{{__('location.DESTINATAIRE')}}</label>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <input type="text" name="adresse" value="{{$locataire->TenantEmail}}" class="form-control" readonly>
                        <input type="hidden" name="location_id" value="{{$locataire->id}}">
                    </div>
                </div>
                <div class="row align-middle mt-2">
                    <div class="col-md-2 text-end" style="margin-top:5px">
                        <label for="" class="lab-mob  form-label">{{__('location.SUJET')}}</label>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <input type="text" name="sujet" class="form-control @error('sujet') is-invalid @enderror" required>
                    </div>
                </div>

                <div class="row align-middle mt-2">
                    <div class="col-md-2 text-end" style="margin-top:5px">
                        <label for="" class="lab-mob form-label">MESSAGE</label>
                    </div>
                    <div class="col-md-10 col-sm-12" >
                        {{-- <div id="editor" style="height: 150px;">
                            <input type="text" name="text">
                        </div> --}}
                        <textarea name="message" id="" class="form-control @error('message') is-invalid @enderror" class="w-100" rows="7" required>{{ old('message') }}</textarea>
                    </div>
                </div>

            </div>
        </div>
        <div class="card" id="ttt" style="margin-top: 5px">
            <div class="card-header text-capitalize"
                style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                {{__('location.document')}}
            </div>
            <div class="card-body" style="margin-top: px;">
                <div class="row align-middle mt-3">
                    <div class="col-md-2 text-end" style="margin-top:5px">
                        <label for="" class="lab-mob form-label text-capitalize">{{__('location.document')}}</label>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <input type="file" name="fichier" class="form-control" >
                        <p>{{__('location.formatAccepte')}} : jpeg,png,pdf,doc,docx,xls,xlsx,zip </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" style="margin-top: 5px">
            <div class="row p-4">
                <div class="col-md-2">

                </div>
                <div class="col-10">
                    <a href="/location" class="btn btn-secondary">{{__('location.Annuler')}}</a>
                    <button   type="submit" class="btn btn-primary"> {{__('location.envoyer')}}  </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>

var form = document.querySelector('form');
var editor = new Quill('#editor', {
    theme: 'snow'
});

form.addEventListener('submit', function(e) {
    e.preventDefault(); // Empêche le comportement par défaut du formulaire
    alert('teste')
    var content = editor.root.innerHTML;
    document.querySelector('input[name="content"]').value = content;
    form.submit(); // Soumettre le formulaire manuellement après la mise à jour de la valeur du champ d'entrée
});
</script>
@endsection
