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

                            @if($pages->count() > 0)
                                <input type="text" name="search-pages" id="page-search" class="form-control mb-3 search-box" placeholder="Search Page">
                                <ul id="pages" class="page-list">
                                @foreach($pages as $page)
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="pages[{{ $page->id }}]" value="" data-page="{{ $page->id }}" data-title="{{ $page->title }}" data-slug="{{ $page->slug }}">
                                                {{ \Illuminate\Support\Str::limit($page->title, 35, $end='...') }}
                                                <a href="{{ route('admin.pages.edit', $page->id) }}" target="_blank"> <i class="fa fa-link"></i></a>
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
                                                <input type="checkbox" name="postCategories[{{ $id }}]" value="" data-slug="{{ \Illuminate\Support\Str::plural(strtolower($title)) }}" data-post="{{ $id }}" data-title="{{ $title }}">
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
                                                <input type="checkbox" name="documentCategories[{{ $id }}]" value="" data-slug="{{ \Illuminate\Support\Str::plural(str_slug($name)) }}" data-doc="{{ $id }}" data-title="{{ $name }}">
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

        <!-- New Modal -->
        <div class="modal fade" id="addNewSubmenuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="POST" id="create-submenus-form">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="submenu_modal_heading"></h5>
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
                                </div>
                                <div id="newPage" style="display:none;">
                                    <label for="usr">Page:</label>
                                    <select class="form-control" id="newMenuPage" name="submenu_page">
                                        @foreach($pages as $page)
                                            <option value="{{ $page->id }}" data-page-slug="{{ $page->slug }}">{{ $page->id }} - {{ truncateWords($page->title, 35) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="newCurrentMenuId" value="" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" id="deleteButton">Delete</button>
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

            let isEditing = false;

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
                maxDepth: 10
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
                    $('ol#submenu').append(`<li class="dd-item dd3-item" data-id="${lastSubMenuId}" data-page="${$(this).data('page')}" data-title="${$(this).data('title')}" data-slug="${$(this).data('slug')}">
                        <div class="dd-handle dd3-handle"></div><div class="dd3-content">
                        ${lastSubMenuId} ( page ) ${$(this).data('title')}
                        </div><div class="dd3-edit"><i class="fa fa-trash"></i></div>
                        </li>`
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
                        <li class="dd-item dd3-item" data-id="${lastSubMenuId}" data-post="${$(this).data('post')}" data-title="${$(this).data('title')}" data-slug="${$(this).data('slug')}">
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
                        <li class="dd-item dd3-item" data-id="${lastSubMenuId}" data-doc="${$(this).data('doc')}" data-title="${$(this).data('title')}" data-slug="${$(this).data('slug')}">
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
                $('#newCurrentMenuId').val(currentMenuId);
                $('#submenu_modal_heading').text("Add new Submenu");
                $('#deleteButton').hide();
                clearErrors();
                isEditing = false;
                $('#addNewSubmenuModal').modal('toggle');
            });

            // On type change
            $('input[name="newMenuType"]').on('change', function() {
                if( $('input[name="newMenuType"]:checked').val() == 'anchor') {
                    $('#newAnchor').show();
                    $('#newPage').hide();
                } else if( $('input[name="newMenuType"]:checked').val() == 'page') {
                    $('#newPage').show();
                    $('#newAnchor').hide();
                }
                clearErrors();
            });

            /************** Edit Modal Features **************/
            // Show modal
            $('body').on('click','.dd3-edit', function () {
                // reset all fields of modal
                $("#addNewSubmenuModal").find("input[type='text'],select").val("");
                $("#addNewSubmenuModal input[name=newMenuType][value='anchor']").prop("checked", true);

                // set hidden field value
                let currentMenuId = $(this).closest('li').data('id');
                $('#newCurrentMenuId').val(currentMenuId);

                // set values
                let title = $(`li[data-id="${currentMenuId}"`).attr('data-title');
                let page = $(`li[data-id="${currentMenuId}"`).attr('data-page');
                let anchor = $(`li[data-id="${currentMenuId}"`).attr('data-anchor');

                $("#addNewSubmenuModal").find("#NewMenuTitle").val(title);
                
                if (page != undefined) {
                    $("#addNewSubmenuModal input[name=newMenuType][value='page']").prop("checked",true).trigger('change');
                    $("#addNewSubmenuModal #newMenuPage").val(page);
                } else if (anchor) {
                    $("#addNewSubmenuModal input[name=newMenuType][value='anchor']").prop("checked",true).trigger('change');
                    $("#addNewSubmenuModal #newMenuAnchor").val(anchor);
                }

                $('#submenu_modal_heading').text("Update Submenu");
                $('#deleteButton').show();
                clearErrors();
                isEditing = true;
                $('#addNewSubmenuModal').modal('toggle');
            });

            // Delete submenu
            $('#deleteButton').click(function(){
                let menuIdToDelete = $('#newCurrentMenuId').val();
                $(`li[data-id="${menuIdToDelete}"]`).remove();
                $('#addNewSubmenuModal').modal('toggle');
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

            function clearErrors() {
                $('.my-error-class').remove();
            }

            $('#create-submenus-form').submit((e) => {
                e.preventDefault();

                if (!validateFields()) return;

                if (isEditing) {
                    let menuIdToUpdate = $('#newCurrentMenuId').val();

                    // Set title
                    let title = $('#NewMenuTitle').val();
                    $(`li[data-id="${menuIdToUpdate}"]`).attr('data-title', title);
                    //$("li[data-id='" + menuIdToUpdate +"']").find('.dd3-content').text();

                    // Check menuType
                    let menuType = $('input[name="newMenuType"]:checked').val();
                    if (menuType === 'anchor') {
                        let anchor = $("#newMenuAnchor").val();
                        let title = $('#NewMenuTitle').val();

                        $(`li[data-id="${menuIdToUpdate}"]`).removeAttr('data-page').attr('data-anchor', anchor);
                        $(`li[data-id="${menuIdToUpdate}"] > .dd3-content`).html(menuIdToUpdate + ' (anchor) ' + title);

                    } else if (menuType === 'page') {
                        let page = $("#newMenuPage").val();
                        let pageSlug = $('#newMenuPage').find(":selected").data('page-slug');
                        let title = $('#NewMenuTitle').val();
                        page = (page !== "") ? page : '';
                        $(`li[data-id="${menuIdToUpdate}"]`).removeAttr('data-anchor').attr('data-page', page).attr('data-slug', pageSlug);
                        $(`li[data-id="${menuIdToUpdate}"] > .dd3-content`).html(menuIdToUpdate + ' (page) ' + title);
                    }

                    $(`li[data-id="${menuIdToUpdate}"]`).clone().insertBefore(`li[data-id="${menuIdToUpdate}"]`);
                    $(`li[data-id="${menuIdToUpdate}"]`).eq(1).remove();

                    $('#addNewSubmenuModal').modal('toggle');
                    $('#nestable').trigger('change');
                    return;
                }

                lastSubMenuId = lastSubMenuId + 1;
                let menuIdToUpdate = $('#newCurrentMenuId').val();

                // Set title
                let title = '';
                if ($('#NewMenuTitle').val() !== '') {
                    title = $('#NewMenuTitle').val();
                    $(`li[data-id="${menuIdToUpdate}"]`).attr('data-title', title);
                    $(`li[data-id="${menuIdToUpdate}"]`).find('.dd3-content').text();
                }

                // Check menuType
                let menuType = $('input[name="newMenuType"]:checked').val();
                let attributes = '';
                let html = '';
                if (menuType === 'anchor') {
                    let anchor = $("#newMenuAnchor").val();
                    attributes = `data-anchor="${anchor}" data-title="${title}"`;
                    html = lastSubMenuId + ' ( anchor ) ' + title;
                } else if(menuType === 'page') {
                    let page = $("#newMenuPage").val();
                    let pageSlug = $('#newMenuPage').find(":selected").data('page-slug');
                    page = (page !== "") ? page : '';
                    attributes = `data-page="${page}" data-title="${title}" data-slug="${pageSlug}"`;
                    html = lastSubMenuId + ' ( page ) ' + title;
                }

                $('ol#submenu').append(`
                    <li class="dd-item dd3-item" data-id="${lastSubMenuId}" ${attributes}>
                        <div class="dd-handle dd3-handle"></div>
                        <div class="dd3-content">${html}</div>
                        <div class="dd3-edit"><i class="fa fa-trash"></i></div>
                    </li>`
                );

                $('#addNewSubmenuModal').modal('toggle');
                $('#nestable').trigger('change');
            });

            function validateFields() {
                let hasTitleError = hasURLError = hasPageError = false;

                if ($('#NewMenuTitle').val() === "") {
                    if (! ($('#NewMenuTitle').next().hasClass("my-error-class"))) {
                        $('#NewMenuTitle').after(`<span class="my-error-class">This field is required.</span>`);
                    }
                    hasTitleError = true;
                }
                let val = $('input[name="newMenuType"]:checked').val();
                
                if (val === "anchor") {
                    let value = $('#newMenuAnchor').val();
                    if (value === "") {
                        if (! ($('#newMenuAnchor').next().hasClass("my-error-class"))) {
                            $('#newMenuAnchor').after(`<span class="my-error-class">This field is required.</span>`);
                        }
                        hasURLError = true;
                    } else {
                        let regex = /^(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/;
                        if (value === '#') {
                            // do nothing
                        } else if(regex.test(value)) {
                            $('#addNewSubmenuModal #newUrlError').hide();
                        } else {
                            if (! ($('#newMenuAnchor').next().hasClass("my-error-class"))) {
                                $('#newMenuAnchor').after(`<span class="my-error-class">Please enter a valid URL.</span>`);
                                hasURLError = true;
                            }
                        }
                    }
                } else {
                    let selectedPage = $('#newMenuPage').find(":selected").text();

                    if (selectedPage.trim() === "") {
                        if (! ($('#newMenuPage').next().hasClass("my-error-class"))) {
                            $('#newMenuPage').after(`<span class="my-error-class">This field is required.</span>`);
                        }
                        hasPageError = true;
                    }
                }

                return !(hasTitleError || hasURLError || hasPageError);
            }
        });
    </script>

@endpush
