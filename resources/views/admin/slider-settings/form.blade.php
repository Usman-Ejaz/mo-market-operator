<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="transition">Transition <span class="text-danger">*</span></label>
                <select class="custom-select" name="transition" id="transition">
					<option value="">Please select an option</option>
					@foreach(\App\Models\SliderSetting::TRANSITIONS as $key => $transition)
						@if(old('transition') == $key)
							<option value="{{ $key }}" data-name="{{ $transition['name'] }}" selected> {{ $transition['label'] }} </option>
						@else
							<option value="{{ $key }}" data-name="{{ $transition['name'] }}"  {{ ( isset($sliderSetting->transition) && $sliderSetting->transition == $key) ? 'selected' : '' }}> {{ $transition['label'] }} </option>
						@endif
					@endforeach
				</select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Speed <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="speed" placeholder="Enter Speed" name="speed" value="{{ old('speed') ?? $sliderSetting->speed }}">
                <span class="form-text text-danger">{{ $errors->first('speed') }} </span>
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->

@csrf
