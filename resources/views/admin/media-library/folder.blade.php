@extends('admin.layouts.app')
@section('header', 'Manage Files')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.media-library.index') }}">Media Library</a></li>
<li class="breadcrumb-item active">Manage Files</li>
@endsection
@section('addButton')
    {{-- @if(hasPermission('media_library', 'delete'))
    <form method="POST" action="{{ route('admin.media-library.destroy', $contactPageQuery->id) }}" class="float-right">
        @method('DELETE')
        @csrf
        <button class="btn btn-danger" onclick="return confirm('Are You Sure Want to delete this record?')">Delete</button>
    </form>
    @endif --}}
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">View Media Library - {{ $mediaLibrary->name }}</h3>
				</div>
				<div class="card-body">
					<div class="row">
						@foreach ($files as $media)
							<div class="folder-container" id="{{ strtolower(str_replace(" ", "_", basename($media->file))) }}" data-record="{{ json_encode($media) }}">
								<div class="folder-icon">
									<img src="{{ $media->file }}" alt="" style="object-fit: contain; height: 50px;">
									{{-- <i class="fa fa-folder"></i> --}}
								</div>
								<div class="folder-name">
									{{-- {{ $media->title }} --}}
								</div>
							</div>
						@endforeach						
					</div>
					<div class="row mt-3">
						<div class="col-md-12">
							<div class="form-group">
								<input type="file" multiple/>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>	
</div>

<div class="modal fade" id="imageEditorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close editor-modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="my-editor"></div>
			</div>              
		</div>
	</div>
</div>

<div class="modal fade" id="imageViewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="" method="POST" id="create-submenus-form" onsubmit="return false;">
				<div class="modal-header bg-primary">
					<h5 class="modal-title" id="exampleModalLabel">Edit Image</h5>
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="usr">Featured:</label>
						<input type="checkbox" name="featured" id="featured">
					</div>
					<div class="form-group">
						<label for="image">Image:</label>
						<div class="img-container">
							<img src="" alt="" id="imageSrc" style="height: 180px;">
						</div>
					</div>
					<input type="hidden" id="imageId" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="newSaveButton">Save changes</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('optional-styles')
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
<link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet"/>

<link rel="stylesheet" href="{{ asset('editor/editor.css') }}">
<link rel="stylesheet" href="{{ asset('editor/style.css') }}">

<style>
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
		width: 20%;
	}

	.folder-container:hover {
		background: #4da7e8;
		color: #fff;
	}

	.selected {
		background: #4da7e8;
		color: #fff;
	}

	.selected > .folder-icon {
		color: #fff;
	}

	.folder-container:hover .folder-icon {
		color: #fff !important;
	}

	.folder-icon {
		padding-left: 0;
		margin-left: 10px;
		margin-right: 5px;
		font-size: 50px;
		color: #9f9f9fcc;
	}

	.folder-name {
		display: flex;
		align-items: center;
		margin: 0px 20px;
	}

	.my-editor > .PinturaRoot {
		background: #fff;
	}

	.img-container {
		max-width: 20%;
	}
</style>
@endpush

