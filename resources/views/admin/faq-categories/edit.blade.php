@extends('admin.layouts.app')
@section('header', 'FAQ Categories')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.faqs.index') }}">FAQs</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.faq-categories.index') }}">FAQ Categories</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.faq-categories.update', $faqCategory->id) }}" enctype="multipart/form-data" id="update-faq-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit FAQ Category - {{ $faqCategory->name }}</h3>
					</div>
					@method('PATCH')
					@include('admin.faq-categories.form')
					<div class="card-footer">
						<div class="float-right">
							<button type="submit" class="btn btn-primary width-120 draft_button">Update</button>
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
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	$(document).ready(function() {

		$('#update-faq-form').validate({
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				name: {
					required: true,
					minlength: 3,
					maxlength: 64,
					notNumericValues: true,
                    prevent_special_characters: true
				}
			},
			messages: {
				name: {
					required: '{{ __("messages.required") }}',
					minlength: '{{ __("messages.min_characters", ["field" => "Name", "limit" => 3]) }}',
					maxlength: '{{ __("messages.max_characters", ["field" => "Name", "limit" => 64]) }}'
				}
			}
		});
	});
</script>

@endpush
