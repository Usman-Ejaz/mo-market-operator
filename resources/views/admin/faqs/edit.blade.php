@extends('admin.layouts.app')
@section('header', 'Edit FAQ')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.faqs.index') }}">FAQs</a></li>
<li class="breadcrumb-item active">Edit FAQ</li>
@endsection

@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.faqs.update', $faq->id) }}" enctype="multipart/form-data" id="update-faq-form">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit FAQ - {{ $faq->question }}</h3>
					</div>
					<!-- form start -->
					@method('PATCH')
					@include('admin.faqs.form')
					<div class="card-footer">
						<div class="float-right">
							<input type="hidden" name="active" id="status">
							<input type="hidden" name="action" id="action">

							@if($faq->active == 'Active')
							<button type="submit" class="btn width-120 btn-primary update_button">Update</button>
							@if(hasPermission('faqs', 'publish'))
							<button type="submit" class="btn width-120 btn-danger unpublish_button">Unpublish</button>
							@endif
							@elseif($faq->active == 'Draft')
							<button type="submit" class="btn width-120 btn-primary draft_button">Update</button>
							@if( hasPermission('faqs', 'publish'))
							<button type="submit" class="btn width-120 btn-success publish_button">Publish</button>
							@endif
							@endif
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

		// Set hidden fields based on button click
		$('.draft_button').click(function(e) {
			$('#status').val("0");
			$('#action').val("Updated");
		});

		$('.publish_button').click(function(e) {
			$('#status').val("1");
			$('#action').val("Published");
		});

		$('.update_button').click(function(e) {
			$('#status').val("1");
			$('#action').val("Updated");
		});

		$('.unpublish_button').click(function(e) {
			$('#status').val("0");
			$('#action').val("Unpublished");
		});

		$('#update-faq-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				question: {
					required: true,
					minlength: 3,
					maxlength: 150,
					notNumericValues: true,
                    prevent_special_characters: true
				},
				category_id: {
					required: true,
				},
				answer: {
					ckeditor_required: true,
					minlength: 5
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "answer") {
					element = $("#cke_" + element.attr("id"));
				}
				error.insertAfter(element);
			},
			messages: {
				question: {
					required: "{{ __('messages.required') }}",
					minlength: "{{ __('messages.min_characters', ['field' => 'Question', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Question', 'limit' => 150]) }}"
				},
				category_id: {
					required: "{{ __('messages.required') }}",
				}
			}
		});
	});
</script>

@endpush