@push('optional-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-rename/dist/filepond-plugin-file-rename.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

<script type="module">
	import { 
		appendDefaultEditor, 
		openEditor, 
		createDefaultImageReader,  
		createDefaultImageWriter,
	} from "{{ asset('editor/editor.js') }}";
	
	let imageEditor = null;

	let previousSelected = "";
	$(document).ready(function () {
		$('.folder-container').on('click', function () {
			let { record } = $(this).data();
			
			$("#featured").prop("checked", record.featured === 1);
			$('#imageSrc').attr('src', record.file);
			$('#imageId').val(record.id);

			$('#imageViewModal').modal('show');
		});

		$('.editor-modal').on('click', function () {
			if (imageEditor !== null) imageEditor.destroy();
			$('#imageEditorModal').modal('hide');
		})
	});

	const inputElement = document.querySelector('input[type="file"]');


	FilePond.registerPlugin(
		FilePondPluginImagePreview,
		FilePondPluginFileValidateType,
		FilePondPluginFileValidateSize,
		FilePondPluginFileRename,
		FilePondPluginImageEdit,
		FilePondPluginImageCrop,
		FilePondPluginImageTransform,
		FilePondPluginImageResize
	);

	const editor = {
		// Called by FilePond to edit the image
		// - should open your image editor
		// - receives file object and image edit instructions
		open: (file, instructions) => {
			// open editor here
			// console.log(instructions);
			// console.log(file);
			imageEditor = appendDefaultEditor(".my-editor", {
				// The source image to load
				src: file,
				utils: ['crop', 'filter', 'annotate'],
				imageReader: createDefaultImageReader(),
				imageWriter: createDefaultImageWriter(),
				// This will set a square crop aspect ratio
				imageCropAspectRatio: instructions.aspectRatio,
				willRenderCanvas: (shapes, state) => {
					// console.log(shapes);
					// console.log(state);
					return {
						// copy other shape lists
						...shapes,

						// add an `ellipse` shape
						// interfaceShapes: [
						// 	{
						// 		x: x + width * 0.5,
						// 		y: y + height * 0.5,
						// 		rx: width * 0.5,
						// 		ry: height * 0.5,
						// 		opacity: state.opacity,
						// 	},
						// 	...shapes.interfaceShapes,
						// ],
					};
				}
			});

			imageEditor.on('update', console.log);

			$('#imageEditorModal').modal('show');

			imageEditor.on('process', (res) => {
				console.log('process => ', res);
				if (imageEditor !== null) imageEditor.destroy();
				$('#imageEditorModal').modal('hide');
				editor.onconfirm({
					data: {
						// This is the same as the instructions object
						crop: {
							center: {
								x: .5,
								y: .5
							},
							flip: {
								horizontal: res.imageState.flipX,
								vertical: res.imageState.flipY
							},
							zoom: 1,
							rotation: res.imageState.rotation,
							aspectRatio: null
						}
					}
				});
			})
		},

		// Callback set by FilePond
		// - should be called by the editor when user confirms editing
		// - should receive output object, resulting edit information
		onconfirm: (output) => {
			console.log('onconfirm triggered ', output);
		},

		// Callback set by FilePond
		// - should be called by the editor when user cancels editing
		oncancel: () => {},

		// Callback set by FilePond
		// - should be called by the editor when user closes the editor
		onclose: () => {},
	};
	
	const pond = FilePond.create(inputElement, {
		acceptedFileTypes: ['image/*'],
		maxFileSize: '2MB',
		credits: false,
		allowMultiple: true,
		maxFiles: 3,
    	required: true,
		imageEditEditor: editor,
		server: {
			process: {
				url: '{{ route("admin.media-library.files.save", $mediaLibrary->id) }}',
				method: 'POST',
				onload: (response) => console.log('response.key => ', response),
				onerror: (response) => console.log('response.data => ', response.data),
			},
			revert: '{{ route("admin.media-library.files.save", $mediaLibrary->id) }}',
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			}
		},
	});





    // {
    //     const container = document.currentScript.parentNode;
    //     container.querySelector('.button').addEventListener('click', () => {

    //             // This sandbox version of Pintura Image Editor is for use on pqina.nl only.
    //             // For testing purposes please purchase a license.
    //             import('./demo/pintura.js?v=1648212601').then(({
    //                 setPlugins,
    //                 openEditor,
    //                 createDefaultImageReader,
    //                 createDefaultImageWriter,
    //                 plugin_crop,
    //                 locale_en_gb,
    //                 plugin_crop_locale_en_gb,
    //             }) => {

    //             setPlugins(plugin_crop)
                
    //             const editor = openEditor({
    //                 src: 'demo/mountains.jpeg',
    //                 utils: ['crop'],
    //                 imageReader: createDefaultImageReader(),
    //                 imageWriter: createDefaultImageWriter(),
    //                 imageCropAspectRatio: 1,
    //                 locale: Object.assign({}, locale_en_gb, plugin_crop_locale_en_gb),
    //                 willRenderCanvas: (shapes, state) => {

    //                     const { utilVisibility, selectionRect } = state;

    //                     // shortcuts to selection rect
    //                     const { x, y, width, height } = selectionRect;

    //                     // return updated UI shapes list
    //                     return {
    //                         // copy other shape lists
    //                         ...shapes,

    //                         // add an `ellipse` shape
    //                         interfaceShapes: [
    //                             {
    //                                 x: x + width * 0.5,
    //                                 y: y + height * 0.5,
    //                                 rx: width * 0.5,
    //                                 ry: height * 0.5,
    //                                 opacity: state.opacity,
    //                                 inverted: true,
    //                                 backgroundColor: [0, 0, 0, 0.5],
    //                                 strokeWidth: 1,
    //                                 strokeColor: [1, 1, 1],
    //                             },
    //                             ...shapes.interfaceShapes,
    //                         ],
    //                     };
    //                 }
    //             });

    //             editor.on('process', (res) => {
    //                 previewImage && previewImage(res.dest, res.imageState);
    //             });

    //         });

    //     });
    // }

	$(function(){
	
	'use strict';

	var console = window.console || { log: function () {} };
	var $image = $('#image');
	var $download = $('#download');
	var $dataX = $('#dataX');
	var $dataY = $('#dataY');
	var $dataHeight = $('#dataHeight');
	var $dataWidth = $('#dataWidth');
	var $dataRotate = $('#dataRotate');
	var $dataScaleX = $('#dataScaleX');
	var $dataScaleY = $('#dataScaleY');
	var options = {
		aspectRatio: 16 / 9,
		preview: '.img-preview',
		crop: function (e) {
			$dataX.val(Math.round(e.x));
			$dataY.val(Math.round(e.y));
			$dataHeight.val(Math.round(e.height));
			$dataWidth.val(Math.round(e.width));
			$dataRotate.val(e.rotate);
			$dataScaleX.val(e.scaleX);
			$dataScaleY.val(e.scaleY);
		}
	};

	// Tooltip
	$('[data-toggle="tooltip"]').tooltip();

	// Cropper
	$image.on({
		'build.cropper': function (e) {
			console.log(e.type);
		},
		'built.cropper': function (e) {
			console.log(e.type);
		},
		'cropstart.cropper': function (e) {
			console.log(e.type, e.action);
		},
		'cropmove.cropper': function (e) {
			console.log(e.type, e.action);
		},
		'cropend.cropper': function (e) {
			console.log(e.type, e.action);
		},
		'crop.cropper': function (e) {
			console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
		},
		'zoom.cropper': function (e) {
			console.log(e.type, e.ratio);
		}
	}).cropper(options);

	// Buttons
	if (!$.isFunction(document.createElement('canvas').getContext)) {
		$('button[data-method="getCroppedCanvas"]').prop('disabled', true);
	}

	if (typeof document.createElement('cropper').style.transition === 'undefined') {
		$('button[data-method="rotate"]').prop('disabled', true);
		$('button[data-method="scale"]').prop('disabled', true);
	}


	// Download
	if (typeof $download[0].download === 'undefined') {
		$download.addClass('disabled');
	}


	// Options
	$('.docs-toggles').on('change', 'input', function () {
		var $this = $(this);
		var name = $this.attr('name');
		var type = $this.prop('type');
		var cropBoxData;
		var canvasData;

		if (!$image.data('cropper')) {
			return;
		}

		if (type === 'checkbox') {
			options[name] = $this.prop('checked');
			cropBoxData = $image.cropper('getCropBoxData');
			canvasData = $image.cropper('getCanvasData');

			options.built = function () {
				$image.cropper('setCropBoxData', cropBoxData);
				$image.cropper('setCanvasData', canvasData);
			};
		} else if (type === 'radio') {
			options[name] = $this.val();
		}

		$image.cropper('destroy').cropper(options);
	});


	// Methods
	$('.docs-buttons').on('click', '[data-method]', function () {
		var $this = $(this);
		var data = $this.data();
		var $target;
		var result;

		if ($this.prop('disabled') || $this.hasClass('disabled')) {
			return;
		}

		if ($image.data('cropper') && data.method) {
			data = $.extend({}, data); // Clone a new one

			if (typeof data.target !== 'undefined') {
				$target = $(data.target);

				if (typeof data.option === 'undefined') {
					try {
						data.option = JSON.parse($target.val());
					} catch (e) {
						console.log(e.message);
					}
				}
			}

			if (data.method === 'rotate') {
				$image.cropper('clear');
			}

			result = $image.cropper(data.method, data.option, data.secondOption);

			if (data.method === 'rotate') {
				$image.cropper('crop');
			}

			switch (data.method) {
				case 'scaleX':
				case 'scaleY':
					$(this).data('option', -data.option);
					break;

				case 'getCroppedCanvas':
					if (result) {

						// Bootstrap's Modal
						$('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

						if (!$download.hasClass('disabled')) {
							$download.attr('href', result.toDataURL('image/jpeg'));
						}
					}

					break;
			}

			if ($.isPlainObject(result) && $target) {
				try {
					$target.val(JSON.stringify(result));
				} catch (e) {
					console.log(e.message);
				}
			}

		}
	});


	// Keyboard
	$(document.body).on('keydown', function (e) {

		if (!$image.data('cropper') || this.scrollTop > 300) {
			return;
		}

		switch (e.which) {
			case 37:
				e.preventDefault();
				$image.cropper('move', -1, 0);
				break;

			case 38:
				e.preventDefault();
				$image.cropper('move', 0, -1);
				break;

			case 39:
				e.preventDefault();
				$image.cropper('move', 1, 0);
				break;

			case 40:
				e.preventDefault();
				$image.cropper('move', 0, 1);
				break;
					   }

	});


	// Import image
	var $inputImage = $('#inputImage');
	var URL = window.URL || window.webkitURL;
	var blobURL;

	if (URL) {
		$inputImage.change(function () {
			var files = this.files;
			var file;

			if (!$image.data('cropper')) {
				return;
			}

			if (files && files.length) {
				file = files[0];

				if (/^image\/\w+$/.test(file.type)) {
					blobURL = URL.createObjectURL(file);
					$image.one('built.cropper', function () {

						// Revoke when load complete
						URL.revokeObjectURL(blobURL);
					}).cropper('reset').cropper('replace', blobURL);
					$inputImage.val('');
				} else {
					window.alert('Please choose an image file.');
				}
			}
		});
	} else {
		$inputImage.prop('disabled', true).parent().addClass('disabled');
	}
	
});
    
</script>
@endpush