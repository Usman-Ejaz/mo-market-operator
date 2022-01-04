@extends('admin.layouts.app')
@section('header', 'News')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">News</li>
</ol>
@endsection

@section('addButton')
<a class="btn btn-primary float-right" href="{{ route('admin.news.create') }}">Add new news</a>
@endsection

@section('content')
  <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              Listing Page
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </div>   
@endsection


@push('optional-scripts')
    
@endpush
