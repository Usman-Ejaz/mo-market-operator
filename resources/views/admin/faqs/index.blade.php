@extends('admin.layouts.app')
@section('header', 'FAQs')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">FAQs</li>
@endsection

@section('addButton')
<a class="btn btn-primary float-right" href="{{ route('admin.faqs.create') }}">Add new FAQ</a>
@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
  
              <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                  @if(Session::has('alert-' . $msg))

                  <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                  @endif
                @endforeach
              </div>

              <table class="table table-bordered yajra-datatable">
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Question</th>
                          <th>Answer</th>
                          <th>Created date</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>

              <br/>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>


@endsection

@push('optional-styles')
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
     
      ul.pagination li{
        padding:0px!important;
        border-color: #fff!important;
      }
      ul.pagination li.active{
        background-color: #fff!important;
      }
    </style>
@endpush

@push('optional-scripts')
      
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js" defer></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" defer></script>

    <script type="text/javascript">
      $(function () {
        
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 15,
            ajax: "{{ route('admin.faqs.list') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'question', name: 'question'},
                {data: 'answer', name: 'answer'},
                {data: 'created_at', name: 'created_at'},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: true, 
                    searchable: true
                },
            ]
        });

      });
    </script>
@endpush