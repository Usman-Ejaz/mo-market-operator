@extends('admin.layouts.app')
@section('header', 'Edit Job')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
<li class="breadcrumb-item active">Edit Job</li>
@endsection

@push('optional-styles')
<link rel="stylesheet" href="{{ asset('admin-resources/css/bootstrap-tagsinput.css') }}" />
<style type="text/css">
        .bootstrap-tagsinput{
            width: 100%;
            padding: 7px 6px !important;
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
            white-space: break-spaces !important;
            max-width: 30.5em;
            margin: 0px 0px 5px 0px;
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
@include('admin.includes.confirm-popup')
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
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>
<script>
	let oldFiles = [];
	$(document).ready(function() {

		//Date and time picker
		$('#start_datetime').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 5,
			roundTime: 'ceil',
			minDate: new Date(),
			validateOnBlur: false,
			onChangeDateTime: function(selectedDateTime, $input) {

				let todaysDate = (new Date()).setHours(0, 0, 0, 0);

				if (selectedDateTime >= todaysDate) {
					let currentDateTime = (new Date()).setSeconds(0, 0);
					if (selectedDateTime >= currentDateTime) {
						$('#start_date').val(mapDate(selectedDateTime));

						let endDate = new Date($("#end_date").val()).setSeconds(0, 0);
						selectedDateTime = selectedDateTime.setSeconds(0, 0);

						if (selectedDateTime >= endDate) {
							$input.val("");
							$('#start_date').val("");
							$input.parent().next().text("{{ __('messages.min_date', ['first' => 'Start', 'second' => 'end']) }}");
						} else {
							$input.parent().next().text("");
						}
					} else {
						$input.val("");
						$('#start_date').val("");
						$input.parent().next().text("{{ __('messages.current_system_datetime') }}");
					}
				} else {
					$input.val("");
					$('#start_date').val("");
					$input.parent().next().text("{{ __('messages.todays_date') }}");
				}

                $('#start_datetime').datetimepicker('hide');
			},
			onShow: function () {
				this.setOptions({
					maxDate: $('#end_date').val() ? $('#end_date').val() : false
				})
			}
		});

		$('#end_datetime').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 5,
			roundTime: 'ceil',
			minDate: new Date(),
			validateOnBlur: false,
			onChangeDateTime: function(selectedDateTime, $input) {

				let todaysDate = (new Date()).setHours(0, 0, 0, 0);

				if (selectedDateTime >= todaysDate) {
					$('#end_date').val(mapDate(selectedDateTime));

					let startDate = new Date($("#start_date").val()).setSeconds(0, 0);
					selectedDateTime = selectedDateTime.setSeconds(0, 0);

					if (selectedDateTime <= startDate) {
						$input.val("");
						$('#end_date').val("");
						$input.parent().next().text("{{ __('messages.max_date', ['first' => 'End', 'second' => 'start']) }}");
					} else {
						$input.parent().next().text("");
					}
				} else {
					$input.val("");
					$('#end_date').val("");
					$input.parent().next().text("{{ __('messages.todays_date') }}");
				}

                $('#end_datetime').datetimepicker('hide');
			},
			onShow: function () {
				this.setOptions({
					minDate: $('#start_date').val() ? $('#start_date').val() : new Date()
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
            $('#msg_heading').text('Delete record?');
            $('#msg_body').text('Are you sure you want to delete this image?');
            $('#confirmModal').modal('toggle');
            $('body').on('click', '#confirm', (e) => {
                $(this).parent().remove();
                $('#confirmModal').modal('toggle');
            });
		});

		let attachments = [];
		$(".remove-file").on('click', function() {
			let { file } = $(this).data();
            $('#msg_heading').text('Delete record?');
            $('#msg_body').text('Are you sure you want to delete this file?');
            $('#confirmModal').modal('toggle');
            $('body').on('click', '#confirm', (e) => {
                attachments.push(file);
				$(this).parent().remove();
				$("#removeFile").val(`${attachments.toString()}`);
                $('#confirmModal').modal('toggle');
            });
		});

		$('#update-job-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				title: {
					required: true,
					minlength: 3,
					maxlength: 150,
					notNumericValues: true,
                    prevent_special_characters: true
				},
				short_description: {
					required: true,
					minlength: 10,
					maxlength: 300,
					notNumericValues: true,
                    // prevent_special_characters: true
				},
				description: {
					ckeditor_required: true,
					minlength: 5
				},
				qualification: {
					required: true,
					minlength: 5,
                    maxlength: 64,
					notNumericValues: true,
                    // prevent_special_characters: true
				},
				experience: {
					required: true,
                    number: true,
				},
				location: {
					required: true,
					minlength: 5,
                    maxlength: 64,
				},
				total_positions: {
					required: true,
					number: true
				},
				specialization: {
					required: true,
					minlength: 5,
                    maxlength: 64,
					notNumericValues: true,
                    // prevent_special_characters: true
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
					docx_extension: "doc|docx|pdf",
                    upload_threshold: 5,
				},
				enable: {
					required: false,
				},
				start_datetime: {
					required: {
						depends: function () {
							return $('#end_datetime').val().length > 0;
						}
					}
				},
				end_datetime: {
					required: false
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("id") == "description") {
					element = $("#cke_" + element.attr("id"));
				}
				if (element.attr("id") == "start_datetime" || element.attr("id") == "end_datetime") {
					element = $('#' + element.attr("id")).parent();
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
				},
				title: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
					required: '{{ __("messages.required") }}',
					maxlength: "{{ __('messages.max_characters', ['field' => 'Title', 'limit' => 150]) }}"
				},
                location: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Location', 'limit' => 5]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Location', 'limit' => 64]) }}"
				},
                specialization: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Specialization', 'limit' => 5]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Specialization', 'limit' => 64]) }}"
				},
                qualification: {
					minlength: "{{ __('messages.min_characters', ['field' => 'Qualification', 'limit' => 5]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Qualification', 'limit' => 64]) }}"
				}
			}
		});

		$('.bootstrap-tagsinput > input').on('blur keypress', function (e) {
			if ((e.which === 13 && $(this).val().trim().length > 0) || document.getElementsByClassName('label-info').length > 0) {
				$(this).attr('placeholder', '');
				return;
			}

			$(this).attr('placeholder', '{{ __("Enter location") }}');
		});

		if (document.getElementsByClassName('label-info').length > 0) {
			$('.bootstrap-tagsinput > input').attr('placeholder', '');
		}

		$(document).on('focusin', 'input[type="file"]', function(e){
			oldFiles = e.target.files;
		});
	});

	function mapDate(date) {
		return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:00`;
	}

	function handleFileChoose (e)
	{
		if (e.target.files.length === 0) {
			e.preventDefault();
			e.target.files = oldFiles;
			return false;
		}
	}
</script>

@endpush
