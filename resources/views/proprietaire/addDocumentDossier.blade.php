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
            </div>

            <div class="row" style="margin-top: 30px">
                <div class="d-flex flex-wrap" id="">
                    <div class="col">
                        <div class="card">
                            <form method="POST" action="{{ route('documents.saveDocumentDossier') }}" enctype="multipart/form-data">
                                @csrf
                                <input hidden type="text" name="dossier_id" value="{{$containedDossier->id}}">
                                <table class="table table-striped table-hover" id="tab_doc">
                                    <thead>
                                        <tr>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 20px;">
                                                {{-- <input type="checkbox" id="master" class="checkbox_input align-middle " style="height: 20px;width:20px;"> --}}
                                            </th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 192.25px;">Nom du document</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($listeDocuments as $document)
                                            <tr id="" class="even">
                                                <td style="width: 5px" class="check">
                                                    <input name="document_id[]" type="checkbox" value="{{$document->id}}" class="check_document align-middle sub_chk" style="height: 15px;width:15px;">
                                                </td>
                                                <td>
                                                    <strong>{{$document->nomFichier}}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3 mb-3" id="section_btn_document" style="display: flex; margin-left: 3rem">
                                    <button type="submit" class="btn btn-primary btn-sm" id="addDocDossier"><i class='fa fa-plus-circle'></i>
                                        Ajouter
                                    </button>
                                </div>
                            </form>
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

            $(".check_document").change(function(){
                if ($(".check_document").is(':checked')) {
                    $("#section_btn_document").show();
                } else {
                    $("#section_btn_document").hide();
                }
            });
        })

    </script>
@endpush
