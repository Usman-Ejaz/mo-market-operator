@extends('admin.layouts.app')
@section('header', 'Posts')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
  <li class="breadcrumb-item active">View</li>
@endsection

@section('addButton')
<form method="POST" action="{{ route('admin.posts', $post->id) }}" class="float-right">
  @method('DELETE')
  @csrf
  <button class="btn btn-danger" onclick="return confirm('Are You Sure Want to delete this record?')">Delete</button>
</form>

<a class="btn btn-primary float-right mr-2" href="{{ route('admin.posts.edit', $post->id)}}">Edit Posts</a>

@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">View Post - {{ $post->title }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Title</label>
                      <span>{{$post->title}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Slug</label>
                      <span>{{$post->slug}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description</label>
                      <br/>
                      <div>{{$post->description}}</div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Keywords</label>
                      <span>{{$post->keywords}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Category</label>
                      <span>{{ $post->post_category ?? 'None' }}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Start DateTime</label>
                      <span>{{$post->start_datetime}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>End DateTime</label>
                      <span>{{$post->end_datetime}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <span>{{$post->active}}</span>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Image</label>
                        @if( isset($post->image) )
                            <img src="{{ asset( config('filepaths.postImagePath.public_path') .$post->image) }}" class="img-fluid">
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
  <link rel="stylesheet" href="{{ asset('admin-resources/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('optional-scripts')
  <script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
  <script src="{{ asset('admin-resources/plugins/daterangepicker/daterangepicker.min.js') }}"></script>

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
