@extends('admin.layouts.app')
@section('header', 'Contact Page Queries')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Contact Page Queries</li>
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
						<th>Subject</th>
						<!-- <th>Message</th> -->
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
</div>
@endsection

@push('optional-styles')
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('optional-scripts')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
	$(function() {

		var table = $('.yajra-datatable').DataTable({
			processing: true,
			serverSide: true,
			pageLength: 25,
			order: [[4, 'desc']],
			ajax: "{{ route('admin.contact-page-queries.list') }}",
			fnDrawCallback: function() {
				if (this.fnSettings()._iRecordsDisplay === 0 || this.fnSettings()._iRecordsDisplay === 1) {
					const searchedRecods = this.fnSettings()._iRecordsDisplay;
					const totalRecords = this.fnSettings()._iRecordsTotal;
					$('.dataTables_info').text(`Showing ${searchedRecods} to ${searchedRecods} of ${searchedRecods} entry ${"("}filtered from ${totalRecords} total entries${")"}`);
				} else {
					$('.dataTables_info').show();
				}
			},
			columns: [
				{ data: 'id', name: 'id' },
				{ data: 'name', name: 'name' },
				{ data: 'email', name: 'email' },
				{ data: 'subject', name: 'subject' },
				// { data: 'message', name: 'message' },
				{ data: 'created_at', name: 'created_at' },
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