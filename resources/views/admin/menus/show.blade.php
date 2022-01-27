@extends('admin.layouts.app')
@section('header', 'Menus')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">Menus></li>
  <li class="breadcrumb-item active">Details</li>
@endsection

@section('addButton')
<form method="POST" action="{{ route('admin.menus', $menu->id) }}" class="float-right">
  @method('DELETE')
  @csrf
  <button class="btn btn-danger">Delete</button>
</form>

<a class="btn btn-primary float-right mr-2" href="{{ route('admin.menus.edit', $menu->id)}}">Edit Menu</a>

@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Viewing Menu - {{ $menu->name }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Name</label>
                      <span>{{$menu->name}}</span>
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
