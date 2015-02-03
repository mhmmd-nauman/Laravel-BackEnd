@extends ('frontend._layouts.default');

@section('header')
@stop

@section('content')
<a href="{{ URL::route('frontend.spa.search') }}">search</a>
@stop

@section('footer')
@stop