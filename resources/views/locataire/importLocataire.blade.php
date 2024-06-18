@extends('proprietaire.index')

@section('contenue')
<style>
    td{
        font-size: 13px;
    }
</style>
<div class="container">
    <div class="row" style="margin-top: 30px;">
        <div class="row tete">
            <div class="col-lg-4 col-sm-4 col-md-4 titre">
                <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>Import locataire</h3>
            </div>
        </div>
    </div>
    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7">
        <span class="label m-r-2" style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
        </p style="margin-top:50px;">Vous pouvez importer un fichier au format CSV, Excel ou Open Office. Veuillez utiliser les modèles suivants pour faire l'import :</p>
        <ul>
            <li><a href="{{route('locataire.download')}}">Modèle de document Excel</a></li>
            {{-- <li><a href="{{route('import.downloadODS')}}">Modèle de document Open Office</a></li>
            <li><a href="{{route('import.downloadCSV')}}">Modèle de document CSV</a></li> --}}
        </ul>
    </div>
    <div class="alert alert-danger" id="errorLoge" role="alert">
        <strong id="erro"></strong>
    </div>

    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #ffff">
        <div id="import-table">
        </div>
        <div class="card" id="ttt" style="margin-top: 5px">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                Importation
            </div>
            <form id="import-form" method="POST"  enctype="multipart/form-data">
                @csrf
            <div class="card-body" style="margin-top: 20px;">

                <div class="row align-middle">
                    <div class="col-md-2 align-middle ">
                        <label for="" class="form-label">Fichier: </label>
                    </div>

                    <div class="col-10">
                        <input type="file" name="file" id="file">
                        <p>Formats acceptés: xlsx, csv, ods</p>
                    </div>
                </div>
            </div>
            <div id="import-table"></div>
        </div>
        <div class="card" style="margin-top: 5px">
            <div class="row p-4">
                <center>
                    <a href="" class="btn btn-secondary">Annuler</a>
                    <button   type="submit" id="valid" class="btn btn-primary"> Valider  </button>
                    <button   id="sauvegarder" class="btn btn-primary"> Sauvegarder </button>
                    </form>
                </center>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#errorLoge').hide();
            $('#sauvegarder').hide();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
                }
            });
            $('#import-form').submit(function(e) {
                $("#myLoader").removeClass("d-none")
                e.preventDefault();

                $.ajax({
                    url: '{{ route('import.data') }}',
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                            if(response == 'tsisy'){
                                $("#myLoader").addClass("d-none")
                                $('#errorLoge').show()
                                $('#erro').text('Veuillez sélectioner une fichier valide')
                            }else{
                                $("#myLoader").addClass("d-none")
                                $('#ttt').hide()
                                $('#errorLoge').hide()
                                $('#sauvegarder').show();
                                $('#valid').hide();
                                var data = response.data;

                                var table = '<div class="table-responsive"><table class="table table-hover">\
                                                <thead>\
                                                    <tr>\
                                                        <th>Locataire</th>\
                                                        <th>Type</th>\
                                                        <th>Télephone</th>\
                                                        <th>Email</th>\
                                                        <th>Adresse</th>\
                                                        \<th>Civilite</th>\
                                                        \<th>Profession</th>\
                                                    </tr>\
                                                </thead>\
                                            <tbody>';

                                                $.each(data, function(index, row) {
                                                    table += '<tr>\
                                                                <td>'+ row[0] +' </td>\
                                                                <td> '+ row[1] +' </td>\
                                                                <td>' + row[2] + '</td>\
                                                                <td>' + row[3] +' </td>\
                                                                <td> '+ row[4] +' </td>\
                                                                <td> '+ row[5] +' </td>\
                                                                <td> '+ row[6] +' </td>\
                                                            </tr>';
                                                });

                                table += '</tbody></table></div>';

                                $('#import-table').html(table);
                            }
                    }
                });
            });
            $('#sauvegarder').click(function(e) {
                e.preventDefault(); // empêcher le formulaire de se recharger
                $("#myLoader").removeClass("d-none")
                const formData = new FormData($('#import-form')[0]);

                // envoyer une requête POST à une route qui sauvegardera les données en base de données
                $.ajax({
                    url: '{{route('locataire.impordonne')}}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if ($.isEmptyObject(data.errors)) {
                            window.location = "{{ redirect()->route('locataire.locataire')->getTargetUrl() }}";
                        }else{
                            $("#myLoader").addClass("d-none")
                            $('#errorLoge').show()
                            $('#erro').text(data.errors)
                            console.log(data.errors)
                            // $("#myLoader").addClass("d-none")
                        }
                    },
                    error: function(response) {
                        // alert("Veuillez selectioner");
                    }
                });
            });
        });
    </script>
</div>
@endsection
