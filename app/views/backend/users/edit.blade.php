@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.users') }}">Users</a> » {{ $user->email }}</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-8">
			{{ Form::model($user, array('url' => URL::route('backend.users.update', $user->id), 'method' => 'PUT', 'class' => 'form-horizontal')) }}
			<div class="form-group">
				{{ Form::label('email', 'Email', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::email('email', null, array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('password', 'New Password', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::password('password', array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('first_name', 'First Name', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::text('first_name', null, array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('last_name', 'Last Name', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::text('last_name', null, array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('gender', 'Gender', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::select('gender', array('' => 'Select', 'F' => 'Female', 'M' => 'Male'), null, array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('is_admin', 'Administrator', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::select('is_admin', array('' => 'Select', '0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ Form::label('status', 'Status', array('class' => 'col-sm-3 control-label') ) }}
				<div class="col-sm-9">
					{{ Form::select('status', array('' => 'Select', '0' => 'Inactive', '1' => 'Active'), null, array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="clearfix">
				<div class="pull-right">
					{{ Form::submit('Update User', array('class' => 'btn btn-primary')) }}
				</div>
			</div>
			{{ Form::close() }}
			</div>
		</div>
	</div>
@stop