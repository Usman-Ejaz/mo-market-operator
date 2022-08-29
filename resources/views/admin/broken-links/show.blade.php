@extends('admin.layouts.app')
@section('header', 'View Broken Link')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.broken-links.index') }}">Broken Links</a></li>
<li class="breadcrumb-item active">View Broken Link</li>
@endsection

@section('addButton')
@if(hasPermission('broken_links', 'delete'))
<button class="btn btn-danger deleteButton float-right">Delete</button>
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
@include('admin.includes.delete-popup')
@endsection

@push('optional-styles')

@endpush

@push('optional-scripts')
    <script>
        let action = "";
        $(function () {
            $('body').on('click', '.deleteButton', (e) => {
                action = '{{ route("admin.broken-links.destroy", $brokenLink->id) }}';
                $('#deleteModal').modal('toggle');
            });

            $('#deleteForm').submit(function (event) {
                $(this).attr('action', action);
            });
        });

    </script>
@endpush
