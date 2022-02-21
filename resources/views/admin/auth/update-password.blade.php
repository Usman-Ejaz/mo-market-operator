@extends('admin.layouts.guest')

@section('content')

<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}">{{ config('app.name', 'ISMO') }}</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ __('You are only one step a way from your new password, create your password now.') }}</p>

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.password-update') }}">
                @csrf

                <!-- Password Reset Token -->
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="{{ __('Old Password') }}" id="password" name="old_password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-eye"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="{{ __('New Password') }}" id="password" name="password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-eye"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-eye"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Update Password') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>

@endsection