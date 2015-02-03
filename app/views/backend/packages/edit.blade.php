@extends ('backend._layouts.default');
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><a href="{{ URL::route('backend.packages') }}">Packages</a> » {{ $package->name }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {{ Form::model($package, array('url' => URL::route('backend.packages.update', $package->id), 'method' => 'PUT')) }}
                <div class="row">
                    <div class="col-sm-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('package_hotel', 'Package hotel') }}
                                    {{ Form::text('package_hotel', $package->hotel->name, array('class' => 'form-control')) }}
                                    {{ Form::hidden('hotel_id', $package->hotel->id , array('id' => 'hotel_id')) }}
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
                                    {{ Form::label('name_in_uri', 'Name in URI') }}
                                    {{ Form::text('slug', Input::old('slug'), array('class' => 'form-control')) }}
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
                                                    <input type="checkbox" name="available_weekdays[]" value="Mon" @if( in_array('Mon',$available_weekday) ) checked  @endif /> Monday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="available_weekdays[]" value="Tue" @if( in_array('Tue',$available_weekday) ) checked  @endif /> Tuesday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="available_weekdays[]" value="Wed" @if( in_array('Wed',$available_weekday) ) checked  @endif /> Wednesday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="available_weekdays[]" value="Thu" @if( in_array('Thu',$available_weekday) ) checked  @endif /> Thursday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="available_weekdays[]" value="Fri" @if( in_array('Fri',$available_weekday) ) checked  @endif /> Friday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="available_weekdays[]" value="Sat" @if( in_array('Sat',$available_weekday) ) checked  @endif /> Saturday
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="available_weekdays[]" value="Sun" @if( in_array('Sun',$available_weekday) ) checked  @endif /> Sunday
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
                                {{ Form::submit('Update package', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('treatments', 'Treatments') }}
                                    <div id="treatments-container">
                                        @foreach($package_treatments as $package_treatment)
                                        <div class="col-sm-12 js-remove-treatment-{{ $package_treatment->id }}" style="margin: 10px 0 10px 0">
                                            <div class="form-group">
                                                <div class="col-sm-9">
                                                    <select name="treatment_{{ $package_treatment->id }}" class="form-control">
                                                        @foreach ($treatments as $treatment)
                                                            @if ( $package_treatment->treatment_id == $treatment->treatment_id )
                                                                <option value="{{ $treatment->treatment_id }}" selected="selected">{{ $treatment->treatment_name }}</option>
                                                            @else
                                                                <option value="{{ $treatment->treatment_id }}">{{ $treatment->treatment_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <a href="javascript:deleteTreatment({{ $package_treatment->id }})" class="btn btn-danger btn-sm pull-right"><i class="glyphicon glyphicon-minus"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" id="add-treatment" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add new treatment</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('rooms', 'Package rooms') }}
                                    <div id="rooms-container">
                                        @foreach($package_rooms as $package_room)
                                        <div class="col-sm-12 js-remove-package-room-{{ $package_room->package_id }}-{{ $package_room->room_id }}" style="margin: 10px 0 10px 0">
                                            <div class="form-group">
                                                <div class="col-sm-9">
                                                    <select name="room_{{ $package_room->room_id }}" class="form-control">
                                                        @foreach ($rooms as $room)
                                                            @if ( $package_room->room_id == $room->id )
                                                                <option value="{{ $room->id }}" selected="selected">{{ $room->name }}</option>
                                                            @else
                                                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <a href="javascript:deleteRoom({{ $package_room->package_id }}, {{ $package_room->room_id }})" class="btn btn-danger btn-sm pull-right"><i class="glyphicon glyphicon-minus"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" id="add-room" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add new room</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('rooms', 'Package includes') }}
                                    <div id="package-includes-container">
                                        @foreach($package_includes as $package_include)
                                        @if ( $package_include )
                                        <div class="col-sm-12 js-remove-package-include" style="margin: 10px 0 10px 0">
                                            <div class="form-group">
                                                <div class="col-sm-9">
                                                    <input class="form-control" type="text" name="package_includes[]" value="{{ $package_include }}"/>
                                                </div>
                                                <div class="col-sm-3">
                                                    <a href="#" class="btn btn-danger btn-sm pull-right"><i class="glyphicon glyphicon-minus"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" id="add-package-include" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add new include</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                {{ Form::submit('Update package', array('class' => 'btn btn-lg btn-primary btn-block')) }}
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
            </div>
        </div>


        <div class="row">
            <div class="col-sm-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @foreach ($photos as $photo)
                        <div class="col-md-2 js-photo-button js-make-default-photo-{{ $photo->id }}">
                            <img src="{{ URL::route('frontend.image.photo', array( 'size' => 'small', 'id' => $photo->id )) }}" class="img-responsive img-rounded" />
                            @if ( $photo->id == $package->default_photo_id )
                            <a href="javascript:makeDefault({{ $package->id }}, {{ $photo->id }})" class="btn btn-block btn-success">Default</a>
                            @else
                            <a href="javascript:makeDefault({{ $package->id }}, {{ $photo->id }})" class="btn btn-block btn-default">Make default</a>
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
                            {{ Form::hidden('content_type', 'packages') }}
                            {{ Form::hidden('content_id', $package->id) }}
                            {{ Form::submit('Add Photo', array('class' => 'btn btn-primary')) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

<div class="hidden" id="js-treatments-template">
    <div class="col-sm-12 js-remove-treatment" style="margin: 10px 0 10px 0">
        <div class="form-group">
            <div class="col-sm-9">
                <select class="form-control" name="new_treatment[]">
                    @foreach ($treatments as $treatment)
                    <option value="{{ $treatment->treatment_id }}">{{ $treatment->treatment_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <a href="#" class="btn btn-danger btn-sm pull-right"><i class="glyphicon glyphicon-minus"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="hidden" id="js-rooms-template">
    <div class="col-sm-12 js-remove-room" style="margin: 10px 0 10px 0">
        <div class="form-group">
            <div class="col-sm-9">
                <select class="form-control" name="new_room[]">
                    @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <a href="#" class="btn btn-danger btn-sm pull-right"><i class="glyphicon glyphicon-minus"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="hidden" id="js-package-includes-template">
    <div class="col-sm-12 js-remove-package-include" style="margin: 10px 0 10px 0">
        <div class="form-group">
            <div class="col-sm-9">
                <input class="form-control" type="text" name="package_includes[]"/>
            </div>
            <div class="col-sm-3">
                <a href="#" class="btn btn-danger btn-sm pull-right"><i class="glyphicon glyphicon-minus"></i></a>
            </div>
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


        $('#add-treatment').on('click', function(e) {
            e.preventDefault();

            var t_clone = $('#js-treatments-template').clone();
            $('#treatments-container').append( t_clone.html() );

            $('#treatments-container .js-remove-treatment a.btn').on('click', function(e) {
                e.preventDefault();
                $(this).closest('.js-remove-treatment').remove();
            });
        });

        $('#add-room').on('click', function(e) {
            e.preventDefault();

            var r_clone = $('#js-rooms-template').clone();
            $('#rooms-container').append( r_clone.html() );

            $('#rooms-container .js-remove-room a.btn').on('click', function(e) {
                e.preventDefault();
                $(this).closest('.js-remove-room').remove();
            });
        });

        $('#add-package-include').on('click', function(e) {
            e.preventDefault();

            var pi_clone = $('#js-package-includes-template').clone();
            $('#package-includes-container').append( pi_clone.html() );

            $('#package-includes-container .js-remove-package-include a.btn').each(function(index, el) {
                var _this = this;

                $(_this).off();
                $(_this).on('click', function(evnt) {
                    evnt.preventDefault();
                    $(_this).closest('.js-remove-package-include').remove();
                });
            });
        });

        $('#package-includes-container .js-remove-package-include a.btn').each(function(index, el) {
            var _this = this;

            $(_this).off();
            $(_this).on('click', function(evnt) {
                evnt.preventDefault();
                $(_this).closest('.js-remove-package-include').remove();
            });
        });

    });

    function deleteTreatment(id) {
        if (confirm('Are you sure you want to delete this treatment?')) {
            $.ajax({
                url: "{{ URL::route('backend.package_treatment.destroy', '') }}/" + id,
                type: "DELETE",
                success: function(response) {
                    alertify.success(response.content);
                    $('#treatments-container .js-remove-treatment-'+id).remove();
                }
            });
        }
    }

    function deleteRoom(package_id, room_id) {
        if (confirm('Are you sure you want to delete this treatment?')) {
            $.ajax({
                url: "{{ URL::route('backend.package_room.destroy', '') }}/" + package_id + "/" + room_id,
                type: "DELETE",
                success: function(response) {
                    alertify.success(response.content);
                    $('#rooms-container .js-remove-package-room-'+package_id+'-'+room_id).remove();
                }
            });
        }
    }

    function makeDefault(package_id, photo_id) {
        $.ajax({
            url: "{{ URL::route('backend.packages.make_default_photo', '') }}/" + package_id+"/"+photo_id,
            type: "PUT",
            success: function(response) {
                alertify.success(response.content);
                $('.js-photo-button a').text('Make default').removeClass('btn-success').addClass('btn-default');
                $('.js-make-default-photo-'+photo_id+' a').text('Default').removeClass('btn-default').addClass('btn-success');
            }
        });
    }

</script>
@stop