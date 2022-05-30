<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="question">Question <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="question" placeholder="Enter Question" name="question"
                    value="{{ old('question') ?? $knowledge_base->question }}" autocomplete="off">
                <span class="form-text text-danger">{{ $errors->first('question') }} </span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="answer">Answer <span class="text-danger">*</span></label>
        <textarea class="form-control ckeditor" id="answer" placeholder="Enter Answer" name="answer" rows="30"
            cols="50">{{ old('answer') ?? $knowledge_base->answer }}</textarea>
        <span class="form-text text-danger">{{ $errors->first('answer') }} </span>
    </div>

	<div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="keywords">Keywords <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="keywords" placeholder="Enter keywords" name="keywords"
                    value="{{ old('keywords') ?? $knowledge_base->keywords }}" data-role="tagsinput" autocomplete="off">
                <span class="form-text text-danger">{{ $errors->first('keywords') }} </span>
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->

@csrf
