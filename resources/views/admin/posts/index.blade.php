@extends('admin.layouts.app')
@section('header', 'Posts')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Posts</li>
@endsection

@section('addButton')
@if( hasPermission('posts', 'create') )
<a class="btn btn-primary float-right" href="{{ route('admin.posts.create') }}">Add Post</a>
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
                        {{-- <th>Slug</th> --}}
                        <th>Category</th>
                        <th>Keywords</th>
                        <th>Status</th>
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
@include('admin.includes.delete-popup')
@endsection

@push('optional-styles')
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('optional-scripts')

<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    let action = "";
    $(function () {

        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            order: [[0, 'desc']],
            ajax: "{{ route('admin.posts.list') }}",
            fnDrawCallback: function () {
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
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'post_category',
                    name: 'post_category'
                },
                {
                    data: 'keywords',
                    name: 'keywords'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: { '_': 'created_at.display', 'sort': 'created_at.sort' },
                    name: 'created_at',

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
