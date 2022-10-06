@extends('admin.layouts.app')
@section('header')
Applications Of - {{ $job->title }}
@endsection
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
<li class="breadcrumb-item active">Applications</li>
@endsection
@section('addButton')
@if( hasPermission('jobs', 'export_applications') )
<a role="button" class="btn btn-primary float-right"
    href="{{ route('admin.job.applications.list.export',$job->id) }}">Export Applications</a>
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
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Experience</th>
                        <th>Created date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <br />
        </div>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
</div>
@include('admin.includes.delete-popup')
@endsection

@push('optional-styles')
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('optional-scripts')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" defer></script>

<script type="text/javascript">
    let action = "";
    $(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            order: [
                [0, 'desc']
            ],
            ajax: "{{ route('admin.job.applications.list',$job->id) }}",
            fnDrawCallback: function () {
                if (this.fnSettings()._iRecordsDisplay === 0 || this.fnSettings()
                    ._iRecordsDisplay === 1) {
                    const searchedRecods = this.fnSettings()._iRecordsDisplay;
                    const totalRecords = this.fnSettings()._iRecordsTotal;
                    $('.dataTables_info').text(
                        `Showing ${searchedRecods} to ${searchedRecods} of ${searchedRecods} entry ${"("}filtered from ${totalRecords} total ${totalRecords > 1 ? 'entries' : 'entry'}${")"}`
                        );
                } else {
                    $('.dataTables_info').show();
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'gender',
                    name: 'gender'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'city',
                    name: 'city'
                },
                {
                    data: 'experience',
                    name: 'experience'
                },
                {
                    data: { '_': 'created_at.display', 'sort': 'created_at.sort' },
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        table.on('page.dt', function () {
            $('html, body').animate({
            scrollTop: $(".dataTables_wrapper").offset().top
                }, 'fast');
        });

        $('body').on('click', '.deleteButton', (e) => {
            action = e.target.dataset.action;
            $('#deleteModal').modal('toggle');
        });

        $('#deleteForm').submit(function (event) {
            $(this).attr('action', action);
        });

    });

</script>
@endpush
