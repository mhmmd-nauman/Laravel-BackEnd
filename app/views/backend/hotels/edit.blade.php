@extends ('backend._layouts.default');
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><a href="{{ URL::route('backend.hotels') }}">Hotels</a> » {{ $hotel->name }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {{ Form::model($hotel, array('url' => URL::route('backend.hotels.update', $hotel->id), 'method' => 'PUT')) }}
                <div class="row">
                    <div class="col-sm-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('name', 'Name') }}
                                    {{ Form::text('name', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('name_in_uri', 'Name in URI') }}
                                    {{ Form::text('slug', $hotel->slug, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('address', 'Address') }}
                                    {{ Form::text('address', $hotel->address->address, array('class' => 'form-control')) }}
                                    {{ Form::hidden('lat', $hotel->address->lat, array('class' => 'form-control js-location-lat')) }}
                                    {{ Form::hidden('lng', $hotel->address->lng, array('class' => 'form-control js-location-lng')) }}
                                </div>
                                <div class="form-group">
                                    <div id="js-location" class="venues-map"></div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('description', 'Description') }}
                                    {{ Form::textarea('description', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('summary', 'Summary') }}
                                    {{ Form::textarea('summary', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-3">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                {{ Form::submit('Update hotel', array('class' => 'btn btn-lg btn-primary btn-block')) }}
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('status', 'Status') }}
                                    {{ Form::select('status', array('' => 'Select', '0' => 'Inactive', '1' => 'Active'), null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('phone', 'Phone') }}
                                    {{ Form::text('phone', null, array('class' => 'form-control')) }}
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

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('services', 'Services (wi-fi, parking etc.)') }}
                                    <div id="services-container">
                                        @foreach($services as $service)
                                        <div class="col-sm-12 js-remove-service-{{ $service->id }}" style="margin: 10px 0 10px 0">
                                            <div class="form-group">
                                                <div class="col-sm-9">
                                                    <input name="hotel_service_{{ $service->id }}" class="form-control" value="{{ $service->name }}">
                                                </div>
                                                <div class="col-sm-3">
                                                    <a href="javascript:deleteService({{ $service->id }})" class="btn btn-danger btn-sm pull-right"><i class="glyphicon glyphicon-minus"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" id="add-service" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add new service</a>
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
                                {{ Form::hidden('user_id',$user->id) }}
                                {{ Form::submit('Update hotel', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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
                                @if ( $photo->id == $hotel->default_photo_id )
                                    <a href="javascript:makeDefault({{ $hotel->id }}, {{ $photo->id }})" class="btn btn-block btn-success">Default</a>
                                @else
                                    <a href="javascript:makeDefault({{ $hotel->id }}, {{ $photo->id }})" class="btn btn-block btn-default">Make default</a>
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
                                {{ Form::hidden('content_type', 'hotels') }}
                                {{ Form::hidden('content_id', $hotel->id) }}
                                {{ Form::submit('Add Photo', array('class' => 'btn btn-primary')) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="hidden" id="js-service-template">
    <div class="col-sm-12 js-remove-service" style="margin: 10px 0 10px 0">
        <div class="form-group">
            <div class="col-sm-9">
                <input class="form-control" type="text" name="new_hotel_service[]"/>
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
    function makeDefault(hotel_id, photo_id) {
        $.ajax({
            url: "{{ URL::route('backend.hotels.make_default_photo', '') }}/" + hotel_id+"/"+photo_id,
            type: "PUT",
            success: function(response) {
                alertify.success(response.content);
                $('.js-photo-button a').text('Make default').removeClass('btn-success').addClass('btn-default');
                $('.js-make-default-photo-'+photo_id+' a').text('Default').removeClass('btn-default').addClass('btn-success');
            }
        });
    }

    function deleteService(id) {
        if (confirm('Are you sure you want to delete this service?')) {
            $.ajax({
                url: "{{ URL::route('backend.hotels.destroy_service', '') }}/" + id,
                type: "DELETE",
                success: function(response) {
                    alertify.success(response.content);
                    $('#services-container .js-remove-service-'+id).remove();
                }
            });
        }
    }

    $(document).ready(function() {
        $('#add-service').on('click', function(e) {
            e.preventDefault();

            var s_clone = $('#js-service-template').clone();
            $('#services-container').append( s_clone.html() );

            $('#services-container .js-remove-service a.btn').on('click', function(e) {
                e.preventDefault();
                $(this).closest('.js-remove-service').remove();
            });
        });
    });

</script>
@stop