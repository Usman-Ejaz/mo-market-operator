@extends('admin.layouts.app')
@section('header', 'Reports')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('addButton')
    {{-- @if (hasPermission('jobs', 'create')) --}}
    <a class="btn btn-primary float-right" href="{{ route('admin.reports.create') }}">Add New Report</a>
    {{-- @endif --}}
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
                            <th>Category/Sub Category</th>
                            <th>Publish Date</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <br />
            </div>
        </div>
    </div>
@endsection

@push('optional-styles')
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('optional-scripts')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" defer></script>

    <script type="text/javascript">
        let action = "";
        $(function() {

            var isScroll = false;
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                order: [
                    [0, 'asc']
                ],
                ajax: "{{ route('admin.reports.list') }}",
                fnDrawCallback: function() {
                    if (this.fnSettings()._iRecordsDisplay === 0 || this.fnSettings()
                        ._iRecordsDisplay === 1) {
                        const searchedRecods = this.fnSettings()._iRecordsDisplay;
                        const totalRecords = this.fnSettings()._iRecordsTotal;
                        $('.dataTables_info').text(
                            `Showing ${searchedRecods} to ${searchedRecods} of ${searchedRecods} entry ${"("}filtered from ${totalRecords} total entries${")"}`
                        );
                    } else {
                        $('.dataTables_info').show();
                    }
                    if (isScroll) {
                        $('html, body').animate({
                            scrollTop: $("body").offset().top
                        }, 500);
                        isScroll = false;
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'Name'
                    },
                    {
                        data: 'category/sub_category',
                        name: 'Category / Sub-Category'
                    },
                    {
                        data: 'publish_date',
                        name: 'Publish Date'
                    },
                    {
                        data: 'created_at',
                        name: 'Created At'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $(document).on('click', '.paginate_button:not(.disabled)', function() {
                isScroll = true;
            });
        });
    </script>
@endpush