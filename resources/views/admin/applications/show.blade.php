@extends('admin.layouts.app')
@section('header', 'Applications')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">Application</li>
  <li class="breadcrumb-item active">Details</li>
@endsection
@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Viewing Application - {{ $application->name }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Name</label>
                      <span>{{$application->name}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email</label>
                      <span>{{$application->email}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Gender</label>
                      <span>{{$application->gender}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Phone</label>
                      <span>{{$application->phone}}</span>
                    </div>
                  </div>
                </div> 

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Experience</label>
                      <span>{{$application->experience}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>City</label>
                      <span>{{$application->city}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Degree Level</label>
                      <span>{{$application->degree_level}}</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Degree Title</label>
                      <span>{{$application->degree_title}}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Address</label>
                      <br/>
                      <div>{{$application->address}}</div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Resume</label>
                      <p>Open Resume Of <a href="{{ asset('storage/'.$application->resume.'#page2' ) }}" target="_blank"> {{$application->name}}</a>.</p>
                      </iframe>
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
    //Date and time picker
    $(document).ready(function(){
    //   $('#starttime').datetimepicker({ icons: { time: 'far fa-clock' } });
    });

  </script>
  

@endpush
