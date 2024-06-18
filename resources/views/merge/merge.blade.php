<!DOCTYPE html>
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>DevTools</title>

    <meta name="description" content="" />

    <!-- Favicon -->
{{--    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />--}}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
</head>

<body>


<form id="merge" method="POST">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">BailtiDev /</span> Merge Branch (fonctionne sur local uniquement)</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Input</h5>
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        <div class="form-password-toggle">
                            <label class="form-label" for="basic-default-password32">Branche à fusionner :</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="branch" name="branch"
                                       placeholder="BAIL-904-recapitulatif-des-5-prochains-rendez-vous-dans-le-bureau-du-proprietaire">
                            </div>
                        </div>
                        <div class="form-password-toggle">
                            <label class="form-label" for="basic-default-password32">Branche de destination :</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="destination" name="destination" value="merge-location-finance"
                                       placeholder="BAIL-898-publication-dune-revue-rating-par-un-proprietaire" readonly>
                                {{--                                <select id="defaultSelect" class="form-select">--}}
                                {{--                                    <option>Default select</option>--}}
                                {{--                                    <option value="merge-location-finance">merge-location-finance</option>--}}
                                {{--                                </select>--}}
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Merger</button>
                        <button type="button" id="chmod" class="btn btn-primary">Run Chmod 777</button>
                        <button type="button" id="cache" class="btn btn-primary">Empty all Cache</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="../assets/vendor/js/menu.js"></script>
<!-- endbuild -->

<!-- Vendors JS -->

<!-- Main JS -->
<script src="../assets/js/main.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Page JS -->

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script defer>
    $(document).ready(function() {
        $('#merge').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('merge') }}',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    branch: $('#branch').val(),
                    destination: $('#destination').val()
                },
                dataType: 'json',
                success: function (response) {
                    $('#responseMessage').text(response.message);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        $("#branch").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('getGitBranches') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2 // Nombre minimum de caractères avant de déclencher l'autocomplétion
        });


        $('#chmod').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('chmodwecoco') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // Gérez la réponse ici
                    console.log(response.message);
                },
                error: function(xhr) {
                    // Gérez les erreurs ici
                    console.log(xhr.responseText);
                }
            });
        });
        $('#cache').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('cacheClear') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // Gérez la réponse ici
                    console.log(response.message);
                },
                error: function(xhr) {
                    // Gérez les erreurs ici
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>

</body>
</html>
