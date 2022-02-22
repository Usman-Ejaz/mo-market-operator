@extends('admin.layouts.app')
@section('header', 'Settings')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
@endsection

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="update-settings-form">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        @method('PATCH')
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <label>Current Theme <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="custom-control custom-radio text-center">
                                        <input class="custom-control-input" type="radio" id="theme_1" name="current_theme" value="theme1" {{ $theme->value === "theme1" ? "checked" : "" }}>
                                        <label for="theme_1" class="custom-control-label">Theme 1</label>
                                        <div class="row d-flex" style="align-items: center;justify-content: center;">
                                            <img src="{{ asset('themes/theme_1.png') }}" alt="Theme 1" height="200px" width="200px" class="mt-3 {{ $theme->value === 'theme1' ? 'active-theme' : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="custom-control custom-radio text-center">
                                        <input class="custom-control-input" type="radio" id="theme_2" name="current_theme" value="theme2" {{ $theme->value === "theme2" ? "checked" : "" }}>
                                        <label for="theme_2" class="custom-control-label">Theme 2</label>
                                        <div class="row d-flex" style="align-items: center;justify-content: center;">
                                            <img src="{{ asset('themes/theme_2.png') }}" alt="Theme 1" height="200px" width="200px" class="mt-3 {{ $theme->value === 'theme2' ? 'active-theme' : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="custom-control custom-radio text-center">
                                        <input class="custom-control-input" type="radio" id="theme_3" name="current_theme" value="theme3" {{ $theme->value === "theme3" ? "checked" : "" }}>
                                        <label for="theme_3" class="custom-control-label">Theme 3</label>
                                        <div class="row d-flex" style="align-items: center;justify-content: center;">
                                            <img src="{{ asset('themes/theme_3.png') }}" alt="Theme 1" height="200px" width="200px" class="mt-3 {{ $theme->value === 'theme3' ? 'active-theme' : '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary draft_button">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('optional-styles')
    <style>
        .active-theme {
            border-radius: 10px;
            border: 2px solid blue;
            box-shadow: 5px 5px #d2d6d3;
        }
    </style>
@endpush

@push('optional-scripts')
    <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('admin-resources/js/jquery.nestable.js') }}"></script>

    <script>
        $(document).ready(function() {

        });
    </script>

@endpush
