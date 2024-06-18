<?php
if (!isTenant()) {
    $entete = 'proprietaire.index';
    $contenue = 'contenue';
}else{
    $entete = 'espace_locataire.index';
    $contenue = 'locataire-contenue';
}
?>

@extends($entete)

@section($contenue)
    <style>
        label{
            font-size: 12px;
            color: rgb(88, 85, 85);
        }
        p{
            font-size: 12px;
        }
        @media only screen and (max-width: 600px) {
            label{
                float:  left !important;
            }
            .card{
                box-shadow: none !important;
            }
        }
    </style>
    <div class="container">
        <div class="row" style="margin-top: 30px;">
            <div class="row tete">
                <div class="col-lg-4 col-sm-4 col-md-4 titre">
                    <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>Nouveaux documents</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="p-12" style="margin-top:-20px;">
        <form action="{{route('document.nouvaux')}}" method="POST" enctype="multipart/form-data">
            @csrf
        <header class="bg-white " style="margin:25px auto;margin-left:25px;margin-right: 25px">
            {{-- <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"> --}}
                <div class="card mt-2" style="margin-top: 5px;box-shadow:none ">
                    <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                        Information
                    </div>
                    <div class="card-body mt-3 ">
                        <div class="row" >
                            <div class="col-md-2  text-end">
                                <label for="" style="margin-top:8px;">TYPE</label>
                            </div>
                            <div class="col-md-8 col-sm-10" >
                                <input type="text" name="type" value="{{ old('type')}}" class="form-control">
                            </div>
                        </div>
                    </div>
                    @if (isTenant())
                        <div class="card-body " style="margin-top:-40px;">
                            <div class="row" >
                                <div class="col-md-2 text-end">
                                    <label for="" style="margin-top:10px;">LOCATION</label>
                                </div>
                                <div class="col-md-8 col-sm-10" >
                                    <select name="location_id" class="form-control" id="location_id_select">
                                        <option value="" selected>Pas lié</option>
                                        @foreach ($locations as $location)
                                            <option value="{{$location->id}}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{$location->identifiant}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body " style="margin-top:-40px;">
                            <div class="row" >
                                <div class="col-md-2 text-end">
                                    <label for="" style="margin-top:10px;">BIEN</label>
                                </div>
                                <div class="col-md-8 col-sm-10" >
                                    <input type="text" disabled class="form-control" value="Pas lié" id="logement_id_field_value">
                                    <input type="text" hidden class="form-control" name="logement_id" id="logement_id_field">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body " style="margin-top:-40px;">
                            <div class="row" >
                                <div class="col-md-2 text-end">
                                    <label for="" style="margin-top:10px;">BIEN</label>
                                </div>
                                <div class="col-md-8 col-sm-10" >
                                    <select name="logement_id" id="" class="form-control">
                                        <option value="0" selected>Pas lié</option>
                                        @foreach ($biens as $bien)
                                            <option value="{{$bien->id}}" {{ old('logement_id') == $bien->id ? 'selected' : '' }}>{{$bien->identifiant}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body " style="margin-top:-40px;">
                            <div class="row" >
                                <div class="col-md-2 text-end">
                                    <label for="" style="margin-top:10px;">LOCATION</label>
                                </div>
                                <div class="col-md-8 col-sm-10" >
                                    <select name="location_id" id="" class="form-control">
                                        <option value="0" selected>Pas lié</option>
                                        @foreach ($locations as $location)
                                            <option value="{{$location->id}}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{$location->identifiant}}</option>
                                        @endforeach
                                    </select>

                                    {{-- <input type="text" name="location_id" class="form-control" value=""> --}}
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body" style="margin-top:-40px;">
                        <div class="row" >
                            <div class="col-md-2 text-end">
                                <label for="">DESCRIPTION</label>
                            </div>
                            <div class="col-md-8 col-sm-10" >
                                <textarea name="description" id="" style="width: 100%"  rows="5">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-body " style="margin-top:-40px;">
                        <div class="row" >
                            <div class="col-md-2 text-end">
                                <label for="" style="margin-top:10px;">FICHIER</label>
                            </div>
                            <div class="col-md-8 col-sm-10" >
                                <input type="file" name="fichier" class="form-control @error('fichier') is-invalid @enderror" value="">
                                <p>Formats acceptés: jpeg,png,pdf,doc,docx,xls,xlsx,zip </p>
                                @error('fichier')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-1 ">
                        <div class="row" >
                            <div class="col-md-2 text-end">
                                <label for="" style="margin-top:8px;">DATE</label>
                            </div>
                            <div class="col-md-2 col-sm-10" >
                                <input type="date" name="date" value="{{ old('date') }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-2" style="margin-top: 5px;box-shadow:none ">
                    <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                        Partage
                    </div>
                    <div class="card-body mt-3 ">
                        <div class="row" >
                            <div class="col-md-2 text-end">
                                <label for="" style="margin-top:8px;">PARTAGE</label>
                            </div>
                            <div class="col-md-4 col-sm-10 mt-1" >
                                <input type="checkbox" name="partage" {{ old('partage') ? 'checked' : '' }}>
                                <p style="margin-top: 5px;">Partager le document avec votre locataire</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card" style="margin-top: 5px">
                    <div class="row">
                        <div class="col-md-12" style="padding: 15px;">
                            <center>
                                <a class="btn btn-dark"  style="color:white;">Annuler</a>
                                <button type="submit"  class="btn btn-primary"> Sauvegarder </button>
                            </center>
                        </div>
                    </div>
                </div>
        </header>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const locations = @json($locations);
        $('#location_id_select').on('change', function() {
            let id_location = $('#location_id_select').val();
            if (!id_location) {
                $('#logement_id_field_value').val('Pas lié');
            } else {
                locations.forEach(element => {
                    if (id_location == element.id) {
                        $('#logement_id_field').val(element.logement.id);
                        $('#logement_id_field_value').val(element.logement.identifiant);
                    }
                });
            }
        })
    </script>
@endsection
