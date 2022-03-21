<div class="card-body">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Start Date & Time:</label>
				<div class="input-group">
					<input type="text" autocomplete="off" class="form-control bg-white" id="start_datetime" name="start_datetime" value="{{ old('start_datetime') ?? $page->start_datetime }}" placeholder="{{  config('settings.datetime_placeholder') }}" readonly>
					<input type="hidden" name="start_date" id="start_date" value="{{ old('start_datetime') ?? $page->parseStartDate() }}">
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
					<input type="text" autocomplete="off" class="form-control bg-white" id="end_datetime" name="end_datetime" value="{{ old('end_datetime') ?? $page->end_datetime }}" placeholder="{{  config('settings.datetime_placeholder') }}" readonly>
					<input type="hidden" name="end_date" id="end_date" value="{{ old('start_datetime') ?? $page->parseEndDate() }}">
					<div class="input-group-append">
						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					</div>
				</div>
				<span class="form-text text-danger">{{ $errors->first('end_datetime') }} </span>
			</div>
		</div>
	</div>

	<div class="row">

		@if( $page->created_at )
		<div class="col-md-12">
			<label for="endtime">Created At:</label>
			<span>{{$page->created_at}}</span>
		</div>
		@endif

		@if( $page->modified_at )
		<div class="col-md-12">
			<label for="endtime">Modified At:</label>
			<span>{{$page->modified_at ?? 'Not yet'}}</span>
		</div>
		@endif

		@if(\Route::current()->getName() == 'admin.pages.edit')
			<div class="col-md-12">
				<label for="endtime">Status:</label>
				<span>{{ ($page->isPublished()) ? 'Published' : 'Draft' }}</span>
			</div>
		@endif

	</div>

</div>