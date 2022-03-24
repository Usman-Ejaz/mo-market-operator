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
							<div class="folder-container" id="{{ strtolower(str_replace(" ", "_", basename($media->file))) }}" data-link="{{ $media->file }}">
								<div class="folder-icon">
									<img src="{{ $media->file }}" alt="" style="object-fit: contain; height: 50px;">
									{{-- <i class="fa fa-folder"></i> --}}
								</div>
								<div class="folder-name">
									{{ $media->title }}
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
					<div class="my-editor"></div>
				</div>
			</div>
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
	import { appendDefaultEditor } from "{{ asset('editor/editor.js') }}";

	let previousSelected = "";
	$(document).ready(function () {
		$('.folder-container').on('click', function () {
			if (previousSelected !== "") {
				$(previousSelected).toggleClass('selected');
			}
			previousSelected = '#' + $(this).attr('id');
			$(this).toggleClass('selected');
		});

		$('.folder-container').on('dblclick', function () {
			
		});
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
			appendDefaultEditor(".my-editor", {
				// The source image to load
				src: file,

				// This will set a square crop aspect ratio
				imageCropAspectRatio: 1,

				// Stickers available to user
				// stickers: [
				// 	["Emoji", ["⭐️", "😊", "👍", "👎", "☀️", "🌤", "🌥"]],
				// 	[
				// 		"Markers",
				// 		[
				// 			{
				// 				src: "sticker-one.svg",
				// 				width: "5%",
				// 				alt: "One"
				// 			},
				// 			{
				// 				src: "sticker-two.svg",
				// 				width: "5%",
				// 				alt: "Two"
				// 			},
				// 			{
				// 				src: "sticker-three.svg",
				// 				width: "5%",
				// 				alt: "Three"
				// 			}
				// 		]
				// 	]
				// ],
			});
		},

		// Callback set by FilePond
		// - should be called by the editor when user confirms editing
		// - should receive output object, resulting edit information
		onconfirm: (output) => {
			console.log(output);
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
		imageCropAspectRatio: '16:10',
		credits: false,
		maxFiles: 3,
    	required: true,
		imageEditEditor: editor,
		server: {
			process: '{{ route("admin.media-library.files.save", $mediaLibrary->id) }}',
			revert: '{{ route("admin.media-library.files.save", $mediaLibrary->id) }}',
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			}
		},
	});
</script>
@endpush