@extends('admin.layouts.app')
@section('header', 'Slider Images')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Slider Images</li>
@endsection

@section('addButton')
@if(hasPermission('slider_images', 'create'))
<a class="btn btn-primary float-right" href="{{ route('admin.slider-images.create') }}">Add Slider Image</a>
@endif
@if(hasPermission('slider_settings', 'edit'))
<a class="btn btn-primary float-right mr-2" href="{{ route('admin.slider-settings.index') }}">Slider Settings</a>
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
						<th>Slot One</th>
						<th>Slot Two</th>
						<th>Image</th>
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
		var isScroll = false;
		var table = $('.yajra-datatable').DataTable({
			processing: true,
			serverSide: true,
			pageLength: 25,
			order: [[0, 'desc']],
			ajax: "{{ route('admin.slider-images.list') }}",
			fnDrawCallback: function() {
				if (this.fnSettings()._iRecordsDisplay === 0 || this.fnSettings()._iRecordsDisplay === 1) {
					const searchedRecods = this.fnSettings()._iRecordsDisplay;
					const totalRecords = this.fnSettings()._iRecordsTotal;
					$('.dataTables_info').text(`Showing ${searchedRecods} to ${searchedRecods} of ${searchedRecods} entry ${"("}filtered from ${totalRecords} total entries${")"}`);
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
					data: 'slot_one',
					name: 'slot_one'
				},
				{
					data: 'slot_two',
					name: 'slot_two'
				},
				{
					data: 'image',
					name: 'image',
					orderable: false,
					searchable: false
				},
				{
					data: 'action',
					name: 'action',
					orderable: false,
					searchable: false
				},
			]
		});

		$(document).on('click', '.paginate_button:not(.disabled)', function () {
            isScroll = true;
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
