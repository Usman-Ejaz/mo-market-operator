@extends('admin.layouts.app')
@section('header', 'Users')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item">Users</li>
  <li class="breadcrumb-item active">View</li>
@endsection

@section('addButton')
<form method="POST" action="{{ route('admin.users', $user->id) }}" class="float-right">
  @method('DELETE')
  @csrf
  <button class="btn btn-danger" onclick="return confirm('Are You Sure Want to delete this record?')">Delete</button>
</form>

@if( hasPermission('users', 'edit') ){
    <a class="btn btn-primary float-right mr-2" href="{{ route('admin.users.edit', $user->id)}}">Edit User</a>
@endif
@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">View User - {{ $user->name }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Name</label>
                      <span>{{$user->name}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email</label>
                      <span>{{$user->email}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Role</label>
                      <br/>
                      <div>{{$user->role}}</div>
                    </div>
                  </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Department</label>
                            <span>{{$user->department ?? 'None'}}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <span>{{$user->active}}</span>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Image</label>
                        @if( isset($user->image) )
                            <img src="{{ asset( config('filepaths.userProfileImagePath.public_path') . $user->image ) }}" class="img-fluid">
                        @else
                            <span>None</span>
                        @endif
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
