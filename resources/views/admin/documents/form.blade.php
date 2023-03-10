<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="input" class="form-control" autocomplete="off" id="title"
                    placeholder="Enter Document Title" name="title" value="{{ old('title') ?? $document->title }}">
                <span class="form-text text-danger">{{ $errors->first('title') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Category <span class="text-danger">*</span></label>
                <select class="custom-select" name="category_id" id="category_id">
                    <option value="">Please select a category</option>
                    @foreach ($categories as $category)
                        @if (old('category_id') == $category->id)
                            <option value="{{ $category->id }}" selected>
                                {{ $category->name }}
                            </option>
                            @include('admin.includes.subcategory', [
                                'subcategories' => $category->children,
                                'separator' => '--',
                            ])
                        @else
                            <option value="{{ $category->id }}"
                                {{ $category->id === $document->category_id ? 'selected' : '' }}>{{ $category->name }}
                            </option>

                            @include('admin.includes.subcategory', [
                                'subcategories' => $category->children,
                                'separator' => '--',
                            ])
                        @endif
                    @endforeach
                </select>
                <span class="form-text text-danger">{{ $errors->first('category') }} </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="keywords">Keywords</label>
                <input type="input" class="form-control" autocomplete="off" id="keywords"
                    placeholder="Enter keywords" name="keywords" value="{{ old('keywords') ?? $document->keywords }}"
                    data-role="tagsinput">
                <span class="form-text text-danger">{{ $errors->first('keywords') }} </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="file" class="form-label">Document File <span class="text-danger">*</span> <small>(Max
                        allowed size is {{ config('settings.maxDocumentSize') / 1000 }}MB. Allowed types are doc, docx,
                        txt,
                        ppt, pptx, csv, xls, xlsx, pdf,
                        odt)</small> </label>
                <input class="form-control" type="file" id="file" name="file[]"
                    onchange="resetConvertCheckbox(event)" multiple>
                <span class="form-text text-danger">{{ $errors->first('file.*') }} </span>
                @if (isset($document->file) && !empty($document->file))
                    @foreach ($document->file as $key => $path)
                        <small class="fileExists">
                            <p>
                                Open Attachment Of -
                                <a href="{{ route('admin.attachment.download', ['documents', $path]) }}"
                                    target="_blank"> {{ getFileOriginalName($path) }} </a>
                                <span class="btn-sm btn-danger float-right remove-file" data-path="{{ $path }}"
                                    id="{{ $key }}-filebutton" title="Delete File"><i
                                        class="fa fa-trash"></i></span>
                            </p>
                        </small>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="convert" name="convert" value="1"
                        onchange="validateFileExtension(event)">
                    <label class="form-check-label" for="convert"> Convert File To PDF <small>(Allowed conversion types
                            are doc, docx, txt, ppt, pptx, odt)</small></label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="image" class="form-label">Document Image <span class="text-danger">*</span> <small>(Max
                        allowed size is {{ config('settings.maxImageSize') / 1000 }}MB. Allowed types are
                        {{ str_replace('|', ',', config('settings.image_file_extensions')) }}. Recommended Image
                        dimensions are 400 x 400)</small> </label>
                <input class="form-control" type="file" id="image" name="image"
                    onchange="handleFileChoose(event)">
                <span class="form-text text-danger">{{ $errors->first('image') }} </span>
                @if (isset($document->image) && !empty($document->image))
                    <small class="imageExists">
                        <a href="{{ $document->image }}" target="_blank">
                            <img src="{{ $document->image }}" target="_blank" class="img-thumbnail"
                                style="height: 200px;" />
                        </a>
                        <span class="btn-sm btn-danger float-right" id="deleteImage" title="Delete Image"><i
                                class="fa fa-trash"></i></span>
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->

@csrf
