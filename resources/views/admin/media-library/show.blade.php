@extends('admin.layouts.app')
@section('header', 'Media Library')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item">Media Library</li>
  <li class="breadcrumb-item active">View</li>
@endsection

@section('addButton')
<form method="POST" action="{{ route('admin.media-library', $role->id) }}" class="float-right">
  @method('DELETE')
  @csrf
  <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
</form>

<a class="btn btn-primary float-right mr-2" href="{{ route('admin.media-library.edit', $role->id)}}">Edit Role</a>

@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">View Role - {{ $role->name }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Name</label>
                      <span>{{$role->name}}</span>
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
