@extends('admin.layouts.app')
@section('header', 'Users')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item">Users</li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">

      <form method="POST" action="{{ url('/admin/users/'.$user->id)}}" enctype="multipart/form-data" id="update-users-form">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editing User - {{ $user->name }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @method('PATCH')
              @include('admin.users.form')

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
  <script src="{{ asset('admin/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin/js/additional-methods.min.js') }}"></script>

  <script>
    $(document).ready(function(){

      $('#update-users-form').validate({
        errorElement: 'span',
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        rules:{
          name: {
            required: true,
            maxlength: 5000
          },
          email:{
            required: true,
            maxlength: 50000
          },
          department: {
            maxlength: 2000
          },
          role_id: {
            required: true,
            maxlength: 100
          },
          active: {
            required: true,
          },
          image: {
            extension: "jpg|jpeg|png|ico|bmp"
          }
        },
        messages: {
          name: {
            required: "Name is required",
            maxlength: "Name cannot be more than 5000 characters"
          },
          email: {
            required: "Email is required",
            maxlength: "Email cannot be more than 50000 characters"
          },
          role_id: {
            required: "Role is required",
            maxlength: "Role cannot be more than 2000 characters",
          },
          department: {
            required: "Department is required",
            maxlength: "Department cannot be more than 500 characters"
          },
          image: {
            extension: "This type of file is not accepted"
          },
        }
      });

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#deleteImage").click(function(){

            if (confirm('Are you sure you want to this image?')) {
                $.ajax({
                    url: "{{ route('admin.users.deleteImage') }}",
                    type: 'POST',
                    data: {_token: "{{ csrf_token() }}", user_id: "{{$user->id}}"},
                    dataType: 'JSON',
                    success: function (data) {
                        if(data.success){
                            alert('image deleted successfully');
                            $('.imageExists').remove();
                        }
                    }
                });
            }
        });


    });

  </script>

@endpush
