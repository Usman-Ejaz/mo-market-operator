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
                <label for="name">Speed <span class="text-danger">*</span> <small> (Speed range 1000 - 10,000)</small></label>
                <input type="number" class="form-control" autocomplete="off" id="speed" placeholder="Enter Speed" name="speed" step="500" min="1000" max="10000" value="{{ old('speed') ?? $sliderSetting->speed }}">
                <span class="form-text text-danger">{{ $errors->first('speed') }} </span>
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->

@csrf
