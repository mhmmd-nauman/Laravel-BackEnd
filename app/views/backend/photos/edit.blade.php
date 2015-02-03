@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.photos') }}">Photos</a> » {{ $photo->file }}</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-8">
			{{ Form::model($photo, array('url' => URL::route('backend.photos.update', $photo->id), 'method' => 'PUT', 'class' => 'form-horizontal', 'files' => true)) }}
			<div class="form-group">
                {{ Form::label('venue_name', 'Venue', array('class' => 'col-sm-3 control-label')) }}
                <div class="col-sm-9">
                    {{ Form::text('venue_name', $photo->venue->name, array('class' => 'form-control js-venue-autocomplete')) }}
                    {{ Form::hidden('venue_id', $photo->venue->id, array('class' => 'form-control js-venue-id')) }}
                </div>
            </div>
            
            <div class="col-sm-9 col-sm-offset-3">
                <div class="thumbnail">
                    <img src="{{ URL::route('frontend.image.venues', array( 'size' => 'medium', 'id' => $photo->id ) ) }}" class="img-responsive" /> 
                </div>
            </div>
            
            <div class="form-group">
                {{ Form::label('file', 'Change Photo', array('class' => 'col-sm-3 control-label') ) }}
                <div class="col-sm-4">
                    {{ Form::file('file') }}
                </div>
            </div>
            
            <div class="form-group">
                {{ Form::label('status', 'Status', array('class' => 'col-sm-3 control-label')) }}
                <div class="col-sm-9">
                {{ Form::select('status', array('1' => 'Active', '0' => 'Inactive'), null, array('class' => 'form-control')) }}
                </div>
            </div>
            
			<div class="clearfix">
				<div class="pull-right">
					{{ Form::submit('Update Photo', array('class' => 'btn btn-primary')) }}
				</div>
			</div>
			{{ Form::close() }}
			</div>
		</div>
	</div>
@stop