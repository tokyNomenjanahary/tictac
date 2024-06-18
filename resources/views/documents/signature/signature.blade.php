@extends('proprietaire.index')
@section('contenue')
    <style>
        #canv1 {
            height: 140px;
            border-style: solid;
            border-width: 1px;
            border-color: black;
            margin-bottom: 20px;
            margin-left: -86px;
        }
        /* Styles for signature plugin v1.2.0. */
        .kbw-signature {
            display: inline-block;
            border: 1px solid #a0a0a0;
            -ms-touch-action: none;
        }
        .kbw-signature-disabled {
            opacity: 0.35;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css"
          rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1689770056/signature_vzgivi.js"></script>

    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">GESTION DE SIGNATURE</h4>
            <div class="row">
                <!-- Inline text elements -->
                <div class="col">
                    <div class="card mb-4">
                        <h5 class="card-header">Signature</h5>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                <tr class="mb-0">
                                    <td class="align-top"><h6 class="ml-2 text-black fw-semibold">Votre
                                            Signature</h6>
                                    </td>
                                    <td class="py-3">
                                        <div class="mt-5">
                                        <span class="btn btn-file">
                                            <canvas hidden id="canv1"></canvas>
                                            <input class="form-control signaturePhoto" type="file" name="signaturePhoto" id="signaturePhoto"
                                                   onchange="upload()">
                                            <span class="text-danger" id="signaturePhotoErrorMsg"></span>
                                         </span>
                                            @if($signature)
                                                <a class="btn btn-danger fileupload-exists" data-dismiss="fileupload"
                                                   style="margin-left: 47px; margin-top: -19px"
                                                   onclick="deletephotos({{ $signature->id }})">
                                                    <i class="fa-solid fa-trash" style="color:white;"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-danger fileupload-exists" data-dismiss="fileupload"
                                                   style="display:none; margin-left: 47px; margin-top: -19px">
                                                    <i class="fa-solid fa-trash" style="color:white;"></i>
                                                </a>
                                            @endif

                                            <p class="m-2 signaturePhoto">Ajouter votre signature scannée pour personnaliser vos
                                                documents.</p>
                                            <p class="m-2 signaturePhoto">Formats accepter : GIF, JPG, PNG Taille maximale: 15 Mo.</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-top"><small class="text-light fw-semibold"></small></td>
                                    <td class="py-3">
                                        <button type="button"
                                                class="btn btn-outline-primary text-dark border-light-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalCenter">Dessiner la signature
                                        </button>
                                        <p class="m-2">Vous pouvez également dessiner votre signature à la main à l'aide
                                            de
                                            votre souris ou trackpad.</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-left" id="modalCenterTitle">Signature</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{route('documents.uploadGestionSignature')}}">
                        @csrf
                        <div class="mx-auto" style="width: min-content">
                            <label class="" for="">Dessinée la signature ci-dessous:</label>
                            <br/>
                            <div id="sig"></div>
                            <br/>
                            <button id="clear" class="btn btn-danger btn-sm mt-3">Clear Signature</button>
                            <textarea id="signature64" name="signed" style="display: none"></textarea>
                        </div>
                        <br/>
                        {{--                    <button class="btn btn-success">Save</button>--}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-primary">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://www.dukelearntoprogram.com/course1/common/js/image/SimpleImage.js"></script>
    <script type="text/javascript">
        var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
        $('#clear').click(function (e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature64").val('');
        });
        $('#signaturePhoto').on('input', (function (e) {
            if ($(this).val()) {
                var file = $(this)[0].files[0]; // Récupérer le fichier
                // Valider la taille du fichier
                var maxSize = 15 * 1024 * 1024; // Taille maximale en octets (10 Mo dans cet exemple)
                if (file.size > maxSize) {
                    toastr.error('La taille du fichier est trop grande. La taille maximale autorisée est de 15 Mo.');
                    $(this).val('');
                    return;
                }
                // Valider l'extension du fichier
                var allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Extensions autorisées
                var fileExtension = file.name.split('.').pop().toLowerCase(); // Extension du fichier
                if (!allowedExtensions.includes(fileExtension)) {
                    toastr.error('L\'extension du fichier n\'est pas autorisée.');
                    $(this).val('');
                    return;
                }
            }

            $("#canv1").removeAttr("hidden");
            // si un fichier est choisi
            if ($(this).val()) {
                $('.fileupload-exists').show(); // montrer le bouton de suppression
                var data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("name_file", $(this)[0].files[0]);
                $.ajax({
                    url: "{{ route('documents.uploadGestionSignatureNopad') }}",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: data,
                    beforeSend: function () {
                        $('#myLoader').removeClass('d-none')
                    },
                    success: function (data) {
                        $('#myLoader').addClass('d-none')
                        $('#successMsg').show();
                        $('.signaturePhoto').hide();
                        $('.fileupload-exists').attr('onclick', 'deletephotos(' + data.id + ')');
                    },
                    error: function (xhr) {
                        $('#myLoader').addClass('d-none')
                    }
                });
            } else {
                $('.fileupload-exists').hide(); // cacher le bouton de suppression
            }

        }));


        var TenantPhoto = '{{ $signature ? $signature->name_file : null }}';
        if (TenantPhoto) {
            $("#canv1").removeAttr("hidden");
            var imgcanvas = document.getElementById("canv1");
            var image = new Image();
            image.onload = function () {
                var ctx = imgcanvas.getContext("2d");
                imgcanvas.width = image.width * 2; // double la largeur de l'image
                imgcanvas.height = image.height * 2; // double la hauteur de l'image
                ctx.drawImage(image, 0, 0, imgcanvas.width, imgcanvas.height);
            };
            image.src = '{{ $signature ? '/uploads/signature/' . $signature->name_file : null }}';
            $('.fileupload-exists').show();
            $('.signaturePhoto').hide();
        } else {

        }


        function deletephotos(id) {
            $('#myLoader').removeClass('d-none')
            $.ajax({
                url: "/suppsignature/" + id,
                type: 'get',
                success: function (data) {
                    $('#myLoader').addClass('d-none')
                    $('.signaturePhoto').show();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }

        //evenement lorque on clique l'icone supprime le photo de locataire
        $('.fileupload-exists').click(function () {
            // supprimer l'image et réinitialiser l'input de fichier
            $('#signaturePhoto').val('');
            // $('.fileupload-preview').hide();
            $(this).hide();
            const canv1Div = document.getElementById('canv1');
            // const defaultImage = document.getElementById('default-image');
            // defaultImage.style.display = '';
            canv1Div.style.display = 'none';
        });

        function upload() {
            const canv1Div = document.getElementById('canv1');
            // const defaultImage = document.getElementById('default-image');
            var imgcanvas = document.getElementById("canv1");
            var fileinput = document.getElementById("signaturePhoto");

            if (fileinput.files.length === 0) {
                // Aucun fichier sélectionné, cacher le canvas et afficher l'image par défaut
                canv1Div.style.display = 'none';
                defaultImage.style.display = '';
                return;
            }
            // Afficher le canvas et cacher l'image par défaut
            canv1Div.style.display = '';
            // defaultImage.style.display = 'none';

            var image = new SimpleImage(fileinput);
            image.drawTo(imgcanvas);
        }

    </script>
@endsection
