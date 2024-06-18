@extends('proprietaire.index')

@section('file-input')
    <!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
    {{-- <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css"> --}}

    <!-- bootstrap 5.x or 4.x is supported. You can also use the bootstrap css 3.3.x versions -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        crossorigin="anonymous">

    <!-- default icons used in the plugin are from Bootstrap 5.x icon library (which can be enabled by loading CSS below) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
        crossorigin="anonymous">

    <!-- alternatively you can use the font awesome icon library if using with `fas` theme (or Bootstrap 4.x) by uncommenting below. -->
    <!-- link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" crossorigin="anonymous" -->

    <!-- the fileinput plugin styling CSS file -->
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all"
        rel="stylesheet" type="text/css" />
    <style>
        .kv-file-rotate,
        .kv-file-upload {
            display: none !important;
        }

        .nav-tabs .nav-item .nav-link:not(.active) {
            background-color: rgb(250, 250, 250);
        }

        .nav-tabs .nav-item .nav-link.active {
            border-top: 3px solid blue !important;
            border-bottom: 0 !important;
        }

        .nav-tabs .nav-item .nav-link {
            color: blue !important;
            font-size: 13px !important;
        }

        .nav-tabs .nav-item .nav-link.active {
            border-top: 3px solid blue !important;
            border-bottom: 3px solid white !important;
        }

        .nav-align-top .nav-tabs .nav-item .nav-link {
            border-top-right-radius: 0 !important;
        }
    </style>
@endsection

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@section('contenue')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- HEADER -->
            <div class="mb-4">
                <div>
                    @if (isset($inventaire))
                        <h3 class="page-header page-header-top m-0">{{ __('inventaire.titre_modification') }}</h3>
                    @else
                        <h3 class="page-header page-header-top m-0"><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>{{ __('inventaire.nouveau') }}</h3>
                        
                    @endif

                </div>
            </div>
            <!-- END HEADER -->
            <div class="row">
                <div class="col">
                    <div class="card mb-4 p-3">
                        <div class="row">
                            <div class="col">
                                <form method="POST" id="form-inventaire" enctype="multipart/form-data">
                                    <input type="hidden" name="nombre_piece" id="nombre_piece"
                                        @if (isset($inventaire)) value="{{ count($inventaire->piece) }}" @else value="0" @endif>
                                    <div class="mb-4">
                                        <div class="card h-100">

                                            <div class="card-body p-0">
                                                <div class="p-3 border-bottom">
                                                    {{-- <div class="nav-align-top mb-4"> --}}
                                                    <ul class="nav nav-tabs" role="tablist" id="nav-id">
                                                        <li class="nav-item">
                                                            <button type="button" class="nav-link active text-uppercase"
                                                                id="info-gen-id" data-id="info-gen-id" role="tab"
                                                                data-bs-toggle="tab" data-bs-target="#navs-info-gen"
                                                                aria-controls="navs-info-gen" aria-selected="true">
                                                                {{ __('inventaire.info_g') }}
                                                            </button>
                                                        </li>
                                                        @if (isset($inventaire))
                                                            @foreach ($inventaire->piece as $index_count_piece => $piece)
                                                                <li class="nav-item"
                                                                    id="{{ 'piece-nav-' . ($index_count_piece + 1) }}">
                                                                    <button type="button"
                                                                        id="{{ 'nav-tab-' . ($index_count_piece + 1) }}"
                                                                        data-id="{{ 'nav-tab-' . ($index_count_piece + 1) }}"
                                                                        class="nav-link text-uppercase" role="tab"
                                                                        data-bs-toggle="tab"
                                                                        data-bs-target="{{ '#nav-nouvelle-piece-' . ($index_count_piece + 1) }}"
                                                                        aria-controls="{{ 'nav-nouvelle-piece-' . ($index_count_piece + 1) }}"
                                                                        aria-selected="false">{{ $piece->identifiant }}
                                                                    </button>
                                                                </li>
                                                            @endforeach
                                                        @endif

                                                        <li class="nav-item">
                                                            <button type="button" class="nav-link text-uppercase"
                                                                id="observer" data-id="observer" role="tab"
                                                                data-bs-toggle="tab" data-bs-target="#navs-observer"
                                                                aria-controls="navs-observer" aria-selected="true">
                                                                Observation
                                                            </button>
                                                        </li>
                                                        <li class="nav-item" id="nouv">
                                                            <button type="button" class="nav-link text-uppercase">
                                                                {{ __('inventaire.ajouter_piece') }}
                                                            </button>
                                                        </li>
                                                    </ul>


                                                    <div class="tab-content" id="tab-id">
                                                        <div class="tab-pane fade active show" id="navs-info-gen"
                                                            role="tabpanel">

                                                            @csrf
                                                            <div class="mb-4">

                                                                {{-- INFO GENERAL --}}
                                                                @include('inventaire.nouveaux_inventaire_info_generale')
                                                                {{-- FIN INFO GENERAL --}}
                                                            </div>

                                                        </div>
                                                        <div class="tab-pane fade" id="navs-observer" role="tabpanel">
                                                            <!-- CARD FILTER -->
                                                            <div>
                                                                <label for="observerText"
                                                                    class="form-label">Observation</label>
                                                                <textarea class="form-control" id="observerText" name="observation" rows="3">
@if (isset($inventaire->observation))
{{ $inventaire->observation }}
@endif
</textarea>
                                                            </div>
                                                            <!-- END CARD FILTER -->
                                                        </div>

                                                        @if (isset($inventaire))
                                                            @foreach ($inventaire->piece as $index => $piece)
                                                                @php
                                                                    $nombre_piece = $index + 1;
                                                                @endphp
                                                                <div class="tab-pane fade mb-3 all-piece"
                                                                    id="{{ 'nav-nouvelle-piece-' . $nombre_piece }}"
                                                                    role="tabpanel">

                                                                    <div>
                                                                        <div
                                                                            class="card-header border-bottom py-2 px-3 mb-3">
                                                                            <div
                                                                                class="card-title mb-0 row  align-items-center">
                                                                                <div class="w-auto"
                                                                                    style="background-color: rgb(249,249,249)">
                                                                                    <h5 class="m-0 me-2 w-auto">
                                                                                        {{ __('inventaire.piece') }}</h5>
                                                                                </div>
                                                                                <div class="w-auto align-self-end">
                                                                                    <span
                                                                                        class="btn btn-sm btn-danger btn-remove-piece"
                                                                                        data-id="{{ 'nav-nouvelle-piece-' . $nombre_piece }}"
                                                                                        data-nav="{{ 'piece-nav-' . $nombre_piece }}"><i
                                                                                            class="fa-solid fa-trash-can"></i>{{ __('finance.Supprimer') }}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="" id="">
                                                                            <div class="card-body p-0">
                                                                                <div class="p-3">
                                                                                    <div class="mb-3 row">
                                                                                        <label for="etat_name"
                                                                                            class="col-md-2 col-form-label  text-end">{{ __('inventaire.nom_piece') }}</label>
                                                                                        <div class="col-lg-5">
                                                                                            <input
                                                                                                class="{{ 'form-control cls-piece-' . $nombre_piece }}"
                                                                                                name="{{ 'piece-id-' . $nombre_piece }}"
                                                                                                id="{{ 'piece-id-' . $nombre_piece }}"
                                                                                                data-input="{{ 'piece-id-' . $nombre_piece }}"
                                                                                                type="text"
                                                                                                placeholder="Identifiant"
                                                                                                value='{{ $piece->identifiant }}'
                                                                                                onkeyup="updateNamePiece({{ $nombre_piece }})">
                                                                                            <input type="number" hidden
                                                                                                id="{{ 'nbr-element-' . $nombre_piece }}"
                                                                                                name="{{ 'nbr-element-' . $nombre_piece }}"
                                                                                                value="{{ count($piece->elementPiece) }}">
                                                                                            <input type="hidden"
                                                                                                name="{{ 'piece-id-modif-' . $nombre_piece }}"
                                                                                                value="{{ $piece->id }}">

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
                                                                        <div id="{{ 'mur-plafond-sol-' . $nombre_piece }}">
                                                                            @foreach ($piece->elementPiece as $index_piece => $element)
                                                                                @php
                                                                                    $current_element_loop = $index_piece + 1;
                                                                                @endphp
                                                                                <div class="row p-3 align-items-start">

                                                                                    <div class="col-md-1 text-end">
                                                                                        <div>
                                                                                            <label class="col-form-label text-end my-1"></label>
                                                                                        </div>
                                                                                        <span class="btn btn-danger btn-remove">
                                                                                            <i class="fa-solid fa-trash-can"></i>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div>
                                                                                            <label
                                                                                                class="col-form-label text-end">{{ __('inventaire.element') }}</label>
                                                                                        </div>
                                                                                        <input
                                                                                            class="{{ 'form-control cls-piece-' . $current_element_loop }}"
                                                                                            type="text"
                                                                                            name="{{ 'element-' . $nombre_piece . '-' . $current_element_loop }}"
                                                                                            value="{{ $element->identifiant }}"
                                                                                            data-input="{{ 'mur-val-mur-plafond-sol-' . $nombre_piece }}">
                                                                                        <input type="hidden"
                                                                                            name="{{ 'element-id-' . $nombre_piece . '-' . $current_element_loop }}"
                                                                                            value="{{ $element->id }}">
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div>
                                                                                            <label
                                                                                                class="col-form-label text-end">{{ __('inventaire.nombre') }}</label>
                                                                                        </div>
                                                                                        <input
                                                                                            class="{{ 'form-control check-minus cls-piece-' . $nombre_piece }}"
                                                                                            type="number" min="1"
                                                                                            value="{{ $element->nombre }}"
                                                                                            name="{{ 'nbr-' . $nombre_piece . '-' . $current_element_loop }}">
                                                                                            <span class="text-danger d-none">Veuillez entrer un nombre positif</span>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div>
                                                                                            <label
                                                                                                class="col-form-label text-end">{{ __('inventaire.etat') }}</label>
                                                                                        </div>
                                                                                        <select class="form-select "
                                                                                            name="{{ 'usure-' . $nombre_piece . '-' . $current_element_loop }}">
                                                                                            <option value=""
                                                                                                selected>
                                                                                                {{ __('inventaire.non_verifie') }}
                                                                                            </option>
                                                                                            @foreach ($etat_usures as $etat_usure)
                                                                                                @if ($etat_usure->id == $element->etat_usure_id)
                                                                                                    <option
                                                                                                        value="{{ $etat_usure->id }}"
                                                                                                        selected>
                                                                                                        {{ $etat_usure->name }}
                                                                                                    </option>
                                                                                                @else
                                                                                                    <option
                                                                                                        value="{{ $etat_usure->id }}">
                                                                                                        {{ $etat_usure->name }}
                                                                                                    </option>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <div>
                                                                                            <label
                                                                                                class="col-form-label text-end">{{ __('inventaire.prix') }}</label>
                                                                                        </div>
                                                                                        <input class="form-control check-minus"
                                                                                            type="number" min="1"
                                                                                            value="{{ $element->prix }}"
                                                                                            name="{{ 'prix-' . $nombre_piece . '-' . $current_element_loop }}">
                                                                                            <span class="text-danger d-none">Veuillez entrer un nombre positif</span>
                                                                                    </div>
                                                                                    <div class="col-lg">
                                                                                        <div>
                                                                                            <label
                                                                                                class="col-form-label text-end">{{ __('inventaire.commentaire') }}</label>
                                                                                        </div>
                                                                                        <input class="form-control "0
                                                                                            name="{{ 'commentaire-' . $nombre_piece . '-' . $current_element_loop }}"
                                                                                            value="{{ $element->commentaire }}"
                                                                                            type="text"
                                                                                            placeholder="Description">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach

                                                                        </div>
                                                                        <div class="row g-0">
                                                                            <div class="col-md-1"></div>
                                                                            <div class="col ps-3 mb-3">
                                                                                <span class="btn btn-primary"
                                                                                    data-piece="{{ $nombre_piece }}"
                                                                                    data-cls="{{ 'cls-piece-' . $nombre_piece }}"
                                                                                    data-id="{{ 'mur-plafond-sol-' . $nombre_piece }}"
                                                                                    id="{{ 'add-wall-' . $nombre_piece }}"
                                                                                    onclick="addElementIntoRoom(this)">{{ __('inventaire.ajout_champ') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>


                                                    {{-- </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="card" style="margin-top: 5px"> --}}
                                    <div class="row align-items-end">
                                        <label class="col-md-2 colcol-form-label text-end"></label>
                                        <div class="col justify-content-end">

                                            <span class="btn btn-success btn-md d-none col-md-2 col-4 text-truncate"
                                                id="prec-etat" style="padding-left: 0px;padding-right:0px">
                                                {{ __('inventaire.precedent') }}</span>
                                            @if (isset($inventaire))
                                                <span type="submit"
                                                    class="btn btn-md btn-primary col-md-2 col-4 text-truncate"
                                                    id="modifier-inventaire" style="padding-left: 0px;padding-right:0px">
                                                    {{ __('inventaire.modifier') }}</span>
                                            @else
                                                <span type="submit"
                                                    class="btn btn-md btn-primary col-md-2 col-4 text-truncate"
                                                    id="enregistrer-inventaire"
                                                    style="padding-left: 0px;padding-right:0px">
                                                    {{ __('inventaire.sauvegarder') }}</span>
                                            @endif

                                            <span class="btn btn-success col-md-2 col-3 text-truncate" id="suiv-etat"
                                                style="padding-left: 0px;padding-right:0px">
                                                {{ __('inventaire.suivant') }}</span>

                                        </div>
                                    </div>
                                    {{-- </div> --}}



                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endPush
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        var clsePi = function() {
            document.querySelectorAll("span.btn-remove-piece").forEach(function(element) {
                element.addEventListener("click", function(e) {
                    let id = this.getAttribute('data-id');
                    let nav = this.getAttribute('data-nav');
                    let searArr = document.querySelector('#' + nav).parentElement.children;
                    let activeBefore = Array.prototype.indexOf.call(searArr, document.querySelector(
                        '#' + nav));
                    document.querySelector("#nav-id li:nth-child(" + activeBefore + ")");
                    let tac = document.querySelector("#nav-id li:nth-child(" + activeBefore + ")")
                        .children[0].getAttribute("data-id");
                    this.closest('#' + id).remove();
                    document.querySelector('#' + nav).remove();
                    $("#" + tac).tab("show")
                });
            });
        }
        clsePi()
        var checkMinus = function() {
            var expression = /^[0-9]+(\.[0-9]+)?$/;
            $('.check-minus').on('input', function () {
                let inpVal = $(this).val()
                if (expression.test(inpVal)) {
                    $(this).removeClass('border-danger')
                    $(this).next().addClass('d-none')
                } else if (!expression.test(inpVal)) {
                    $(this).addClass('border-danger')
                    $(this).next().removeClass('d-none')
                }
            })
        }
        checkMinus()
        var stepHandlerOnTabs = function() {
            $("ul#nav-id li").on("click", function() {
                let active = $(this)
                let index = $("ul#nav-id").children().index(active)
                let tab = $("ul#nav-id li").length - 2
                //console.log(index)
                $("#prec-etat").removeClass("d-none")
                $("#suiv-etat").removeClass("d-none")

                if (index === 0) {
                    $("#prec-etat").addClass("d-none")
                    $("#suiv-etat").removeClass("d-none")
                } else if (index === tab) {
                    $("#suiv-etat").addClass("d-none")
                    $("#prec-etat").removeClass("d-none")

                }
            })
        }
        stepHandlerOnTabs()

        function updateNamePiece(date) {
            var inputValuePiece = document.getElementById("piece-id-" + date).value;
            if (inputValuePiece == "") {
                inputValuePiece.innerHTML = "Piece"
            } else {
                document.getElementById("nav-tab-" + date).innerHTML = inputValuePiece;
            }

        }

        function addElementIntoRoom() {
            let mur = $(this).attr('data-id');
            let piece = $(this).attr('data-piece');
            /* nombre d'element deja ajouté a la piece */
            let nbr_element = parseInt($('#nbr-element-' + piece).val()) + 1;

            let cls = $(this).attr('data-cls');
            let child = `
            <div class="row p-3 align-items-start">
                <div class="col-md-1 text-end">
                    <div>
                        <label class="col-form-label text-end my-1"></label>
                    </div>
                    <span class="btn btn-danger btn-remove"><i class="fa-solid fa-trash-can"></i></span>
                </div>
                <div class="col-lg-2">
                <input type="number" hidden data-input="mur-val-modif-">
                <div>
                    <label class="col-form-label text-end">{{ __('inventaire.element') }}</label>
                </div>
                <input class="form-control" type="text" data-input="mur-val-" name="element-${ piece }-${ nbr_element }">
                </div>
                <div class="col-lg-2">
                    <div>
                        <label class="col-form-label text-end">{{ __('inventaire.nombre') }}</label>
                    </div>
                    <input class="form-control cls-piece-${piece }  check-minus" name="nbr-${ piece }-${ nbr_element }" type="number" min="1" data-input="mur-nnombre-mur-plafond-sol-${nombre_piece}" >
                    <span class="text-danger d-none">Veuillez entrer un nombre positif</span>
                </div>
                <div class="col-lg-2">
                <div>
                    <label for="fonction-eau" class="col-form-label text-end">{{ __('inventaire.etat') }}</label>
                    </div>
                    <select class="form-select ${cls}" data-input="mur-usure-" name="usure-${ piece }-${ nbr_element }">
                        <option value="" selected>{{ __('inventaire.non_verifie') }}</option>
                        @foreach ($etat_usures as $etat_usure)
                            <option value="{{ $etat_usure->id }}">{{ $etat_usure->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <div>
                        <label class="col-form-label text-end">{{ __('inventaire.prix') }}</label>
                    </div>
                    <input class="form-control cls-piece-${piece } check-minus" name="prix-${ piece }-${ nbr_element }" type="number" min="1" data-input="mur-nnombre-mur-plafond-sol-${piece }">
                    <span class="text-danger d-none">Veuillez entrer un nombre positif</span>
                </div>
                <div class="col-lg">
                <div>
                    <label class="col-form-label text-end">{{ __('inventaire.commentaire') }}</label>
                </div>
                <input class="form-control ${cls}" name="commentaire-${ piece }-${ nbr_element  }" type="text" placeholder="Description" data-input="mur-commentaire-">
                </div>
            </div>
            `;

            // $('#nbr-element-' + piece).val(nbr_element + 1);

            /* modification du nombre d'element d'une piece  */
            $('#nbr-element-' + piece).val(nbr_element);

            $("#" + mur).append(child)
            checkMinus()
            $("span.btn-remove").on('click', function(e) {
                $(this).closest('.row.p-3.align-items-start').remove()
            })
        }

        function addElementIntoRoomEvent() {
            let count_nombre_piece = 0;
            @if (isset($inventaire))
                let inventaire = @json($inventaire);
                count_nombre_piece = inventaire.piece.length;
            @endif
            if (count_nombre_piece > 0) {
                for (let index = 1; index <= count_nombre_piece; index++) {
                    $('#add-wall-' + index).on('click', addElementIntoRoom);
                }
            }

        }

        /* modification dynamic de la liste des locations en fonction du bien selectioné (fichier= nouveau_inventaire_info_generale) */
        function checkLocation() {
            let bien = $('#bien').val();
            jQuery.ajax({
                url: "{{ route('inventaire.recherche_bien_location') }}",
                method: 'get',
                data: {
                    bien: bien
                },
                success: function(result) {
                    console.log(result)
                },
                error: function(data) {

                }
            });
        }

        $(document).ready(function() {
            let activeTab = $('.nav-tabs > .nav-item > .active');
            let activeTabId = activeTab.attr('id');

            addElementIntoRoomEvent();



            if (activeTabId == "home-tab") {
                $("#precedent").hide();
            }

            $("span.btn-remove").on('click', function(e) {
                $(this).closest('.row.p-3.align-items-start').remove()
            })

            $('.nav-link').on('click', function() {
                // Mettre à jour l'affichage des boutons en fonction de l'onglet actif
                let activeTab = $('.nav-tabs > .nav-item > .active');
                let activeTabId = activeTab.attr('id');
                if (activeTabId === "home-tab") {
                    $("#precedent").hide();
                    $("#suivant").show();
                } else if (activeTabId === "document-tab") {
                    $("#precedent").show();
                    $("#suivant").hide();
                } else {
                    $("#precedent").show();
                    $("#suivant").show();
                }
            });


            $('#suivant').click(function() {
                let currentTab = $('.nav-tabs > .nav-item > .active');
                let nextTab = $(currentTab).parent().next().find('.nav-link');

                if (nextTab.length) {
                    nextTab.tab('show');
                }
                let activeTab = $('.nav-tabs > .nav-item > .active');
                let activeTabId = activeTab.attr('id');
                if (activeTabId == "profile-tab") {
                    $("#precedent").show();
                }
                if (activeTabId == "document-tab") {
                    $("#suivant").hide();
                }
            });


            $('#precedent').click(function(e) {
                e.preventDefault()
                let activeTabe = $('.nav-tabs > .nav-item > .active');
                let activeTabIde = activeTabe.attr('id');
                if (activeTabIde == "profile-tab") {
                    $("#precedent").hide();
                    $("#suivant").show();
                }
                if (activeTabIde == "document-tab") {
                    $("#suivant").show();
                }

                let currentTab = $('.nav-tabs > .nav-item > .active');
                let previousTab = $(currentTab).parent().prev().find('.nav-link');
                if (previousTab.length) {
                    previousTab.tab('show');
                }
            });

            var current_page = 1;

            $("#nouv").on('click', function name() {
                let nombre_piece = parseInt($('#nombre_piece').val()) + 1;


                /* entête du nouvel onglet pièce que l'ont vient de creer */
                let pieceTab = `
                                <li class="nav-item" id="piece-nav-${nombre_piece}">
                                <button type="button" id="nav-tab-${nombre_piece}" data-id="nav-tab-${nombre_piece}" class="nav-link text-uppercase" role="tab" data-bs-toggle="tab" data-bs-target="#nav-nouvelle-piece-${nombre_piece}" aria-controls="nav-nouvelle-piece-${nombre_piece}" aria-selected="false">{{ __('inventaire.nouvelle_piece') }}
                                </button>
                                </li>`;

                /* contenut (tab-panel) du nouvel onglet pièce que l'ont vient de creer */
                let piece = `
                            <div class="tab-pane fade mb-3 all-piece" id="nav-nouvelle-piece-${nombre_piece}" role="tabpanel">

                                <div>
                                  <div class="card-header border-bottom py-2 px-3 mb-3">
                                    <div class="card-title mb-0 row  align-items-center">
                                      <div class="w-auto" style="background-color: rgb(249,249,249)">
                                        <h5 class="m-0 me-2 w-auto">{{ __('inventaire.piece') }}</h5>
                                      </div>
                                      <div class="w-auto align-self-end">
                                        <span class="btn btn-sm btn-danger btn-remove-piece" data-id="nav-nouvelle-piece-${nombre_piece}" data-nav="piece-nav-${nombre_piece}"><i class="fa-solid fa-trash-can"></i>{{ __('finance.Supprimer') }}</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="" id="">
                                    <div class="card-body p-0">
                                      <div class="p-3">
                                        <div class="mb-3 row">
                                          <label for="etat_name" class="col-md-2 col-form-label  text-end">{{ __('inventaire.nom_piece') }}</label>
                                          <div class="col-lg-5">
                                            <input class="form-control cls-piece-${nombre_piece}" name="piece-id-${nombre_piece}" id="piece-id-${nombre_piece}" data-input="piece-id-${nombre_piece}" type="text" placeholder="Identifiant" value='{{ __('inventaire.piece') }}' onkeyup="updateNamePiece(${nombre_piece})">
                                            <input type="number" hidden id="nbr-element-${nombre_piece}" name="nbr-element-${nombre_piece}" value="1">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <hr>
                                  <div id="mur-plafond-sol-${nombre_piece}">
                                    <div class="row p-3 align-items-start">
                                      <div class="col-md-1 text-end"></div>
                                      <input type="number" hidden data-input="mur-val-modif-mur-plafond-sol-${nombre_piece}">
                                      <div class="col-lg-2">
                                        <div>
                                          <label class="col-form-label text-end">{{ __('inventaire.element') }}</label>
                                        </div>
                                        <input class="form-control cls-piece-${nombre_piece}" type="text" name="element-${ nombre_piece}-1" data-input="mur-val-mur-plafond-sol-${nombre_piece}">
                                      </div>
                                      <div class="col-lg-2">
                                        <div>
                                          <label class="col-form-label text-end">{{ __('inventaire.nombre') }}</label>
                                        </div>
                                        <input class="form-control cls-piece-${nombre_piece} check-minus" type="number" min="1" name="nbr-${ nombre_piece}-1" >
                                        <span class="text-danger d-none">Veuillez entrer un nombre positif</span>
                                      </div>
                                      <div class="col-lg-2">
                                        <div>
                                          <label class="col-form-label text-end">{{ __('inventaire.etat') }}</label>
                                        </div>
                                        <select class="form-select cls-piece-${nombre_piece}" data-input="mur-usure-mur-plafond-sol-${nombre_piece}" name="usure-${ nombre_piece}-1">
                                          <option value="" selected>{{ __('inventaire.non_verifie') }}</option>
                                          @foreach ($etat_usures as $etat_usure)
                                            <option value="{{ $etat_usure->id }}">{{ $etat_usure->name }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="col-lg-2">
                                        <div>
                                          <label class="col-form-label text-end">{{ __('inventaire.prix') }}</label>
                                        </div>
                                        <input class="form-control cls-piece-${nombre_piece} check-minus" type="number" min="1" name="prix-${ nombre_piece}-1" data-input="mur-nnombre-mur-plafond-sol-${nombre_piece}" >
                                        <span class="text-danger d-none">Veuillez entrer un nombre positif</span>
                                      </div>
                                      <div class="col-lg">
                                        <div>
                                          <label class="col-form-label text-end">{{ __('inventaire.commentaire') }}</label>
                                        </div>
                                        <input class="form-control cls-piece-${nombre_piece}" name="commentaire-${ nombre_piece}-1"  type="text" placeholder="Description" data-input="mur-commentaire-mur-plafond-sol-${nombre_piece}">
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row g-0">
                                    <div class="col-md-1"></div>
                                    <div class="col ps-3 mb-3">
                                      <span class="btn btn-primary" data-piece="${nombre_piece}" data-cls="cls-piece-${nombre_piece}" data-id="mur-plafond-sol-${nombre_piece}" id="add-wall-${ nombre_piece }">{{ __('inventaire.ajout_champ') }}</span>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            `;



                $("#nav-id .nav-item:nth-last-child(2)").before(pieceTab)

                $("#tab-id").append(piece)

                /* nouton d'ajout d'un nouvel element à une piece */
                $('#add-wall-' + nombre_piece).on('click', addElementIntoRoom);

                /* se focaliser la nouvelle piece qui vient d'être créée */
                $('#nav-tab-' + nombre_piece).trigger('click');;


                clsePi()
                stepHandlerOnTabs()
                checkMinus()
                $('#nombre_piece').val(nombre_piece);

            })

            function saveData(url) {
                $("#myLoader").removeClass("d-none");
                let form = document.getElementById('form-inventaire');
                let formData = new FormData(form);
                let data = {};
                for (const [key, value] of formData) {
                    data[key] = value;
                }
                jQuery.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    method: 'post',
                    data: {
                        information: JSON.stringify(data)
                    },
                    success: function(result) {
                        location.href = "{{ route('inventaire.index') }}"
                    },
                    error: function(data) {
                        let errors = data.responseJSON.errors;
                        $("#myLoader").addClass("d-none")
                        $.each(errors, function(key, value) {
                            /* reinitialiser le statut de validiter de l'indentifiant */
                            $("#identifiant").removeClass("is-invalid");
                            $('#err_identifiant').text('');

                            $("#" + key).addClass("is-invalid")
                            if ($("#" + key).hasClass("is-invalid")) {
                                $("#" + "err_" + key).text(value);
                            } else {
                                toastr.error(value);
                            }
                        });
                        $("#info-gen-id").tab("show")
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#" + Object.keys(errors)[0]).offset().top - 50
                        }, 100);
                        $("#myLoader").addClass("d-none")
                    }
                });
            }

            $("#enregistrer-inventaire").on('click', function(e) {
                var url = "{{ route('inventaire.enregistrer_inventaire') }}";
                saveData(url)
            });
            $("#modifier-inventaire").on('click', function(e) {
                @if (isset($inventaire))
                    var url = "{{ route('inventaire.saveUpdateInventaire', $inventaire->id) }}";
                    saveData(url)
                @endif
            });
        })
    </script>
    <script>
        var stepHandler = function(i) {
            window.scrollTo(0, 0)
            let active = $("ul#nav-id li").find("button.active").closest("li")
            let index = $("ul#nav-id").children().index(active) + i //suivant
            let countChild = $("#nav-id").children().length
            let number = $("ul#nav-id li:nth-child(" + index + ")").children().attr("id")
            $("#" + number).tab("show")
            return {
                index: index,
                countChild: countChild
            };
        }

        $("#suiv-etat").on('click', function(e) {
            const {
                countChild,
                index
            } = stepHandler(2)
            limit = countChild - 1
            if (limit === index) {
                $(this).addClass("d-none")
            }
            $("#prec-etat").removeClass("d-none")
        })

        $("#prec-etat").on('click', function(e) {
            const {
                index
            } = stepHandler(0)
            limit = index - 1
            if (limit === 0) {
                $(this).addClass("d-none")
            }
            $("#suiv-etat").removeClass("d-none")
        })
    </script>
@endPush
