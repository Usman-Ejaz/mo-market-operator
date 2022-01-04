@extends('admin.layouts.app')
@section('header', 'News')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">News</li>
</ol>
@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Editing News - {{ $news->title }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->

              <form method="POST" action="{{ url('/admin/news/'.$news->id)}}">
                  @method('PATCH')
                  @include('admin.news.form')

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update News</button>
                  </div>
              </form>
              
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
