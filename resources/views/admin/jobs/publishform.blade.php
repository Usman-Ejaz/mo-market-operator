<div class="card-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Start Date & Time:</label>
				<div class="input-group">
					<input type="text" autocomplete="off" class="form-control bg-white" id="start_datetime" name="start_datetime" value="{{ old('start_datetime') ?? $job->start_datetime }}" placeholder="{{  config('settings.datetime_placeholder') }}" readonly>
					<input type="hidden" name="start_date" id="start_date" value="{{ old('start_datetime') ?? $job->parseStartDate() }}">
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
				<label for="endtime">End Date & Time</label>
				<div class="input-group">
					<input type="text" autocomplete="off" class="form-control bg-white" id="end_datetime" name="end_datetime" value="{{ old('end_datetime') ?? $job->end_datetime }}" placeholder="{{  config('settings.datetime_placeholder') }}" readonly>
					<input type="hidden" name="end_date" id="end_date" value="{{ old('start_datetime') ?? $job->parseEndDate() }}">
					<div class="input-group-append">
						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
				<span class="form-text text-danger">{{ $errors->first('end_datetime') }} </span>
			</div>
		</div>
	</div>

	<div class="row">

		@if( $job->created_at )
		<div class="col-md-12">
			<label for="endtime">Created At:</label>
			<span>{{$job->created_at}}</span>
		</div>
		@endif

		@if( $job->modified_at )
		<div class="col-md-12">
			<label for="endtime">Modified At:</label>
			<span>{{$job->modified_at ?? 'Not yet'}}</span>
		</div>
		@endif

		@if( $job->isPublished() && \Route::current()->getName() == 'admin.jobs.edit' )
		<div class="col-md-12">
			<label for="endtime">Status:</label>
			<span>{{ ($job->isPublished()) ? 'Published' : 'Draft' }}</span>
		</div>
		@endif

	</div>

</div>