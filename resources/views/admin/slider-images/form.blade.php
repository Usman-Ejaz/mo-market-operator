<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Block One <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="block_one" placeholder="Enter Block One" name="block_one" value="{{ old('block_one') ?? $sliderImage->block_one }}">
                <span class="form-text text-danger">{{ $errors->first('block_one') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Block Two <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="block_two" placeholder="Enter Block Two" name="block_two" value="{{ old('block_two') ?? $sliderImage->block_two }}">
                <span class="form-text text-danger">{{ $errors->first('block_two') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">URL <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="url" placeholder="Enter URL" name="url" value="{{ old('url') ?? $sliderImage->url }}">
                <span class="form-text text-danger">{{ $errors->first('url') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Order <span class="text-danger">*</span></label>
                <input type="input" class="form-control" id="order" placeholder="Enter Order" name="order" value="{{ old('order') ?? $sliderImage->order }}">
                <span class="form-text text-danger">{{ $errors->first('order') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="slider_image" class="form-label">Slider Image <span class="text-danger">*</span><small> (Allowed max size is 2MB. Allowed types are jpg, jpeg, png)</small></label>
                <input class="form-control" type="file" id="slider_image" name="image">
                <span class="form-text text-danger">{{ $errors->first('image') }} </span>
                @if (isset($sliderImage->image))
                    <small class="text-primary imageExists">
                        <a href="{{ $sliderImage->image }}" target="_blank">
                            <img src="{{ $sliderImage->image }}" target="_blank" class="img-thumbnail" style="width: 23%;">
                        </a>
                        <span class="btn-sm btn-danger float-right" id="deleteImage"><i class="fa fa-trash"></i></span> 
                    </small>
                @endif
            </div>
        </div>        
    </div>
</div>
<!-- /.card-body -->

@csrf
