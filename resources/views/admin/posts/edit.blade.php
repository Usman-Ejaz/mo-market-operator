@extends('admin.layouts.app')
@section('header', 'Posts')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
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
	<form method="POST" action="{{ route('admin.posts.update', $post->id) }}" enctype="multipart/form-data" id="update-post-form">
		<div class="row">
			<div class="col-md-9">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Edit Post - {{ $post->title }}</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					@method('PATCH')
					@include('admin.posts.form')

				</div>
			</div>
			<div class="col-md-3">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">Schedule Content</h3>
					</div>

					@include('admin.posts.publishform')

				</div>

				<!-- /.card-body -->
				<div class="float-right">

					<input type="hidden" name="active" id="status">
					<input type="hidden" name="action" id="action">
					@if($post->isPublished())
						<button type="submit" class="btn width-120 btn-primary update_button">Update</button>
						@if(hasPermission('posts', 'publish'))
							<button type="submit" class="btn width-120 btn-danger unpublish_button">Unpublish</button>
						@endif
					@else
						<button type="submit" class="btn width-120 btn-primary draft_button">Update</button>
						@if(hasPermission('posts', 'publish'))
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

		// Slug generator
		$("#title").keyup(function() {
			var Text = $(this).val();
			Text = Text.toLowerCase().trim();
			Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
			$("#slug").val(Text);

			if ($("#slug").val().length > 0 && $("#slug").next().hasClass("my-error-class")) {
				$("#slug").next().remove();
				$("#slug").removeClass("my-error-class");
			}
		});

		$.validator.addMethod("notNumericValues", function(value, element) {
			return isNaN(Number(value)) || value.indexOf('e') !== -1;
		}, '{{ __("messages.not_numeric") }}');

		$.validator.addMethod("ckeditor_required", function(value, element) {
			var editorId = $(element).attr('id');
			var messageLength = CKEDITOR.instances[editorId].getData().replace(/<[^>]*>/gi, '').length;
			return messageLength !== 0;
		}, '{{ __("messages.ckeditor_required") }}');

		$('#update-post-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				title: {
					required: true,
					maxlength: 255,
					minlength: 3,
					notNumericValues: true
				},
				description: {
					ckeditor_required: true,
					maxlength: 50000
				},
				slug: {
					required: true,
					notNumericValues: true,
				},
				post_category: {
					required: true,
				},
				image: {
					extension: "jpg|jpeg|png",
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
				if (element.attr("id") == "post_image") {
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

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$("#deleteImage").click(function() {

			if (confirm('Are you sure you want to this image?')) {
				$.ajax({
					url: "{{ route('admin.posts.deleteImage') }}",
					type: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
						post_id: "{{$post->id}}"
					},
					dataType: 'JSON',
					success: function(data) {
						if (data.success) {
							alert('Image Deleted Successfully');
							$('.imageExists').remove();
						}
					}
				});
			}
		});

		$('.bootstrap-tagsinput > input').on('blur', function (e) {
			if (document.getElementsByClassName('label-info').length > 0) {
				$(this).attr('placeholder', '');
			}
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