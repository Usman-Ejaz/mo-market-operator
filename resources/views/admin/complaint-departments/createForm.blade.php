<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="name">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            <span class="form-text text-danger">{{ $errors->first('name') }}</span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="pm_id ">Select a PM <span class="text-danger">*</span></label>
            <input class="form-control user-autocomplete" name="pm_id" type="text" data-role="tagsinput" required>
            <span class="form-text text-danger">{{ $errors->first('pm_id') }} </span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="apm_id ">Select an APM <span class="text-danger">*</span></label>
            <input class="form-control user-autocomplete" name="apm_id" type="text" data-role="tagsinput" required>
            <span class="form-text text-danger">{{ $errors->first('apm_id') }} </span>
        </div>
    </div>

    <div class="col-md-12">
        <button type="submit" class="btn btn-success btn-block">Add</button>
    </div>
</div>

@push('optional-scripts')
    <script src="{{ asset('admin-resources/js/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('admin-resources/js/typeahead.bundle.js') }}"></script>

    <script type="text/javascript">
        //get data pass to json
        var task = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: "{{ route('admin.complaint-departments.getUserInfo') }}?search=%QUERY",
                wildcard: '%QUERY'
            },
            identify: function(item) {
                return item.id;
            },
        });

        task.initialize();

        var elt = $(".user-autocomplete");
        elt.tagsinput({
            maxTags: 1,
            itemValue: "id",
            itemText: function(item) {
                return `${item.name} - ${item.designation}`;
            },
            typeaheadjs: {
                name: "users",
                display: function(item) {
                    return `${item.name}(${item.email}) - ${item.designation}`;
                },
                source: task.ttAdapter(),
                templates: {
                    empty: [
                        '<div class="empty-message">',
                        'No such user found.',
                        '</div>'
                    ].join('\n'),
                },

            }
        });
    </script>
@endpush

@push('optional-styles')
    <link rel="stylesheet" href="{{ asset('admin-resources/css/bootstrap-tagsinput.css') }}" />
    {{-- Extra css for tags input bootstrap --}}
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

    {{-- CSS to make typeahead plugin work with bootstrap 4 --}}
    <style type="text/css">
        span.twitter-typeahead .tt-menu {
            cursor: pointer;
        }

        .dropdown-menu,
        span.twitter-typeahead .tt-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            font-size: 1rem;
            color: #373a3c;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 0.25rem;
        }

        span.twitter-typeahead .tt-suggestion {
            display: block;
            width: 100%;
            padding: 3px 20px;
            clear: both;
            font-weight: normal;
            line-height: 1.5;
            color: #373a3c;
            text-align: inherit;
            white-space: nowrap;
            background: none;
            border: 0;
        }

        span.twitter-typeahead .tt-suggestion:focus,
        .dropdown-item:hover,
        span.twitter-typeahead .tt-suggestion:hover {
            color: #2b2d2f;
            text-decoration: none;
            background-color: #f5f5f5;
        }

        span.twitter-typeahead .active.tt-suggestion,
        span.twitter-typeahead .tt-suggestion.tt-cursor,
        span.twitter-typeahead .active.tt-suggestion:focus,
        span.twitter-typeahead .tt-suggestion.tt-cursor:focus,
        span.twitter-typeahead .active.tt-suggestion:hover,
        span.twitter-typeahead .tt-suggestion.tt-cursor:hover {
            color: #fff;
            text-decoration: none;
            background-color: #0275d8;
            outline: 0;
        }

        span.twitter-typeahead .disabled.tt-suggestion,
        span.twitter-typeahead .disabled.tt-suggestion:focus,
        span.twitter-typeahead .disabled.tt-suggestion:hover {
            color: #818a91;
        }

        span.twitter-typeahead .disabled.tt-suggestion:focus,
        span.twitter-typeahead .disabled.tt-suggestion:hover {
            text-decoration: none;
            cursor: not-allowed;
            background-color: transparent;
            background-image: none;
            filter: "progid:DXImageTransform.Microsoft.gradient(enabled = false)";
        }

        span.twitter-typeahead {
            width: 100%;
        }

        .input-group span.twitter-typeahead {
            display: block !important;
        }

        .input-group span.twitter-typeahead .tt-menu {
            top: 2.375rem !important;
        }
    </style>
@endpush
