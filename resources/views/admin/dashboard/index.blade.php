@extends('admin.layouts.app')
@section('header', 'Dashboard')
@section('breadcrumbs')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Active Visitors</h3>
                        {{-- <a href="javascript:void(0);">View Report</a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg" id="active-users"></span>
                            <span>Visitors In last 5 Minutes</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">What pages do your users visit?</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle" id="result-pages">
                        <thead>
                            <tr>
                                <th>Top Active Pages</th>
                                <th>Users</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Sessions by Country</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle" id="countries-sources">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Users</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Actvity Logs</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle" id="activity-logs">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Message</th>
                                <th>Type</th>
                                <th>Done By</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection


@push('optional-scripts')
<script type="text/javascript">
	$(document).ready(() => {
        getAnalyticsData();
        // getLatestActivityLogs();
	});

    function getAnalyticsData() {
        $.ajax({
			url: '{{ route("admin.search-statistics.analytics-data") }}',
			method: 'GET',
			success: function (response) {
				$("#result-pages tbody").html(response.activePages);
				$("#active-users").html(response.activeUsers);
                $('#countries-sources tbody').html(response.usersByCountry);

                setTimeout(() => {
                    getAnalyticsData();
                }, 10000);
			},
			error: function (error) {
				console.log('error => ', error);
			}
		});
    }


    function getLatestActivityLogs() {
        $.ajax({
			url: '{{ route("admin.dashboard.activity-logs") }}',
			method: 'GET',
			success: function (response) {
                console.log(response);
				$("#activity-logs tbody").html(response.activityLogs);

                setTimeout(() => {
                    getLatestActivityLogs();
                }, 60000);
			},
			error: function (error) {
				console.log('error => ', error);
			}
		});
    }

</script>
@endpush
