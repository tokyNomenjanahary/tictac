@extends('proprietaire.index')
    <style>

    </style>
@section('contenue')
    <div class="content-wrapper"
        style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row tete mt-4">
                <div class="col-lg-4 col-sm-4 col-12 col-md-4 titre">
                    <h3 class="page-header page-header-top m-0">Dossier</h3>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 arh">

                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 nouv text-end">
                    <div>
                        <button type="button" class="btn btn-primary">
                            <a style="color: white;" data-bs-toggle="modal" data-bs-target="#addDossier">
                                <i class='bx bxs-folder-plus'></i>
                                {{ __('documents.nouveau_dossier') }}
                            </a>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 30px">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <p>{!! $message_status !!}
                                <a class="text-primary" href="{{ route('documents.subscription_documents') }}"> {{ __('Besoin de plus d\'espace ?') }}</a>
                            </p>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ round($storage_status['status']) }}%"
                                    aria-valuenow="{{ round($storage_status['status']) }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 30px">
                <div class="d-flex flex-wrap" id="icons-container">
                    @foreach ($listeDossier as $dossier)
                        <a href="{{ route('documents.containedDossier', encrypt($dossier->id)) }}" class="text-decoration-none text-reset">
                            <div class="card icon-card text-center mb-4 mx-2">
                                <div class="card-body">
                                    <i class='bx bxs-folder fs-2'></i>
                                    <p class="icon-name text-capitalize text-truncate mb-0">{{ $dossier->nom }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                  </div>
                  <div class="mt-3">

                    <!-- Modal -->
                    <div class="modal fade" id="addDossier" tabindex="-1" style="display: none;" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Créer un nouveau dossier</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form method="POST" action="{{ route('documents.saveDossier') }}" enctype="multipart/form-data">
                          <div class="modal-body">
                                @csrf
                                <div>
                                    <label class="form-label">Nom du dossier</label>
                                    <input type="text" name="nom" class="form-control" placeholder="">
                                </div>


                          </div>
                            <div class="modal-footer">
                                <div class="mt-3">
                                    <button type="button" class="btn rounded-pill btn-secondary" style="margin-right: 1rem">Annuler</button>
                                    <button type="submit" class="btn rounded-pill btn-primary">Sauvegarder</button>
                                </div>
                            </div>
                        </form>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>

</script>
