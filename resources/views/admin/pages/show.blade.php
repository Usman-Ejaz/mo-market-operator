@extends('admin.layouts.app')
@section('header', 'Pages')
@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">CMS Pages</a></li>
<li class="breadcrumb-item active">View</li>
@endsection

@section('addButton')
<form method="POST" action="/admin/pages/{{$cms_page->id}}" class="float-right">
    @method('DELETE')
    @csrf
    <button class="btn btn-danger" onclick="return confirm('Are You Sure Want to delete this record?')">Delete</button>
</form>

<a class="btn btn-primary float-right mr-2" href="{{ route('admin.pages.edit', $cms_page->id)}}">Edit Page</a>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">View CMS Page - {{ $cms_page->title }}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Title</label>
                                <span>{{$cms_page->title}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Slug</label>
                                <span>{{$cms_page->slug}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <br />
                                <div>{{$cms_page->description}}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Keywords</label>
                                <span>{{$cms_page->keywords}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start DateTime</label>
                                <span>{{$cms_page->start_datetime}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>End DateTime</label>
                                <span>{{$cms_page->end_datetime}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <span>{{$cms_page->active}}</span>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Image</label>
                                @if( isset($cms_page->image) )
                                <img src="{{ asset( config('filepaths.pageImagePath.public_path') .$cms_page->image) }}"
                                    class="img-fluid">
                                @else
                                <span>None</span>
                                @endif
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
<link rel="stylesheet" href="{{ mix('admin/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('optional-scripts')
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
<script src="{{ mix('admin/plugins/daterangepicker/daterangepicker.min.js') }}"></script>

<script>
    CKEDITOR.replace('editor1', {
        height: 400,
        baseFloatZIndex: 10005,
        removeButtons: 'PasteFromWord'
    });

    //Date and time picker
    $(document).ready(function () {
        $('#starttime').datetimepicker({
            icons: {
                time: 'far fa-clock'
            }
        });
    });

</script>


@endpush
