<div class="card">
    <div class="card-header" style="color: #4C8DCB">
        Contrats et diagnostics :
    </div>
    <div class="card-body">
        <div class="row align-middle">
            <div class="col-lg-2 col-md-12 align-middle ">
               <label for="" class="form-label">Documents :</label>
            </div>
            <div class="col-lg-10 col-md-12" id="list_contrat_diagnostic">
                {{-- @if (isset($dataContratDiagnostics))
                    @foreach ($dataContratDiagnostics as $dataContratDiagnostic)
                        <div class="card mb-3 mt-3" id="contenair_contrat_diagnostic{{$dataContratDiagnostic->id}}">
                            <h5 class="card-header">{{$dataContratDiagnostic->typeContratDiagnostic->type}}</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2">
                                        <div class="extension-contrat" style="background-color:{{ '#' . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, '0', STR_PAD_LEFT) }};">
                                            @if (isset($dataContratDiagnostic->document_original_name))
                                                {{strtoupper(substr($dataContratDiagnostic->document_original_name, strrpos($dataContratDiagnostic->document_original_name, '.') + 1))}}
                                            @else
                                            {{strtoupper(substr("document.doc", strrpos("document.doc", '.') + 1))}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-10">
                                        <blockquote class="blockquote mb-0">
                                            <p>{{$dataContratDiagnostic->description}}</p>
                                            <footer class="blockquote-footer">
                                            <cite title="Date d'établissement:{{$dataContratDiagnostic->date_establishment}}">Date d'échéance:{{$dataContratDiagnostic->due_date}}</cite>
                                            </footer>
                                        </blockquote>
                                    </div>
                                    <div class="col-lg-4 col-md-12 align-middle">
                                        <center>
                                            <a id="{{$dataContratDiagnostic->id}}" onClick="updateContratDiagnostic({{ $dataContratDiagnostic->id }},{{ $dataContratDiagnostic->type_contrat_diagnostic_id}},'{{ $dataContratDiagnostic->document_original_name}}','{{ $dataContratDiagnostic->description}}','{{ $dataContratDiagnostic->date_establishment}}','{{ $dataContratDiagnostic->due_date}}')" data-bs-toggle="modal" data-bs-target="#modalContratDiagnostic" type="button" class="btn btn-sm btn-info rounded-pill btn-modifier">Modifier</a>
                                            <a onClick="deleteContratDiagnostic({{ $dataContratDiagnostic->id }})" id="delete_contrat_diagnostic_{{$dataContratDiagnostic->id}}" type="button" class="btn btn-sm btn-danger rounded-pill ms-3 btn-supprimer">Supprimer</a>
                                        </center>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif --}}

                @if (isset($contratDiagEdits))
                    @foreach ($contratDiagEdits as $contrat)
                        <div class="card mb-3 mt-3" id="contenair_contrat_diagnostic{{$contrat->id}}">
                            <h5 class="card-header">{{$contrat->typeContratDiagnostic->type}}</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2">
                                        <div class="extension-contrat" style="background-color:{{ '#' . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, '0', STR_PAD_LEFT) }};">
                                            @if (isset($contrat->document_original_name))
                                                {{strtoupper(substr($contrat->document_original_name, strrpos($contrat->document_original_name, '.') + 1))}}
                                            @else
                                            {{strtoupper(substr("document.doc", strrpos("document.doc", '.') + 1))}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-10">
                                        <blockquote class="blockquote mb-0">
                                            <p>{{$contrat->description}}</p>
                                            <footer class="blockquote-footer">
                                            <cite title="Date d'établissement:{{$contrat->date_establishment}}">Date d'échéance:{{$contrat->due_date}}</cite>
                                            </footer>
                                        </blockquote>
                                    </div>
                                    <div class="col-lg-4 col-md-12 align-middle">
                                        <center>
                                            <a id="{{$contrat->id}}" onClick="updateContratDiagnostic({{ $contrat->id }},{{ $contrat->type_contrat_diagnostic_id}},'{{ $contrat->document_original_name}}','{{ $contrat->description}}','{{ $contrat->date_establishment}}','{{ $contrat->due_date}}')" data-bs-toggle="modal" data-bs-target="#modalContratDiagnostic" type="button" class="btn btn-sm btn-info rounded-pill btn-modifier">Modifier</a>
                                            <a onClick="deleteContratDiagnostic({{ $contrat->id }})" id="delete_contrat_diagnostic_{{$contrat->id}}" type="button" class="btn btn-sm btn-danger rounded-pill ms-3 btn-supprimer">Supprimer</a>
                                        </center>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif

             </div>
            <div class="col-lg-6 col-md-12 align-middle">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalContratDiagnostic">
                    Ajouter document
                </button><br>
            </div>
            <small id="helpId" class="text-muted">Vous pouvez ajouter plusieurs contrats. Les fichiers seront sauvegardés dans la rubrique Documents.</small>
        </div>
    </div>

</div>
<div class="card" style="margin-top: 5px">
    <div class="card-body" style="margin-top: -5px">
        <div class="row">
            <div class="col-md-12">
                <div class="float-start">

                </div>
                <div class="float-end">
                    <button type="button" class="btn btn-primary" id="precedentPhoto"> Précédent </button>
                    <button type="button" class="btn btn-primary" id="suivantContact"> Suivant </button>
                </div>
            </div>
        </div>
    </div>
</div>
<input hidden type="text" name="idContratDiagnostic[]" id="dataIDContratDiagnostic">

<!-- Modal trigger button -->
{{-- <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalId">
  Launch
</button> --}}

<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div class="modal fade" id="modalContratDiagnostic" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content" id="form_contract_diagnostic">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Ajouter un nouveau</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <p class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7"><span class="label m-r-2"
                style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
            Ajouter un doctument de contrat ou diagnostic
            </p>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="contratDiagId">
                    <div class="col-4 mt-2" >
                        Type
                    </div>
                    <div class="col-8">
                        <select name="type_contract" id="type_contract" class="form-select">
                            @foreach ($listeTypeContractDiagnostic as $type)
                                <option value="{{ $type->id }}">{{ $type->type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-4 " >
                        Document
                    </div>
                    <div class="col-8">
                        <input name="document" type="file" id="document" class="form-control">
                        <span style="color: #dc3545;" id="error_document" class=""></span>
                        <p>Formats acceptés: jpeg,png,pdf,doc,docx,xls,xlsx,zip </p>
                        <span id="old_value_document"></span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-4 " >
                        Description
                    </div>
                    <div class="col-8">
                        <textarea name="description" type="text" id="description" class="form-control"></textarea>
                        <span style="color: #dc3545;" id="error_description" class=""></span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-4" >
                        Date d'établissement
                    </div>
                    <div class="col-8">
                        <input name="date_establishment" type="date" id="date_establishment" class="form-control">
                        <span style="color: #dc3545;" id="error_date_establishment" class=""></span>
                    </div>
                </div><div class="row mt-3"  style="border-bottom: 1px solid rgb(223, 216, 216);padding-bottom:10px">
                    <div class="col-4" >
                        Date d'échéance
                    </div>
                    <div class="col-8">
                        <input name="due_date" type="date" id="due_date" class="form-control">
                        <span style="color: #dc3545;" id="error_due_date" class=""></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_contrat_diagnostic">Save</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $("#precedentPhoto").click(function() {
            $('#photo-tab').tab('show');
        });

        $("#suivantContact").click(function() {
            $('#contact-tab').tab('show');
        });

        const modalContratDiagnostic = document.getElementById('modalContratDiagnostic')
        modalContratDiagnostic.addEventListener('hidden.bs.modal', event => {
            $('#old_value_document').text('');
            $('input[name=contratDiagId]').val('');
            $('select[name=type_contract] option').attr("selected",false);
            $("input[name=document]").val('');
            $("textarea[name=description]").val('');
            $("input[name=date_establishment]").val('');
            $("input[name=due_date]").val('');
        })

        function deleteContratDiagnostic(id) {
            let url = "/deleteContratDiagnostic/" + id
            $.ajax({
                type: "GET",
                url: url,
                success:function(){
                    toastr.success("Votre document sur le contrat|diagnostic a été bien supprimer.");
                    // __("logement.delete-document-contrat-success")
                    $("#delete_contrat_diagnostic_" + id).closest("#contenair_contrat_diagnostic" + id).remove()
                },
                error: function(data) {
                    toastr.error(data.responseJSON.message)
                }
            })
        }


        function updateContratDiagnostic(id,type_contrat_diagnostic_id,document_original_name,description,date_establishment,due_date){
            if(document_original_name != ''){
                $('#old_value_document').text(document_original_name);
            }else{
                $('#old_value_document').text('');
            }
            $('select[name=type_contract]').find("option[value=" + type_contrat_diagnostic_id +"]").attr('selected', true);
            $('select[name=type_contract]').val(type_contrat_diagnostic_id);
            $('input[name=contratDiagId]').val(id);
            $("input[name=document]");
            $("textarea[name=description]").val(description);
            $("input[name=date_establishment]").val(date_establishment);
            $("input[name=due_date]").val(due_date);

        }

        $(document).ready(function(){
            /*** Insertion de document contrat et diagnostic  ***/
            const modalContratDiagnostic = new bootstrap.Modal('#modalContratDiagnostic')

            var dataIDContratDiagnostic = []
            $('#save_contrat_diagnostic').click(function(e){
                e.preventDefault();
                var contratDiagId = $('input[name=contratDiagId]').val();
                if(contratDiagId != ''){
                    var url = '/saveContratDiagnostic/'+contratDiagId;
                }else{
                    var url = "{{ route('proprietaire.saveContratDiagnostic')}}";
                }
                var type_contract = $("select[name=type_contract]");
                var document = $("#document")[0].files[0];
                var description = $("textarea[name=description]");
                var date_establishment = $("input[name=date_establishment]");
                var due_date = $("input[name=due_date]");

                var formData = new FormData();
                formData.append('type_contract', type_contract.val());
                formData.append('document', document);
                formData.append('description', description.val());
                formData.append('date_establishment', date_establishment.val());
                formData.append('due_date', due_date.val());

                if (!description.val() == '') {
                    description.removeClass('border')
                    description.removeClass('border-danger')
                    $("#error_description").text("")
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            dataIDContratDiagnostic.push(data.id)
                            $('#dataIDContratDiagnostic').val(dataIDContratDiagnostic);
                            if(contratDiagId != ''){
                                $('#contenair_contrat_diagnostic'+data.id).remove()
                            }
                            $('#list_contrat_diagnostic').append('<div class="card mb-3 mt-3" id="contenair_contrat_diagnostic'+data.id+'">\
                                                                    <h5 class="card-header">'+data.type_contrat_diagnostic.type+'</h5>\
                                                                    <div class="card-body">\
                                                                        <div class="row">\
                                                                            <div class="col-lg-2 col-md-2">\
                                                                                <div class="extension-contrat" style="background-color:{{ '#' . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, '0', STR_PAD_LEFT) }};">\
                                                                                    {{strtoupper(substr("document.doc", strrpos("document.doc", ".") + 1))}}\
                                                                                </div>\
                                                                            </div>\
                                                                            <div class="col-lg-6 col-md-10">\
                                                                                <blockquote class="blockquote mb-0">\
                                                                                    <p>'+data.description+'</p>\
                                                                                    <footer class="blockquote-footer">\
                                                                                    <cite title="">Date d\'échéance:'+data.due_date+'</cite>\
                                                                                    </footer>\
                                                                                </blockquote>\
                                                                            </div>\
                                                                            <div class="col-lg-4 col-md-12 align-middle">\
                                                                                <center>\
                                                                                    <a id="'+data.id+'" data-id="'+data.id+'" onClick="updateContratDiagnostic('+data.id+','+data.type_contrat_diagnostic_id+',\''+data.document_original_name+'\',\''+data.description+'\',\''+data.date_establishment+'\',\''+data.due_date+'\')" data-bs-toggle="modal" data-bs-target="#modalContratDiagnostic" type="button" class="btn btn-sm btn-info rounded-pill btn-modifier">Modifier</a>\
                                                                                    <a onClick="deleteContratDiagnostic('+data.id+')" id="delete_contrat_diagnostic_'+data.id+'" type="button" class="btn btn-sm btn-danger rounded-pill ms-3 btn-supprimer">Supprimer</a>\
                                                                                </center>\
                                                                            </div>\
                                                                        </div>\
                                                                    </div>\
                                                                </div>');
                            if(contratDiagId != ''){
                                toastr.success("Votre modificaion de documetn sur le contrat|diagnostic a été bien enregistrer.");
                                // __("logement.update-document-contrat-success")
                            }else{
                                toastr.success("Votre document sur le contrat|diagnostic a été bien enregistrer.");
                                // __("logement.save-document-contrat-success")
                            }

                            modalContratDiagnostic.hide();
                            type_contract.val('');
                            $("input[name=document]").val('');
                            description.val('');
                            date_establishment.val('');
                            due_date.val('');
                        },
                        error: function(data) {
                            let msgs = data.responseJSON.message
                            $.each(msgs,function (key,value) {
                                $('#'+key).addClass('border-danger')
                                $('#error_' + key).text(value)
                            });
                        }
                    });
                }else{
                    description.addClass('border')
                    description.addClass('border-danger')
                    $("#error_description").text("veiller remplir ce champ!")//__("logement.champ-vide-error")
                }
            })
        })
    </script>

@endpush


<!-- Optional: Place to the bottom of scripts -->
<script>

</script>
