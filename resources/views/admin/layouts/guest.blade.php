<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ISMO') }}</title>


        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        
        <!-- Icons -->
        <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome/all.min.css') }}">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('admin/css/adminlte.css') }}">

    </head>
    <body class="hold-transition login-page">
        @yield('content')

        <!-- Scripts -->
        <script src="{{ asset('js/jquery.min.js') }}" defer></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
        <script src="{{ asset('admin/js/adminlte.js') }}" defer></script>

    </body>
</html>