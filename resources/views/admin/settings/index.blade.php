@extends('admin.layouts.app')
@section('header', 'Site Configuration')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Site Configuration</li>
@endsection

@push('optional-styles')
<link rel="stylesheet" href="{{ asset('admin-resources/css/bootstrap-tagsinput.css') }}" />
<style type="text/css">
        .bootstrap-tagsinput{
            width: 100%;
            padding: 7px 6px !important;
        }
        .label-info{
            background-color: #17a2b8;
        }
        .label {
            display: inline-block;
            padding: .25em .4em;
            font-size: 85%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,
            border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            white-space: break-spaces !important;
            max-width: 63em;
            margin: 0px 0px 5px 0px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.site-configuration.update') }}" enctype="multipart/form-data" id="update-settings-form">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Site Configuration</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        @method('PATCH')
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <label>Current Theme</label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="custom-control custom-radio text-center">
                                        {{-- <input class="custom-control-input" type="radio" id="theme_1" name="current_theme" value="theme1" {{ $theme->value === "theme1" ? "checked" : "" }}> --}}
                                        {{-- <label for="theme_1" class="custom-control-label">Theme 1</label> --}}
                                        <input type="hidden" id="theme_1" name="current_theme" value="theme1">
                                        <div class="row d-flex" style="align-items: center;justify-content: center;">
                                            <img src="{{ asset('themes/home_page_theme_1.png') }}" alt="Theme 1" height="200px" width="200px" class="mt-3 {{ $theme->value === 'theme1' ? 'active-theme' : '' }}">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
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
                                </div> --}}
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notification_emails">Notification Receiver Emails </label>
                                        <input type="input" class="form-control" id="notification_emails" placeholder="Notification Receiver Emails" name="notification_emails" data-role="tagsinput" value={{ old('notification_emails') ?? $notification_emails ? $notification_emails->value : '' }}>
                                        <span class="form-text text-danger">{{ $errors->first('notification_emails') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="from_emails">From Emails </label>
                                        <input type="text" class="form-control" id="from_emails" placeholder="From Emails" name="from_emails" data-role="tagsinput" value={{ old('from_emails') ?? $from_emails ? $from_emails->value : '' }}>
                                        <span class="form-text text-danger">{{ $errors->first('from_emails') }} </span>
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
    <script src="{{ asset('admin-resources/js/bootstrap-tagsinput.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.bootstrap-tagsinput > input').on('blur keypress', function (e) {
                if ((e.which === 13 && $(this).val().trim().length > 0) || $(this).parent().children("span").length > 0) {
                    $(this).attr('placeholder', '');
                    return;
                }
                var placeholder = $(this).parent().parent().find('> label').text().replace('*', '');
                $(this).attr('placeholder', `${placeholder}`);
            });

            if ($('.bootstrap-tagsinput > .label-info').length > 0) {
                $('.bootstrap-tagsinput > .label-info').parent().find('input').attr('placeholder', '');
            }
        });
    </script>

@endpush
