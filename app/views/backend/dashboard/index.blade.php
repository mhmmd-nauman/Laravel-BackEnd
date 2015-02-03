@extends ('backend._layouts.default');

@section('header')
<link rel="stylesheet" href="{{ URL::asset('css/plugins/data-tables/dataTables.bootstrap.css') }}">
@stop

@section('content')
	<div id="page-wrapper">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="page-header">Dashboard</h1>
			</div>
        </div>
		<div class="row">
			<div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Users</th>
                                <th>Hotels</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr>
                                <th>Total</th>
                                <td>{{$users['total']}}</td>
                                <td>{{$hotels['total']}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
			</div>
		</div>

        <br><br>
        <div class="row">
            <div class="col-sm-12">
                <h1>Settings</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <form class="form-horizontal" role="form" action="{{ URL::route('backend.settings.store') }}" method="POST">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Orders email</label>
                        <div class="col-sm-5">
                            <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Email" value="{{ $settings['email'][0]->value }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Google analytics code</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" rows="15" cols="220" name="google_analytics">{{ $settings['ga'][0]->value }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-3">
                            <button type="submit" class="btn btn-default">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
@stop

@section('footer')
<script type="text/javascript" src="{{ URL::asset('js/plugins/data-tables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/plugins/data-tables/dataTables.bootstrap.js') }}"></script>
@stop