<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Spason - Administration Panel</title>
	<link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('css/plugins/alertify/alertify.core.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('css/plugins/alertify/alertify.default.css') }}">
    {{ $links or '' }}
	<link rel="stylesheet" href="{{ URL::asset('css/backend.css') }}">
	@yield('header')
</head>
<body>
	<div id="wrapper">
		@include('backend._partials.navigation')
		@yield('content')
	</div>
	<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
	<!--[if IE 8]><script type="text/javascript" src="{{ URL::asset('js/respond.min.js') }}"></script><![endif]-->
	<script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/plugins/metis-menu/jquery.metisMenu.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/plugins/alertify/alertify.min.js') }}"></script>
    {{ $scripts or '' }}
	<script type="text/javascript" src="{{ URL::asset('js/backend.js') }}"></script>
	@include('backend._partials.feedback')
	@yield('footer')
</body>
</html>