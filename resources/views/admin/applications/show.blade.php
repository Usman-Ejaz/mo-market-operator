@extends('admin.layouts.app')
@section('header', 'Application Details')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.job.applications', $application->job_id) }}">Applications</a></li>
  <li class="breadcrumb-item active">Application Details</li>
@endsection
@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Application Detail - {{ $application->name }}</h3>
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

                @if(isset($application->resume) && !empty($application->resume))
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Resume</label>
                      <p>Open Resume Of <a href="{{ asset(config('filepaths.applicationsPath.public_path') . $application->resume) }}" target="_blank"> {{$application->name}}</a>.</p>
                      </iframe>
                    </div>
                  </div>
                </div>
                @endif
            </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </div>
@endsection
