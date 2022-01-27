@extends('admin.layouts.app')
@section('header', 'Sub Menus')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Main Menus</a></li>
    <li class="breadcrumb-item active">Sub Menus</li>
@endsection

@section('content')
    <div class="container-fluid">

        <form method="POST" action="{{ route('admin.menus.submenusupdate', $menu->id) }}" enctype="multipart/form-data" id="update-menus-form">
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Editing Menu - {{ $menu->name }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        @method('PATCH')
                        @csrf

                        <div class="card-body">

                            <div class="cf nestable-lists" style="border:none;padding:0;">
                                <div class="dd" id="nestable">
                                    <ol class="dd-list">

                                        {!! $html !!}

                                    </ol>
                                </div>

                            </div>

                            <textarea id="nestable-output" name="menu_order" style="display:none;"></textarea>

                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary draft_button">Update</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Pages</h3>
                        </div>

                        <div class="card-body">

                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-secondary">Add to menu</button>
                        </div>
                    </div>
                </div>

        </form>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('optional-styles')
    <link rel="stylesheet" href="{{ asset('admin-resources/css/jquery.nestable.min.css') }}">
    <style>
        .dd3-edit{
            position: absolute;
            margin: 0;
            right: 0;
            top: 0;
            cursor: pointer;
            width: 30px;
            text-indent: 100%;
            white-space: nowrap;
            overflow: hidden;
            border: 1px solid #aaa;
            background: -webkit-linear-gradient(top,#ddd 0,#bbb 100%);
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .dd3-edit:before{
            content: "✐";
            display: block;
            position: absolute;
            left: 0;
            top: 3px;
            width: 100%;
            text-align: center;
            text-indent: 0;
            color: #fff;
            font-size: 20px;
            font-weight: 400;
        }
    </style>
@endpush

@push('optional-scripts')
    <script src="{{ asset('admin-resources/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-resources/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('admin-resources/js/jquery.nestable.js') }}"></script>

    <script>
        $(document).ready(function(){

            $('#update-menus-form').validate({
                errorElement: 'span',
                errorClass: "my-error-class",
                validClass: "my-valid-class",
                rules:{
                    name: {
                        required: true,
                        maxlength: 255
                    }
                }
            });

            var updateOutput = function(e)
            {
                var list   = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };

            // activate Nestable for list 1
            $('#nestable').nestable({
                group: 1
            }).on('change', updateOutput);

            // activate Nestable for list 2
            $('#nestable2').nestable({
                group: 1
            }).on('change', updateOutput);

            // output initial serialised data
            updateOutput($('#nestable').data('output', $('#nestable-output')));

            $('#nestable-menu').on('click', function(e)
            {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

            $('#nestable').nestable();

            // edit modal
            $('.dd3-edit').on('click', function () {
                var test = $(this).closest('li').attr('data-id');
                $('#myModal').modal('toggle');
            })

        });
    </script>

@endpush
