@extends ('backend._layouts.default');
@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="page-header"><a href="{{ URL::route('backend.location_groups') }}">Location Group</a> » Edit</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{ Form::model($location_group, array('url' => URL::route('backend.location_groups.update', $location_group->id), 'method' => 'PUT')) }}
            <div class="row">
                <div class="col-sm-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                {{ Form::label('location_group_hotel', 'Location group hotel') }}
                                {{ Form::text('location_group_hotel', Input::old('location_group_hotel'), array('class' => 'form-control')) }}
                                {{ Form::hidden('hotel_id', '', array('id' => 'hotel_id')) }}
                                <a href="#" id="add-hotel" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add hotel</a>
                            </div>
                        </div>

                        <div class="panel-body">
                            <table class="table table-hover" id="hotels-list">
                                <thead>
                                <tr>
                                    <td><b>Hotel name</b></td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($location_hotels as $location)
                                <tr>
                                    <td>{{ $location->hotel_name }}</td>
                                    <td><a href="javascript:deleteHotel({{ $location->hotel_id }}, {{ $location->location_id }})" class="btn btn-danger pull-right js-remove-hotel-{{ $location->hotel_id }}"><i class="glyphicon glyphicon-minus"></i></a></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
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
                                {{ Form::label('slug', 'URL') }}
                                {{ Form::text('slug', Input::old('slug'), array('class' => 'form-control')) }}
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
                            {{ Form::submit('Update location group', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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
                                {{ Form::label('type', 'Type') }}
                                {{ Form::select('type', array('city' => 'City', 'area' => 'Area'), Input::old('type'), array('class' => 'form-control')) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 col-sm-offset-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {{ Form::submit('Update location group', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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

        $("#location_group_hotel").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },{
            displayKey: 'name',
            source: function (query, process) {
                return $.get( '{{ URL::route("backend.location_groups.autocomplete") }}', {name: $("#location_group_hotel").val()} ).done(function(data){
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

    $('#add-hotel').on('click', function(e) {
        e.preventDefault();

        var hotelName = $('#location_group_hotel').val();
        var hotelId   = $('#hotel_id').val();

        if ( hotelName && hotelId ) {
            if ( !$('.js-remove-hotel-'+hotelId).html() ) {
                var html =
                    '<tr>'+
                        '<td>'+hotelName+'</td>'+
                        '<td><a href="javascript:deleteHotel('+hotelId+')" class="btn btn-danger pull-right js-remove-hotel-'+hotelId+'"><i class="glyphicon glyphicon-minus"></i></a></td>'+
                        '<input type="hidden" value="'+hotelId+'" name="location_hotels[]">'+
                    '</tr>';

                $('#hotels-list tbody').append(html);
                $('#location_group_hotel').val('');
            }
        }
    });

    function deleteHotel(id, locationId) {
        if (confirm('Are you sure you want to delete this hotel from location?')) {

            if ( locationId ) {
                $.ajax({
                    url: "{{ URL::route('backend.location_groups.destroy_hotel', '') }}/" + id + "/" + locationId,
                    type: "DELETE",
                    success: function(response) {
                        alertify.success(response.content);
                        $('#hotels-list .js-remove-hotel-'+id).closest('tr').remove();
                    }
                });
            } else {
                $('#hotels-list .js-remove-hotel-'+id).closest('tr').remove();
            }


        }
    }

</script>
@stop