@extends('admin.layouts.app')
@section('header', 'Media Library')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Media Library</li>
@endsection

@section('addButton')
@if( hasPermission('media_library', 'create') )
<a class="btn btn-primary float-right" href="{{ route('admin.media-library.create') }}">Add Media Library</a>
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
						<th>Created date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>

			<br />
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    let action = "";
	$(function() {
		var table = $('.yajra-datatable').DataTable({
			order: [[0, 'desc']],
			processing: true,
			serverSide: true,
			pageLength: 25,
			ajax: "{{ route('admin.media-library.list') }}",
			fnDrawCallback: function() {
				if (this.fnSettings()._iRecordsDisplay === 0 || this.fnSettings()._iRecordsDisplay === 1) {
					const searchedRecods = this.fnSettings()._iRecordsDisplay;
					const totalRecords = this.fnSettings()._iRecordsTotal;
					$('.dataTables_info').text(`Showing ${searchedRecods} to ${searchedRecods} of ${searchedRecods} entry ${"("}filtered from ${totalRecords} total entries${")"}`);
				} else {
					$('.dataTables_info').show();
				}
			},
			columns: [{
					data: 'id',
					name: 'id',
				},
				{
					data: 'name',
					name: 'name'
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
