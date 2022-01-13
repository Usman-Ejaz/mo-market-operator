<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ISMO') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <!-- Icons -->
        <link rel="stylesheet" href="{{asset('admin/css/all.min.css')}}">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="{{asset('admin/css/ionicons.min.css')}}">
        
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('admin/css/adminlte.min.css')}}">

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
    <script src="{{asset('admin/js/jquery.min.js')}}" defer></script>
    <script src="{{asset('admin/js/bootstrap.bundle.min.js')}}" defer></script>
    <script src="{{asset('admin/js/adminlte.js')}}" defer></script>
    <script src="{{asset('admin/js/Chart.min.js')}}" defer></script>
    <script src="{{asset('admin/js/dashboard3.js')}}" defer></script>
    <script src="{{asset('admin/js/demo.js')}}" defer></script>
    

    @stack('optional-scripts')
    
    </body>
</html>
