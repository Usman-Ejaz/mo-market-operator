<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="contents">Contents <span class="text-danger">*</span></label>
                <textarea class="form-control" id="contents" rows="40" cols="50" placeholder="Enter Block Contents" name="contents">
                    {{ old('contents') ?? $static_block->contents }}
                </textarea>
                <span class="form-text text-danger">{{ $errors->first('contents') }} </span>
            </div>
        </div>
    </div>

</div>
<!-- /.card-body -->

@csrf
