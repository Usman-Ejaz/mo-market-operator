@extends('admin.layouts.app')
@section('header', 'Pages')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Pages</li>
@endsection

@section('addButton')
  @if( Auth::user()->role->hasPermission('pages', 'create') )
    <a class="btn btn-primary float-right" href="{{ route('admin.pages.create') }}">Add New Page</a>
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
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Keywords</th>
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
@endpush

@push('optional-scripts')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">
      $(function () {

        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: "{{ route('admin.pages.list') }}",
            fnDrawCallback: function () {
              if (this.fnSettings()._iRecordsDisplay === 0 || this.fnSettings()._iRecordsDisplay === 1) {
                const searchedRecods = this.fnSettings()._iRecordsDisplay;
                const totalRecords = this.fnSettings()._iRecordsTotal;
                $('.dataTables_info').text(`Showing ${searchedRecods} to ${searchedRecods} of ${searchedRecods} entry ${"("}filtered from ${totalRecords} total entries${")"}`);
              } else {
                $('.dataTables_info').show();
              }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'title', name: 'title'},
                {data: 'slug', name: 'slug'},
                {data: 'keywords', name: 'keywords'},
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
