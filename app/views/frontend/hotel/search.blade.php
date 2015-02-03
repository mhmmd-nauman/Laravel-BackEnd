@extends ('frontend._layouts.search')

@section('header')
    @include('frontend._partials.header_menu')
@stop

@section('secondary_header')
    @include('frontend._partials.search.secondary_header')
@stop

@section('content')
<div class="find">
    <div class="container">
        @if ( $hotels_count )
        <div class="row">
            <div id="find-map-box" class="find-map-box col-md-4 hidden-sm hidden-xs">
                <div id="find-map" class="find-map">
                    <button id="find-map-show-markers" class="find-map-zoom btn btn-default btn-xs">Vissa hotell är utanför kartan - visa alla</button>
                    <div id="js-location" class="find-map-canvas"></div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="find-location">
                            @if(isset($location_name))
                                <h1 class="find-location-title">{{ Lang::get('search.location_name_title_text') }} {{ $location_name }}</h1>
                            @else
                                <h1 class="find-location-title">Spahotell i Sverige</h1>
                            @endif
                            @if (isset($location_description))
                                <p class="find-location-descr">{{ $location_description }}</p>
                                <a href="{{ URL::route('frontend.hotel.search') }}" class="btn btn-primary">{{ Lang::get('search.location_show_all_hotels_btn') }}</a>
                            @else
                                <p>Hej där! Här kan du söka efter och boka spahotell i hela Sverige. Prova att välja från kartan eller filtrera på ort.
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($hotels as $hotel)
                    @if ( $hotel->min_package_price > 0 )
                        <div class="col-sm-6">
                            <div class="box box-search js-hotel-location" @if ( $hotel->hotel_photo_id ) style="background-image: url({{ URL::route('frontend.image.photo', array( 'size' => 'medium', 'id' => $hotel->hotel_photo_id )) }})" @endif data-lat="{{ $hotel->lat }}" data-lng="{{ $hotel->lng }}" data-marker-id="{{ $hotel->hotel_id }}">
                                <div class="box-info">
                                    <h3 class="box-title">{{ $hotel->hotel_name }}</h3>
                                    <div class="box-panel">
                                        <h4 class="box-price">Fr. {{ $hotel->min_package_price }} SEK/person</h4>
                                    </div>
                                </div>

                                <a href="{{ URL::route('frontend.hotel.highlights', array('name' => $hotel->hotel_slug)) }}" class="box-link"></a>
                                <a href="{{ URL::route('frontend.hotel.packages', array('name' => $hotel->hotel_slug)) }}" class="box-book btn btn-primary btn-sm">{{ Lang::get('booking.book') }}</a>
                            </div>
                        </div>
                    @endif
                    @endforeach
                </div>
                <div class="row hidden">
                    <div class="col-md-12 text-center">
                        <button class="find-more btn btn-default btn-lg">{{ Lang::get('search.button_end_of_list') }}</button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-sm-12">
                <p class="find-filters">{{ $hotels_count }} {{ Lang::get('search.hotel_searchterm') }}{{{ Input::get('s') }}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <p class="find-no-results">{{ Lang::get('search.no_hotels_found') }}</p>
                <a href="{{ URL::route('frontend.hotel.search') }}" class="btn btn-default">{{ Lang::get('search.button_end_of_list') }}</a>
            </div>
        </div>
        @endif
    </div>
</div>

<script type="text/javascript">window.gm_data = {{ $js_hotels }}</script>
@stop

@section('footer')
@stop