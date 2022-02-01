@extends('admin.layouts.app')
@section('header', 'Roles')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Roles</li>
@endsection

@section('addButton')
    @if( Auth::user()->role->hasPermission('roles', 'create') )
        <a class="btn btn-primary float-right" href="{{ route('admin.roles.create') }}">Add role</a>
    @endif
@endsection

@section('content')
<div class="container-fluid">
      <div class="row">
        <div class="col-md-12">

          <table class="table table-bordered yajra-datatable">
              <thead>
                  <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Created at</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
              </tbody>
          </table>

          <br/>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
@endsection

@push('optional-styles')
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('optional-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
      $(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: "{{ route('admin.roles.list') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'created_at', name: 'created_at'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
      });
    </script>
@endpush
