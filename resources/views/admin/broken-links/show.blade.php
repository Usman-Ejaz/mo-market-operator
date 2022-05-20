@extends('admin.layouts.app')
@section('header', 'Broken Links')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.broken-links.index') }}">Broken Links</a></li>
<li class="breadcrumb-item active">View</li>
@endsection

@section('addButton')
@if(hasPermission('broken_links', 'delete'))
<form method="POST" action="{{ route('admin.broken-links.destroy', $brokenLink->id) }}" class="float-right">
    @method('DELETE')
    @csrf
    <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
</form>
@endif

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">View Broken Link - {{ $brokenLink->title }}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Title: </label>
                                <span>{{ $brokenLink->title }}</span>
                            </div>
                        </div>						
                    </div>

					<div class="row">
						<div class="col-md-12">
                            <div class="form-group">
                                <label>Link: </label>
                                <span>{{ $brokenLink->link }}</span>
                            </div>
                        </div>
					</div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Menu: </label>
                                <span>{{ $brokenLink->menu_name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->

</div>
@endsection

@push('optional-styles')

@endpush

@push('optional-scripts')
@endpush
