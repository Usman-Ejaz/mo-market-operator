@extends('admin.layouts.app')
@section('header', 'FAQ')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
  <li class="breadcrumb-item">FAQ</li>
  <li class="breadcrumb-item active">View</li>
@endsection

@section('addButton')
<form method="POST" action="/admin/jobs/{{$faq->id}}" class="float-right">
  @method('DELETE')
  @csrf
  <button class="btn btn-danger" onclick="return confirm('Are You Sure Want to delete this record?')">Delete</button>
</form>

<a class="btn btn-primary float-right mr-2" href="{{ route('admin.faqs.edit', $faq->id)}}">Edit FAQ</a>

@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">View FAQ - {{ $faq->id }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Question</label>
                      <span>{{$faq->question}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Answer</label>
                      <span>{{$faq->answer}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <span>{{$faq->active}}</span>
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
  <link rel="stylesheet" href="{{ mix('admin/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('optional-scripts')
  <script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
  <script src="{{ mix('admin/plugins/daterangepicker/daterangepicker.min.js') }}"></script>

  <script>
    CKEDITOR.replace('editor1', {
      height: 400,
      baseFloatZIndex: 10005,
      removeButtons: 'PasteFromWord'
    });
  </script>
  

@endpush
