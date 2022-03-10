@extends('admin.layouts.app')
@section('header', 'Jobs')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.jobs.store') }}" enctype="multipart/form-data" id="create-job-form">
		<div class="row">
			<div class="col-md-9">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Create Job</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@include('admin.jobs.form')

				</div>
			</div>
			<div class="col-md-3">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Schedule Content</h3>
					</div>
					@include('admin.jobs.publishform')
				</div>

				<!-- /.card-body -->
				<div class="float-right">

					<input type="hidden" name="active" id="status">
					<input type="hidden" name="action" id="action">

					<button type="submit" class="btn width-120 btn-primary draft_button">Save</button>
					@if(hasPermission('jobs', 'publish'))
					<button type="submit" class="btn width-120 btn-success publish_button">Publish</button>
					@endif

				</div>

			</div>
		</div>
	</form>


</div>
</div>
<!-- /.row -->
</div>
<!-- /.container-fluid -->

</div>
@endsection

@push('optional-scripts')
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/moment.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

<script>
	//Date and time picker
	$(document).ready(function() {

		CKEDITOR.instances.description.on('blur', function(e) {
			var messageLength = CKEDITOR.instances.description.getData().replace(/<[^>]*>/gi, '').length;
			if (messageLength !== 0) {
				$('#cke_description').next().hasClass("my-error-class") && $('#cke_description').next().remove();
			}
		});

		$('#start_datetime').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 30,
			roundTime: 'ceil',
			minDate: new Date(),
			validateOnBlur: false,
			onChangeDateTime: function(dp, $input) {
				$('#start_date').val(mapDate(dp));
				let endDate = $("#end_datetime").val();
				if (endDate.trim().length > 0 && $input.val() >= endDate) {
					$input.val("");
					$input.parent().next().text("Start Date cannot be less than end date");
				} else {
					$input.parent().next().text("");
				}
			},
			onShow: function () {
				this.setOptions({
					maxDate: $('#end_date').val() ? $('#end_date').val() : false
				})
			}
		});

		$('#end_datetime').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 30,
			roundTime: 'ceil',
			minDate: new Date(),
			validateOnBlur: false,
			onChangeDateTime: function(dp, $input) {
				$('#end_date').val(mapDate(dp));
				let startDate = $("#start_datetime").val();
				if (startDate.trim().length > 0 && $input.val() <= startDate) {
					$input.val("");
					$input.parent().next().text("{{ __('messages.min_date') }}");
				} else {
					$input.parent().next().text("");
				}
			},
			onShow: function () {
				this.setOptions({
					minDate: $('#start_date').val() ? $('#start_date').val() : false
				})
			}
		});
		// Set hidden fields based on button click
		$('.draft_button').click(function(e) {
			$('#status').val("0");
			$('#action').val("Added");
		});

		$('.publish_button').click(function(e) {
			$('#status').val("1");
			$('#action').val("Published");
		});

		$.validator.addMethod("notNumericValues", function(value, element) {
			return this.optional(element) || isNaN(Number(value));
		}, '{{ __("messages.not_numeric") }}');

		$.validator.addMethod("ckeditor_required", function(value, element) {
			var editorId = $(element).attr('id');
			var messageLength = CKEDITOR.instances[editorId].getData().replace(/<[^>]*>/gi, '').length;
			return messageLength !== 0;
		}, '{{ __("messages.ckeditor_required") }}');

		$('#create-job-form').validate({
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			ignore: [],
			rules: {
				title: {
					required: true,
					minlength: 3,
					maxlength: 255,
					notNumericValues: true,
				},
				description: {
					ckeditor_required: true,
					minlength: 5
				},
				qualification: {
					required: true,
					minlength: 5,
					notNumericValues: true,
				},
				experience: {
					required: true,
					minlength: 2,
					notNumericValues: true,
				},
				location: {
					required: true,
					minlength: 5,
					notNumericValues: true,
				},
				total_positions: {
					required: true,
					number: true,
					min: 1,
					maxlength: 4
				},
				image: {
					extension: "jpg|jpeg|png|ico|bmp"
				},
				enable: {
					required: false,
				},
				start_datetime: {
					required: false
				},
				end_datetime: {
					required: false
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "description") {
					element = $("#cke_" + element.attr("id"));
				}
				if (element.attr("id") == "image") {
					element.next().text('');
				}
				error.insertAfter(element);
			},
			messages: {
				image: '{{ __("messages.valid_file_extension") }}',
				title: {
					required: "This field is required.",
					minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Title', 'limit' => 255]) }}"
				}
			}
		});

	});

	function mapDate(date) {
		return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:00`;
	}
</script>

@endpush