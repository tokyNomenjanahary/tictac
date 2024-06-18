<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('acceuil.master_title') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648722849/css/countrySelect_luefw2.css" rel="stylesheet">
    <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648722693/css/intlTelInput.min_ft4ncf.css">
    <link href="https://res.cloudinary.com/dnnf3mdjs/raw/upload/v1648722991/css/register.min_zos78a.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/footer.min.css" rel="stylesheet">
</head>
<body>
@include('common.header_login_verif')

    @yield('content')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    @stack('scripts')
    @include('common.footer')
</body>

<style>
                .dropdown-login-nrh{
                    z-index: 100;
                    position: absolute;
                    top: 10px;
                    right: 20px;

                    background: white;
                    width: 60px;
                    border-radius: 10px 4px 4px 10px;
                }

                .dropdown-menu-login-nrh {
                    min-width: 60px;
                }

                .dropdown-item-login-nrh {
                    width: 46px;
                    padding-left: 20px;
                }

                .dropdown-item-header-1-nrh {
                    border: none !important;
                    padding: 20px !important;
                }

                .dropdown-item-header-1-nrh:hover {
                    background-color: transparent !important;
                }

                .dropdown-header-1-nrh {
                    /*position: relative;
                    top: 15px;*/
                }

                @media (max-width: 990px) {
                    .custum-navbar-nav-menuone {
                        width: 100%;
                    }

                    .dropdown-header-1-nrh.open {
                        /*width: 56px !important;*/
                    }

                    .dropdown-header-1-nrh.open .dropdown-menu-header-1-nrh{
                        width: 56px !important;
                        z-index: 100;
                        float: right;
                        position: relative;
                        top: 20px;
                        left: -60px;
                    }

                    .dropdown-menu {
                        float: right;
                    }
                }

                @media only screen and (max-width: 990px){
                    .home-custom-navbar ul li a {
                        max-width: 200px;
                        /* display: inline; */
                        width: 100%;
                    }

                    .home-custom-navbar-menuone ul li{
                        display: block !important;
                    }

                    .home-custom-navbar {
                        height: auto;
                    }
                }



            </style>

</html>