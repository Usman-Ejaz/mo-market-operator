@extends('admin.layouts.app')
@section('header', 'Document Categories')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.document-categories.index') }}">Document Categories</a></li>
<li class="breadcrumb-item active">Create</li>
@endsection


@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.document-categories.store') }}" enctype="multipart/form-data" id="create-document-category-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Create Document Category</h3>
					</div>

					@include('admin.document-categories.form')
					<div class="card-footer">
						<div class="float-right">
							<button type="submit" class="btn btn-primary draft_button">Save</button>
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
			return this.optional(element) || isNaN(Number(value)) || value.indexOf('e') !== -1;
		}, '{{ __("messages.not_numeric") }}');

		$('#create-document-category-form').validate({
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				name: {
					required: true,
					minlength: 3,
					maxlength: 255,
					notNumericValues: true
				}
			},
			messages: {
				name: {
					required: '{{ __("messages.required") }}',
					minlength: '{{ __("messages.min_characters", ["field" => "Name", "limit" => 3]) }}',
					maxlength: '{{ __("messages.max_characters", ["field" => "Name", "limit" => 255]) }}'
				}
			}
		});
	});
</script>

@endpush