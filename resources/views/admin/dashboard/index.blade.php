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
                    <h3 class="card-title">Online Store Overview</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-sm btn-tool">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-tool">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-success text-xl">
                            <i class="ion ion-ios-refresh-empty"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                <i class="ion ion-android-arrow-up text-success"></i> 12%
                            </span>
                            <span class="text-muted">CONVERSION RATE</span>
                        </p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-warning text-xl">
                            <i class="ion ion-ios-cart-outline"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                <i class="ion ion-android-arrow-up text-warning"></i> 0.8%
                            </span>
                            <span class="text-muted">SALES RATE</span>
                        </p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <p class="text-danger text-xl">
                            <i class="ion ion-ios-people-outline"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                <i class="ion ion-android-arrow-down text-danger"></i> 1%
                            </span>
                            <span class="text-muted">REGISTRATION RATE</span>
                        </p>
                    </div>

                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection


@push('optional-scripts')
<script type="text/javascript">
	$(document).ready(() => {
		$.ajax({
			url: '{{ route("admin.search-statistics.analytics-data") }}',
			method: 'GET',
			success: function (response) {
				$("#result-pages tbody").html(response.activePages);
				$("#active-users").html(response.activeUsers);
                $('#countries-sources tbody').html(response.usersByCountry);
			},
			error: function (error) {
				console.log('error => ', error);
			}
		});
	});
</script>
@endpush
