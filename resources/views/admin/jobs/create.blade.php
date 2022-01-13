@extends('admin.layouts.app')
@section('header', 'Jobs')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Jobs</li>
</ol>
@endsection

@section('content')
  <div class="container-fluid">
              <form method="POST" action="{{ url('/admin/jobs')}}" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-9">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Editing Job - {{ $job->title }}</h3>
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      @include('admin.jobs.form')

                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Schedule Content</h3>
                      </div>
                        @include('admin.jobs.publishform')
                    </div>

                    <!-- /.card-body -->
                    <div class="float-right">

                      <input type="hidden" name="active" id="status">

                      @if( \Route::current()->getName() == 'admin.jobs.edit' )
                          @if($job->active == 'Active')
                            <button type="submit" class="btn btn-primary publish_button">Update</button>
                            <button type="submit" class="btn btn-danger draft_button">Unpublish</button>
                          @elseif($job->active == 'Draft')
                            <button type="submit" class="btn btn-primary draft_button">Update</button>
                            <button type="submit" class="btn btn-success publish_button">Publish</button>
                          @endif
                      @else 
                            <button type="submit" class="btn btn-primary draft_button">Save</button>
                            <button type="submit" class="btn btn-success publish_button">Publish</button>
                      @endif

                    </div>

                  </div>
                </div>
              </form>
              
            
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </div>   
@endsection


@push('optional-styles')
  <link rel="stylesheet" href="{{ asset('admin/css/daterangepicker.css') }}">
@endpush

@push('optional-scripts')
  <script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
  <script src="{{ asset('admin/js/daterangepicker.js') }}" defer></script>

  <script>
    CKEDITOR.replace('editor1', {
      height: 800,
      baseFloatZIndex: 10005,
      removeButtons: 'PasteFromWord'
    });

    //Date and time picker
    $(document).ready(function(){
      // $('#starttime').datetimepicker({ icons: { time: 'far fa-clock' } });

      // Set hidden fields based on button click
      $('.draft_button').click(function(e) {
        $('#status').val("0");
      });

      $('.publish_button').click(function(e) {
        $('#status').val("1");  
      });
    });
  </script>
  
@endpush