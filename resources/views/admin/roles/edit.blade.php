@extends('admin.layouts.app')
@section('header', 'Roles')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">

      <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" enctype="multipart/form-data" id="update-roles-form">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editing Role - {{ $role->name }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @method('PATCH')
              @include('admin.roles.form')

              <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary draft_button">Update</button>
              </div>
            </div>
          </div>

        </div>
      </form>

    </div>
@endsection

@push('optional-styles')

@endpush

@push('optional-scripts')
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>
    $(document).ready(function(){

        $('#update-roles-form').validate({
            errorElement: 'span',
            errorClass: "my-error-class",
            validClass: "my-valid-class",
            rules:{
                name: {
                    required: true,
                    maxlength: 255
                }
            }
        });

    });

  </script>

@endpush
