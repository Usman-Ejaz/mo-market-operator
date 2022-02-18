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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Current Theme <span class="text-danger">*</span></label>
                                        <select class="custom-select" name="current_theme">
                                            <option value="">Please select an option</option>
                                            @foreach( config('settings.themes') as $key => $value)
                                                <option value="{{$key}}" {{ (\App\Models\Settings::get_option('current_theme') === $key) ? 'selected' : '' }}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-danger">{{ $errors->first('current_theme') }} </small>
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
