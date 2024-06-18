@extends('proprietaire.index')
@push('styles')

@endpush
@section('contenue')

    <div class="container mt-3">
        <div>
            <h3 class="page-header page-header-top m-0"><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>Import Bien</h3>
        </div>
        <div class="alert alert-primary mt-3" role="alert">
            <h5 class="alert-heading">Information</h5>
            <p style="color: #697a8d">Vous pouvez importer un fichier au format CSV, Excel ou Open Office. Veuillez utiliser les modèles suivants pour faire l'import :</p>
            <a href="{{ route('proprietaire.downloadExempleImportBien') }}">Modèle de document Excel</a><br>
            <a href="{{ route('proprietaire.downloadExempleImportBienOds') }}">Modèle de document Open Office</a><br>
            <a href="{{ route('proprietaire.downloadExempleImportBienCsv') }}">Modèle de document CSV</a>
        </div>

        <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #ffff">
            <div class="card" id="ttt" style="margin-top: 5px">
                <div class="card-header" style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                    Importation
                </div>
                <form method="POST" action="{{ route('proprietaire.saveImporterBien') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body" style="margin-top: 20px;">

                        <div class="row align-middle">
                            <div class="col-md-2 align-middle ">
                                <label class="form-label">Fichier: </label>
                            </div>

                            <div class="col-10">
                                <input type="file" name="file">
                                <p>Formats acceptés: xlsx, csv, ods</p>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="margin-top: 5px">
                        <div class="row p-4">
                            <center>
                                <a href="" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-primary">Sauvegarder</button>
                            </center>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')

    @endpush
@endsection
