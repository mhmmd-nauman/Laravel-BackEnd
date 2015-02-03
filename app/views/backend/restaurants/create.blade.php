@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.hotels') }}">Restaurant</a> » Add</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-12">
			{{ Form::open(array('url' => URL::route('backend.restaurants.store'))) }}
				<div class="row">
					<div class="col-sm-9">

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('restaurant_hotel', 'Restaurant hotel') }}
                                    {{ Form::text('restaurant_hotel', Input::old('restaurant_hotel'), array('class' => 'form-control')) }}
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
                                    {{ Form::label('description', 'Description') }}
                                    {{ Form::textarea('description', Input::old('description'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

					</div>
					<div class="col-sm-3">
						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::submit('Create restaurant', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 col-sm-offset-9">
						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::submit('Create restaurant', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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
        $("#restaurant_hotel").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },{
            displayKey: 'name',
            source: function (query, process) {
                return $.get( '{{ URL::route("backend.packages.autocomplete") }}', {name: $("#restaurant_hotel").val()} ).done(function(data){
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