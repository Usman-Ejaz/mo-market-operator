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

              <form method="POST" action="{{ url('/admin/news/'.$news->id)}}" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-9">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Create News</h3>
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      @include('admin.news.form')

                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card card-primary">
                      <div class="card-header">
                        <h3 class="card-title">Schedule Content</h3>
                      </div>
                        @include('admin.news.publishform')
                    </div>

                    <!-- /.card-body -->
                    <div class="float-right">

                      <input type="hidden" name="active" id="status">

                      @if( \Route::current()->getName() == 'admin.news.edit' )
                          @if($news->active == 'Active')
                            <button type="submit" class="btn btn-primary publish_button">Update</button>
                            <button type="submit" class="btn btn-danger draft_button">Unpublish</button>
                          @elseif($news->active == 'Draft')
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
  <link rel="stylesheet" href="{{ mix('admin/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('optional-scripts')
  <script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
  <script src="{{ mix('admin/plugins/daterangepicker/daterangepicker.min.js') }}"></script>

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

      // Slug generator
      $("#title").keyup(function() {
        var Text = $(this).val();
        Text = Text.toLowerCase();
        Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
        $("#slug").val(Text);
      });

    });
  </script>

@endpush
