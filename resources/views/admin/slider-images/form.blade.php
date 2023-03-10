<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Slot One <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="slot_one" autocomplete="off" placeholder="Enter Slot One" name="slot_one" value="{{ old('slot_one') ?? $sliderImage->slot_one }}">
                <span class="form-text text-danger">{{ $errors->first('slot_one') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Slot Two <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="slot_two" autocomplete="off" placeholder="Enter Slot Two" name="slot_two" value="{{ old('slot_two') ?? $sliderImage->slot_two }}">
                <span class="form-text text-danger">{{ $errors->first('slot_two') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">URL <span class="text-danger">*</span> <small>Please enter the URL after '{{ config('settings.client_app_base_url') }}'</small></label>
                <input type="text" class="form-control" id="url" autocomplete="off" placeholder="Enter URL" name="url" value="{{ old('url') ?? $sliderImage->url }}">
                <span class="form-text text-danger">{{ $errors->first('url') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Order <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="order" autocomplete="off" placeholder="Enter Order" name="order" value="{{ old('order') ?? $sliderImage->order }}">
                <span class="form-text text-danger">{{ $errors->first('order') }} </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="slider_image" class="form-label">Slider Image <span class="text-danger">*</span><small> (Allowed max size is 8MB. Allowed types are {{ str_replace("|", ", ", config('settings.image_file_extensions')) }}. Recommended Image dimensions are 1920 x 950)</small></label>
                <input class="form-control" type="file" id="slider_image" name="image" onchange="handleFileChoose(event)">
                <span class="form-text text-danger">{{ $errors->first('image') }} </span>
                @if (isset($sliderImage->image))
                    <small class="text-primary imageExists">
                        <a href="{{ $sliderImage->image }}" target="_blank">
                            <img src="{{ $sliderImage->image }}" target="_blank" class="img-thumbnail" style="height: 200px;">
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
