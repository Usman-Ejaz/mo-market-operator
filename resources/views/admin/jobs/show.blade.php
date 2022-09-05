@extends('admin.layouts.app')
@section('header', 'View Job')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
  <li class="breadcrumb-item active">View Job</li>
@endsection

@section('addButton')
<form method="POST" action="{{ route('admin.jobs', $job->id) }}" class="float-right">
  @method('DELETE')
  @csrf
  <button class="btn btn-danger" onclick="return confirm('Are You Sure Want to delete this record?')">Delete</button>
</form>

<a class="btn btn-primary float-right mr-2" href="{{ route('admin.jobs.edit', $job->id)}}">Edit Job</a>

@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">View Job - {{ $job->title }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Title</label>
                      <span>{{$job->title}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Qualification</label>
                      <span>{{$job->qualification}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Experience</label>
                      <span>{{$job->experience}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Location</label>
                      <span>{{$job->location}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Total Positions</label>
                      <span>{{$job->total_positions}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description</label>
                      <br/>
                      <div>{{$job->description}}</div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Start DateTime</label>
                      <span>{{$job->start_datetime}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>End DateTime</label>
                      <span>{{$job->end_datetime}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <span>{{$job->active}}</span>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Image</label>
                      <img src="{{ asset('storage/'.$job->image ) }}">
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
