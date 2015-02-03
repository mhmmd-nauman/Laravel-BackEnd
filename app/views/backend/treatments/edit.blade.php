@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.spa') }}">Treatment</a> » {{ $treatment->name }}</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-12">
				{{ Form::model($treatment, array('url' => URL::route('backend.treatments.update', $treatment->id), 'method' => 'PUT')) }}
                <div class="row">
                    <div class="col-sm-9">
                        <div class="panel-body">
                            <div class="form-group">
                                {{ Form::label('treatment_spa', 'Treatment spa') }}
                                {{ Form::text('treatment_spa', $treatment->spa->name, array('class' => 'form-control')) }}
                                {{ Form::hidden('spa_id', $treatment->spa->id, array('id' => 'spa_id')) }}
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
                                {{ Form::submit('Update treatment', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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
                                    {{ Form::label('duration', 'Duration') }}
                                    {{ Form::text('duration', Input::old('duration'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('persons', 'Persons') }}
                                    {{ Form::text('persons', Input::old('persons'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('price', 'Price') }}
                                    {{ Form::text('price', Input::old('price'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                {{ Form::submit('Update treatment', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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

        $("#treatment_spa").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },{
            displayKey: 'name',
            source: function (query, process) {
                return $.get( '{{ URL::route("backend.treatments.autocomplete") }}', {name: $("#treatment_spa").val()} ).done(function(data){
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