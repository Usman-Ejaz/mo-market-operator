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
					<div class="row" id="mediafiles">
											
					</div>
					<div class="row mt-3">
						<div class="col-md-12">
							<div class="form-group">
								<input type="file" multiple id="filepond"/>
							</div>
						</div>
					</div>						
				</div>
			</div>
		</div>
	</div>	
</div>

<div class="modal fade" id="imageEditorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content" style="height: 100vh;">
			<div class="modal-header">
				<button type="button" class="close editor-modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-8">
						<img src="" alt="" id="cropper-image">
					</div>
					<div class="col-md-4">
						<div class="image-preview">

						</div>
					</div>
				</div>
				<canvas id="canvas"> Your browser does not support the HTML5 canvas element. </canvas>
			</div>
			<div class="modal-footer">
				{{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
				<button type="submit" class="btn btn-primary" id="saveFinalImage">Save changes</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="imageViewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<form action="" method="POST" id="create-submenus-form" onsubmit="return false;">
				<div class="modal-header bg-primary">
					<h5 class="modal-title" id="exampleModalLabel">Edit Image</h5>
					<button type="button" class="close editor-modal" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="featured">Featured:</label>
						<input type="checkbox" name="featured" id="featured">
					</div>
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
												Opacity
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
												<span class="dropdown-item" data-value="0">None</span>
											</div>
										</div>
										<div class="btn-group mt-2">
											<button type="button" class="btn btn-primary btn-sm cropper-action-button" data-method="crop" title="Crop">
												<span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.crop()">
													Crop
												</span>
											</button>
											<button type="button" class="btn btn-primary btn-sm cropper-action-button" data-method="clear" title="Clear">
												<span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.clear()">
													Clear
												</span>
											</button>
										</div>
										<div class="input-group input-group-sm mt-3">
											<span class="input-group-prepend">
												<label class="input-group-text" for="dataWidth">Width</label>
											</span>
											<input type="text" class="form-control crop-box-ratio" id="dataWidth" placeholder="width">
											<span class="input-group-append">
												<span class="input-group-text">px</span>
											</span>
										</div>
										<div class="input-group input-group-sm mt-3">
											<span class="input-group-prepend">
												<label class="input-group-text" for="dataHeight">Height</label>
											</span>
											<input type="text" class="form-control crop-box-ratio" id="dataHeight" placeholder="Height">
											<span class="input-group-append">
												<span class="input-group-text">px</span>
											</span>
										</div>
									</div>
								</div>
							</div>							
						</div>
					</div>
					<input type="hidden" id="imageId" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" id="customizeImage">Customize Image</button>
					<button type="button" class="btn btn-secondary editor-modal" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="saveImageInfo">Save changes</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('optional-styles')
<link href="{{ asset('admin-resources/plugins/filepond/css/filepond.css') }}" rel="stylesheet" />
<link href="{{ asset('admin-resources/plugins/filepond/css/filepond-plugin-image-preview.css') }}" rel="stylesheet"/>
<link href="{{ asset('admin-resources/plugins/cropperjs/css/cropper.min.css') }}" rel="stylesheet" />

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

	.image-aspact-ratio {
		object-fit: contain;
		height: 150px;
	}
</style>
@endpush

@push('optional-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

	@include('admin.media-library.filepond-config')
@endpush