@extends('admin.layouts.app')
@section('header', 'Media Library')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Media Library</li>
@endsection

@section('addButton')
@if( hasPermission('media_library', 'create') )
<a class="btn btn-primary float-right" href="{{ route('admin.media-library.create') }}">Add Media Library</a>
@endif
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">Media Library List</h3>
				</div>
				<div class="card-body">
					<div class="row item-list">
						@foreach ($media as $item)
							<div class="folder-container" id="{{ strtolower(str_replace(" ", "_", $item)) }}" data-link="{{ $item }}">
								<div class="folder-icon">
									<img src="{{ asset('storage/uploads/pages/0d93aa0752b022289f094d1d2e0d217f.png') }}" alt="" style="object-fit: contain; height: 50px;">
									{{-- <i class="fa fa-folder"></i> --}}
								</div>
								<div class="folder-name">
									{{ $item }}
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('optional-styles')
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
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

<script>
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
</script>
@endpush