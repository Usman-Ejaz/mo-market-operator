@extends('admin.layouts.app')
@section('header', 'Newsletters')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Newsletters</li>
@endsection

@section('addButton')
@if( hasPermission('newsletters', 'create') )
<a class="btn btn-primary float-right" href="{{ route('admin.newsletters.create') }}">Add Newsletter</a>
@endif

@if( hasPermission('subscribers', 'list') )
<a class="btn btn-primary float-right mr-2" href="{{ route('admin.subscribers.index') }}">Subscribers</a>
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
						<th>Subject</th>
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
@include('admin.includes.confirm-popup')
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
			processing: true,
			serverSide: true,
			pageLength: 25,
			order: [[0, 'desc']],
			ajax: "{{ route('admin.newsletters.list') }}",
			fnDrawCallback: function() {
				if (this.fnSettings()._iRecordsDisplay === 0 || this.fnSettings()._iRecordsDisplay === 1) {
					$('.dataTables_info').hide();
				} else {
					$('.dataTables_info').show();
				}
			},
			columns: [{
					data: 'id',
					name: 'id'
				},
				{
					data: 'subject',
					name: 'subject'
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

        $('body').on('click', '.subscribe_button', (e) => {
            action = e.target.dataset.link;
            $('#msg_heading').text('Are you sure?');
            $('#msg_body').text('Are you sure you want to send this newsletter?');
            $('#confirm').addClass('btn-primary').removeClass('btn-danger');
            $('#confirmModal').modal('toggle');
        });

        $('#confirm').click(e => {
            $.ajax({
                url: action,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: (response) => {
                    if (response.message) {
                        $('#confirmModal').modal('toggle');
                        toastr.success(response.message);
                    }
                },
                error: (error) => {
                    $('#confirmModal').modal('toggle');
                    toastr.error('Something went wrong!');
                }
            })
        })

	});
</script>
@endpush
