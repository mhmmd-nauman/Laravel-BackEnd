@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.rooms') }}">Room</a> » {{ $room->name }}</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-12">
				{{ Form::model($room, array('url' => URL::route('backend.rooms.update', $room->id), 'method' => 'PUT')) }}
                <div class="row">
                    <div class="col-sm-9">

                        <div class="panel-body">
                            <div class="form-group">
                                {{ Form::label('room_hotel', 'Room hotel') }}
                                {{ Form::text('room_hotel', $room->hotel->name, array('class' => 'form-control')) }}
                                {{ Form::hidden('hotel_id', $room->hotel->id, array('id' => 'hotel_id')) }}
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

                                    @foreach($room_prices as $room_price)

                                        @if($room_price->weekday == 'mon')
                                            <div class="col-sm-1">
                                                {{ Form::label('mon', 'Mon') }}
                                                {{ Form::text('mon_price', $room_price->price, array('class' => 'form-control')) }}
                                            </div>
                                        @endif

                                        @if($room_price->weekday == 'tue')
                                            <div class="col-sm-1">
                                                {{ Form::label('tue', 'Tue') }}
                                                {{ Form::text('tue_price', $room_price->price, array('class' => 'form-control')) }}
                                            </div>
                                        @endif

                                        @if($room_price->weekday == 'wed')
                                            <div class="col-sm-1">
                                                {{ Form::label('wed', 'Wed') }}
                                                {{ Form::text('wed_price', $room_price->price, array('class' => 'form-control')) }}
                                            </div>
                                        @endif

                                        @if($room_price->weekday == 'thu')
                                            <div class="col-sm-1">
                                                {{ Form::label('thu', 'Thu') }}
                                                {{ Form::text('thu_price', $room_price->price, array('class' => 'form-control')) }}
                                            </div>
                                        @endif

                                        @if($room_price->weekday == 'fri')
                                            <div class="col-sm-1">
                                                {{ Form::label('fri', 'Fri') }}
                                                {{ Form::text('fri_price', $room_price->price, array('class' => 'form-control')) }}
                                            </div>
                                        @endif

                                        @if($room_price->weekday == 'sat')
                                            <div class="col-sm-1">
                                                {{ Form::label('sat', 'Sat') }}
                                                {{ Form::text('sat_price', $room_price->price, array('class' => 'form-control')) }}
                                            </div>
                                        @endif

                                        @if($room_price->weekday == 'sun')
                                            <div class="col-sm-1">
                                                {{ Form::label('sun', 'Sun') }}
                                                {{ Form::text('sun_price', $room_price->price, array('class' => 'form-control')) }}
                                            </div>
                                        @endif

                                    @endforeach


                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-3">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                {{ Form::submit('Update room', array('class' => 'btn btn-lg btn-primary btn-block')) }}
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('status', 'Status') }}
                                    {{ Form::select('status', array('' => 'Select', '0' => 'Inactive', '1' => 'Active'), Input::old('status'), array('class' => 'form-control')) }}
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
                                {{ Form::submit('Update room', array('class' => 'btn btn-lg btn-primary btn-block')) }}
                            </div>
                        </div>
                    </div>
                </div>
			{{ Form::close() }}
			</div>

            <div class="row">
                <div class="col-sm-9">
                    <div class="panel panel-default btn-">
                        <div class="panel-body">
                            @foreach ($photos as $photo)
                            <div class="col-md-2 js-photo-button js-make-default-photo-{{ $photo->id }}">
                                <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small', 'id' => $photo->id )) }}" class="img-responsive img-rounded" />
                                @if ( $photo->id == $room->default_photo_id )
                                <a href="javascript:makeDefault({{ $room->id }}, {{ $photo->id }})" class="btn btn-block btn-success">Default</a>
                                @else
                                <a href="javascript:makeDefault({{ $room->id }}, {{ $photo->id }})" class="btn btn-block btn-default">Make default</a>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="form-group">
                    <div class="col-md-8">
                        {{ Form::open(array('url' => URL::route('backend.photos.store') , 'class' => 'form-horizontal', 'files' => true)) }}
                        <div class="form-group">
                            {{ Form::label('file', 'Photo', array('class' => 'col-sm-3 control-label') ) }}
                            <div class="col-sm-4">
                                {{ Form::file('file') }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('status', 'Status', array('class' => 'col-sm-3 control-label')) }}
                            <div class="col-sm-9">
                                {{ Form::select('status', array('' => 'Select', '0' => 'Inactive', '1' => 'Active'), Input::old('status'), array('class' => 'form-control')) }}
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="pull-right">
                                {{ Form::hidden('content_type', 'rooms') }}
                                {{ Form::hidden('content_id', $room->id) }}
                                {{ Form::submit('Add Photo', array('class' => 'btn btn-primary')) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

		</div>
	</div>
@stop

@section('footer')
<script type="text/javascript">
    function makeDefault(room_id, photo_id) {
        $.ajax({
            url: "{{ URL::route('backend.rooms.make_default_photo', '') }}/" + room_id+"/"+photo_id,
            type: "PUT",
            success: function(response) {
                alertify.success(response.content);
                $('.js-photo-button a').text('Make default').removeClass('btn-success').addClass('btn-default');
                $('.js-make-default-photo-'+photo_id+' a').text('Default').removeClass('btn-default').addClass('btn-success');
            }
        });
    }


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