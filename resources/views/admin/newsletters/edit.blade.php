@extends('admin.layouts.app')
@section('header', 'NewsLetters')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.newsletters.index') }}">Newsletters</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.newsletters.update', $newsletter->id) }}" enctype="multipart/form-data" id="update-newsletter-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Newsletter - {{ $newsletter->subject }}</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
					@include('admin.newsletters.form')
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
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	//Date and time picker
	$(document).ready(function() {

		$('#update-newsletter-form').validate({
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			ignore: [],
			rules: {
				subject: {
					required: true,
					minlength: 2,
					notNumericValues: true,
                    prevent_special_characters: true
				},
				description: {
					ckeditor_required: true,
					maxlength: 50000
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
