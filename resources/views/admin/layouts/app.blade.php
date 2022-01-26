<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ISMO') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('admin-resources/css/adminlte.min.css')}}">
        <link rel="stylesheet" href="{{asset('admin-resources/css/toastr.min.css')}}">
        <link rel="stylesheet" href="{{asset('admin-resources/css/jquery.datetimepicker.min.css')}}">
        <link rel="stylesheet" href="{{asset('admin-resources/css/app.css')}}">
        @stack('optional-styles')

    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            @include('admin.layouts.topbar')
            @include('admin.layouts.navigation')

            <div class="content-wrapper">


            <!-- Page Heading -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('header')</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @yield('addButton')
                    </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <!-- Page Content -->
            <div class="content">
                @yield('content')

            </div>

        </div>
    </div>


    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" type="text/javascript"></script>
    <script src="{{asset('admin-resources/js/bootstrap.bundle.min.js')}}" defer></script>
    <script src="{{asset('admin-resources/js/adminlte.js')}}" defer></script>
    <script src="{{asset('admin-resources/js/toastr.min.js')}}" defer></script>
    <script src="{{asset('admin-resources/js/jquery.datetimepicker.full.min.js')}}" defer></script>

    @include('admin.layouts.toaster')

    @stack('optional-scripts')

    </body>
</html>
