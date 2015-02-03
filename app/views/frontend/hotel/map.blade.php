@extends ('frontend._layouts.default')

@section('header')
    @include('frontend._partials.header_menu')
@stop

@section('secondary_header')
    @include('frontend._partials.secondary_header')
@stop

@section('content')
<div class="container">
    <div class="content-bg">
        <div class="row">
            <div class="col-md-12">
                <div class="map">
                    <h1 class="map-title">{{ Lang::get('map.map_title_h1') }}</h1>

                    <div id="js-location" class="map-canvas"></div>
                    <p class="map-marker"><i class="marker-icon" data-icon="&#xe081;"></i> {{ $hotel->address }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer')
<script type="text/javascript">window.gm_data = {{ $js_hotels }}</script>
@stop