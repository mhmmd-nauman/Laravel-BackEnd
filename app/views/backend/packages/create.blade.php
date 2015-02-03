@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.packages') }}">Package</a> » Add</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-12">
			{{ Form::open(array('url' => URL::route('backend.packages.store'))) }}
				<div class="row">
					<div class="col-sm-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('package_hotel', 'Package hotel') }}
                                    {{ Form::text('package_hotel', Input::old('package_hotel'), array('class' => 'form-control')) }}
                                    {{ Form::hidden('hotel_id', '', array('id' => 'hotel_id')) }}
                                </div>
                            </div>
                        </div>
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
                                    {{ Form::label('short_description', 'Short description') }}
                                    {{ Form::textarea('short_description', Input::old('short_description'), array('class' => 'form-control')) }}
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
                                    {{ Form::label('disabled_weekdays', 'Disabled weekdays') }}

                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    {{ Form::checkbox('available_weekdays[]', 'Mon') }} Monday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    {{ Form::checkbox('available_weekdays[]', 'Tue') }} Tuesday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    {{ Form::checkbox('available_weekdays[]', 'Wed') }} Wednesday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    {{ Form::checkbox('available_weekdays[]', 'Thu') }} Thursday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    {{ Form::checkbox('available_weekdays[]', 'Fri') }} Friday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    {{ Form::checkbox('available_weekdays[]', 'Sat') }} Saturday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    {{ Form::checkbox('available_weekdays[]', 'Sun') }} Sunday
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="panel panel-default col-sm-3 col-sm-offset-0">
                                <div class="panel-body">
                                    <div class="form-group">
                                        {{ Form::label('overnights_min', 'Overnights min') }}
                                        {{ Form::text('overnights_min', Input::old('overnights_min'), array('class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default col-sm-3 col-sm-offset-1">
                                <div class="panel-body">
                                    <div class="form-group">
                                        {{ Form::label('overnights_max', 'Overnights max') }}
                                        {{ Form::text('overnights_max', Input::old('overnights_max'), array('class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default col-sm-3 col-sm-offset-1">
                                <div class="panel-body">
                                    <div class="form-group">
                                        {{ Form::label('discount', 'Discount') }}
                                        {{ Form::text('discount', Input::old('discount'), array('class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default col-sm-3 col-sm-offset-0">
                                <div class="panel-body">
                                    <div class="form-group">
                                        {{ Form::label('last_minute', 'Last minute') }}
                                        {{ Form::checkbox('last_minute', Input::old('last_minute'), array('class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="panel panel-default col-sm-3 col-sm-offset-0">
                                <div class="panel-body">
                                    <div class="form-group">
                                        {{ Form::label('days_in_advance', 'Days in advance') }}
                                        {{ Form::text('days_in_advance', Input::old('days_in_advance'), array('class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default col-sm-3 col-sm-offset-1">
                                <div class="panel-body">
                                    <div class="form-group">
                                        {{ Form::label('price_per_person', 'Price per person') }}
                                        {{ Form::text('price_per_person', Input::old('price_per_person'), array('class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>
                        </div>

					</div>

					<div class="col-sm-3">
						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::submit('Create package', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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
                                    {{ Form::label('campaign', 'Campaign') }}
                                    {{ Form::checkbox('campaign', Input::old('campaign'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('recommended', 'Recommended') }}
                                    {{ Form::checkbox('recommended', Input::old('recommended'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('start_date', 'Start date') }}
                                    {{ Form::text('start_date', Input::old('start_date'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('end_date', 'End date') }}
                                    {{ Form::text('end_date', Input::old('end_date'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 col-sm-offset-9">
						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::submit('Create package', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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
    $(document).ready(function() {

        $("#package_hotel").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },{
            displayKey: 'name',
            source: function (query, process) {
                return $.get( '{{ URL::route("backend.packages.autocomplete") }}', {name: $("#package_hotel").val()} ).done(function(data){
                    var options = [];
                    $.each(data,function (i,val){
                        options.push({name: val.name, id: val.id});
                    });
                    process(options);
                });
            }
        }).on('typeahead:selected', function (e, suggestion, name) {
            $('#hotel_id').val(suggestion.id);
        });
    });
</script>
@stop