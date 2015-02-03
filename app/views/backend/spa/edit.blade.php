@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.spa') }}">Spa</a> » {{ $spa->name }}</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-12">
				{{ Form::model($spa, array('url' => URL::route('backend.spa.update', $spa->id), 'method' => 'PUT')) }}
                <div class="row">
                    <div class="col-sm-9">

                        <div class="panel-body">
                            <div class="form-group">
                                {{ Form::label('spa_hotel', 'Spa hotel') }}
                                {{ Form::text('spa_hotel', $spa->hotel->name, array('class' => 'form-control')) }}
                                {{ Form::hidden('hotel_id', $spa->hotel->id, array('id' => 'hotel_id')) }}
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
                                {{ Form::submit('Update spa', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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
                                    {{ Form::label('services', 'Services (bubble bath and etc.)') }}
                                    <div id="services-container">
                                        @foreach($services as $service)
                                        <div class="col-sm-12 js-remove-service-{{ $service->id }}" style="margin: 10px 0 10px 0">
                                            <div class="form-group">
                                                <div class="col-sm-9">
                                                    <input name="spa_service_{{ $service->id }}" class="form-control" value="{{ $service->name }}">
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
                                {{ Form::submit('Update spa', array('class' => 'btn btn-lg btn-primary btn-block')) }}
                            </div>
                        </div>
                    </div>
                </div>
			{{ Form::close() }}
			</div>
		</div>
	</div>

<div class="hidden" id="js-service-template">
    <div class="col-sm-12 js-remove-service" style="margin: 10px 0 10px 0">
        <div class="form-group">
            <div class="col-sm-9">
                <input class="form-control" type="text" name="new_spa_service[]"/>
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

    function deleteService(id) {
        if (confirm('Are you sure you want to delete this service?')) {
            $.ajax({
                url: "{{ URL::route('backend.spa.destroy_service', '') }}/" + id,
                type: "DELETE",
                success: function(response) {
                    alertify.success(response.content);
                    $('#services-container .js-remove-service-'+id).remove();
                }
            });
        }
    }

    $(document).ready(function() {

        $("#spa_hotel").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },{
            displayKey: 'name',
            source: function (query, process) {
                return $.get( '{{ URL::route("backend.spa.autocomplete") }}', {name: $("#spa_hotel").val()} ).done(function(data){
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