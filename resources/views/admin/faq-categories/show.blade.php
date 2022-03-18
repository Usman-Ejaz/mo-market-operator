@extends('admin.layouts.app')
@section('header', 'Faq Categories')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.faq-categories.index') }}">Faq Categories</a></li>
  <li class="breadcrumb-item active">View</li>
@endsection
@section('addButton')
@if(hasPermission('faq_categories', 'delete'))
<form method="POST" action="{{ route('admin.faq-categories.destroy', $faqCategory->id) }}" class="float-right">
  @method('DELETE')
  @csrf
  <button class="btn btn-danger" onclick="return confirm('Are You Sure Want to delete this record?')">Delete</button>
</form>
@endif
@if(hasPermission('faq_categories', 'edit'))
  <a class="btn btn-primary float-right mr-2" href="{{ route('admin.faq-categories.edit', $faqCategory->id)}}">Edit Document Category</a>
@endif
@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">View Category - {{ $faqCategory->name }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Name</label>
                      <span> {{$faqCategory->name}} </span>
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

