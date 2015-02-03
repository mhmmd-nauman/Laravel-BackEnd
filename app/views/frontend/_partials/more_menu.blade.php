<div class="nav-primary-dropdown dropdown">
    <a href="#" class="primary-dropdown-toggle" data-toggle="dropdown">{{ Lang::get('menus.other') }}</a>
    <ul class="primary-dropdown-menu dropdown-menu" role="menu" aria-labelledby="dLabel">
        <li><a href="{{ URL::to('/konferens') }}">{{ Lang::get('menus.conference') }}</a></li>
        <li><a href="{{ URL::to('/presentkort') }}">{{ Lang::get('menus.giftcard') }}</a></li>
        <li><a href="{{ URL::to('/nyheter') }}">{{ Lang::get('menus.news') }}</a></li>
        <li><a href="{{ URL::to('/om-spason') }}">{{ Lang::get('menus.about') }}</a></li>
        <li><a href="{{ URL::to('/jobb') }}">{{ Lang::get('menus.jobs') }}</a></li>
    </ul>
</div>