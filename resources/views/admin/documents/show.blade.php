@extends('admin.layouts.app')
@section('header', 'Documents')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
  <li class="breadcrumb-item active">Details</li>
@endsection

@section('addButton')
<form method="POST" action="/admin/documents/{{$document->id}}" class="float-right">
  @method('DELETE')
  @csrf
  <button class="btn btn-danger" onclick="return confirm('Are You Sure Want to delete this record?')">Delete</button>
</form>

<a class="btn btn-primary float-right mr-2" href="{{ route('admin.documents.edit', $document->id)}}">Edit Document</a>

@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">View Document - {{ $document->title }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Title</label>
                      <span>{{$document->title}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Keywords</label>
                      <span>{{$document->keywords}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>File</label>
                        @if( isset($document->file) )
                            <img src="{{ asset( config('filepaths.documentsFilePath.public_path') .$document->file) }}">
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

    //Date and time picker
    $(document).ready(function(){
      $('#starttime').datetimepicker({ icons: { time: 'far fa-clock' } });
    });

  </script>


@endpush
