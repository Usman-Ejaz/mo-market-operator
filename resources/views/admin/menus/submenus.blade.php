@extends('admin.layouts.app')
@section('header', 'Sub Menus')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></li>
    <li class="breadcrumb-item active">Sub Menus</li>
@endsection

@section('addButton')
    <button class="btn btn-primary float-right" id="addNewSubmenu" href="#">Add New Submenu</button>
@endsection

@section('content')
    <div class="container-fluid">

        <form method="POST" action="{{ route('admin.menus.submenusupdate', $menu->id) }}" enctype="multipart/form-data" id="update-menus-form">
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Menu - {{ $menu->name }}</h3>
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
                            <h3 class="card-title">CMS Pages</h3>
                        </div>

                        <div class="card-body">

                            @if( is_array($pages) && count($pages) )
                                <input type="text" name="search-pages" id="page-search" class="form-control mb-3 search-box" placeholder="Search Page">
                                <ul id="pages" class="page-list">
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Post Categories</h3>
                        </div>

                        <div class="card-body">

                            @if( is_array($postCategories) && count($postCategories) )
                                <input type="text" name="search-categories" id="post-categories" class="form-control mb-3 search-box" placeholder="Search Categories">
                                <ul id="pages" class="post-category-list">
                                @foreach($postCategories as $id => $title)
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="postCategories[{{ $id }}]" value="" data-post="{{ $id }}" data-title="{{ $title }}">
                                                {{ \Illuminate\Support\Str::limit($title, 35, $end='...') }}
                                                <!-- <a href="{{ route('admin.pages.edit', $id) }}" target="_blank"> <i class="fa fa-link"></i></a> -->
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-secondary" id="add_post_categories_to_menu">Add to menu</button>
                        </div>
                    </div>
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Document Categories</h3>
                        </div>

                        <div class="card-body">

                            @if( is_array($documentCategories) && count($documentCategories) )
                                <input type="text" name="search-categories" id="document-categories" class="form-control mb-3 search-box" placeholder="Search Categories">
                                <ul id="pages" class="document-category-list">
                                @foreach($documentCategories as $name => $id)
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="documentCategories[{{ $id }}]" value="" data-doc="{{ $id }}" data-title="{{ $name }}">
                                                {{ \Illuminate\Support\Str::limit($name, 35, $end='...') }}
                                                <a href="{{ route('admin.document-categories.edit', $id) }}" target="_blank"> <i class="fa fa-link"></i></a>
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-secondary" id="add_doc_categories_to_menu">Add to menu</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Edit Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="POST" id="update-submenus-form" onsubmit="return false;">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="exampleModalLabel">Update Submenu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="usr">Menu Title:</label>
                                <input type="text" class="form-control" id="MenuTitle" name="submenu_title">
                            </div>
                            <div class="type">
                                <label> <input type="radio" name="menuType" value="anchor" checked="checked"> Anchor </label>
                                <label style="margin-left:15px;"> <input type="radio" name="menuType" value="page"> Page </label>
                            </div>
                            <div class="form-group">
                                <div id="anchor">
                                    <label for="usr">Anchor:</label>
                                    <input type="text" class="form-control" id="menuAnchor" name="submenu_anchor">
                                </div>
                                <div id="page" style="display:none;">
                                    <label for="usr">Page:</label>
                                    <select class="form-control" id="menuPage" name="submenu_page">
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
                            <button type="submit" class="btn btn-primary" id="saveButton">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- New Modal -->
        <div class="modal fade" id="addNewSubmenuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="POST" id="create-submenus-form" onsubmit="return false;">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="exampleModalLabel">Add new Submenu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="usr">Menu Title:</label>
                                <input type="text" class="form-control" id="NewMenuTitle" name="submenu_title">
                            </div>
                            <div class="type">
                                <label> <input type="radio" name="newMenuType" value="anchor" checked="checked"> Anchor </label>
                                <label class="ml-2"> <input type="radio" name="newMenuType" value="page" id="menuTypeRadio"> Page </label>
                            </div>
                            <div class="form-group">
                                <div id="newAnchor">
                                    <label for="usr">Anchor:</label>
                                    <input type="text" class="form-control" id="newMenuAnchor" name="submenu_anchor">
                                    <span id="newUrlError" class="my-error-class" style="display: none;">Please provide a valid url</span>
                                </div>
                                <div id="newPage" style="display:none;">
                                    <label for="usr">Page:</label>
                                    <select class="form-control" id="newMenuPage" name="submenu_page">
                                        @foreach($pages as $id => $title)
                                            <option value="{{ $id }}">{{ $id }} - {{ \Illuminate\Support\Str::limit($title, 35, $end='...') }}</option>
                                        @endforeach
                                    </select>
                                    <span id="newUrlError" class="my-error-class" style="display: none;">Please provide a valid url</span>
                                </div>
                            </div>
                            <input type="hidden" id="newCurrentMenuId" value="" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="newSaveButton">Save changes</button>
                        </div>
                    </form>                    
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

            $.validator.addMethod("notNumericValues", function(value, element) {
                return this.optional(element) || isNaN(Number(value)) || value.indexOf('e') !== -1;
            }, '{{ __("messages.not_numeric") }}');

            $.validator.addMethod("validURL", function(value, element) {
                var pattern = /^(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/;
                return (this.optional(element) || value === "#" || pattern.test(value));
            }, 'Please enter a valid URL.');

            $('#update-menus-form').validate({
                errorElement: 'span',
                errorClass: "my-error-class",
                validClass: "my-valid-class",
                rules:{
                    name: {
                        required: true,
                        maxlength: 255,
                        notNumericValues: true,
                    }
                }
            });

            $('#create-submenus-form').validate({
                ignore: [],
                errorElement: 'span',
                errorClass: "my-error-class",
                validClass: "my-valid-class",
                rules: {
                    submenu_title: {
                        required: true,
                        minlength: 3,
                        maxlength: 255,
                        notNumericValues: true,
                    },
                    submenu_anchor: {
                        required: true,
                        validURL: true,
                    },
                    submenu_page: {
                        required: {
                            depends: function () {
                                return $('#menuTypeRadio').is(':checked');
                            }
                        }
                    }
                }
            })

            $('#update-submenus-form').validate({
                ignore: [],
                errorElement: 'span',
                errorClass: "my-error-class",
                validClass: "my-valid-class",
                rules: {
                    submenu_title: {
                        required: true,
                        minlength: 3,
                        maxlength: 255,
                        notNumericValues: true,
                    },
                    submenu_anchor: {
                        required: true,
                        validURL: true,
                    },
                    submenu_page: {
                        required: {
                            depends: function () {
                                return $('#menuTypeRadio').is(':checked');
                            }
                        }
                    }
                }
            })

            // Update json for submenu order
            var updateOutput = function(e)
            {
                var list = e.length ? e : $(e.target),
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
            $('#add_post_categories_to_menu').prop("disabled", true);
            $('#add_doc_categories_to_menu').prop("disabled", true);

            $(document).on('click', "input[name^='page']:checkbox", (function() {
                if ($(this).is(':checked')) {
                    $('#add_pages_to_menu').prop("disabled", false);
                } else if ( $("input[name^='page']:checkbox:checked").length < 1){
                    $('#add_pages_to_menu').attr('disabled',true);
                }
            }));

            $(document).on('click', "input[name^='postCategories']:checkbox", (function() {
                if ($(this).is(':checked')) {
                    $('#add_post_categories_to_menu').prop("disabled", false);
                } else if ( $("input[name^='postCategories']:checkbox:checked").length < 1){
                    $('#add_post_categories_to_menu').attr('disabled',true);
                }
            }));

            $(document).on('click', "input[name^='documentCategories']:checkbox", (function() {
                if ($(this).is(':checked')) {
                    $('#add_doc_categories_to_menu').prop("disabled", false);
                } else if ( $("input[name^='documentCategories']:checkbox:checked").length < 1){
                    $('#add_doc_categories_to_menu').attr('disabled',true);
                }
            }));

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
                    $('ol#submenu').append(`<li class="dd-item dd3-item" data-id="${lastSubMenuId}" data-page="${$(this).data('page')}" data-title="${$(this).data('title')}">` +
                        '<div class="dd-handle dd3-handle"></div><div class="dd3-content">' +
                        lastSubMenuId + ' ( page ) ' + $(this).data('title') +
                        '</div><div class="dd3-edit"><i class="fa fa-trash"></i></div>' +
                        '</li>'
                    );
                    $("input[name^='page']:checkbox:checked").prop('checked', false);
                    //$('#nestable').nestable();
                });
                $('#add_pages_to_menu').prop('disabled', true);
                $('#nestable').trigger('change');
            });

            // Add Post categories to menu functionality
            $('#add_post_categories_to_menu').click(function(){
                $("input[name^='postCategories']:checkbox:checked").each(function () {
                    lastSubMenuId = lastSubMenuId + 1;
                    $('ol#submenu').append(`
                        <li class="dd-item dd3-item" data-id="${lastSubMenuId}" data-post="${$(this).data('post')}" data-title="${$(this).data('title')}">
                            <div class="dd-handle dd3-handle"></div>
                            <div class="dd3-content">
                                ${lastSubMenuId} ${'('} post category ${')'} ${$(this).data('title')}
                            </div>
                            <div class="dd3-edit"><i class="fa fa-trash"></i></div>
                        </li>`
                    );
                    $("input[name^='postCategories']:checkbox:checked").prop('checked', false);
                    //$('#nestable').nestable();
                });
                $('#add_post_categories_to_menu').prop('disabled', true);
                $('#nestable').trigger('change');
            });

            // Add Document categories to menu functionality
            $('#add_doc_categories_to_menu').click(function(){
                $("input[name^='documentCategories']:checkbox:checked").each(function () {
                    lastSubMenuId = lastSubMenuId + 1;
                    $('ol#submenu').append(`
                        <li class="dd-item dd3-item" data-id="${lastSubMenuId}" data-doc="${$(this).data('doc')}" data-title="${$(this).data('title')}">
                            <div class="dd-handle dd3-handle"></div>
                            <div class="dd3-content">
                                ${lastSubMenuId} ${'('} document category ${')'} ${$(this).data('title')}
                            </div>
                            <div class="dd3-edit"><i class="fa fa-trash"></i></div>
                        </li>`
                    );
                    $("input[name^='documentCategories']:checkbox:checked").prop('checked', false);
                    //$('#nestable').nestable();
                });
                $('#add_doc_categories_to_menu').prop('disabled', true);
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
            $('#addNewSubmenuModal #newUrlError #newTitleError').hide();
            $('body').on('click','#newSaveButton',function(){
                lastSubMenuId = lastSubMenuId + 1;
                let menuIdToUpdate = $('#newCurrentMenuId').val();

                // Set title
                let title = $('#NewMenuTitle').val();
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
                    html = lastSubMenuId + ' ( anchor ) ' + title;

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
                    if (anchor === '#') {
                        // do nothing, just bypass for # sign, don't check any URL validation
                    } else if( regex.test(anchor)){
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

            $('.search-box').on('keyup', debounce((e) => {
                let targetId = '';
                switch(e.target.id) {
                    case 'page-search':
                        targetId = 'page-list';
                        break;
                    case 'document-categories':
                        searchModel = '';
                        targetId = 'document-category-list';
                        break;
                    case 'post-categories':
                        targetId = 'post-category-list';
                        break;
                }

                jQuery.post('{{ route("admin.menus.search") }}', {
                    id: (e.target.id).trim(),
                    searchKey: (e.target.value).trim(),
                    _token: "{{ csrf_token() }}",
                }, function (data, status) {
                    if (status == 'success') {
                        jQuery(`.${targetId}`).html(data);
                    }
                });
            }, 200));

            function debounce(func, timeout = 300){
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => { func.apply(this, args); }, timeout);
                };
            }
        });
    </script>

@endpush
