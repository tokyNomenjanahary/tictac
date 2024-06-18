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
                <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>{{__('location.importLocation')}}</h3>
            </div>
        </div>
    </div>
    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7">
        <span class="label m-r-2" style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{__('location.information')}}</span>
        </p style="margin-top:50px;">{{__('location.textImport')}}</p>
        <ul>
            <li><a href="{{route('import.download')}}">{{__('location.templateExcel')}}</a></li>
            <li><a href="{{route('import.downloadODS')}}">{{__('location.templateOpenOffice')}}</a></li>
            <li><a href="{{route('import.downloadCSV')}}">{{__('location.templateCSV')}}</a></li>
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
                {{__('location.importer')}}
            </div>
            <form id="import-form" method="POST"  enctype="multipart/form-data">
                @csrf
            <div class="card-body" style="margin-top: 20px;">

                <div class="row align-middle">
                    <div class="col-md-2 align-middle ">
                        <label for="" class="form-label">{{__('location.fichier')}}: </label>
                    </div>

                    <div class="col-10">
                        <input type="file" name="file" id="file">
                        <p>{{__('location.fichierAccepter')}}</p>
                    </div>
                </div>
            </div>
            <div id="import-table"></div>
        </div>
        <div class="card" style="margin-top: 5px">
            <div class="row p-4">
                <center>
                    <a href="/location" class="btn btn-secondary">{{__('location.Annuler')}}</a>
                    <button   type="submit" id="valid" class="btn btn-primary"> {{__('location.valider')}}  </button>
                    <button   id="sauvegarder" class="btn btn-primary"> {{__('location.enregistrer')}} </button>
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
                                $('#erro').text('{{__('location.valideImport')}}')
                            }else{
                                $("#myLoader").addClass("d-none")
                                $('#ttt').hide()
                                $('#errorLoge').hide()
                                $('#sauvegarder').show();
                                $('#valid').hide();
                                var data = response.data;

                                var table = '<table class="table table-hover">\
                                                <thead>\
                                                    <tr>\
                                                        <th>Identifiant</th>\
                                                        <th>Bien</th>\
                                                        <th>debut</th>\
                                                        <th>fin</th>\
                                                        <th>loyer_HC</th>\
                                                        <th>charge</th>\
                                                        <th>Nom</th>\
                                                        <th>Prenoms</th>\
                                                        <th>Email</th>\
                                                    </tr>\
                                                </thead>\
                                            <tbody>';

                                                $.each(data, function(index, row) {
                                                    table += '<tr>\
                                                                <td>'+ row[0] +' </td>\
                                                                <td> '+ row[1] +' </td>\
                                                                <td>' + row[2] + '</td>\
                                                                <td>' + row[3] + '</td>\
                                                                <td>' + row[4] +' </td>\
                                                                <td> '+ row[5] +' </td>\
                                                                <td> '+ row[6] +' </td>\
                                                                <td> '+ row[7] + '</td>\
                                                                <td>' + row[8] + '</td>\
                                                            </tr>';
                                                });

                                table += '</tbody></table>';

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
                    url: '{{route('location.impordonne')}}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if ($.isEmptyObject(data.errors)) {
                            window.location = "{{ redirect()->route('location.index')->getTargetUrl() }}";
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
