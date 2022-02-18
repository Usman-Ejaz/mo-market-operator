@extends('admin.layouts.app')
@section('header', 'Document Categories')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.document-categories.index') }}">Document Categories</a></li>
<li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.document-categories.update', $documentCategory->id) }}" enctype="multipart/form-data" id="update-document-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Editing - {{ $documentCategory->name }}</h3>
					</div>
					@method('PATCH')
					@include('admin.document-categories.form')
					<div class="card-footer">
						<div class="float-right">
							<button type="submit" class="btn btn-primary draft_button">Update</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@push('optional-scripts')
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

<script>
	$(document).ready(function() {
		$.validator.addMethod("notNumericValues", function(value, element) {
				return this.optional(element) || isNaN(Number(value));
		}, '{{ __("messages.not_numeric") }}');

		$('#update-document-form').validate({
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				name: {
					required: true,
					minlength: 2,
					notNumericValues: true
				}
			}
		});
	});
</script>

@endpush