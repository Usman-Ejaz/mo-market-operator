@extends('admin.layouts.guest')

@section('title', '| Update Password')

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

            <form method="POST" action="{{ route('admin.password-update') }}" autocomplete="off">
                @csrf

                <!-- Password Reset Token -->
                <div class="input-group mb-3">
                    <input type="password" autocomplete="current-password" class="form-control" placeholder="{{ __('Old Password') }}" id="old_password" name="old_password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-eye" id="eye-old-password"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" autocomplete="new-password" class="form-control" placeholder="{{ __('New Password') }}" id="new_password" name="password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-eye" id="eye-new-password"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" autocomplete="new-password" class="form-control" placeholder="{{ __('Confirm Password') }}" id="confirm_password" name="password_confirmation" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-eye" id="eye-confirm-password"></span>
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

@push("optional-scripts")
<script>
    
    $('form').disableAutoFill();

    $("#eye-old-password").click(function() {
		if ($("#old_password").attr("type") === "password") {
			$("#old_password").attr("type", "text");
			$(this).attr("class", "fas fa-eye-slash");
		} else {
			$("#old_password").attr("type", "password");
			$(this).attr("class", "fas fa-eye");
		}
	})

	$("#eye-new-password").click(function() {
		if ($("#new_password").attr("type") === "password") {
			$("#new_password").attr("type", "text");
			$(this).attr("class", "fas fa-eye-slash");
		} else {
			$("#new_password").attr("type", "password");
			$(this).attr("class", "fas fa-eye");
		}
	})

	$("#eye-confirm-password").click(function() {
		if ($("#confirm_password").attr("type") === "password") {
			$("#confirm_password").attr("type", "text");
			$(this).attr("class", "fas fa-eye-slash");
		} else {
			$("#confirm_password").attr("type", "password");
			$(this).attr("class", "fas fa-eye");
		}
	})
</script>
@endpush