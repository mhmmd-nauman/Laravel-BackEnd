<div class="l-header-primary">
    <div class="header-primary">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <a href="{{ URL::to('/') }}" class="header-logo"><i class="logo"></i></a>
                </div>
                <div class="col-sm-9">
                    <div id="nav-primary" class="nav-primary-wrap">
                        <div class="nav-overlay js-nav-primary-close"></div>
                        <nav class="nav-primary" role="navigation">
                            <span class="nav-primary-close js-nav-primary-close" data-icon="&#x3d;"></span>

                            <a href="{{ URL::route('frontend.hotel.search') }}" class="nav-primary-item @if(Route::currentRouteName() == 'frontend.hotel.search') is-current @endif">
                                <span>{{ Lang::get('menus.hotel') }}</span>
                            </a>
                            <a href="{{ URL::to('/magasin') }}" class="nav-primary-item">
                                <span>{{ Lang::get('menus.magazine') }}</span>
                            </a>
                            <a href="{{ URL::to('/kampanjer') }}" class="nav-primary-item">
                                <span>{{ Lang::get('menus.campaigns') }}</span>
                            </a>
                            <a href="{{ URL::to('/kontakt') }}" class="nav-primary-item">
                                <span>{{ Lang::get('menus.contacts') }}</span>
                            </a>
                            <!-- <a href="#" class="nav-primary-item">
                                <span>{{ Lang::get('menus.other') }}</span>
                            </a> -->
                            <div class="nav-primary-item">
                                <div class="item-center">
                                    @include('frontend._partials.more_menu')
                                </div>
                            </div>
                            @if ( !Sentry::check() )
                            <a href="#signup-popup" class="nav-primary-item" data-toggle="modal">
                                <span>{{ Lang::get('menus.login') }}</span>
                            </a>
                            @else
                            <a href="{{ URL::route('frontend.client.logout') }}" class="nav-primary-item">
                                <span>{{ Lang::get('menus.logout') }}</span>
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>