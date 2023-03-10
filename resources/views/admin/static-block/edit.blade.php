@extends('admin.layouts.app')
@section('header', 'Edit Static Block')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.static-block.index') }}">Static Block</a></li>
<li class="breadcrumb-item active">Edit Static Block</li>
@endsection

@section('content')
<div class="container-fluid">

	<form method="POST" action="{{ route('admin.static-block.update', $staticBlock->id) }}" enctype="multipart/form-data" id="update-static-block-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Static Block - {{ truncateWords($staticBlock->name, 30) }}</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
					@include('admin.static-block.form')

					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary draft_button width-120">Update</button>
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
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

<script>
	$(document).ready(function() {

		$('#update-static-block-form').validate({
			ignore: [],
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
				contents: {
					ckeditor_required: true,
					minlength: 3
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "contents") {
					element = $("#cke_" + element.attr("id"));
				}
				error.insertAfter(element);
			},
			messages: {
				name: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Name', 'limit' => 3]) }}",
					required: "{{ __('messages.required') }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Name', 'limit' => 64]) }}"
				},
				contents: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Contents', 'limit' => 3]) }}",
					ckeditor_required: "{{ __('messages.required') }}",
				}
			}
		});
	});
</script>

@endpush
