@extends('admin.layouts.app')
@section('header', 'Edit Menu')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></li>
<li class="breadcrumb-item active">Edit Menu</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.menus.update', $menu->id) }}" enctype="multipart/form-data" id="update-menus-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Menu - {{ $menu->name }}</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
					@include('admin.menus.form')

					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary width-120 draft_button">Update</button>
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
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	$(document).ready(function() {

		$('#update-menus-form').validate({
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				name: {
					required: true,
					maxlength: 64,
					minlength: 3,
					notNumericValues: true,
                    // prevent_special_characters: true
				},
				theme: {
					required: true,
					maxlength: 255,
					minlength: 1,
				},
				active: {
					required: true,
				},
				identifier: {
					required: true,
					maxlength: 64,
					minlength: 3,
					notNumericValues: true,
                    // prevent_special_characters: true
				}
			},
			messages: {
				name: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Name', 'limit' => 3]) }}",
					required: "This field is required.",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Name', 'limit' => 64]) }}"
				},
                identifier: {
					required: "{{ __('messages.required') }}",
					minlength: "{{ __('messages.min_characters', ['field' => 'Identifier', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Identifier', 'limit' => 64]) }}"
				}
			}
		});
	});
</script>

@endpush
