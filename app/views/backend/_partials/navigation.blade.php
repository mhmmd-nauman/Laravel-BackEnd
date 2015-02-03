<nav class="navbar navbar-default navbar-static-top" role="navigation">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="#">Spason</a>
	</div>
</nav>
@if (Sentry::check())
<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<li>
				<a href="{{ URL::route('backend.default') }}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
			</li>
            <li>
				<a href="{{ URL::route('backend.hotels') }}"><i class="fa fa-building-o fa-fw"></i> Hotels</a>
			</li>
			<li>
				<a href="{{ URL::route('backend.packages') }}"><i class="fa fa-archive fa-fw"></i> Packages</a>
			</li>
            <li>
                <a href="{{ URL::route('backend.photos') }}"><i class="fa fa-picture-o fa-fw"></i> Photos</a>
            </li>
            <li>
                <a href="{{ URL::route('backend.spa') }}"><i class="fa fa-star-o fa-fw"></i> Spa</a>
            </li>
            <li>
                <a href="{{ URL::route('backend.treatments') }}"><i class="fa fa-smile-o fa-fw"></i> Treatments</a>
            </li>
            <li>
                <a href="{{ URL::route('backend.rooms') }}"><i class="fa fa-home fa-fw"></i> Rooms</a>
            </li>
            <li>
                <a href="{{ URL::route('backend.restaurants') }}"><i class="fa fa-cutlery fa-fw"></i> Restaurants</a>
            </li>
            <li>
                <a href="{{ URL::route('backend.amusements') }}"><i class="fa fa-star fa-fw"></i> Amusements</a>
            </li>
            <li>
                <a href="{{ URL::route('backend.hotel_highlights') }}"><i class="fa fa-bolt fa-fw"></i> Highlights</a>
            </li>
            <li>
                <a href="{{ URL::route('backend.hotel_awards') }}"><i class="fa fa-certificate fa-fw"></i> Awards</a>
            </li>
            <li>
                <a href="{{ URL::route('backend.discounts') }}"><i class="fa fa-money fa-fw"></i> Discounts</a>
            </li>
            <li>
                <a href="{{ URL::route('backend.location_groups') }}"><i class="fa fa-map-marker fa-fw"></i> Location groups</a>
            </li>
            <li>
                <a href="{{ URL::route('backend.users') }}"><i class="fa fa-group fa-fw"></i> Users</a>
            </li>
			<li>
				<a href="{{ URL::route('backend.logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
			</li>
		</ul>
	</div>
</nav>
@endif