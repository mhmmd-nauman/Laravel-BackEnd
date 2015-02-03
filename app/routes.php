<?php

/*
    Wordpress frontend part
*/

/*
    Frontend routes
*/
Route::get ('/', array('as' => 'frontend.site.index', 'uses' => 'App\Controllers\Frontend\SiteController@index'));
Route::get('image/photos/{size}/{id}', array('as' => 'frontend.image.photo', 'uses' => 'App\Controllers\Frontend\ImageController@photo'));

// Frontend: Hotel module
Route::get ('spahotell',                                    array('as' => 'frontend.hotel.search', 'uses' => 'App\Controllers\Frontend\HotelController@search'));
Route::get ('spahotell/{name}',                             array('before' => 'spahotell.frontend_slug', 'as' => 'frontend.hotel.highlights', 'uses' => 'App\Controllers\Frontend\HotelController@highlights'));
Route::get ('spahotell/{name}/allmant',                     array('as' => 'frontend.hotel.general', 'uses' => 'App\Controllers\Frontend\HotelController@general'));
Route::get ('spahotell/{name}/paket',                       array('as' => 'frontend.hotel.packages', 'uses' => 'App\Controllers\Frontend\HotelController@packages'));
Route::get ('spahotell/{name}/paket/{package_slug}',        array('as' => 'frontend.hotel.package', 'uses' => 'App\Controllers\Frontend\HotelController@package'));
Route::post ('spahotell/{name}/paket/{package_slug}/boka',  array('as' => 'frontend.hotel.booking', 'uses' => 'App\Controllers\Frontend\HotelController@booking'));
Route::get ('spahotell/{name}/paket/{package_slug}/boka',   array('as' => 'frontend.hotel.booking', 'uses' => 'App\Controllers\Frontend\HotelController@booking'));
Route::post ('spahotell/{name}/paket/{package_slug}/order', array('as' => 'frontend.hotel.order', 'uses' => 'App\Controllers\Frontend\HotelController@order'));
Route::get ('spahotell/{name}/karta',                       array('as' => 'frontend.hotel.map', 'uses' => 'App\Controllers\Frontend\HotelController@map'));
Route::get ('spahotell/{name}/spa',                         array('as' => 'frontend.hotel.spa', 'uses' => 'App\Controllers\Frontend\HotelController@spa'));
Route::get ('spahotell/{name}/hotell',                       array('as' => 'frontend.hotel.hotel', 'uses' => 'App\Controllers\Frontend\HotelController@hotel'));
Route::post ('/hotel/booking/validate_discount',            array('as' => 'frontend.hotel.booking_discount_validate', 'uses' => 'App\Controllers\Frontend\HotelController@bookingValidateDiscount'));
Route::post ('/hotel/validate_package_availability',        array('as' => 'frontend.hotel.highlights_package_availability', 'uses' => 'App\Controllers\Frontend\HotelController@packageValidateAvailability'));


// Frontend: User module
Route::post ('client/register',          array('as' => 'frontend.client.register',          'uses' => 'App\Controllers\Frontend\ClientController@register'));
Route::post ('client/register/validate', array('as' => 'frontend.client.register_validate', 'uses' => 'App\Controllers\Frontend\ClientController@registerValidate'));
Route::post ('client/update',            array('as' => 'frontend.client.update',            'uses' => 'App\Controllers\Frontend\ClientController@update'));
Route::get ('client/logout',             array('as' => 'frontend.client.logout',            'uses' => 'App\Controllers\Frontend\ClientController@logout'));
Route::get ('client/facebook_login',     array('as' => 'frontend.client.facebook_login',    'uses' => 'App\Controllers\Frontend\ClientController@loginWithFacebook'));
Route::post ('client/login',             array('as' => 'frontend.client.login',             'uses' => 'App\Controllers\Frontend\ClientController@login'));


/*
    Backend routes
*/
Route::get ('admin/logout', array('as' => 'backend.logout',     'uses' => 'App\Controllers\Backend\AuthController@getLogout'));
Route::get ('admin/login',  array('as' => 'backend.login',      'uses' => 'App\Controllers\Backend\AuthController@getLogin'));
Route::post('admin/login',  array('as' => 'backend.login.post', 'uses' => 'App\Controllers\Backend\AuthController@postLogin'));

