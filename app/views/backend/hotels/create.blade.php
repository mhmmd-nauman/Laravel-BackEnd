@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.hotels') }}">Hotel</a> » Add</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-12">
			{{ Form::open(array('url' => URL::route('backend.hotels.store'))) }}
				<div class="row">
					<div class="col-sm-9">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group">
									{{ Form::label('name', 'Name') }}
									{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
								</div>
							</div>
						</div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('description', 'Description') }}
                                    {{ Form::textarea('description', Input::old('description'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('summary', 'Summary') }}
                                    {{ Form::textarea('summary', Input::old('summary'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::label('address', 'Address') }}
								{{ Form::text('address', Input::old('address'), array('class' => 'form-control')) }}
							</div>
						</div>

					</div>
					<div class="col-sm-3">
						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::submit('Create hotel', array('class' => 'btn btn-lg btn-primary btn-block')) }}
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group">
									{{ Form::label('status', 'Status') }}
									{{ Form::select('status', array('1' => 'Active', '0' => 'Inactive'), Input::old('status'), array('class' => 'form-control')) }}
								</div>
							</div>
						</div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('phone', 'Phone') }}
                                    {{ Form::text('phone', Input::old('phone'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('reception_times', 'Reception times') }}
                                    {{ Form::text('reception_times', Input::old('reception_times'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('offsite_booking', 'Offsite booking') }}
                                    {{ Form::checkbox('offsite_booking', Input::old('offsite_booking'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('offsite_booking_url', 'Offsite booking URL') }}
                                    {{ Form::text('offsite_booking_url', Input::old('offsite_booking_url'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 col-sm-offset-9">
						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::hidden('user_id',$user->id) }}
								{{ Form::submit('Create hotel', array('class' => 'btn btn-lg btn-primary btn-block')) }}
							</div>
						</div>
					</div>
				</div>
			{{ Form::close() }}
			</div>
		</div>
	</div>
@stop

@section('footer')
<script type="text/javascript">
</script>
@stop