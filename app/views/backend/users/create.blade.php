@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.users') }}">Users</a> » Add User</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-8">
			{{ Form::open(array('url' => URL::route('backend.users.store') , 'class' => 'form-horizontal')) }}
			<div class="form-group">
				{{ Form::label('email', 'Email', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::email('email', Input::old('email'), array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('password', 'Password', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::password('password', array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('first_name', 'First Name', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::text('first_name', Input::old('first_name'), array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('last_name', 'Last Name', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::text('last_name', Input::old('last_name'), array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('gender', 'Gender', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::select('gender', array('' => 'Select', 'F' => 'Female', 'M' => 'Male'), Input::old('gender'), array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('is_admin', 'Administrator', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::select('is_admin', array('' => 'Select', '0' => 'No', '1' => 'Yes'), Input::old('is_admin'), array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('status', 'Status', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::select('status', array('' => 'Select', '0' => 'Inactive', '1' => 'Active'), Input::old('status'), array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="clearfix">
				<div class="pull-right">
					{{ Form::submit('Create User', array('class' => 'btn btn-primary')) }}
				</div>
			</div>
			{{ Form::close() }}
			</div>
		</div>
	</div>
@stop