<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

Route::filter('spahotell.frontend_slug', function($route)
{
    $slug = $route->getParameter('name');
    $location_group = \LocationGroup::where('status', '=', 1)
                                    ->where('slug', '=', $slug);
    $hotel = \Hotel::where('status', '=', 1)
                   ->where('slug', '=', $slug);

    if ( $hotel->count() ) {
        return App::make('App\Controllers\Frontend\HotelController')->highlights($slug);
    } else if ( $location_group->count() ) {
        return App::make('App\Controllers\Frontend\HotelController')->searchLocation($slug);
    }

    App::abort(404);
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

//Route::filter('auth', function()
//{
//	if (Auth::guest()) return Redirect::guest('login');
//});

Route::filter('sentry.backend', function()
{
    $user = Sentry::getUser();
    $adminGroup = Sentry::findGroupByName('Admins');

    // if user is not admin
    if ( !$user || !$user->inGroup($adminGroup) ) {
        return Redirect::route('backend.login');
    }

    // if admin not logged in
    if ( !Sentry::check() ) return Redirect::route('backend.login');
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
