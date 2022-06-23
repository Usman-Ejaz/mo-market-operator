@extends('admin.layouts.guest')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}">{{ config('app.name', 'ISMO') }}</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                {{ $errors->first() }}
            </div>
            @endif

            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible">
                    {{ session()->get('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="{{__('Email')}}" required
                        autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="{{__('Password')}}" name="password"
                        required autocomplete="current-password" id="password-input">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-eye" id="password-eye"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                    </div>

                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Log in') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            @if (Route::has('admin.password.request'))
            <p class="mb-1">
                <a href="{{ route('admin.password.request') }}">{{ __('Forgot your password?') }}</a>
            </p>
            @endif

        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
@endsection

@push("optional-scripts")
<script>
    $("#password-eye").click(function () {
        if ($("#password-input").attr("type") === "password") {
            $("#password-input").attr("type", "text");
            $(this).attr("class", "fas fa-eye-slash");
        } else {
            $("#password-input").attr("type", "password");
            $(this).attr("class", "fas fa-eye");
        }
    })

</script>
@endpush
