@extends('admin.layouts.app')
@section('header', 'Edit Report')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Edit Report</li>
@endsection

@push('optional-styles')
    <link rel="stylesheet" href="{{ asset('admin-resources/css/bootstrap-tagsinput.css') }}" />
    <style type="text/css">
        .bootstrap-tagsinput {
            width: 100%;
            padding: 7px 6px !important;
        }

        .label-info {
            background-color: #17a2b8;
        }

        .label {
            display: inline-block;
            padding: .25em .4em;
            font-size: 85%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out,
                border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            white-space: break-spaces !important;
            max-width: 63em;
            margin: 0px 0px 5px 0px;
        }

        .item-list {
            display: flex;
            list-style: none;
            width: 100%;
            margin: 0;
            flex-wrap: wrap;
            padding: 10px;
            position: relative;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

        }

        .folder-container {
            padding: 10px;
            margin: 10px;
            cursor: pointer;
            border-radius: 3px;
            border: 1px solid #ecf0f1;
            overflow: hidden;
            background: #f6f8f9;
            display: flex;
            /* width: 20%; */
        }

        .folder-container:hover {
            background: #4da7e8;
            color: #fff;
        }

        .folder-container:hover .folder-icon {
            color: #fff !important;
        }


        /* Limit image width to avoid overflow the container */
        img {
            max-width: 100%;
            /* This rule is very important, please do not ignore this! */
        }

        .modal-image-preview {
            display: block;
            max-width: 464px;
            max-height: 390px;
            width: auto;
            height: auto;
        }

        .image-preview {
            border: 1px solid black;
            height: 200px;
            width: 200px;
            max-width: 20%;
            /* min-width: 0px !important; */
            /* min-height: 0px !important; */
            /* max-width: none !important; */
            /* max-height: none !important; */
            transform: none;
        }

        .folder-icon {
            /* display: flex; */
            /* margin: auto; */
            width: 100%;
        }

        .image-aspact-ratio {
            /* object-fit: contain; */
            height: 150px;
        }

        .btn-container {
            text-align: center;
        }

        .featured {
            border: 2px solid #4da7e8;
            box-shadow: 5px 5px #d2d6d3;
        }

        .dropdown-menu span {
            cursor: pointer;
            margin: 2px 0px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
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
            </div>

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Report Data</h3>
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

@push('optional-scripts')
    <script type="text/javascript" src="{{ asset('admin-resources/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('admin-resources/js/form-custom-validator-methods.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('#update-post-form').validate({
                ignore: [],
                errorElement: 'span',
                errorClass: "my-error-class",
                validClass: "my-valid-class",
                rules: {
                    title: {
                        required: true,
                        maxlength: 150,
                        minlength: 3,
                        notNumericValues: true,
                        prevent_special_characters: true
                    },
                    description: {
                        ckeditor_required: true,
                        maxlength: 50000
                    },
                    slug: {
                        required: true,
                        notNumericValues: true,
                        // prevent_special_characters: true
                    },
                    keywords: {
                        maxlength: {
                            depends: () => {
                                let tags = $('#keywords').val().split(',');
                                return tags.filter(tag => tag.length > 64).length > 0 ? 64 : 0;
                            }
                        }
                    },
                    post_category: {
                        required: true,
                    },
                    image: {
                        extension: "{{ config('settings.image_file_extensions') }}"
                    },
                    start_datetime: {
                        required: {
                            depends: function() {
                                return $('#end_datetime').val().length > 0;
                            }
                        }
                    },
                    end_datetime: {
                        required: false
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.attr("id") == "description") {
                        element = $("#cke_" + element.attr("id"));
                    }
                    if (element.attr("id") == "start_datetime" || element.attr("id") ==
                        "end_datetime") {
                        element = $('#' + element.attr("id")).parent();
                    }
                    if (element.attr("id") == "post_image") {
                        element.next().text('');
                    }
                    error.insertAfter(element);
                },
                messages: {
                    image: '{{ __('messages.valid_image_extension') }}',
                    title: {
                        required: "{{ __('messages.required') }}",
                        minlength: "{{ __('messages.min_characters', ['field' => 'Title', 'limit' => 3]) }}",
                        maxlength: "{{ __('messages.max_characters', ['field' => 'Title', 'limit' => 150]) }}"
                    },
                    keywords: {
                        maxlength: "{{ __('messages.max_characters', ['field' => 'Keywords', 'limit' => 64]) }}"
                    }
                }
            });
        });
    </script>
@endpush
