<div id="header-secondary" class="header-secondary">
    <div class="container">
        <div class="row">
            <div class="col-md-5 hidden-xs hidden-sm">
                <ul class="breadcrumbs">
                    <li class="breadcrumbs-item">
                        <a href="{{ URL::to('/') }}" data-icon="&#xe009;"></a>
                    </li>
                    <li class="breadcrumbs-item">
                        <a href="{{ URL::route('frontend.hotel.search') }}">{{ Lang::get('menus.spahotels') }}</a>
                    </li>
                    <li class="breadcrumbs-item is-current">
                        <a href="{{ URL::route('frontend.hotel.highlights', array('name' => $hotel->hotel_slug)) }}">{{ $hotel->hotel_name }}</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-7">
                <nav id="nav-secondary" class="nav-secondary">
                    <ul id="nav-secondary-list" class="nav-secondary-list">
                        <li class="nav-secondary-item @if ( Route::currentRouteName() == 'frontend.hotel.highlights' ) is-current @endif">
                            <a href="{{ URL::route('frontend.hotel.highlights', array('name' => $hotel->hotel_slug)) }}" class="nav-secondary-link"><span>{{ Lang::get('menus.highlights') }}</span></a>
                        </li>
                        <li class="nav-secondary-item @if ( Route::currentRouteName() == 'frontend.hotel.hotel' ) is-current @endif">
                            <a href="{{ URL::route('frontend.hotel.hotel', array('name' => $hotel->hotel_slug)) }}" class="nav-secondary-link"><span>{{ Lang::get('menus.hotel') }}</span></a>
                            <ul class="nav-secondary-subnav">
                                <li><a href="{{ URL::route('frontend.hotel.hotel', array('name' => $hotel->hotel_slug)) }}#hotel-general">{{ Lang::get('menus.hotel_general') }}</a></li>
                                <li><a href="{{ URL::route('frontend.hotel.hotel', array('name' => $hotel->hotel_slug)) }}#hotel-rooms">{{ Lang::get('menus.hotel_rooms') }}</a></li>
                                <li><a href="{{ URL::route('frontend.hotel.hotel', array('name' => $hotel->hotel_slug)) }}#hotel-restuarants">{{ Lang::get('menus.hotel_restaurants') }}</a></li>
                                <li><a href="{{ URL::route('frontend.hotel.hotel', array('name' => $hotel->hotel_slug)) }}#hotel-directions">{{ Lang::get('menus.hotel_directions') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-secondary-item @if ( Route::currentRouteName() == 'frontend.hotel.spa' ) is-current @endif">
                            <a href="{{ URL::route('frontend.hotel.spa', array('name' => $hotel->hotel_slug)) }}" class="nav-secondary-link"><span>{{ Lang::get('menus.spa') }}</span></a>
                        </li>
                        <li class="nav-secondary-item">
                            <a href="#" class="nav-secondary-link js-gallery-open"><span>{{ Lang::get('menus.images') }}</span></a>
                        </li>
                        <li class="nav-secondary-item @if ( Route::currentRouteName() == 'frontend.hotel.packages' || Route::currentRouteName() == 'frontend.hotel.booking' || Route::currentRouteName() == 'frontend.hotel.package') is-current @endif">
                            <a href="{{ URL::route('frontend.hotel.packages', array('name' => $hotel->hotel_slug)) }}" class="nav-secondary-link"><span>{{ Lang::get('menus.packages') }}</span></a>
                        </li>
                    </ul>

                    <a href="#" class="nav-secondary-open" data-icon="&#x33;"></a>
                </nav>

                <a href="#" class="nav-primary-open js-nav-primary-open" data-icon="&#x61;"></a>
            </div>
        </div>
    </div>
</div>