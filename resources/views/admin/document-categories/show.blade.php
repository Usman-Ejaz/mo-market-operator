@extends('admin.layouts.app')
@section('header', 'Documents')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.document-categories.index') }}">Document Categories</a></li>
  <li class="breadcrumb-item active">Details</li>
@endsection
@section('addButton')
@if(Auth::user()->role->hasPermission('document-categories', 'delete'))
<form method="POST" action="{{ route('admin.document-categories.destroy', $documentCategory->id) }}" class="float-right">
  @method('DELETE')
  @csrf
  <button class="btn btn-danger">Delete</button>
</form>
@endif
@if(Auth::user()->role->hasPermission('document-categories', 'edit'))
  <a class="btn btn-primary float-right mr-2" href="{{ route('admin.document-categories.edit', $documentCategory->id)}}">Edit Document Category</a>
@endif
@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Viewing Document - {{ $documentCategory->name }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Name</label>
                      <span> {{$documentCategory->name}} </span>
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

