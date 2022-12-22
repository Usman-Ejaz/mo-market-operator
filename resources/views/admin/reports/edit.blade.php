@extends('admin.layouts.app')
@section('header', 'Edit Report')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Edit Report</li>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            {{-- <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Report Attachments</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.reports.add-attachment', $report->id) }}"
                            enctype="multipart/form-data">
                            @include('admin.reports.editAttachmentsForm')
                            @csrf
                        </form>

                        @include('admin.reports.attachmentList')
                    </div>
                </div>
            </div> --}}

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Report - {{ $report->name }}</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.reports.update', $report->id) }}"
                            enctype="multipart/form-data">
                            @include('admin.reports.editForm')
                            @method('PUT')
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
    {{-- @include('admin.includes.confirm-popup') --}}
@endsection

@push('optional-styles')
    <link rel="stylesheet" href="{{ asset('admin-resources/css/tempusdominus-bootstrap-4.min.css') }}">
    <style>
        .social-share-icon {
            font-size: 40px;
            cursor: pointer;
        }
    </style>
@endpush
