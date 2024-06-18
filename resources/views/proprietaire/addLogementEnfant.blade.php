@extends('proprietaire.index')
<style>
    .nav-link.active{
        border-bottom: 3px solid #4C8DCB !important;
    }
    hr{
      color : blue;
      width: 2px;
    }
</style>
@section('contenue')


<style>
  #active_records {
      background-color: rgba(114, 163, 51, 0.14) !important;
      border: 1px solid rgba(114, 163, 51, 0.6);
      border-right: 1px solid rgba(114, 163, 51, 0.4);
  }

  #archived_records {
      /* background-color: rgba(114, 163, 51, 0.14) !important; */
      border: 1px solid rgba(114, 163, 51, 0.6);
      border-right: 1px solid rgba(114, 163, 51, 0.4);
  }

  .extension-contrat{
      display: flex;
      align-items: center;
      justify-content: center;
      width: 3rem;
      height: 3rem;
      color: black;
  }

    input{
        border-radius: none !important;
    }
    .card-header{
        color:#4C8DCB;
        padding:10px;
        background-color:F5F5F9;
        margin-top:20px;
        border-radius:0px;
    }
    .card-body{
        margin-top:20px;
    }

    .nav-tabs .nav-item .nav-link:not(.active) {
        background-color: rgb(250, 250, 250);
    }
    .nav-tabs .nav-item .nav-link.active  {
        border-top: 3px solid blue !important;
        border-bottom: 3px solid white !important;
    }
    .nav-tabs .nav-item .nav-link   {
        color: blue !important;
        font-size: 13px !important;
    }
</style>
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"> --}}
@endpush

    <div class="p-12">
        <header class="bg-white shadow" style="margin:25px auto;margin-left:25px;margin-right: 25px">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="row">
                    <div class="col-md-12 p-3">
                        <div class="float-start">
                            <h3 class="page-header page-header-top m-0"><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>Ajout des chambres sur logement {{$logementMere->identifiant}}</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <form id="" method="POST" action="{{ route('proprietaire.saveLogementEnfant',$logementMere->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header" style="color: #4C8DCB">
                                Choisir les chambres que vous voulez inserer sur {{$logementMere->identifiant}}:
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if ($listChambreSansMere)
                                        @foreach ($listChambreSansMere as $listChambre)
                                            <div class="col-md-6">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input radio" id="chambre{{ $listChambre->id }}" type="checkbox" value="{{ $listChambre->id }}" name="logementEnfants[]">
                                                    <label class="form-check-label lr" for="chambre{{ $listChambre->id }}">{{$listChambre->identifiant}}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <a href="{{ route('proprietaire.creatChambreInLogement',$logementMere->id )}}" type="button" class="btn btn-sm btn-primary">Ajouter une chambre</a>
                                        <p>Vous pouvez ajouter une chambre sur ce bien si vous n'avez pas encore une chambre approprié sur votre bien</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card" style="margin-top: 5px">
                            <div class="card-body" style="margin-top: -5px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="float-end">
                                            <a href="#" id="return_back" class="btn btn-secondary">Annuler</a>
                                            <button type="submit" class="btn btn-success" id=""> Sauvegarder </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </header>
    </div>

    @push('script')
        <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/buffer.min.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/piexif.min.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/sortable.min.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/fileinput.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/locales/LANG.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(document).ready(function() {
                $("#return_back").on('click', function(){
                    window.history.back();
                });
            });
        </script>
    @endpush
@endsection
