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
        <link rel="stylesheet" href="{{ mix('admin/plugins/fontawesome/all.min.css') }}">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ mix('admin/css/adminlte.css') }}">

    </head>
    <body class="hold-transition login-page">
        @yield('content')

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" type="text/javascript"></script>
        <script src="{{ mix('js/bootstrap.bundle.min.js') }}" defer></script>
        <script src="{{ mix('admin/js/adminlte.js') }}" defer></script>

    </body>
</html>