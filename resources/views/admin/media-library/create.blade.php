@extends('admin.layouts.app')
@section('header', 'Create Media Library')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.media-library.index') }}">Media Library</a></li>
<li class="breadcrumb-item active">Create Media Library</li>
@endsection

@section('content')
	<div class="container-fluid">
		<form method="POST" action="{{ route('admin.media-library.store') }}" enctype="multipart/form-data" id="create-media-library-form">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Create Media Library</h3>
						</div>

						@include('admin.media-library.form')

						<div class="card-footer text-right">
							<button type="submit" class="btn btn-primary width-120">Save</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection


@push('optional-styles')
<link rel="stylesheet" href="{{ asset('admin-resources/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@push('optional-scripts')
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	$(document).ready(function() {

		$('#create-media-library-form').validate({
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
				}
			},
			messages: {
				name: {
					required: "{{ __('messages.required') }}",
					minlength: "{{ __('messages.min_characters', ['field' => 'Name', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Name', 'limit' => 64]) }}"
				}
			}
		});
	});
</script>

@endpush
