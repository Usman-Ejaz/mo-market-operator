<div class="card-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Start Date & Time <span class="text-danger">*</span></label>
				<div class="input-group">
					<input type="text" class="form-control bg-white" id="start_datetime" name="start_datetime" value="{{ old('start_datetime') ?? $training->start_datetime }}" placeholder="{{ config('settings.datetime_placeholder') }}" readonly>
					<input type="hidden" name="start_date" id="start_date" value="{{ old('start_datetime') ?? parseDate($training->start_datetime) }}">
					<div class="input-group-append">
						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
				<span class="form-text text-danger">{{ $errors->first('start_datetime') }} </span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="endtime">End Date & Time <span class="text-danger">*</span></label>
				<div class="input-group">
					<input type="text" class="form-control bg-white" id="end_datetime" name="end_datetime" value="{{ old('end_datetime') ?? $training->end_datetime }}" placeholder="{{ config('settings.datetime_placeholder') }}" readonly>
					<input type="hidden" name="end_date" id="end_date" value="{{ old('end_datetime') ?? parseDate($training->end_datetime) }}">
					<div class="input-group-append">
						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
				<span class="form-text text-danger">{{ $errors->first('end_datetime') }} </span>
			</div>
		</div>
	</div>

	<div class="row">

		@if ($training->created_at)
			<div class="col-md-12">
				<label for="endtime">Created At:</label>
				<span>{{ $training->created_at }}</span>
			</div>
		@endif

		@if (\Route::is('admin.trainings.edit'))
			<div class="col-md-12">
				<label for="status">Status:</label>
				<span>{{ $training->status() }}</span>
			</div>
		@endif

	</div>

</div>
