@extends('admin.layouts.app')
@section('header', 'Create NewsLetter')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.newsletters.index') }}">Newsletters</a></li>
<li class="breadcrumb-item active">Create Newsletter</li>
@endsection


@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.newsletters.store') }}" enctype="multipart/form-data" id="create-newsletter-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Create Newsletter</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->

					@include('admin.newsletters.form')

					<div class="card-footer">
						<div class="float-right">
							<button type="submit" class="btn btn-primary width-120 draft_button">Save</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@push('optional-scripts')
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	$(document).ready(function() {

		$('#create-newsletter-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				subject: {
					required: true,
					minlength: 3,
                    maxlength: 150,
					notNumericValues: true,
                    prevent_special_characters: true
				},
				description: {
					ckeditor_required: true,
					maxlength: 50000
				},
			},
            messages: {
				subject: {
					required: '{{ __("messages.required") }}',
					minlength: '{{ __("messages.min_characters", ["field" => "Subject", "limit" => 3]) }}',
					maxlength: '{{ __("messages.max_characters", ["field" => "Subject", "limit" => 150]) }}'
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "description") {
					element = $("#cke_" + element.attr("id"));
				}
				error.insertAfter(element);
			},
		});
	});
</script>

@endpush
