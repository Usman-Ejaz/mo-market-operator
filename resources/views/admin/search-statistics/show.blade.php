@extends('admin.layouts.app')
@section('header', 'Search Statistics')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.search-statistics.index') }}">Search Statistics</a></li>
  <li class="breadcrumb-item active">Details</li>
@endsection

@section('addButton')
@if(Auth::user()->role->hasPermission('search-statistics', 'delete'))
  <form method="POST" action="{{ route('admin.search-statistics.destroy', $searchStatistic->id) }}" class="float-right">
    @method('DELETE')
    @csrf
    <button class="btn btn-danger">Delete</button>
  </form>
@endif

@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Viewing Statistics - {{ $searchStatistic->keyword }}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Keyword</label>
                      <span>{{ $searchStatistic->keyword }}</span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Count</label>
                      <span>{{$searchStatistic->count}}</span>
                    </div>
                  </div>
                </div>
            </div>
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </div>   
@endsection

@push('optional-styles')
  
@endpush

@push('optional-scripts')  
@endpush
