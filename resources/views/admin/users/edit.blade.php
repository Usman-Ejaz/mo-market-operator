@extends('admin.layouts.app')
@section('header', 'Users')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
  <li class="breadcrumb-item active">Update</li>
@endsection

@section('content')
  <div class="container-fluid">

      <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" id="update-users-form">
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

@push('optional-scripts')
  <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>

  <script>
    $(document).ready(function(){

      $.validator.addMethod(
        "notNumericValues",
        function(value, element) {
          return this.optional(element) || isNaN(Number(value));
        },
        "String cannot be numeric"
      );

      $.validator.addMethod("noSpace", function(value) { 
        this.value = $.trim(value);
        return this.value;
      });

        $('#update-users-form').validate({
            errorElement: 'span',
            errorClass: "my-error-class",
            validClass: "my-valid-class",
            rules:{
                name: {
                    required: true,
                    maxlength: 255,
                    minlength: 2,
                    notNumericValues: true,
                    noSpace:true,
                },
                email:{
                    required: true,
                    email: true,
                    notNumericValues: true,
                    noSpace:true,
                },
                role_id: {
                    required: true,
                    number: true
                },
                department: {
                    required: true,
                    number: true,
                    noSpace:true,
                },
                image: {
                    extension: "jpg|jpeg|png"
                },
                status: {
                    required : true,
                }
            },
            messages: {
              image: "Please Attach a file with valid extension"
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
                            alert('Image Deleted Successfully');
                            $('.imageExists').remove();
                        }
                    }
                });
            }
        });


    });

  </script>

@endpush
