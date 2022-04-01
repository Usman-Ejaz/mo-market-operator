@extends('admin.layouts.app')
@section('header', 'Search Statistics')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Search Statistics</li>
@endsection

@section('addButton')
@if(hasPermission('search_statistics', 'export_keywords'))
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

			<br />
		</div>
	</div>
	<!-- /.row -->
</div>
<!-- /.container-fluid -->
</div>


@endsection

@push('optional-styles')
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@push('optional-scripts')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" defer></script>

<script type="text/javascript">
	var table = null;

	$(document).ready(() => {
		
		var startDate = null;
		var endDate = null;
		

		renderTable(startDate, endDate);		
		
		// Handle date filters
		$('body').on('click', '#seachByDate', (e) => {
			if ($('#start_date_hidden').val().trim().length === 0 && $('#end_date_hidden').val().trim().length === 0) {
				alert("Please select date first");
				e.preventDefault();
				return;
			}
			startDate = $('#start_date_hidden').val();
			endDate = $('#end_date_hidden').val();

			if (table !== null) {
				table.destroy();
				renderTable(startDate, endDate);
			}
		});
			
	});

	function renderTable(startDate, endDate)
	{
		table = $('.yajra-datatable').DataTable({
			order: [
				[2, 'desc']
			],
			processing: true,
			serverSide: true,
			pageLength: 25,
			ajax: {
				url: "{{ route('admin.search-statistics.list') }}",
				data: {
					start_date: startDate,
					end_date: endDate,
				}
			},			
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
					data: 'DT_RowIndex',
					name: 'DT_RowIndex',
					orderable: false,
					searchable: false
				},
				{
					data: 'keyword',
					name: 'keyword'
				},
				{
					data: 'count',
					name: 'count'
				},
				// {data: 'created_at', name: 'created_at'},
				// {
				//     data: 'action', 
				//     name: 'action', 
				//     orderable: false, 
				//     searchable: false
				// },
			]
		});

		$('#DataTables_Table_0_length').parent().css({display: 'flex', flexDirection: 'row'});
		$('#DataTables_Table_0_length').parent().append(`
			<input name="start_date" id="start_date" class="form-control form-control-sm" readonly placeholder="Start Date" style="position:absolute; width: 35%; right: 100px;"/>
			<input type="hidden" id="start_date_hidden" value="" />
		`);	

		$('#DataTables_Table_0_filter').parent().css({display: 'flex', flexDirection: 'row-reverse'});
		$('#DataTables_Table_0_filter').parent().append(`			
			<input name="end_date" id="end_date" class="form-control form-control-sm" readonly placeholder="End Date" style="position:absolute; width: 35%; left: -92px;"/>
			<input type="hidden" id="end_date_hidden" value="" />
			<button class="btn btn-primary btn-sm" type="button" id="seachByDate" style="position:absolute; left: 140px;" >Search</button>
		`);	

		$('#start_date').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 30,
			roundTime: 'ceil',
			validateOnBlur: false,
			onChangeDateTime: function(dp, $input) {
				$('#start_date_hidden').val(mapDate(dp));
				let endDate = $("#end_date").val();
				if (endDate.trim().length > 0 && $input.val() >= endDate) {
					$input.val("");
					$input.parent().next().text("Start Date cannot be less than end date");
				}
			},
			onShow: function() {
				this.setOptions({
					maxDate: $('#end_date_hidden').val() ? $('#end_date_hidden').val() : false
				})
			}
		});

		$('#end_date').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 30,
			roundTime: 'ceil',
			validateOnBlur: false,
			onChangeDateTime: function(dp, $input) {
				$('#end_date_hidden').val(mapDate(dp));
				let startDate = $("#start_date").val();
				if (startDate.trim().length > 0 && $input.val() <= startDate) {
					$input.val("");
					$input.parent().next().text("{{ __('messages.min_date') }}");
				}
			},
			onShow: function () {
				this.setOptions({
					minDate: $('#start_date_hidden').val() ? $('#start_date_hidden').val() : false
				})
			}
		});	
	}

	function mapDate(date) {
		return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:00`;
	}
</script>
@endpush