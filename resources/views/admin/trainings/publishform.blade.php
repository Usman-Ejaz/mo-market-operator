<div class="card-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Start Date & Time <span class="text-danger">*</span></label>
				<div class="input-group">
					<input type="text" class="form-control bg-white" id="start_date" name="start_date" value="{{ old('start_date') ?? $training->start_date }}" placeholder="{{ config('settings.datetime_placeholder') }}" readonly>
					<div class="input-group-append">
						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
				<span class="form-text text-danger">{{ $errors->first('start_date') }} </span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="endtime">End Date & Time <span class="text-danger">*</span></label>
				<div class="input-group">
					<input type="text" class="form-control bg-white" id="end_date" name="end_date" value="{{ old('end_date') ?? $training->end_date }}" placeholder="{{ config('settings.datetime_placeholder') }}" readonly>
					<div class="input-group-append">
						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
				<span class="form-text text-danger">{{ $errors->first('end_date') }} </span>
			</div>
		</div>
	</div>

	<div class="row">

		@if( $training->created_at )
		<div class="col-md-12">
			<label for="endtime">Created At:</label>
			<span>{{$training->created_at}}</span>
		</div>
		@endif

		@if(\Route::current()->getName() == 'admin.trainings.edit' )
		<div class="col-md-12">
			<label for="status">Status:</label>
			<span>{{ $training->status() }}</span>
		</div>
		@endif

	</div>

</div>