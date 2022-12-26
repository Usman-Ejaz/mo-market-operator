@extends('admin.layouts.app')
@section('header', 'Edit Market Operational Data')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.complaint-departments.index') }}">Complaint Departments</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"> Create Complaint Department </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.complaint-departments.store') }}"
                            enctype="multipart/form-data">
                            @include('admin.complaint-departments.createForm')
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- @include('admin.includes.confirm-popup') --}}
@endsection