Route::group(array('before' => 'sentry.backend'), function()
{
    //Backend: Dashboard and Settings
    Route::get  ('admin', 		 		  array('as' => 'backend.default',        		  'uses' => 'App\Controllers\Backend\DashboardController@index'));
    Route::get  ('admin/datatables',      array('as' => 'backend.datatables', 	          'uses' => 'App\Controllers\Backend\DashboardController@dataTables'));
    Route::delete('admin/{id}', 	      array('as' => 'backend.destroy', 	 	          'uses' => 'App\Controllers\Backend\DashboardController@destroy'));
    Route::post  ('admin/settings',       array('as' => 'backend.settings.store', 	      'uses' => 'App\Controllers\Backend\DashboardController@storeSettings'));


    //Backend: Users Module
    Route::get('admin/users', 		        array('as' => 'backend.users', 			  	  'uses' => 'App\Controllers\Backend\UsersController@index'));
    Route::get('admin/users/datatables',    array('as' => 'backend.users.datatables', 	  'uses' => 'App\Controllers\Backend\UsersController@dataTables'));
    Route::get('admin/users/create', 	    array('as' => 'backend.users.create', 	  	  'uses' => 'App\Controllers\Backend\UsersController@create'));
    Route::post('admin/users', 		        array('as' => 'backend.users.store', 	  	      'uses' => 'App\Controllers\Backend\UsersController@store'));
    Route::get('admin/users/{id}/edit',     array('as' => 'backend.users.edit', 	  	      'uses' => 'App\Controllers\Backend\UsersController@edit'));
    Route::put('admin/users/{id}/update',   array('as' => 'backend.users.update', 	  	  'uses' => 'App\Controllers\Backend\UsersController@update'));
    Route::delete('admin/users/{id}', 	    array('as' => 'backend.users.destroy', 	  	  'uses' => 'App\Controllers\Backend\UsersController@destroy'));
    Route::post('admin/users/activate', 	array('as' => 'backend.users.activate', 	      'uses' => 'App\Controllers\Backend\UsersController@activate'));
    Route::post('admin/users/deactivate',   array('as' => 'backend.users.deactivate',       'uses' => 'App\Controllers\Backend\UsersController@deactivate'));

    //Backend: Hotels Module
    Route::get('admin/hotels/datatables',  			  array('as' => 'backend.hotels.datatables',   'uses' => 'App\Controllers\Backend\HotelsController@dataTables'));
    Route::post('admin/hotels',		  				  array('as' => 'backend.hotels.store', 	   'uses' => 'App\Controllers\Backend\HotelsController@store'));
    Route::get('admin/hotels/create', 	              array('as' => 'backend.hotels.create', 	   'uses' => 'App\Controllers\Backend\HotelsController@create'));
    Route::get('admin/hotels/{id}/edit',   			  array('as' => 'backend.hotels.edit', 	  	   'uses' => 'App\Controllers\Backend\HotelsController@edit'));
    Route::put('admin/hotels/{id}/update', 			  array('as' => 'backend.hotels.update', 	   'uses' => 'App\Controllers\Backend\HotelsController@update'));
    Route::delete('admin/hotels/{id}', 				  array('as' => 'backend.hotels.destroy', 	   'uses' => 'App\Controllers\Backend\HotelsController@destroy'));
    //Route::get('admin/hotels/autocomplete', 		      array('as' => 'backend.hotels.autocomplete', 'uses' => 'App\Controllers\Backend\HotelsController@autocomplete'));
    Route::get('admin/hotels/', 		              array('as' => 'backend.hotels', 			   'uses' => 'App\Controllers\Backend\HotelsController@index'));
    Route::post('admin/hotels/activate', 	       	  array('as' => 'backend.hotels.activate', 	   'uses' => 'App\Controllers\Backend\HotelsController@activate'));
    Route::post('admin/hotels/deactivate', 		      array('as' => 'backend.hotels.deactivate',   'uses' => 'App\Controllers\Backend\HotelsController@deactivate'));
    Route::put('admin/hotels/make_default_photo/{hotel_id}/{photo_id}',     array('as' => 'backend.hotels.make_default_photo',   'uses' => 'App\Controllers\Backend\HotelsController@makeDefaultPhoto'));
    Route::delete('admin/hotels/service/{id}', 	     array('as' => 'backend.hotels.destroy_service',    'uses' => 'App\Controllers\Backend\HotelsController@destroyHotelService'));

    //Backend: Packages Module
    Route::get('admin/packages/datatables',  	      array('as' => 'backend.packages.datatables',     'uses' => 'App\Controllers\Backend\PackagesController@dataTables'));
    Route::post('admin/packages',		  			  array('as' => 'backend.packages.store', 	       'uses' => 'App\Controllers\Backend\PackagesController@store'));
    Route::get('admin/packages/{id}/edit',   		  array('as' => 'backend.packages.edit', 	  	   'uses' => 'App\Controllers\Backend\PackagesController@edit'));
    Route::get('admin/packages/create', 	          array('as' => 'backend.packages.create', 	       'uses' => 'App\Controllers\Backend\PackagesController@create'));
    Route::put('admin/packages/{id}/update', 		  array('as' => 'backend.packages.update', 	       'uses' => 'App\Controllers\Backend\PackagesController@update'));
    Route::delete('admin/packages/{id}', 			  array('as' => 'backend.packages.destroy', 	   'uses' => 'App\Controllers\Backend\PackagesController@destroy'));
    Route::get('admin/packages/autocomplete', 	      array('as' => 'backend.packages.autocomplete',   'uses' => 'App\Controllers\Backend\PackagesController@autocomplete'));
    Route::get('admin/packages/', 		              array('as' => 'backend.packages', 			   'uses' => 'App\Controllers\Backend\PackagesController@index'));
    Route::post('admin/packages/activate', 	    	  array('as' => 'backend.packages.activate', 	   'uses' => 'App\Controllers\Backend\PackagesController@activate'));
    Route::post('admin/packages/deactivate', 		  array('as' => 'backend.packages.deactivate',     'uses' => 'App\Controllers\Backend\PackagesController@deactivate'));
    Route::delete('admin/package_treatments/{id}', 	  array('as' => 'backend.package_treatment.destroy',    'uses' => 'App\Controllers\Backend\PackagesController@destroyPackageTreatment'));
    Route::delete('admin/package_treatments/{package_id}/{room_id}', 	        array('as' => 'backend.package_room.destroy',    'uses' => 'App\Controllers\Backend\PackagesController@destroyPackageRoom'));
    Route::put('admin/packages/make_default_photo/{package_id}/{photo_id}',     array('as' => 'backend.packages.make_default_photo',   'uses' => 'App\Controllers\Backend\PackagesController@makeDefaultPhoto'));

    //Backend: Photos Module
    Route::get('admin/photos/datatables',  	array('as' => 'backend.photos.datatables', 	  'uses' => 'App\Controllers\Backend\PhotosController@dataTables'));
    Route::get('admin/photos/create', 	   	array('as' => 'backend.photos.create', 	  	  'uses' => 'App\Controllers\Backend\PhotosController@create'));
    Route::post('admin/photos', 		   	array('as' => 'backend.photos.store', 	  	  'uses' => 'App\Controllers\Backend\PhotosController@store'));
    Route::get('admin/photos/{id}/edit',   	array('as' => 'backend.photos.edit', 	  	  'uses' => 'App\Controllers\Backend\PhotosController@edit'));
    Route::put('admin/photos/{id}/update', 	array('as' => 'backend.photos.update', 	  	  'uses' => 'App\Controllers\Backend\PhotosController@update'));
    Route::delete('admin/photos/{id}', 		array('as' => 'backend.photos.destroy', 	  'uses' => 'App\Controllers\Backend\PhotosController@destroy'));
    Route::get('admin/photos/{id?}', 	    array('as' => 'backend.photos', 			  'uses' => 'App\Controllers\Backend\PhotosController@index'));
    Route::post('admin/photos/activate',    array('as' => 'backend.photos.activate', 	  'uses' => 'App\Controllers\Backend\PhotosController@activate'));
    Route::post('admin/photos/deactivate', 	array('as' => 'backend.photos.deactivate',    'uses' => 'App\Controllers\Backend\PhotosController@deactivate'));

    //Backend: Spa Module
    Route::get('admin/spa/datatables',  	array('as' => 'backend.spa.datatables', 	  'uses' => 'App\Controllers\Backend\SpasController@dataTables'));
    Route::get('admin/spa/create', 	   	    array('as' => 'backend.spa.create', 	  	  'uses' => 'App\Controllers\Backend\SpasController@create'));
    Route::post('admin/spa', 		    	array('as' => 'backend.spa.store', 	  	      'uses' => 'App\Controllers\Backend\SpasController@store'));
    Route::get('admin/spa/autocomplete',    array('as' => 'backend.spa.autocomplete',     'uses' => 'App\Controllers\Backend\SpasController@autocomplete'));
    Route::get('admin/spa/{id}/edit',   	array('as' => 'backend.spa.edit', 	  	      'uses' => 'App\Controllers\Backend\SpasController@edit'));
    Route::put('admin/spa/{id}/update', 	array('as' => 'backend.spa.update', 	  	  'uses' => 'App\Controllers\Backend\SpasController@update'));
    Route::delete('admin/spa/{id}', 		array('as' => 'backend.spa.destroy', 	      'uses' => 'App\Controllers\Backend\SpasController@destroy'));
    Route::get('admin/spa/',             	array('as' => 'backend.spa', 			      'uses' => 'App\Controllers\Backend\SpasController@index'));
    Route::post('admin/spa/activate', 	    array('as' => 'backend.spa.activate', 	      'uses' => 'App\Controllers\Backend\SpasController@activate'));
    Route::post('admin/spa/deactivate', 	array('as' => 'backend.spa.deactivate',       'uses' => 'App\Controllers\Backend\SpasController@deactivate'));
    Route::delete('admin/spa/service/{id}', array('as' => 'backend.spa.destroy_service',    'uses' => 'App\Controllers\Backend\SpasController@destroySpaService'));

    //Backend: Treatments Module
    Route::get('admin/treatments/datatables',  	array('as' => 'backend.treatments.datatables', 	  'uses' => 'App\Controllers\Backend\TreatmentsController@dataTables'));
    Route::get('admin/treatments/create', 	   	array('as' => 'backend.treatments.create', 	  	  'uses' => 'App\Controllers\Backend\TreatmentsController@create'));
    Route::post('admin/treatments', 		   	array('as' => 'backend.treatments.store', 	  	      'uses' => 'App\Controllers\Backend\TreatmentsController@store'));
    Route::get('admin/treatments/autocomplete', array('as' => 'backend.treatments.autocomplete',     'uses' => 'App\Controllers\Backend\TreatmentsController@autocomplete'));
    Route::get('admin/treatments/{id}/edit',   	array('as' => 'backend.treatments.edit', 	  	      'uses' => 'App\Controllers\Backend\TreatmentsController@edit'));
    Route::put('admin/treatments/{id}/update', 	array('as' => 'backend.treatments.update', 	  	  'uses' => 'App\Controllers\Backend\TreatmentsController@update'));
    Route::delete('admin/treatments/{id}', 		array('as' => 'backend.treatments.destroy', 	      'uses' => 'App\Controllers\Backend\TreatmentsController@destroy'));
    Route::get('admin/treatments/',          	array('as' => 'backend.treatments', 			      'uses' => 'App\Controllers\Backend\TreatmentsController@index'));
    Route::post('admin/treatments/activate', 	array('as' => 'backend.treatments.activate', 	      'uses' => 'App\Controllers\Backend\TreatmentsController@activate'));
    Route::post('admin/treatments/deactivate', 	array('as' => 'backend.treatments.deactivate',       'uses' => 'App\Controllers\Backend\TreatmentsController@deactivate'));

    //Backend: Rooms Module
    Route::get('admin/rooms/datatables',  	array('as' => 'backend.rooms.datatables', 	  'uses' => 'App\Controllers\Backend\RoomsController@dataTables'));
    Route::get('admin/rooms/create', 	   	array('as' => 'backend.rooms.create', 	  	  'uses' => 'App\Controllers\Backend\RoomsController@create'));
    Route::post('admin/rooms', 		    	array('as' => 'backend.rooms.store', 	  	      'uses' => 'App\Controllers\Backend\RoomsController@store'));
    Route::get('admin/rooms/autocomplete',  array('as' => 'backend.rooms.autocomplete',     'uses' => 'App\Controllers\Backend\RoomsController@autocomplete'));
    Route::get('admin/rooms/{id}/edit',   	array('as' => 'backend.rooms.edit', 	  	      'uses' => 'App\Controllers\Backend\RoomsController@edit'));
    Route::put('admin/rooms/{id}/update', 	array('as' => 'backend.rooms.update', 	  	  'uses' => 'App\Controllers\Backend\RoomsController@update'));
    Route::delete('admin/rooms/{id}', 		array('as' => 'backend.rooms.destroy', 	      'uses' => 'App\Controllers\Backend\RoomsController@destroy'));
    Route::get('admin/rooms/',          	array('as' => 'backend.rooms', 			      'uses' => 'App\Controllers\Backend\RoomsController@index'));
    Route::post('admin/rooms/activate', 	array('as' => 'backend.rooms.activate', 	      'uses' => 'App\Controllers\Backend\RoomsController@activate'));
    Route::post('admin/rooms/deactivate', 	array('as' => 'backend.rooms.deactivate',       'uses' => 'App\Controllers\Backend\RoomsController@deactivate'));
    Route::put('admin/rooms/make_default_photo/{hotel_id}/{photo_id}',     array('as' => 'backend.rooms.make_default_photo',   'uses' => 'App\Controllers\Backend\RoomsController@makeDefaultPhoto'));

    //Backend: Restaurants Module
    Route::get('admin/restaurants/',          	      array('as' => 'backend.restaurants', 			      'uses' => 'App\Controllers\Backend\RestaurantsController@index'));
    Route::get('admin/restaurants/datatables', 		  array('as' => 'backend.restaurants.datatables',   'uses' => 'App\Controllers\Backend\RestaurantsController@dataTables'));
    Route::get('admin/restaurants/create', 	          array('as' => 'backend.restaurants.create', 	   'uses' => 'App\Controllers\Backend\RestaurantsController@create'));
    Route::get('admin/restaurants/{id}/edit',  		  array('as' => 'backend.restaurants.edit', 	  	   'uses' => 'App\Controllers\Backend\RestaurantsController@edit'));
    Route::put('admin/restaurants/{id}/update',		  array('as' => 'backend.restaurants.update', 	   'uses' => 'App\Controllers\Backend\RestaurantsController@update'));
    Route::delete('admin/restaurants/{id}', 		  array('as' => 'backend.restaurants.destroy', 	   'uses' => 'App\Controllers\Backend\RestaurantsController@destroy'));
    Route::post('admin/restaurants/activate',      	  array('as' => 'backend.restaurants.activate', 	   'uses' => 'App\Controllers\Backend\RestaurantsController@activate'));
    Route::post('admin/restaurants/deactivate',	      array('as' => 'backend.restaurants.deactivate',   'uses' => 'App\Controllers\Backend\RestaurantsController@deactivate'));
    Route::post('admin/restaurants', 		          array('as' => 'backend.restaurants.store', 	  	      'uses' => 'App\Controllers\Backend\RestaurantsController@store'));
    Route::put('admin/restaurants/make_default_photo/{hotel_id}/{photo_id}',     array('as' => 'backend.restaurants.make_default_photo',   'uses' => 'App\Controllers\Backend\RestaurantsController@makeDefaultPhoto'));

    //Backend: Amusements Module
    Route::get('admin/amusements/',                array('as' => 'backend.amusements', 			      'uses' => 'App\Controllers\Backend\AmusementsController@index'));
    Route::get('admin/amusements/datatables',      array('as' => 'backend.amusements.datatables',   'uses' => 'App\Controllers\Backend\AmusementsController@dataTables'));
    Route::get('admin/amusements/create', 	       array('as' => 'backend.amusements.create', 	   'uses' => 'App\Controllers\Backend\AmusementsController@create'));
    Route::get('admin/amusements/{id}/edit',   	   array('as' => 'backend.amusements.edit', 	  	   'uses' => 'App\Controllers\Backend\AmusementsController@edit'));
    Route::put('admin/amusements/{id}/update', 	   array('as' => 'backend.amusements.update', 	   'uses' => 'App\Controllers\Backend\AmusementsController@update'));
    Route::delete('admin/amusements/{id}', 		   array('as' => 'backend.amusements.destroy', 	   'uses' => 'App\Controllers\Backend\AmusementsController@destroy'));
    Route::post('admin/amusements/activate', 	   array('as' => 'backend.amusements.activate', 	   'uses' => 'App\Controllers\Backend\AmusementsController@activate'));
    Route::post('admin/amusements/deactivate', 	   array('as' => 'backend.amusements.deactivate',   'uses' => 'App\Controllers\Backend\AmusementsController@deactivate'));
    Route::post('admin/amusements', 		       array('as' => 'backend.amusements.store', 	  	      'uses' => 'App\Controllers\Backend\AmusementsController@store'));
    Route::put('admin/amusements/make_default_photo/{hotel_id}/{photo_id}',     array('as' => 'backend.amusements.make_default_photo',   'uses' => 'App\Controllers\Backend\AmusementsController@makeDefaultPhoto'));

    //Backend: Highlights Module
    Route::get('admin/hotel_highlights/',          	      array('as' => 'backend.hotel_highlights', 			      'uses' => 'App\Controllers\Backend\HighlightsController@index'));
    Route::get('admin/hotel_highlights/datatables',		  array('as' => 'backend.hotel_highlights.datatables',   'uses' => 'App\Controllers\Backend\HighlightsController@dataTables'));
    Route::get('admin/hotel_highlights/create',           array('as' => 'backend.hotel_highlights.create', 	   'uses' => 'App\Controllers\Backend\HighlightsController@create'));
    Route::get('admin/hotel_highlights/{id}/edit',		  array('as' => 'backend.hotel_highlights.edit', 	  	   'uses' => 'App\Controllers\Backend\HighlightsController@edit'));
    Route::put('admin/hotel_highlights/{id}/update',	  array('as' => 'backend.hotel_highlights.update', 	   'uses' => 'App\Controllers\Backend\HighlightsController@update'));
    Route::delete('admin/hotel_highlights/{id}', 	      array('as' => 'backend.hotel_highlights.destroy', 	   'uses' => 'App\Controllers\Backend\HighlightsController@destroy'));
    Route::post('admin/hotel_highlights/activate', 		  array('as' => 'backend.hotel_highlights.activate', 	   'uses' => 'App\Controllers\Backend\HighlightsController@activate'));
    Route::post('admin/hotel_highlights/deactivate', 	  array('as' => 'backend.hotel_highlights.deactivate',   'uses' => 'App\Controllers\Backend\HighlightsController@deactivate'));
    Route::post('admin/hotel_highlights', 		    	  array('as' => 'backend.hotel_highlights.store', 	  	      'uses' => 'App\Controllers\Backend\HighlightsController@store'));
    Route::put('admin/hotel_highlights/make_default_photo/{hotel_id}/{photo_id}',     array('as' => 'backend.hotel_highlights.make_default_photo',   'uses' => 'App\Controllers\Backend\HighlightsController@makeDefaultPhoto'));
    Route::put('admin/hotel_highlights/make_default_quote_photo/{hotel_id}/{photo_id}',     array('as' => 'backend.hotel_highlights.make_default_quote_photo',   'uses' => 'App\Controllers\Backend\HighlightsController@makeDefaultQuotePhoto'));

    //Backend: Awards Module
    Route::get('admin/hotel_awards/',          	      array('as' => 'backend.hotel_awards', 			      'uses' => 'App\Controllers\Backend\AwardsController@index'));
    Route::get('admin/hotel_awards/datatables',  	  array('as' => 'backend.hotel_awards.datatables',   'uses' => 'App\Controllers\Backend\AwardsController@dataTables'));
    Route::get('admin/hotel_awards/create', 	      array('as' => 'backend.hotel_awards.create', 	   'uses' => 'App\Controllers\Backend\AwardsController@create'));
    Route::get('admin/hotel_awards/{id}/edit',   	  array('as' => 'backend.hotel_awards.edit', 	  	   'uses' => 'App\Controllers\Backend\AwardsController@edit'));
    Route::put('admin/hotel_awards/{id}/update', 	  array('as' => 'backend.hotel_awards.update', 	   'uses' => 'App\Controllers\Backend\AwardsController@update'));
    Route::delete('admin/hotel_awards/{id}', 		  array('as' => 'backend.hotel_awards.destroy', 	   'uses' => 'App\Controllers\Backend\AwardsController@destroy'));
    Route::post('admin/hotel_awards/activate', 		  array('as' => 'backend.hotel_awards.activate', 	   'uses' => 'App\Controllers\Backend\AwardsController@activate'));
    Route::post('admin/hotel_awards/deactivate', 	  array('as' => 'backend.hotel_awards.deactivate',   'uses' => 'App\Controllers\Backend\AwardsController@deactivate'));
    Route::post('admin/hotel_awards', 		    	  array('as' => 'backend.hotel_awards.store', 	  	      'uses' => 'App\Controllers\Backend\AwardsController@store'));
    Route::put('admin/hotel_awards/make_default_photo/{hotel_id}/{photo_id}',     array('as' => 'backend.hotel_awards.make_default_photo',   'uses' => 'App\Controllers\Backend\AwardsController@makeDefaultPhoto'));
    
    //Backend: Discounts Module
    Route::get('admin/discounts/',          	      array('as' => 'backend.discounts', 			      'uses' => 'App\Controllers\Backend\DiscountsController@index'));
    Route::get('admin/discounts/datatables',  		  array('as' => 'backend.discounts.datatables',   'uses' => 'App\Controllers\Backend\DiscountsController@dataTables'));
    Route::get('admin/discounts/create', 	          array('as' => 'backend.discounts.create', 	   'uses' => 'App\Controllers\Backend\DiscountsController@create'));
    Route::get('admin/discounts/{id}/edit',   	      array('as' => 'backend.discounts.edit', 	  	   'uses' => 'App\Controllers\Backend\DiscountsController@edit'));
    Route::put('admin/discounts/{id}/update', 		  array('as' => 'backend.discounts.update', 	   'uses' => 'App\Controllers\Backend\DiscountsController@update'));
    Route::delete('admin/discounts/{id}', 			  array('as' => 'backend.discounts.destroy', 	   'uses' => 'App\Controllers\Backend\DiscountsController@destroy'));
    Route::post('admin/discounts/activate', 		  array('as' => 'backend.discounts.activate', 	   'uses' => 'App\Controllers\Backend\DiscountsController@activate'));
    Route::post('admin/discounts/deactivate', 		  array('as' => 'backend.discounts.deactivate',   'uses' => 'App\Controllers\Backend\DiscountsController@deactivate'));
    Route::post('admin/discounts', 		    	      array('as' => 'backend.discounts.store', 	  	      'uses' => 'App\Controllers\Backend\DiscountsController@store'));

    //Backend: Location groups Module
    Route::get('admin/location_groups/datatables',  		  array('as' => 'backend.location_groups.datatables',     'uses' => 'App\Controllers\Backend\LocationGroupsController@dataTables'));
    Route::post('admin/location_groups',		  			  array('as' => 'backend.location_groups.store', 	       'uses' => 'App\Controllers\Backend\LocationGroupsController@store'));
    Route::get('admin/location_groups/{id}/edit',   		  array('as' => 'backend.location_groups.edit', 	  	   'uses' => 'App\Controllers\Backend\LocationGroupsController@edit'));
    Route::get('admin/location_groups/create', 	              array('as' => 'backend.location_groups.create', 	       'uses' => 'App\Controllers\Backend\LocationGroupsController@create'));
    Route::put('admin/location_groups/{id}/update', 		  array('as' => 'backend.location_groups.update', 	       'uses' => 'App\Controllers\Backend\LocationGroupsController@update'));
    Route::delete('admin/location_groups/{id}', 			  array('as' => 'backend.location_groups.destroy', 	   'uses' => 'App\Controllers\Backend\LocationGroupsController@destroy'));
    Route::delete('admin/location_groups/hotel/{id}/{location_id?}', 		  array('as' => 'backend.location_groups.destroy_hotel', 	   'uses' => 'App\Controllers\Backend\LocationGroupsController@destroyHotel'));
    Route::get('admin/location_groups/autocomplete', 	      array('as' => 'backend.location_groups.autocomplete',   'uses' => 'App\Controllers\Backend\LocationGroupsController@autocomplete'));
    Route::get('admin/location_groups/', 		              array('as' => 'backend.location_groups', 			   'uses' => 'App\Controllers\Backend\LocationGroupsController@index'));
    Route::post('admin/location_groups/activate', 	    	  array('as' => 'backend.location_groups.activate', 	   'uses' => 'App\Controllers\Backend\LocationGroupsController@activate'));
    Route::post('admin/location_groups/deactivate', 		  array('as' => 'backend.location_groups.deactivate',     'uses' => 'App\Controllers\Backend\LocationGroupsController@deactivate'));
});