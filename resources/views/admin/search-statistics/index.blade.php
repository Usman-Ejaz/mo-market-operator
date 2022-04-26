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
		
		var startDate = "";
		var endDate = "";

		var datePickerStartDate = "";
		var datePickerEndDate = "";
		

		renderTable(startDate, endDate, datePickerStartDate, datePickerEndDate);	
		
		// Handle date filters
		$('body').on('click', '#seachByDate', (e) => {
			if ($('#start_date_hidden').val().trim().length === 0 && $('#end_date_hidden').val().trim().length === 0) {
				alert("Please select the date first");
				e.preventDefault();
				return;
			}
			startDate = $('#start_date_hidden').val();
			endDate = $('#end_date_hidden').val();

			datePickerStartDate = $('#start_date').val();
			datePickerEndDate = $('#end_date').val();

			if (table !== null) {
				table.destroy();
				renderTable(startDate, endDate, datePickerStartDate, datePickerEndDate);
			}
		});

		$('body').on('click', '#clearSearch', (e) => {
			startDate = ""
			endDate = "";

			datePickerStartDate = "";
			datePickerEndDate = "";

			if (table !== null) {
				table.destroy();
				renderTable(startDate, endDate, datePickerStartDate, datePickerEndDate);
			}
		});
			
	});

	function renderTable(startDate, endDate, datePickerStartDate, datePickerEndDate)
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
					name: 'DT_RowIndex'
				},
				{
					data: 'keyword',
					name: 'keyword'
				},
				{
					data: 'count_sum',
					name: 'count_sum'
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
			<input name="start_date" id="start_date" class="form-control form-control-sm" readonly placeholder="Start Date" style="position:absolute; width: 35%; right: 100px;" value="${datePickerStartDate}"/>
			<input type="hidden" id="start_date_hidden" value="${startDate}" />
		`);	

		$('#DataTables_Table_0_filter').parent().css({display: 'flex', flexDirection: 'row-reverse'});
		$('#DataTables_Table_0_filter').parent().append(`			
			<input name="end_date" id="end_date" class="form-control form-control-sm" readonly placeholder="End Date" style="position:absolute; width: 35%; left: -92px;" value="${datePickerEndDate}"/>
			<input type="hidden" id="end_date_hidden" value="${endDate}" />
			<button class="btn btn-primary btn-sm" type="button" id="seachByDate" style="position:absolute; left: 140px;" >Search</button>
			${(datePickerStartDate !== "" && datePickerEndDate !== "") ? `
				<button class="btn btn-primary btn-sm" type="button" id="clearSearch" style="position:absolute; left: 205px;" >Clear</button>
			` : ""}
		`);	

		$('#start_date').datetimepicker({
			format: '{{ config("settings.datetime_format") }}',
			step: 30,
			roundTime: 'ceil',
			validateOnBlur: false,
			onChangeDateTime: function(dp, $input) {
				$('#start_date_hidden').val(mapDate(dp));
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