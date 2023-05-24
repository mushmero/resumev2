<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Resume of Amirul Hakim" />
    <meta name="author" content="Amirul Hakim" />

    <link rel="shortcut icon" href="{!! asset('favicon.ico') !!}">

    <!-- The Stylesheet -->
    <link rel="stylesheet" href="{!! asset('vendor/adminlte/dist/css/adminlte.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/css/welcome.css') !!}">
    <link rel="stylesheet" href="{!! asset('vendor/fontawesome-free/css/all.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('vendor/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') !!}">
    {{-- @yield ('additional_header') --}}
</head>
<body>
    @yield('content')
    @yield('footer')
    <script src="{!! asset('vendor/jquery/jquery.min.js') !!}"></script>
    <script src="{!! asset('vendor/moment/moment-with-locales.min.js') !!}"></script>
    <script src="{!! asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') !!}"></script>
    <script src="{!! asset('vendor/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
    <script src="{!! asset('vendor/sweetalert2/sweetalert2.all.min.js') !!}"></script>
    {{-- <script src="{!! asset('assets/js/welcome.js') !!}"></script> --}}
</body>
</html>