@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-md-12">
				<h1 class="page-header">User Settings</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-8">
				{{ Form::model($user, array('url' => URL::route('backend.settings.update'), 'method' => 'PUT', 'class' => 'form-horizontal')) }}
				<div class="form-group">
					{{ Form::label('email', 'Email', array('class' => 'control-label col-sm-3')) }}
					<div class="col-sm-9">
						{{ Form::email('email', null, array('class' => 'form-control')) }}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('password', 'New Password', array('class' => 'control-label col-sm-3')) }}
					<div class="col-sm-9">
						{{ Form::password('password', array('class' => 'form-control')) }}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('first_name', 'First Name', array('class' => 'control-label col-sm-3')) }}
					<div class="col-sm-9">
						{{ Form::text('first_name', null, array('class' => 'form-control')) }}
					</div>
				</div>
				<div class="form-group">
					{{ Form::label('last_name', 'Last Name', array('class' => 'control-label col-sm-3')) }}
					<div class="col-sm-9">
						{{ Form::text('last_name', null, array('class' => 'form-control')) }}
					</div>
				</div>
				<div class="clearfix">
					<div class="pull-right">
						{{ Form::submit('Save Settings', array('class' => 'btn btn-primary')) }}
					</div>
				</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
@stop
@section('footer')
<script type="text/javascript">

</script>
@stop