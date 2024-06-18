<style>
    label {
        color: black !important;
        margin-top: 12px;
    }

    input {
        border-radius: none !important;
    }

    .card {
        border-style: none;
        border-radius: 0px;
    }
</style>

<div class="card" style="margin-top: 5px">
    <div class="card-header"
        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
        Documents
    </div>
    <div class="card-body" style="margin-top: 20px;">
        <div class="row align-middle">
            <div class="col-md-1 align-middle ">
                <label for="" class="form-label">DOCUMENTS</label>
            </div>
            <div class="col-md-6 align-middle">
                <a href="" class="btn" data-bs-toggle="modal"
                    style="border:1px solid gray;color:blue;background-color:#f5f5f9;" data-bs-target="#Mydocuments"
                    onmouseup="">
                    <i class="fa fa-plus-circle"></i> Ajouter un document
                </a>
            </div>
            <p style="margin-top: 10px;">Vous pouvez ajouter plusieurs documents. Ces documents seront sauvegardés dans
                la rubrique Documents.</p>
        </div>
    </div>
    <!-- Modal Body-->
    <div class="modal fade" id="Mydocuments" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#FAFAFA;">
                    <h5 class="modal-title" id="modalTitleId">document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <label class="control-label" for="DocumentFile">Document <span
                                    class="red">*</span></label>
                        </div>
                        <!-- Nav tabs -->
                        <div class="col-lg-8">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" style="border:1px solid #f9f9f9;color:blue;"
                                        id="home-tab" data-bs-toggle="tab" data-bs-target="#info_generale"
                                        type="button" role="tab" aria-controls="home" aria-selected="true"><i
                                            class="fa fa-paperclip"></i>Nouveau</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" style="border:1px solid #f9f9f9;color:blue;" id="profile-tab"
                                        data-bs-toggle="tab" data-bs-target="#complem" type="button" role="tab"
                                        aria-controls="profile" aria-selected="false"><i class="fa as fa-briefcase"></i>
                                        Déjà existant</a>
                                </li>
                            </ul>
                        </div>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            {{-- information géneral --}}
                            <div class="tab-pane active" id="info_generale" role="tabpanel" aria-labelledby="home-tab"
                                style="">
                                <div class="row align-middle">
                                    <div class="col-md-4 align-middle ">
                                        <label for="" class="form-label">TYPE *</label>
                                    </div>
                                    <div class="col-md-8 align-middle">
                                        <select name="" id="civilite" class="form-control">
                                            <option value="">Choisir</option>
                                            <option value="">Appels de fonds</option>
                                            <option value="">Assemblée générale</option>
                                            <option value="">Attestation d'assurance</option>
                                            <option value="">Attestation de scolarité</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:15px;">
                                    <div class="col-md-4 align-middle ">
                                        <label for="" class="form-label">FICHER *</label>
                                    </div>
                                    <div class="col-md-8 align-middle">
                                        <div id="tab_images_uploader_container" class="text-align-reverse jasnify"
                                            style="">
                                            <div class="fileupload">
                                                <a href="#"
                                                    class="btn "style="position: relative; z-index: 1;border: 1px solid gray;background-color:#FAFAFA;"
                                                    onclick="document.getElementById('file-input').click();">Parcourir
                                                </a>
                                                <form>
                                                    <input type="file" id="file-input" style="display:none;" />
                                                </form>
                                            </div>
                                            <span class="help-block">Formats acceptés: Word, Excel, PDF, Images
                                                (GIF, JPG, PNG). Taille maximale: 15 Mo</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="" class="form-label">DESCRIPTION</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="">
                                            {{-- <input type="text" name="" id="" class="form-control" placeholder="EX. Avenue de opera France"
                                                aria-describedby="helpId"> --}}
                                                <textarea name="" id=""class="form-control" cols="5" rows="2" placeholder=""></textarea>
                                                <br>
                                                <p>Cette note est visible uniquement pour vous.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-middle">
                                    <div class="col-lg-3  align-middle">
                                        <h6 style="color: black;">Invitation</h6>
                                    </div>
                                    <div class="form-check form-switch col-lg-9 align-left">
                                        <input type="checkbox" name="" id="flexSwitchCheckChecked"
                                            class="form-check-input form-control" placeholder="" aria-describedby="helpId">
                                        <br>
                                    </div>
                                    <p class="form-control">Partager le document avec votre locataire</p>
                                </div>
                            </div>

                            <div class="tab-pane " id="complem" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="card-body" style="margin-top: -40px">
                                    <div class="">
                                        <label for="" class="form-label">FICHIER</label>
                                        <select name="" id="civilite" class="form-control"><span class="caret"></span>
                                            <option value="">Choisir</option>
                                        </select>
                                        <br>
                                        <p class>Choisissez parmi les fichiers déjà existants dans la rubrique Mes Documents..</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="border:1px solid #f5f5f9"
                        data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end Modal --}}
<div class="card" style="margin-top: 5px">
    <div class="row">
        <div class="col-md-12" style="padding: 15px;">
            <div class="float-end">
                <a href="" class="btn btn-secondary">Annuler</a>
                <a href="" class="btn btn-primary"> Sauvegarder </a>
            </div>
        </div>
    </div>
</div>
