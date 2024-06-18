@extends('proprietaire.index')
    <style>
        #tab_doc_length,#tab_doc_info{
            display: none !important;
        }
    </style>
@section('contenue')
    <div class="content-wrapper"
        style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row tete mt-4">
                <div class="col-lg-4 col-sm-4 col-12 col-md-4 titre">
                    <h3 class="page-header page-header-top m-0">Dossier {{$containedDossier->nom}}</h3>
                </div>

                <div class="col-lg-4 col-sm-4 col-md-4 arh">
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 nouv text-end">
                    <div>
                        <button type="button" class="btn btn-primary">
                            <a href="{{ route('documents.addDocumentDossier', encrypt($containedDossier->id)) }}" style="color: white;"><i
                                    class="fa fa-plus-circle"></i> Ajouter document</a>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 30px">
                <div class="d-flex flex-wrap" id="">
                    <div class="col">
                        <div class="card">
                            @if ($listeDocumentDossier)
                                <table id="tab_doc" class="table dataTable no-footer" aria-describedby="showLocataire_info">
                                    <thead>
                                        <tr>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 20px;"><input type="checkbox" id="master" class="checkbox_input align-middle " style="height: 20px;width:20px;"></th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 192.25px;">Nom du document</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 72.125px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($listeDocumentDossier as $document)
                                        {{-- {{dd($document)}} --}}
                                            <tr id="" class="even">
                                                <td style="width: 5px" class="check">
                                                    <input type="checkbox" id="selectionner" class="checkbox_input align-middle sub_chk" style="height: 15px;width:15px;" data-id="8">
                                                </td>
                                                <td>
                                                    <strong>{{ $document->nomFichier }}</strong>
                                                </td>
                                                <td>
                                                    <div class="dropdown" style="position: static !important;">
                                                        <button type="button" class="btn dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu" style="z-index: 2000;">
                                                            <a class="dropdown-item" href="{{ route('documents.removeDocumentDossier', encrypt($document->id)) }}"><i class="fa fa-pencil me-1"></i>
                                                                Enlever
                                                            </a>
                                                            {{-- <a class="dropdown-item" href=""><i class="fa fa-paper-plane me-1"></i>Inviter</a>
                                                            <a id="" class="dropdown-item" data-id="tr8" data-archive="0" href="javascript:void(0);" data-href="http://localhost:8000/locataireArchive/8"><i class="fas fa-archive me-1"></i>
                                                                Archiver
                                                            </a> --}}

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="card-body">
                                    <div class="alert alert-secondary mt-3"  role="alert">
                                        Le dossier est vide
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tab_doc').DataTable({
                "pageLength": 10,
                "language": {
                    "paginate": {
                        "previous": "&lt;", // Remplacer "previous" par "<"
                        "next": "&gt;" // Remplacer "next" par ">"
                    },
                }
            });
        });
    </script>
@endpush
