@extends('admin.layouts.guest')

@section('title', 'Reset Password - ')

@section('content')

<div class="login-box">
	<div class="login-logo">
		<a href="{{ url('/') }}">{{ config('app.name', 'ISMO') }}</a>
	</div>
	<!-- /.login-logo -->
	<div class="card">
		<div class="card-body login-card-body">
			<p class="login-box-msg">{{ __('You are only one step a way from your new password, recover your password now.') }}</p>

			@if($errors->any())
			<div class="alert alert-danger alert-dismissible">
				{{ $errors->first() }}
			</div>
			@endif

			<form method="POST" action="{{ route('admin.password.update') }}" autocomplete="off">
				@csrf

				<!-- Password Reset Token -->
				<input type="hidden" name="token" value="{{ $request->route('token') }}">
				<input type="hidden" name="email" value="{{ $request->query('email') }}">

				<div class="input-group mb-3">
					<input type="password" class="form-control" placeholder="{{ __('Password') }}" autocomplete="off" id="password" name="password" required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-eye" id="eye-new-password"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}" autocomplete="off" id="confirm_password" name="password_confirmation" required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-eye" id="eye-confirm-password"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<button type="submit" class="btn btn-primary btn-block">{{ __('Reset Password') }}</button>
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

@push("optional-scripts")
<script>
	$("#eye-new-password").click(function() {
		if ($("#password").attr("type") === "password") {
			$("#password").attr("type", "text");
			$(this).attr("class", "fas fa-eye-slash");
		} else {
			$("#password").attr("type", "password");
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
