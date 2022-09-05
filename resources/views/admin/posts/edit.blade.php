@extends('admin.layouts.app')
@section('header', 'Edit Post')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
<li class="breadcrumb-item active">Edit Post</li>
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
            max-width: 63em;
            margin: 0px 0px 5px 0px;
        }

        .item-list {
            display: flex;
            list-style: none;
            width: 100%;
            margin: 0;
            flex-wrap: wrap;
            padding: 10px;
            position: relative;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

        }
        .folder-container {
            padding: 10px;
            margin: 10px;
            cursor: pointer;
            border-radius: 3px;
            border: 1px solid #ecf0f1;
            overflow: hidden;
            background: #f6f8f9;
            display: flex;
            /* width: 20%; */
        }

        .folder-container:hover {
            background: #4da7e8;
            color: #fff;
        }

        .folder-container:hover .folder-icon {
            color: #fff !important;
        }


        /* Limit image width to avoid overflow the container */
        img {
            max-width: 100%; /* This rule is very important, please do not ignore this! */
        }

        .modal-image-preview {
            display: block;
            max-width: 464px;
            max-height: 390px;
            width: auto;
            height: auto;
        }

        .image-preview {
            border: 1px solid black;
            height: 200px;
            width: 200px;
            max-width: 20%;
            /* min-width: 0px !important; */
            /* min-height: 0px !important; */
            /* max-width: none !important; */
            /* max-height: none !important; */
            transform: none;
        }

        .folder-icon {
            /* display: flex; */
            /* margin: auto; */
            width: 100%;
        }

        .image-aspact-ratio {
            /* object-fit: contain; */
            height: 150px;
        }

        .btn-container {
            text-align: center;
        }

        .featured {
            border: 2px solid #4da7e8;
            box-shadow: 5px 5px #d2d6d3;
        }

        .dropdown-menu span {
            cursor: pointer;
            margin: 2px 0px;
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
					@if(hasPermission('posts', 'view'))
						<a href="{{ $post->link . (!$post->isPublished() ? '?unpublished=true' : '') }}" target="_blank" class="btn btn-primary publish_button">Preview</a>
					@endif
					@if($post->isPublished())
						<button type="submit" class="btn btn-primary update_button">Update</button>
						@if(hasPermission('posts', 'publish'))
							<button type="submit" class="btn btn-danger unpublish_button">Unpublish</button>
							<div class="form-group mt-3">
								<div class="row text-center">
									<div class="col-md-3 col-sm-4 p-2 mr-2 text-center">
										<i class="fab fa-facebook social-share-icon" style="color: var(--facebook-color);"></i>
									</div>
									<div class="col-md-3 col-sm-4 p-2 mr-2 text-center">
										<i class="fab fa-twitter social-share-icon" style="color: var(--twitter-color);"></i>
									</div>
									<div class="col-md-3 col-sm-4 p-2 text-center">
										<i class="fab fa-linkedin social-share-icon" style="color: var(--linkedIn-color);"></i>
									</div>
								</div>
							</div>
						@endif
					@else
						<button type="submit" class="btn btn-primary draft_button">Update</button>
						@if(hasPermission('posts', 'publish'))
							<button type="submit" class="btn btn-success publish_button">Publish</button>
						@endif
					@endif
				</div>
			</div>
		</div>
	</form>
</div>


<div class="modal fade" id="imageViewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<form action="" method="POST" id="create-submenus-form" onsubmit="return false;">
				<div class="modal-header bg-primary">
					<h5 class="modal-title" id="exampleModalLabel">Edit Image</h5>
					<button type="button" class="close editor-modal-close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="image">Image:</label>
						<div class="img-container">
							<div class="row">
								<div class="col-md-8">
									<img src="" alt="" id="imageSrc" class="modal-image-preview">
								</div>
								<div class="col-md-3">
									<div class="cropper-img-preview ml-4"></div>
									<div class="row mt-4 ml-4" id="cropper-actions">
										<div class="btn-group">
											<button type="button" class="btn btn-primary btn-sm cropper-action-button" data-method="scaleX" data-option="-1" title="Flip Horizontal">
												<span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.scaleX(-1)" aria-describedby="tooltip234149">
													<span class="fa fa-arrows-alt-h"></span>
												</span>
											</button>
											<button type="button" class="btn btn-primary btn-sm cropper-action-button" data-method="scaleY" data-option="-1" title="Flip Vertical">
												<span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.scaleY(-1)">
													<span class="fa fa-arrows-alt-v"></span>
												</span>
											</button>
										</div>
										<div class="btn-group">
											<button type="button" class="btn btn-primary btn-sm cropper-action-button" data-method="rotate" data-option="-45" title="Rotate Left">
												<span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.rotate(-45)" aria-describedby="tooltip187138">
													<span class="fa fa-undo-alt"></span>
												</span>
											</button>
											<button type="button" class="btn btn-primary btn-sm cropper-action-button" data-method="rotate" data-option="45" title="Rotate Right">
												<span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.rotate(45)">
													<span class="fa fa-redo-alt"></span>
												</span>
											</button>
										</div>
										<div class="btn-group">
											<button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												{{ __("Visibility") }}
											</button>
											<div class="dropdown-menu" id="opcaityDropdown">
												<span class="dropdown-item" data-value="10">10%</span>
												<span class="dropdown-item" data-value="20">20%</span>
												<span class="dropdown-item" data-value="30">30%</span>
												<span class="dropdown-item" data-value="40">40%</span>
												<span class="dropdown-item" data-value="50">50%</span>
												<span class="dropdown-item" data-value="60">60%</span>
												<span class="dropdown-item" data-value="70">70%</span>
												<span class="dropdown-item" data-value="80">80%</span>
												<span class="dropdown-item" data-value="90">90%</span>
												<span class="dropdown-item" data-value="100">100%</span>
												<span class="dropdown-item" data-value="100">None</span>
											</div>
										</div>
										<div class="btn-group mt-2">
											<button type="button" class="btn btn-primary btn-sm cropper-action-button" data-method="crop" title="Crop">
												<span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.crop()">
													Cropper
												</span>
											</button>
											<button type="button" class="btn btn-primary btn-sm cropper-action-button" data-method="clear" title="Clear">
												<span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.clear()">
													Clear Cropper
												</span>
											</button>
										</div>
										<div class="input-group input-group-sm mt-3">
											<span class="input-group-prepend">
												<label class="input-group-text" for="dataWidth">Width</label>
											</span>
											<input type="number" class="form-control crop-box-ratio" id="dataWidth" placeholder="width">
											<span class="input-group-append">
												<span class="input-group-text">px</span>
											</span>
										</div>
										<div class="input-group input-group-sm mt-3">
											<span class="input-group-prepend">
												<label class="input-group-text" for="dataHeight">Height</label>
											</span>
											<input type="number" class="form-control crop-box-ratio" id="dataHeight" placeholder="Height">
											<span class="input-group-append">
												<span class="input-group-text">px</span>
											</span>
										</div>
										<div class="form-group mt-4">
											<input type="range" class="custom-range custom-range-primary" min="1" max="100" value="100">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" id="postId" value="{{ $post->id }}"/>
                    <input type="hidden" id="uploadImageUrl" value="{{ route('admin.posts.uploadImage') }}">
                    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary editor-modal-close" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="saveImageInfo">Save changes</button>
				</div>
			</form>
		</div>
	</div>
</div>
@include('admin.includes.confirm-popup')
@endsection

@push('optional-styles')
<link rel="stylesheet" href="{{ asset('admin-resources/css/tempusdominus-bootstrap-4.min.css') }}">
<link href="{{ asset('admin-resources/plugins/cropperjs/css/cropper.min.css') }}" rel="stylesheet" />
<style>
	.social-share-icon {
		font-size: 40px;
		cursor: pointer;
	}
</style>
@endpush

@push('optional-scripts')
<script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('admin-resources/js/moment.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/bootstrap-tagsinput.js') }}"></script>
<script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>
<script src="{{ asset('admin-resources/plugins/cropperjs/js/cropper.min.js') }}"></script>
<script src="{{ asset('admin-resources/js/init-cropper.js') }}"></script>

