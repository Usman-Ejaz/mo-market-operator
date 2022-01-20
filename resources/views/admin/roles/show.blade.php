@extends('admin.layouts.app')
@section('header', 'Roles')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">Roles</li>
  <li class="breadcrumb-item active">Details</li>
@endsection

@section('addButton')
<form method="POST" action="/admin/roles/{{$role->id}}" class="float-right">
  @method('DELETE')
  @csrf
  <button class="btn btn-danger">Delete</button>
</form>

<a class="btn btn-primary float-right mr-2" href="{{ route('admin.roles.edit', $role->id)}}">Edit Role</a>

@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Viewing Role - {{ $role->name }}</h3>
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
