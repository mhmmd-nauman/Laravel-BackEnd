@extends ('frontend._layouts.default')

@section('header')
    @include('frontend._partials.header_menu')
@stop

@section('secondary_header')
    @include('frontend._partials.secondary_header')
@stop

@section('content')
<div class="container">
    <div class="package-whiteboard">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1 class="text-center">General</h1>
                {{ $hotel->hotel_description }}
            </div>
        </div>
    </div>
</div>
@stop