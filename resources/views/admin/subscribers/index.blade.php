@extends('admin.layouts.app')
@section('header', 'Subscribers')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.newsletters.index') }}">Newsletters</a></li>
<li class="breadcrumb-item active">Subscribers</li>
@endsection

@section('addButton')
@if(hasPermission('subscribers', 'subscribe'))
<a class="btn btn-primary float-right bulk-action" id="unsubscribe" href="javascript:void(0);">Bulk UnSubscribe</a>
<a class="btn btn-primary float-right mr-2 bulk-action" id="subscribe" href="javascript:void(0);">Bulk Subscribe</a>
@endif
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">

			<table class="table table-bordered yajra-datatable">
				<thead>
					<tr>
						<th>
							<input type="checkbox" name="select-all" id="select-all">
						</th>
						<th>Id</th>
						<th>Email</th>
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
@include('admin.includes.alert-popup')
@endsection

@push('optional-styles')
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('optional-scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
	$(function() {

		var table = $('.yajra-datatable').DataTable({
			order: [[1, 'desc']],
			processing: true,
			serverSide: true,
			pageLength: 25,
			ajax: "{{ route('admin.subscribers.list') }}",
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
					data: 'multiselect',
					name: 'multiselect',
					orderable: false
				},
				{
					data: 'id',
					name: 'id'
				},
				{
					data: 'email',
					name: 'email'
				},
				{
					data: 'status',
					name: 'status'
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

		$('#select-all').on('change', function(e) {
			Array.from(document.querySelectorAll('input[class=multiselect]')).forEach(elem => elem.checked = e.target.checked);
		});

		$('.bulk-action').on('click', function(e) {
			let markedCheckboxs = Array.from(document.querySelectorAll('input[class=multiselect]')).filter(elem => elem.checked === true);

			if (markedCheckboxs.length > 0) {
				let ids = '';
				markedCheckboxs.forEach(checkbox => { ids += checkbox.id.split('_')[1] + ','; });
				ids = ids.slice(0, ids.length - 1);
				if (confirm('Are you sure?')) {
					$.ajax({
						url: "{{ route('admin.subscribers.bulkToggle') }}",
						type: 'POST',
						data: {
							_token: "{{ csrf_token() }}",
							bulkIds: ids,
							subscribe: e.target.id === 'subscribe'
						},
						dataType: 'JSON',
						success: function(data) {
							if (data.success) {
								window.location.reload();
							}
						}
					});
				}
			} else {
                $('#msg_text').text("Please select checkbox first");
                $('#alertModal').modal('toggle');
			}
		});

	});
</script>
@endpush
