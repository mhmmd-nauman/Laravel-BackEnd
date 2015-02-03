@extends('backend._layouts.default')
@section('content')
	<div class="container">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">
					<div class="panel-title">Please sign in</div>
				</div>
				<div class="panel-body">
					{{ Form::open() }}
						<div class="form-group">
							{{ Form::label('email', 'Email') }}
							{{ Form::text('email', null, array('class' => 'form-control') ) }}
						</div>
						<div class="form-group">
							{{ Form::label('password', 'Password') }}
							{{ Form::password('password', array('class' => 'form-control') ) }}
						</div>
						<div class="form-group">
							{{ Form::submit('Login', array('class' => 'btn btn-primary btn-lg btn-block')) }}
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@stop