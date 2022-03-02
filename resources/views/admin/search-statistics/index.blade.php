@extends('admin.layouts.app')
@section('header', 'Search Statistics')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Search Statistics</li>
@endsection

@section('addButton')
  @if(Auth::user()->role->hasPermission('search-statistics', 'export_keywords'))
  <a role="button" class="btn btn-primary float-right" href="{{ route('admin.search-statistics.export-list') }}">Export Keywords</a>
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
                          <th>Keyword</th>
                          <th>Count</th>
                          <!-- <th>Created date</th>
                          <th>Action</th> -->
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
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" defer></script>

    <script type="text/javascript">
      $(function () {
        
        var table = $('.yajra-datatable').DataTable({
            order: [[ 2, 'desc' ]],
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: "{{ route('admin.search-statistics.list') }}",
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
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'keyword', name: 'keyword'},
                {data: 'count', name: 'count'},
                // {data: 'created_at', name: 'created_at'},
                // {
                //     data: 'action', 
                //     name: 'action', 
                //     orderable: false, 
                //     searchable: false
                // },
            ]
        });

      });
    </script>
@endpush