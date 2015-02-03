@extends ('backend._layouts.default');
@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="page-header"><a href="{{ URL::route('backend.hotels') }}">Discount</a> » Add</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-md-12">
			{{ Form::open(array('url' => URL::route('backend.discounts.store'))) }}
				<div class="row">
					<div class="col-sm-9">
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
                                    {{ Form::label('code', 'Code') }}
                                    {{ Form::text('code', Input::old('code'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('count', 'Count') }}
                                    {{ Form::text('count', Input::old('count'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('price_type', 'Price type') }}
                                    {{ Form::select('price_type', array('' => 'Select', 'person' => 'Per person', 'booking' => 'Per booking'), Input::old('status'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('expire', 'Expire') }}
                                    {{ Form::text('expire', Input::old('expire'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    {{ Form::label('discount', 'Discount') }}
                                    {{ Form::text('discount', Input::old('discount'), array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
					</div>

					<div class="col-sm-3">
						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::submit('Create discount', array('class' => 'btn btn-lg btn-primary btn-block')) }}
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group">
									{{ Form::label('status', 'Status') }}
									{{ Form::select('status', array('1' => 'Active', '0' => 'Inactive'), Input::old('status'), array('class' => 'form-control')) }}
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 col-sm-offset-9">
						<div class="panel panel-default">
							<div class="panel-body">
								{{ Form::submit('Create discount', array('class' => 'btn btn-lg btn-primary btn-block')) }}
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
    });
</script>
@stop