<div id="header-secondary" class="header-secondary">
    <div class="container">
        <div class="row">
            <div class="col-md-5 hidden-xs hidden-sm">
                <ul class="breadcrumbs">
                    <li class="breadcrumbs-item">
                        <a href="{{ URL::to('/') }}" data-icon="&#xe009;"></a>
                    </li>
                    <li class="breadcrumbs-item is-current">
                        <a href="{{ URL::route('frontend.hotel.search') }}">{{ Lang::get('menus.spahotels') }}</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-7">
                @if ( Route::currentRouteName() == 'frontend.hotel.search' || isset($hotels_locations) )
                <div id="search-form" class="search-form-wrap pull-right">
                    <form action="{{ URL::route('frontend.hotel.search') }}" class="search-form">
                        <label for="search-form-field" class="search-form-label" data-icon="&#x55;"></label>
                        <input value="{{ Input::get('s') }}" type="search" name="s" id="search-form-field" placeholder="{{ Lang::get('search.search_field_placeholder') }}" class="search-form-field">
                        <button type="reset" id="search-form-reset" class="search-form-reset" data-icon="&#xe051;"></button>
                    </form>
                </div>
                <a href="#" id="search-form-open" class="search-form-open" data-icon="&#x55;"></a>

                    @if ( $hotels_locations->count() )
                    <nav id="nav-secondary" class="nav-secondary">
                        <ul id="nav-secondary-list" class="nav-secondary-list">
                            <li class="nav-secondary-item">
                                <a href="#" class="nav-secondary-link"><span>{{ Lang::get('search.location') }}<? if(isset($location_name)) echo ': '.$location_name; ?></span></a>
                                <ul class="nav-secondary-subnav">
                                    <li class="subnav-item-group"><b>{{Lang::get('menus.cities')}}</b></li>
                                    @foreach($hotels_locations as $hotel_location)
                                        @if( $hotel_location->type == 'city' )
                                            <li><a href="{{ URL::route('frontend.hotel.highlights', array('location' => $hotel_location->slug)) }}">{{ $hotel_location->name }}</a></li>
                                        @endif
                                    @endforeach
                                    <li class="subnav-item-group"><b>{{Lang::get('menus.areas')}}</b></li>
                                    @foreach($hotels_locations as $hotel_location)
                                        @if( $hotel_location->type == 'area' )
                                        <li><a href="{{ URL::route('frontend.hotel.highlights', array('location' => $hotel_location->slug)) }}">{{ $hotel_location->name }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    @endif
                @endif

                <a href="#" class="nav-primary-open js-nav-primary-open" data-icon="&#x61;"></a>
            </div>
        </div>
    </div>
</div>