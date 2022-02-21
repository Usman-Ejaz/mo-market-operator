@extends('admin.layouts.app')
@section('header', 'Sub Menus')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Main Menus</a></li>
    <li class="breadcrumb-item active">Sub Menus</li>
@endsection

@section('addButton')
    @if( Auth::user()->role->hasPermission('news', 'create') )
        <button class="btn btn-primary float-right" id="addNewSubmenu" href="#">Add New Submenu</button>
    @endif
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
                                    <ol class="dd-list" id="submenu">
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

                            @if( is_array($pages) && count($pages) )
                                <ul id="pages">
                                @foreach($pages as $id => $title)
                                        <li>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="pages[{{ $id }}]" value="" data-page="{{ $id }}" data-title="{{ $title }}">
                                                    {{ \Illuminate\Support\Str::limit($title, 35, $end='...') }}
                                                    <a href="{{ route('admin.pages.edit', $id) }}" target="_blank"> <i class="fa fa-link"></i></a>
                                                </label>
                                            </div>
                                        </li>
                                @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-secondary" id="add_pages_to_menu">Add to menu</button>
                        </div>
                    </div>
                </div>

        </form>

        <!-- Edit Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Update Submenu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="usr">Menu Title:</label>
                            <input type="text" class="form-control" id="MenuTitle">
                        </div>
                        <div class="type">
                            <label> <input type="radio" name="menuType" value="anchor" checked="checked"> Anchor </label>
                            <label style="margin-left:15px;"> <input type="radio" name="menuType" value="page"> Page </label>
                        </div>
                        <div class="form-group">
                            <div id="anchor">
                                <label for="usr">Anchor:</label>
                                <input type="text" class="form-control" id="menuAnchor">
                                <span id="editUrlError" class="error invalid-feedback">Please provide a valid url</span>
                            </div>
                            <div id="page" style="display:none;">
                                <label for="usr">Page:</label>
                                <select class="form-control" id="menuPage">
                                    @foreach($pages as $id => $title)
                                        <option value="{{ $id }}">{{ $id }} - {{ \Illuminate\Support\Str::limit($title, 35, $end='...') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="currentMenuId" value="" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="deleteButton">Delete</button>
                        <button type="button" class="btn btn-primary" id="saveButton">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Modal -->
        <div class="modal fade" id="addNewSubmenuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Add new Submenu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="usr">Menu Title:</label>
                            <input type="text" class="form-control" id="NewMenuTitle">
                        </div>
                        <div class="type">
                            <label> <input type="radio" name="newMenuType" value="anchor" checked="checked"> Anchor </label>
                            <label style="margin-left:15px;"> <input type="radio" name="newMenuType" value="page"> Page </label>
                        </div>
                        <div class="form-group">
                            <div id="newAnchor">
                                <label for="usr">Anchor:</label>
                                <input type="text" class="form-control" id="newMenuAnchor">
                                <span id="newUrlError" class="error invalid-feedback">Please provide a valid url</span>
                            </div>
                            <div id="newPage" style="display:none;">
                                <label for="usr">Page:</label>
                                <select class="form-control" id="newMenuPage">
                                    @foreach($pages as $id => $title)
                                        <option value="{{ $id }}">{{ $id }} - {{ \Illuminate\Support\Str::limit($title, 35, $end='...') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="newCurrentMenuId" value="" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="newSaveButton">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('optional-styles')
    <link rel="stylesheet" href="{{ asset('admin-resources/css/jquery.nestable.min.css') }}">
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

            // Update json for submenu order
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

            // activate Nestable for list
            $('#nestable').nestable({
                group: 1,
                maxDepth:10
            }).on('change', updateOutput);

            // output initial serialised data
            updateOutput($('#nestable').data('output', $('#nestable-output')));

            // Activate button based on page checkboxes
            $('#add_pages_to_menu').prop("disabled", true);
            $("input[name^='page']:checkbox").click(function() {
                if ($(this).is(':checked')) {
                    $('#add_pages_to_menu').prop("disabled", false);
                } else if ( $("input[name^='page']:checkbox:checked").length < 1){
                    $('#add_pages_to_menu').attr('disabled',true);
                }
            });

            // Stop form to submit by clicking the enter button
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            // This keeps track of the last id, which is incremented to create new sub menu
            let lastSubMenuId = @if( $lastSubMenuId ) {{ $lastSubMenuId }} @else 0 @endif;

            // Add pages to menu functionality
            $('#add_pages_to_menu').click(function(){
                $("input[name^='page']:checkbox:checked").each(function () {
                    lastSubMenuId = lastSubMenuId + 1;
                    $('ol#submenu').append('<li class="dd-item dd3-item" data-id="'+ lastSubMenuId +'" data-page="'+$(this).data('page')+'" data-title="'+$(this).data('title')+'">' +
                        '<div class="dd-handle dd3-handle"></div><div class="dd3-content">' +
                            ' ( page ) ' + $(this).data('title') +
                        '</div><div class="dd3-edit"><i class="fa fa-trash"></i></div>' +
                        '</li>'
                    );
                    $("input[name^='page']:checkbox:checked").prop('checked', false);
                    //$('#nestable').nestable();
                });
                $('#add_pages_to_menu').prop('disabled', true);
                $('#nestable').trigger('change');
            });

            /************** Add Modal Features **************/
            // Show modal
            $('body').on('click','#addNewSubmenu', function () {
                // reset all fields of modal
                $("#addNewSubmenuModal").find("input[type='text'],select").val("");
                $("#addNewSubmenuModal input[name=menuType][value='anchor']").prop("checked",true);

                // set hidden field value
                let currentMenuId = $(this).closest('li').data('id');
                $('#newCurrentMenuId').val( currentMenuId );
                $('#addNewSubmenuModal').modal('toggle');
            });

            // On type change
            $('input[name="newMenuType"]').on('change', function(){
                if( $('input[name="newMenuType"]:checked').val() == 'anchor' ){
                    $('#newAnchor').show();
                    $('#newPage').hide();
                } else if( $('input[name="newMenuType"]:checked').val() == 'page' ){
                    $('#newPage').show();
                    $('#newAnchor').hide();
                }
            });

            // On save modal
            $('#addNewSubmenuModal #newUrlError').hide();
            $('body').on('click','#newSaveButton',function(){
                lastSubMenuId = lastSubMenuId + 1;
                let menuIdToUpdate = $('#newCurrentMenuId').val();

                // Set title
                let title = '';
                if( $('#NewMenuTitle').val() != '' ) {
                    title = $('#NewMenuTitle').val();
                    $("li[data-id='" + menuIdToUpdate +"']").attr( 'data-title', title );
                    $("li[data-id='" + menuIdToUpdate +"']").find('.dd3-content').text();
                }

                // Check menuType
                let menuType = $('input[name="newMenuType"]:checked').val();
                let attributes = '';
                let html = '';
                if(menuType == 'anchor'){
                    let anchor = $("#newMenuAnchor").val();
                    attributes = "data-anchor='"+anchor+"' data-title='"+title+"'";
                    html = lastSubMenuId + ' - ( anchor ) ' + title;

                    // check if valid url
                    let regex = /^(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/;
                    if( regex.test(anchor)){
                        $('#addNewSubmenuModal #newUrlError').hide();
                    } else {
                        $('#addNewSubmenuModal #newUrlError').show();
                        return;
                    }

                } else if(menuType == 'page'){
                    let page = $("#newMenuPage").val();
                    page = (page != "") ? page : '';
                    attributes = "data-page='"+page+"' data-title='"+title+"'";
                    html = lastSubMenuId + ' ( page ) ' + title;
                }

                $('ol#submenu').append('<li class="dd-item dd3-item" data-id="'+ lastSubMenuId +'" '+attributes+'>' +
                    '<div class="dd-handle dd3-handle"></div><div class="dd3-content">' + html +
                    '</div><div class="dd3-edit"><i class="fa fa-trash"></i></div>' +
                    '</li>'
                );

                $('#addNewSubmenuModal').modal('toggle');
                $('#nestable').trigger('change');
            });

            /************** Edit Modal Features **************/
            // Show modal
            $('body').on('click','.dd3-edit', function () {
                // reset all fields of modal
                $("#myModal").find("input[type='text'],select").val("");
                $("#myModal input[name=menuType][value='anchor']").prop("checked",true);

                // set hidden field value
                let currentMenuId = $(this).closest('li').data('id');
                $('#currentMenuId').val( currentMenuId );

                // set values
                let title = $('li[data-id="'+currentMenuId+'"').attr('data-title');
                let page = $('li[data-id="'+currentMenuId+'"').attr('data-page');
                let anchor = $('li[data-id="'+currentMenuId+'"').attr('data-anchor');

                $("#myModal").find("#MenuTitle").val(title);
                if(page != undefined){
                    $("#myModal input[name=menuType][value='page']").prop("checked",true).trigger('change');
                    $("#myModal #menuPage").val(page);
                } else if( anchor ){
                    $("#myModal input[name=menuType][value='anchor']").prop("checked",true).trigger('change');
                    $("#myModal #menuAnchor").val(anchor);
                }

                $('#myModal').modal('toggle');
            });

            // On type change
            $('input[name="menuType"]').on('change', function(){
                if( $('input[name="menuType"]:checked').val() == 'anchor' ){
                    $('#anchor').show();
                    $('#page').hide();
                } else if( $('input[name="menuType"]:checked').val() == 'page' ){
                    $('#page').show();
                    $('#anchor').hide();
                }
            });

            // Delete submenu
            $('#deleteButton').click(function(){
                let menuIdToDelete = $('#currentMenuId').val();
                $("li[data-id='" + menuIdToDelete +"']").remove();
                $('#myModal').modal('toggle');
                $('#nestable').trigger('change');
            });

            // On save modal
            $('#myModal #editUrlError').hide();
            $('body').on('click','#saveButton',function(){
                let menuIdToUpdate = $('#currentMenuId').val();

                // Set title
                let title = $('#MenuTitle').val();
                $("li[data-id='" + menuIdToUpdate +"']").attr( 'data-title', title );
                //$("li[data-id='" + menuIdToUpdate +"']").find('.dd3-content').text();

                // Check menuType
                let menuType = $('input[name="menuType"]:checked').val();
                if(menuType == 'anchor'){
                    let anchor = $("#menuAnchor").val();
                    let title = $('#MenuTitle').val();

                    // check if valid url
                    var regex = /^(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/;
                    if( regex.test(anchor)){
                        $('#myModal #editUrlError').hide();
                    } else {
                        $('#myModal #editUrlError').show();
                        return;
                    }

                    $("li[data-id='" + menuIdToUpdate +"']").removeAttr('data-page').attr('data-anchor', anchor);
                    $("li[data-id='" + menuIdToUpdate +"'] > .dd3-content").html(menuIdToUpdate + ' (anchor) ' + title);

                } else if(menuType == 'page'){
                    let page = $("#menuPage").val();
                    let title = $('#MenuTitle').val();
                    page = (page != "") ? page : '';
                    $("li[data-id='" + menuIdToUpdate +"']").removeAttr('data-anchor').attr('data-page', page);
                    $("li[data-id='" + menuIdToUpdate +"'] > .dd3-content").html(menuIdToUpdate + ' (page) ' + title);
                }

                $("li[data-id='" + menuIdToUpdate +"']").clone().insertBefore("li[data-id='" + menuIdToUpdate +"']");
                $("li[data-id='" + menuIdToUpdate +"']").eq(1).remove();

                $('#myModal').modal('toggle');
                $('#nestable').trigger('change');
            });

        });
    </script>

@endpush
