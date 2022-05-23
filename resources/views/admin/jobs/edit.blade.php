@extends('admin.layouts.app')
@section('header', 'Jobs')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@push('optional-styles')
<link rel="stylesheet" href="{{ asset('admin-resources/css/bootstrap-tagsinput.css') }}" />
<style type="text/css">
        .bootstrap-tagsinput{
            width: 100%;
        }
        .label-info{
            background-color: #17a2b8;
        }
        .label {
            display: inline-block;
            padding: .25em .4em;
            font-size: 85%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,
            border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
	<form method="POST" action="{{ route('admin.jobs.update', $job->id) }}" enctype="multipart/form-data" id="update-job-form">
		<div class="row">
			<div class="col-md-9">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Job - {{ $job->title }}</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
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
					<input type="hidden" name="removeFile" id="removeFile">

					@if($job->isPublished())
						<button type="submit" class="btn width-120 btn-primary update_button">Update</button>
						@if(hasPermission('jobs', 'publish'))
							<button type="submit" class="btn width-120 btn-danger unpublish_button">Unpublish</button>
						@endif
					@else
						<button type="submit" class="btn width-120 btn-primary draft_button">Update</button>
						@if(hasPermission('jobs', 'publish'))
							<button type="submit" class="btn width-120 btn-success publish_button">Publish</button>
						@endif
					@endif
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
<script src="{{ asset('admin-resources/js/moment.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/bootstrap-tagsinput.js') }}"></script>

<script>
	$(document).ready(function() {

		CKEDITOR.instances.description.on('blur', function(e) {
			var messageLength = CKEDITOR.instances.description.getData().replace(/<[^>]*>/gi, '').length;
			if (messageLength !== 0) {
				$('#cke_description').next().hasClass("my-error-class") && $('#cke_description').next().remove();
			}
		});

		//Date and time picker
		$('#start_datetime').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 30,
			roundTime: 'ceil',
			minDate: new Date(),
			minTime: new Date(),
			validateOnBlur: false,
			onChangeDateTime: function(dp, $input) {
				$('#start_date').val(mapDate(dp));
				let endDate = new Date($("#end_date").val()).setSeconds(0, 0);
				dp = dp.setSeconds(0, 0);
				let curr = (new Date()).setSeconds(0, 0);

				if (dp >= curr) {
					if (dp >= endDate) {
						$input.val("");
						$input.parent().next().text("{{ __('messages.min_date', ['first' => 'start date', 'second' => 'end date']) }}");
					} else {
						$input.parent().next().text("");
					}
				} else {
					$input.val("");
					$input.parent().next().text("{{ __('messages.todays_date') }}");
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
				let startDate = new Date($("#start_date").val()).setSeconds(0, 0);
				dp = dp.setSeconds(0, 0);
				let curr = (new Date()).setSeconds(0, 0);

				if (dp >= curr) {
					if (dp <= startDate) {
						$input.val("");
						$input.parent().next().text("{{ __('messages.max_date', ['first' => 'end', 'second' => 'start']) }}");
					} else {
						$input.parent().next().text("");
					}
				} else {
					$input.val("");
					$input.parent().next().text("{{ __('messages.todays_date') }}");
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

		$("#deleteImage").on('click', function() {
			if (confirm('Are you sure you want to this image?')) {
				$(this).parent().remove();
			}
		});

		let attachments = [];		
		$(".remove-file").on('click', function() {
			let { file } = $(this).data();

			if (confirm('Are you sure you want to this file?')) {
				attachments.push(file);
				$(this).parent().remove();
				$("#removeFile").val(`${attachments.toString()}`);
			}
		});

		$.validator.addMethod("notNumericValues", function(value, element) {
			return this.optional(element) || isNaN(Number(value)) || value.indexOf('e') !== -1;
		}, '{{ __("messages.not_numeric") }}');

		$.validator.addMethod("ckeditor_required", function(value, element) {
			var editorId = $(element).attr('id');
			var messageLength = CKEDITOR.instances[editorId].getData().replace(/<[^>]*>/gi, '').length;
			return messageLength !== 0;
		}, '{{ __("messages.ckeditor_required") }}');

		$.validator.addMethod('docx_extension', function (value, element, param) {
			let files = Array.from(element.files);
			param = param.split('|');
			let invalidFiles = files.filter(file => !param.includes(file.name.split('.').at(-1)));
			return this.optional(element) || invalidFiles.length === 0;
		}, '');

		$('#update-job-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				title: {
					required: true,
					minlength: 3,
					maxlength: 255,
					notNumericValues: true,
				},
				short_description: {
					required: true,
					minlength: 10,
					maxlength: 300,
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
				specialization: {
					required: true,
					minlength: 5,
					notNumericValues: true,
				},
				salary: {
					number: true,
				},
				image: {
					required: {
						depends: function () {
							return $('.imageExists').length > 0 ? false : true;
						}
					},
					extension: "{{ config('settings.image_file_extensions') }}"
				},
				'attachments[]': {
					required: {
						depends: function () {
							return $('.fileExists').length > 0 ? false : true;
						}
					},
					docx_extension: "doc|docx|pdf"
				},
				enable: {
					required: false,
				},
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
				image: {
					required: '{{ __("messages.required") }}',
					extension: '{{ __("messages.valid_image_extension") }}'
				},
				'attachments[]': {
					required: '{{ __("messages.required") }}',
					docx_extension: '{{ __("messages.valid_file_extension") }}'
				},
				title: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
					required: '{{ __("messages.required") }}',
					maxlength: "{{ __('messages.max_characters', ['field' => 'Title', 'limit' => 255]) }}"
				}
			}
		});

		$('.bootstrap-tagsinput > input').on('blur keypress', function (e) {
			if ((e.which === 13 && $(this).val().trim().length > 0) || document.getElementsByClassName('label-info').length > 0) {
				$(this).attr('placeholder', '');
				return;
			}

			$(this).attr('placeholder', '{{ __("Enter Job Location") }}');
		});

		if (document.getElementsByClassName('label-info').length > 0) {
			$('.bootstrap-tagsinput > input').attr('placeholder', '');
		}
	});

	function mapDate(date) {
		return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:00`;
	}
</script>

@endpush