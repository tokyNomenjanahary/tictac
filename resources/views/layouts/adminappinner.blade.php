<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ config('app.name', 'TicTacHouse') }} | Admin</title>
        <link rel='shortcut icon' href='{{URL::asset('images/favicon.ico')}}' type='image/x-icon' />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Bootstrap 3.3.7 -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
        <!-- Ionicons -->
        <link href="{{ asset('css/admin/Ionicons/css/ionicons.min.css') }}" rel="stylesheet">
        <!-- Theme style -->
        <link href="{{ asset('css/admin/AdminLTE.min.css') }}" rel="stylesheet">
        <!-- iCheck -->
        <link href="{{ asset('css/admin/skins/skin-blue.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/admin/custom_admin.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet">
        <link href="{{ asset('bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
        <link href="{{ asset('bootstrap-fileinput/themes/explorer-fa/theme.min.css') }}" rel="stylesheet">
        @stack('styles')
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        <!-- jQuery 3 -->
        <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>

        <!-- jquery dataTables js for this page-->
        <script src="{{ asset('js/ToctocJs/table.js') }}"></script>
        <script src="{{ asset('js/ToctocJs/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Doubons du script jquery provoque u state du requête -->
        <!-- <script src="{{ asset('js/ToctocJs/jquery-3.5.1.js') }}"></script> -->
        <script src="{{ asset('js/ToctocJs/jquery.dataTables.min.js') }}"></script>
        <!-- jquery dataTables js for this page-->

        <!-- AdminLTE App -->
        <script src="{{ asset('js/admin/adminlte.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-select.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
        @stack('scripts')

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            @include('common.adminheader')

            @include('common.adminsidebar')

            @yield('content')

            @include('common.adminfooter')
        </div>
    </body>
</html>

