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

      <form method="POST" action="{{ route('admin.password.create') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $signature }}">            
            <input type="hidden" name="email" value="{{ $user }}" required autofocus class="form-control" placeholder="{{ __('Email Address') }}">
            
            <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="{{ __('Create Password') }}" id="password" name="password" required>
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
                <button type="submit" class="btn btn-primary btn-block">{{ __('Create Password') }}</button>
            </div>
            <!-- /.col -->
            </div>
      </form>

      <p class="mt-3 mb-1 text-center">
        <a href="{{ route('admin.login') }}">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>

@endsection