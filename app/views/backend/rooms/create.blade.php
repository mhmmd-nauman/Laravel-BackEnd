@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.rooms') }}">Room</a> » Add</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-12">
			{{ Form::open(array('url' => URL::route('backend.rooms.store'))) }}
				<div class="row">
					<div class="col-sm-9">

                        <div class="panel-body">
                            <div class="form-group">
                                {{ Form::label('room_hotel', 'Room hotel') }}
                                {{ Form::text('room_hotel', Input::old('room_hotel'), array('class' => 'form-control')) }}
                                {{ Form::hidden('hotel_id', '', array('id' => 'hotel_id')) }}
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
                                    {{ Form::label('description', 'Description') }}
                                    {{ Form::textarea('description', Input::old('description'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-sm-2">
                                    {{ Form::label('room_prices', 'Room prices') }}
                                    </div>

                                    <div class="col-sm-1">
                                        {{ Form::label('mon', 'Mon') }}
                                        {{ Form::text('mon_price', Input::old('mon_price'), array('class' => 'form-control')) }}
                                    </div>

                                    <div class="col-sm-1">
                                        {{ Form::label('tue', 'Tue') }}
                                        {{ Form::text('tue_price', Input::old('tue_price'), array('class' => 'form-control')) }}
                                    </div>

                                    <div class="col-sm-1">
                                        {{ Form::label('wed', 'Wed') }}
                                        {{ Form::text('wed_price', Input::old('wed_price'), array('class' => 'form-control')) }}
                                    </div>

                                    <div class="col-sm-1">
                                        {{ Form::label('thu', 'Thu') }}
                                        {{ Form::text('thu_price', Input::old('thu_price'), array('class' => 'form-control')) }}
                                    </div>

                                    <div class="col-sm-1">
                                        {{ Form::label('fri', 'Fri') }}
                                        {{ Form::text('fri_price', Input::old('fri_price'), array('class' => 'form-control')) }}
                                    </div>

                                    <div class="col-sm-1">
                                        {{ Form::label('sat', 'Sat') }}
                                        {{ Form::text('sat_price', Input::old('sat_price'), array('class' => 'form-control')) }}
                                    </div>

                                    <div class="col-sm-1">
                                        {{ Form::label('sun', 'Sun') }}
                                        {{ Form::text('sun_price', Input::old('sun_price'), array('class' => 'form-control')) }}
                                    </div>

                                </div>
                            </div>
                        </div>

					</div>
					<div class="col-sm-3">
						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::submit('Create room', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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
                                    {{ Form::label('max_residents', 'Max residents') }}
                                    {{ Form::text('max_residents', Input::old('max_residents'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 col-sm-offset-9">
						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::submit('Create room', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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

        $("#room_hotel").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },{
            displayKey: 'name',
            source: function (query, process) {
                return $.get( '{{ URL::route("backend.rooms.autocomplete") }}', {name: $("#room_hotel").val()} ).done(function(data){
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