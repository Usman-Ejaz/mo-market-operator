<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

        <title>{{ config('app.name', 'MO') }} @yield('title')</title>


        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="{{asset('admin-resources/css/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('admin-resources/css/ionicons.min.css')}}">
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{asset('admin-resources/css/icheck-bootstrap.min.css')}}">

        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('admin-resources/css/adminlte.min.css')}}">

        <style>
            input::-ms-reveal,
            input::-ms-clear {
                display: none;
            }
        </style>

    </head>
    <body class="hold-transition login-page">
        @yield('content')

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" type="text/javascript"></script>
        <script src="{{asset('admin-resources/js/bootstrap.bundle.min.js')}}" defer></script>
        <script src="{{asset('admin-resources/js/adminlte.js')}}" defer></script>

        @stack("optional-scripts")
    </body>
</html>
