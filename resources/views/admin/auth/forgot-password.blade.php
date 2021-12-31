@extends('admin.layouts.guest')

@section('content')

<div class="login-box">
    <div class="login-logo">
    <a href="{{ url('/') }}">{{ config('app.name', 'ISMO') }}</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">{{ __('Forgot your password? We can send you a password request link.') }} </p>

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible">
          {{ $errors->first() }}
        </div>
      @endif

      @if (session('status'))
        <div class="alert alert-success alert-dismissible">
        {{ session('status') }}
        </div>
      @endif
      
      <form method="POST" action="{{ route('admin.password.email') }}">
      @csrf
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="{{__('Email Address')}}" name="email" value="{{ old('email') }}" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">{{ __('Email Password Reset Link') }}</button>
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
<!-- /.login-box -->

@endsection
