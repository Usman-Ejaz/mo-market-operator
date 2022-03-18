<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="name" placeholder="Enter full name" name="name" value="{{ old('name') ?? $staticBlock->name }}">
                <span class="form-text text-danger">{{ $errors->first('name') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="contents">Contents <span class="text-danger">*</span></label>
                <textarea class="form-control ckeditor" id="contents" rows="40" cols="50" placeholder="Enter Block Contents" name="contents">
                    {{ old('contents') ?? $staticBlock->contents }}
                </textarea>
                <span class="form-text text-danger">{{ $errors->first('contents') }} </span>
            </div>
        </div>
    </div>

</div>
<!-- /.card-body -->

@csrf