<script>
	//Date and time picker
	let oldFiles = [];
	$(document).ready(function() {

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

		$('#update-post-form').validate({
			ignore: [],
			errorElement: 'span',
			errorClass: "my-error-class",
			validClass: "my-valid-class",
			rules: {
				title: {
					required: true,
					maxlength: 150,
					minlength: 3,
					notNumericValues: true,
                    prevent_special_characters: true
				},
				description: {
					ckeditor_required: true,
					maxlength: 50000
				},
				slug: {
					required: true,
					notNumericValues: true,
                    // prevent_special_characters: true
				},
                keywords: {
                    maxlength: {
                        depends: () => {
                            let tags = $('#keywords').val().split(',');
                            return tags.filter(tag => tag.length > 64).length > 0 ? 64 : 0;
                        }
                    }
                },
				post_category: {
					required: true,
				},
				image: {
					extension: "{{ config('settings.image_file_extensions') }}"
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
				if (element.attr("id") == "post_image") {
					element.next().text('');
				}
				error.insertAfter(element);
			},
			messages: {
				image: '{{ __("messages.valid_image_extension") }}',
				title: {
					required: "{{ __('messages.required') }}",
					minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
					maxlength: "{{ __('messages.max_characters', ['field' => 'Title', 'limit' => 150]) }}"
				},
                keywords: {
					maxlength: "{{ __('messages.max_characters', ['field' => 'Keywords', 'limit' => 64]) }}"
				}
			}
		});

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		$("#deleteImage").click(function() {

            $('#msg_heading').text('Delete record?');
            $('#msg_body').text('Are you sure you want to delete this image?');
            $('#confirmModal').modal('toggle');
            $('body').on('click', '#confirm', (e) => {
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
                            $('#confirmModal').modal('toggle');
							toastr.success(data.message);
							$('.imageExists').remove();
						}
					}
				});
            });
		});

		// handle social share button clicks
		$('.social-share-icon').click(function() {
			let clickedElement = $(this).attr('class').replace('fab fa-', '').replace('social-share-icon', '').trim();

			const FACEBOOK_SHARE_URL = 'https://www.facebook.com/sharer.php';
			const TWITTER_SHARE_URL = 'https://twitter.com/intent/tweet';
			const LINKEDIN_SHARE_URL = 'https://www.linkedin.com/shareArticle?mini=true';
			let url = "";

			switch (clickedElement) {
				case 'facebook':
					url = `${FACEBOOK_SHARE_URL}?u={{ $post->link }}`;
					socialWindow(url);
					break;
				case 'twitter':
					url = `${TWITTER_SHARE_URL}?url={{ $post->link }}`;
					socialWindow(url);
					break;
				case 'linkedin':
					url = `${LINKEDIN_SHARE_URL}&url={{ $post->link }}`;
					socialWindow(url);
					break;
			}
		});

		$('.bootstrap-tagsinput > input').on('blur keypress', function (e) {
			if ((e.which === 13 && $(this).val().trim().length > 0) || document.getElementsByClassName('label-info').length > 0) {
				$(this).attr('placeholder', '');
				return;
			}

			$(this).attr('placeholder', '{{ __("Enter keywords") }}');
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

	function socialWindow(url) {
		var left = (screen.width - 570) / 2;
		var top = (screen.height - 570) / 2;
		var params = "menubar=no,toolbar=no,status=no,width=570,height=570,top=" + top + ",left=" + left;
		window.open(url, "NewWindow", params);
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
