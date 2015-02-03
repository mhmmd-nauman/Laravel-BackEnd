<?php namespace App\Controllers\Frontend;

use Illuminate\Http\Request;
use Sentry;
use View, URL, Redirect, Input, Response, App, DB, Session, Notification, Mail, Lang;
use \Photo, \Hotel, \Package, \PackageTreatments, \PackageRoom, \Settings, \Service, \Treatment, \Room, \Restaurant, \Amusement, \User;

class HotelController extends FrontendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return NULL;
	}

    /**
     *  General information page
     *
     * @param $name    hotel slug
     * @return mixed
     */
    public function general($name)
    {
        $errors = NULL;

        if ( Session::get('error') ) {
            $errors = Notification::error( Session::get('error') );
            $errors = $errors->toArray();
        }

        $hotel = Package::select(
            array(
                '*',
                'hotels.name AS hotel_name',
                'hotels.id AS hotel_id',
                'hotels.slug AS hotel_slug',
                'hotels.default_photo_id AS hotel_photo_id',
                'hotels.description AS hotel_description',
                'packages.id AS package_id'
            )
        )
            ->leftJoin('hotels', 'hotels.id', '=', 'packages.hotel_id')
            ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
            ->leftJoin('addresses', 'hotels.address_id', '=', 'addresses.id')
            ->where('hotels.slug', '=', $this->slugify($name))
            ->where('spas.status', '=', 1)
            ->where('hotels.status', '=', 1)
            ->get();

        if ( $hotel->count() ) {
            $hotel = $hotel[0];
            $hotel_images = Hotel::getHotelPhotos($hotel->id);
        } else {
            App::abort(404);
        }


        return View::make('frontend.hotel.general', array(
            'hotel'        => $hotel,
            'hotel_images' => json_encode($hotel_images),
            'title'        => Lang::get("seo.titles.hotel",  array('hotel' => $hotel->hotel_name )),
            'errors'       => $errors,

        ));
    }

    /**
     * Package view (book)
     *
     * @param $hotel_slug
     * @param $package_slug
     * @return mixed
     */
    public function package($hotel_slug, $package_slug)
    {
        $package_model = new Package();
        $package_room_model = new \PackageRoom();
        $js_calendar_data = array();
        $errors = NULL;

        if ( Session::get('error') ) {
            $errors = Notification::error( Session::get('error') );
            $errors = $errors->toArray();
        }

        $date_from = Session::get('packages_check.date_from', FALSE);
        $date_to   = Session::get('packages_check.date_to', FALSE);
        $dates_hotel_id = Session::get('packages_check.hotel_id', FALSE);

        $hotel = Hotel::select(
            array(
                '*',
                'hotels.name AS hotel_name',
                'hotels.description AS hotel_description',
                'hotels.id AS hotel_id',
                'hotels.slug AS hotel_slug',
                'hotels.default_photo_id AS hotel_photo_id',
                'spas.name AS spa_name',
                'packages.name AS package_name',
                'packages.description AS package_description',
                'packages.default_photo_id AS package_photo_id',
                'packages.id AS package_id',
                'packages.slug AS package_slug',
                'rooms.max_residents AS room_max_residents',
                DB::raw('CAST(`overnights_max` as signed) - CAST(`overnights_min` as signed) AS nights_count')
            )
        )
            ->leftJoin('packages', 'packages.hotel_id', '=', 'hotels.id')
            ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
            ->leftJoin('addresses', 'hotels.address_id', '=', 'addresses.id')
            ->leftJoin('package_rooms', 'package_rooms.package_id', '=', 'packages.id')
            ->leftJoin('rooms', 'rooms.id', '=', 'package_rooms.room_id')
            ->where('packages.slug', '=', $this->slugify($package_slug))
            ->where('hotels.slug', '=', $this->slugify($hotel_slug))
            ->where('spas.status', '=', 1)
            ->where('hotels.status', '=', 1)
            ->where('rooms.status', '=', 1)
            ->where('packages.status', '=', 1)
            ->get();

        if ( $hotel->count() ) {
            $hotel = $hotel[0];

            $package_rooms   = $package_room_model->getAllPackageRooms($hotel->package_id);
            $package_persons = $package_model->findPackageRoomPersonCombinations($package_rooms);

            foreach( $package_persons as $k => $person ) {
                $package_persons[$k] = $person;
            }

            $hotel_packages = $package_model->get_hotel_packages(
                array('id' => $hotel->hotel_id),
                array('with_price' => TRUE)
            );

            $package_price = $package_model->calculate_package_price($hotel->package_id);
            $hotel_images = Hotel::getHotelPhotos($hotel->hotel_id);

            $nights_count = 1;
            $formatted_date_from = date( 'Y-m-d', strtotime( date('Y-m-d') . ' + '.$hotel->overnights_min.' day' ));
            $formatted_date_to   = date( 'Y-m-d', strtotime( date('Y-m-d') . ' + '.($hotel->overnights_min+1).' day' ));

            if ( $date_from && $date_to ) {
                $nights_count = $package_model->getDatesDifference($date_from, $date_to);

                $tmp_date = date_parse_from_format('d/m - Y', $date_from);
                $formatted_date_from = $tmp_date['year'].'-'.(($tmp_date['month'] < 10) ? '0'.$tmp_date['month'] : $tmp_date['month']) .'-'.(($tmp_date['day'] < 10) ? '0'.$tmp_date['day'] : $tmp_date['day']);
                $tmp_date = date_parse_from_format('d/m - Y', $date_to);
                $formatted_date_to = $tmp_date['year'].'-'.(($tmp_date['month'] < 10) ? '0'.$tmp_date['month'] : $tmp_date['month']) .'-'.(($tmp_date['day'] < 10) ? '0'.$tmp_date['day'] : $tmp_date['day']);

                if ( FALSE === $nights_count ) {
                    $nights_count = 1;
                }
            }

            if ( $date_from ) {
                if ( $hotel->hotel_id != $dates_hotel_id ) { // if hotel changed, reset prefilled dates
                    $date_from = NULL;
                    $date_to   = NULL;
                    $nights_count = 1;
                }

                $package_dates_validation = $package_model->checkPackageDates($formatted_date_from, $formatted_date_to, $hotel->package_id);

                if ($package_dates_validation['result'] == FALSE) {
                    $date_from = NULL;
                    $date_to   = NULL;
                    $nights_count = 1;
                }

//                $date_from_weekday = strtolower(date('D', strtotime($formatted_date_from)));
//                if ( !empty($hotel->available_weekdays) && mb_strpos(mb_strtolower($hotel->available_weekdays), $date_from_weekday) !== FALSE ) { // if can't find available weekday, reset dates
//                    $date_from = NULL;
//                    $date_to   = NULL;
//                    $nights_count = 1;
//                }

            }

            $package_available_weekdays = array();
            if ( !empty($hotel->available_weekdays) ) {
                $package_available_weekdays = explode('|:|', rtrim($hotel->available_weekdays, '|:|'));

                foreach($package_available_weekdays as $k => $wday) {
                    switch($wday) {
                        case 'Mon':
                            $package_available_weekdays[$k] = '1';
                            break;
                        case 'Tue':
                            $package_available_weekdays[$k] = '2';
                            break;
                        case 'Wed':
                            $package_available_weekdays[$k] = '3';
                            break;
                        case 'Thu':
                            $package_available_weekdays[$k] = '4';
                            break;
                        case 'Fri':
                            $package_available_weekdays[$k] = '5';
                            break;
                        case 'Sat':
                            $package_available_weekdays[$k] = '6';
                            break;
                        case 'Sun':
                            $package_available_weekdays[$k] = '0';
                            break;
                    }
                }
            }

            $js_calendar_data['daysInAdvance']     = $hotel->days_in_advance;
            $js_calendar_data['availableWeekDays'] = $package_available_weekdays;

            $package_includes = array();
            if ( $hotel->package_includes ) {
                $package_includes = explode('|:|', $hotel->package_includes);
                sort($package_includes);
            }

//var_dump( $package_model->checkPackageDates($formatted_date_from, $formatted_date_to, $hotel->package_id) );

            return View::make('frontend.hotel.package', array(
                'hotel'            => $hotel,
                'title'            => Lang::get("seo.titles.package",  array('hotel' => $hotel->hotel_name, 'package' => $hotel->package_name )),
                'meta_description' => Lang::get("seo.meta_descriptions.package", array('hotel' => $hotel->hotel_name, 'package' => $hotel->package_name )),
                'package_includes' => $package_includes,
                'package_persons'  => $package_persons,
                'available_weekdays' => $package_available_weekdays,
                'hotel_packages'   => $hotel_packages,
                'hotel_images'     => json_encode($hotel_images),
                'package_price'    => $package_price,
                'nights_count'     => $nights_count,
                'date_from'        => $date_from,
                'date_to'          => $date_to,
                'formatted_date_from' => $formatted_date_from,
                'formatted_date_to'   => $formatted_date_to,
                'errors'              => $errors,
                'js_calendar_data'    => json_encode($js_calendar_data),

            ));
        } else {
            App::abort(404);
        }

    }


    /**
     * Packages page
     *
     * @param $hotel_slug
     * @return mixed
     */
    public function packages($hotel_slug)
    {
        $errors = NULL;
        $package_model      = new Package();
        $package_room_model = new PackageRoom();

        // $filter_date_from = Input::get('date_from', Session::get('packages_check.date_from', FALSE));
        // $filter_date_to   = Input::get('date_to', Session::get('packages_check.date_to', FALSE));

        $filter_date_from = Input::get('date_from');
        $filter_date_to   = Input::get('date_to');
        $filter_persons   = Input::get('persons');

        if (date_create_from_format('d/m - Y', $filter_date_from) ) {
            Session::set('packages_check.date_from', $filter_date_from);
        }

        if (date_create_from_format('d/m - Y', $filter_date_to) ) {
            Session::set('packages_check.date_to', $filter_date_to);
        }

        if ( Input::get('clear_filters', FALSE) ) {
            Session::forget('packages_check.date_from');
            Session::forget('packages_check.date_to');
        }

        // prepare calendar config string for frontend
        $calendar_date_str = '[';
        if ( $filter_date_from ) {
            $tmp = date_parse_from_format('d/m - Y', $filter_date_from);
            $calendar_date_str .= '"'.date('d/m - Y', strtotime( "{$tmp['year']}-{$tmp['month']}-{$tmp['day']}" . ' + 1 day' )).'"';
        } else {
            $calendar_date_str .= '"'.date('d/m - Y', strtotime( date('Y-m-d') . ' + 1 day' )).'"';
        }
        $calendar_date_str .= ', false]';

        if ( $filter_date_from ) {
            $filter_date_from = date_parse_from_format('d/m - Y', $filter_date_from);
            $filter_date_from = $filter_date_from['year'].'-'.$filter_date_from['month'].'-'.$filter_date_from['day'];
        }

        if ( $filter_date_to ) {
            $filter_date_to = date_parse_from_format('d/m - Y', $filter_date_to);
            $filter_date_to = $filter_date_to['year'].'-'.$filter_date_to['month'].'-'.$filter_date_to['day'];
        }

        $weekday_array = array();
        if ( strtotime($filter_date_from) || strtotime($filter_date_to) ) {

            $datetime1 = date_create($filter_date_from);
            $datetime2 = date_create($filter_date_to);

            $daterange = new \DatePeriod($datetime1, new \DateInterval('P1D'), $datetime2);
            foreach($daterange as $date) {
                $weekday_array[$date->format('N')] = $date->format('D');
            }
        }

        $hotel = Package::select(
            array(
                '*',
                'hotels.name AS hotel_name',
                'hotels.id AS hotel_id',
                'hotels.slug AS hotel_slug',
                'hotels.description AS hotel_description',
                'hotels.default_photo_id AS hotel_photo_id',
                'packages.id AS package_id',
                'packages.name AS package_name',
                'packages.default_photo_id AS package_photo_id',
                'packages.slug AS package_slug',
                DB::raw('CAST(`overnights_max` as signed) - CAST(`overnights_min` as signed) AS nights_count')
            )
        )
            ->leftJoin('hotels', 'hotels.id', '=', 'packages.hotel_id')
            ->leftJoin('addresses', 'hotels.address_id', '=', 'addresses.id')
            ->leftJoin('package_rooms', 'package_rooms.package_id', '=', 'packages.id')
            ->leftJoin('rooms', 'rooms.id', '=', 'package_rooms.room_id')
            ->where('hotels.slug', '=', $this->slugify($hotel_slug))
            ->where('hotels.status', '=', 1)
            ->where('rooms.status', '=', 1)
            ->where('packages.status', '=', 1)
            ->orderBy('package_name')
            ->groupBy('packages.id');

        $all_packages = clone $hotel; // clone db query object without filters
        $all_packages = $all_packages->get(); // and select all hotel packages without filtering

        if ( $filter_date_from ) {
            $hotel->where(function($query) use ($weekday_array) {
                if ( $weekday_array ) {
                    foreach($weekday_array as $weekday) {
                        $query->orWhere('packages.available_weekdays', 'LIKE', "%$weekday%");
                    }
                }

                //$query->orWhere('packages.available_weekdays', '=', '');
            });
        }

        $hotel = $hotel->get(); // get db query object for get hotel info

        if ( $filter_persons || $filter_date_from || $filter_date_to ) { // if set any filter
            $filtered_packages = clone $hotel;  // db query object with filtered packages
        }else{
            $filtered_packages = array();
        }

        if ( strtotime($filter_date_from) > strtotime($filter_date_to) ) { // if date to bigger date from, break date to
            $filter_date_to = '';
        }

        $filtered_packages_ids = array(); // array of ids filtered hotel packages
        $all_packages_ids      = array(); // array of ids all hotels packages

        $packages_nights      = array();
        $all_packages_persons = array(
            1 => '1 '.Lang::choice('booking.guests', 1), //<-- pluralization
            2 => '2 '.Lang::choice('booking.guests', 2),
            3 => '3 '.Lang::choice('booking.guests', 3),
            4 => '4 '.Lang::choice('booking.guests', 4),
            5 => '5 '.Lang::choice('booking.guests', 5),
            6 => '6 '.Lang::choice('booking.guests', 6),
            7 => '7 '.Lang::choice('booking.guests', 7),
            8 => '8 '.Lang::choice('booking.guests', 8),
        );

        if ( $all_packages->count() ) {
            foreach($all_packages as $k => $package) {
                $all_packages_ids[$package->package_id] = $package->package_id; // array with all packages ids
                $price = $package_model->calculate_package_price($package->package_id);

                if( $package->available_weekdays ) {
                    $weekdays = $package_model->invertPackageWeekdays($package->available_weekdays);
                    $weekdays_arr = array();
                    foreach(explode('|:|', $weekdays) as $weekday) {
                        if ( $weekday ) {
                            $weekdays_arr[] = Lang::get('weekdays.'.mb_strtolower($weekday)); // make available weekdays array
                        }
                    }
                    $weekday_str = implode(', ', $weekdays_arr);
                    $weekday_str = mb_strtoupper(mb_substr($weekday_str, 0, 1)).mb_substr($weekday_str, 1, mb_strlen($weekday_str)); // ucfirst string
                    $all_packages[$k]->available_weekdays = $weekday_str;
                }

                $all_packages[$k]->night_span = '';
                if($package->overnights_max == 0) {
                    $all_packages[$k]->night_span = Lang::choice('packages.min_nights',$package->overnights_min);
                }elseif($package->overnights_max == $package->overnights_min){
                    $all_packages[$k]->night_span = $package->overnights_min." ".Lang::choice('booking.nights',$package->overnights_min);
                }else{
                    $all_packages[$k]->night_span = $package->overnights_min." - ".$package->overnights_max." ".Lang::choice('booking.nights',$package->overnights_max);
                }

                if ( $price > 0 ) {
                    $all_packages[$k]->package_price = $price;
                } else {
                    unset($all_packages[$k]);
                }
                $packages_nights[$package->overnights_min] = $package->overnights_min .' '. Lang::get('packages.filter_nights_select');
            }
        }
///*
        if ( $filtered_packages ) {
            foreach($filtered_packages as $package) {
                $filtered_packages_ids[$package->package_id] = $package->package_id; // set array filtered packages
            }
        }

        if ( $all_packages->count() ) {
            foreach($all_packages as $package) {
                $package_rooms   = $package_room_model->getAllPackageRooms($package->package_id); // get all rooms
                $package_persons = $package_model->findPackageRoomPersonCombinations($package_rooms); // get all rooms persons

                if ( $filter_persons && !in_array($filter_persons, $package_persons) ) { // if have filter by persons, and number of persons hasn't in package
                    $filtered_packages_ids[$package->package_id] = $package->package_id;
                }

                if ( $filter_date_from && $filter_date_to ) {
                    $package_dates_validation = $package_model->checkPackageDates($filter_date_from, $filter_date_to, $package->package_id);
                    if ( $package_dates_validation['result'] == FALSE ) {
                        $filtered_packages_ids[$package->package_id] = $package->package_id;
                    }
                }
            }
        }
//*/
        $disabled_packages_ids = $filtered_packages_ids;

        if ( $hotel->count() ) {
            $hotel = $hotel[0];
            $hotel_images    = Hotel::getHotelPhotos($hotel->hotel_id);
        } else {
            $hotel = Hotel::select(array(
                    '*',
                    'hotels.name AS hotel_name',
                    'hotels.id AS hotel_id',
                    'hotels.slug AS hotel_slug',
                    'hotels.description AS hotel_description',
                    'hotels.default_photo_id AS hotel_photo_id',
                )
            )
                ->where('hotels.slug', '=', $this->slugify($hotel_slug))
                ->first();
            $hotel_images = Hotel::getHotelPhotos($hotel->id);
        }

        Session::set('packages_check.hotel_id', $hotel->hotel_id); // set hotel id, for which set filters dates

        if ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
            return Response::json($disabled_packages_ids);
        } else {
            return View::make('frontend.hotel.packages', array(
                'title'             => Lang::get("seo.titles.packages",  array('hotel' => $hotel->hotel_name)),
                'meta_description'  => Lang::get("seo.meta_descriptions.packages",  array('hotel' => $hotel->hotel_name)),
                'hotel'             => $hotel,
                'hotel_images'      => json_encode($hotel_images),
                'packages'          => $all_packages,
                'disabled_packages' => $disabled_packages_ids,
                'packages_persons'  => $all_packages_persons,
                'filter_date_from'  => $filter_date_from,
                'filter_date_to'    => $filter_date_to,
                'errors'            => $errors,
                'calendar_date_str' => $calendar_date_str,
            ));
        }

    }


    /**
     * Search page
     *
     * @return mixed
     */
    public function search()
    {
        $query = e(Input::get('s'));
        $errors = NULL;

        if ( $query ) {
            $hotels = Hotel::select(array(
                '*',
                'hotels.name AS hotel_name',
                'hotels.description AS hotel_description',
                'hotels.id AS hotel_id',
                'hotels.slug AS hotel_slug',
                'hotels.default_photo_id AS hotel_photo_id',
            ))
                       ->leftJoin('addresses', 'addresses.id', '=', 'hotels.address_id')
                       ->leftJoin('photos', 'photos.id', '=', 'hotels.default_photo_id')
                       ->where('addresses.address', 'like', "%$query%")
                       ->orWhere('hotels.description', 'like', "%$query%")
                       ->orWhere('hotels.name', 'like', "%$query%")
                       ->where('hotels.status', '=', 1)
                       ->orderBy('hotel_name', 'ASC')
                       ->get();

        } else {

            $hotels = Hotel::select(array(
                '*',
                'hotels.name AS hotel_name',
                'hotels.description AS hotel_description',
                'hotels.id AS hotel_id',
                'hotels.slug AS hotel_slug',
                'hotels.default_photo_id AS hotel_photo_id',
            ))
                        ->leftJoin('addresses', 'addresses.id', '=', 'hotels.address_id')
                        ->leftJoin('photos', 'photos.id', '=', 'hotels.default_photo_id')
                        ->where('hotels.status', '=', 1)
                        //->orderBy('hotel_name', 'ASC')
                        ->get();
        }

        $package_model = new Package();
        $hotels_locations = \LocationGroup::where('status', '=', 1)
                                          ->orderBy('name', 'ASC')
                                          ->get();

        $js_hotels = array();
        $hotels_count = 0;
        if ( $hotels ) {
            foreach ($hotels as $k => $hotel) {

                //get all hotel packages
                $hotel_packages = $package_model->get_hotel_packages(array('id' => $hotel->hotel_id));
                $min_package_price = 0;

                if ( $hotel_packages->count() ) {
                    //get package price
                    $min_package_price = $package_model->calculate_package_price($hotel_packages[0]->package_id);

                    //if packages more than one, find min price
                    if ( $hotel_packages->count() > 1 ) {
                        foreach($hotel_packages as $package) {
                            $p_price = $package_model->calculate_package_price($package->package_id);
                            if ( $p_price > 0 ) {
                                if ( $min_package_price == 0 ) { // if first min price 0
                                    $min_package_price = $p_price;
                                } else if ( $p_price < $min_package_price ) { // if first price more than 0, but bigger than current packet price
                                    $min_package_price = $p_price;
                                }
                            }
                        }
                    }
                }

                if ( $min_package_price > 0 ) {
                    $hotels_count++;
                }

                $hotels[$k]->min_package_price = $min_package_price;

                if ( $min_package_price > 0 ) {
                    // hotels info for google maps
                    $js_hotels[$hotel->hotel_id] = array(
                        'id' => $hotel->hotel_id,
                        'description' => strip_tags($hotel->hotel_description),
                        'name' => $hotel->hotel_name,
                        'lat' => $hotel->lat,
                        'lng' => $hotel->lng,
                        'url' => URL::route('frontend.hotel.highlights', array('name' => $hotel->hotel_slug)),
                        'booking_url' => URL::route('frontend.hotel.packages', array('name' => $hotel->hotel_slug)),
                        'photo'       => URL::route('frontend.image.photo', array( 'size' => 'medium', 'id' => $hotel->default_photo_id )),
                        'price'       => $hotel->min_package_price
                    );
                }
            }
        }

        $js_hotels = json_encode($js_hotels);

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';
        return View::make('frontend.hotel.search', array(
            'hotels'           => $hotels,
            'hotels_locations' => $hotels_locations,
            'js_hotels'    => $js_hotels,
            'hotels_count' => $hotels_count,
            'scripts'      => $scripts,
            'errors'       => $errors,
            'title'        => Lang::get("seo.titles.search"),
            'meta_description'        => Lang::get("seo.meta_descriptions.search"),


        ));
    }

    /**
     * Search locations page
     *
     * @param $location_slug
     * @return mixed
     */
    public function searchLocation($location_slug)
    {
        $errors = NULL;
        $hotels = NULL;
        $location_slug = $this->slugify($location_slug);

        $package_model = new Package();
        $hotels_locations = \LocationGroup::where('status', '=', 1)
                                          ->get();

        $location_hotels = \LocationGroup::select(array(
            'location_group_items.item_id AS hotel_id',
            'location_groups.name AS location_name',
            'location_groups.description AS location_description'
        ))
                                         ->where('slug', '=', $location_slug)
                                         ->where('status', '=', 1)
                                         ->leftJoin('location_group_items', 'location_group_items.location_group_id', '=', 'location_groups.id')
                                         ->get();

        if ( $location_hotels->count() ) {

            $location_name        = $location_hotels[0]->location_name;
            $location_description = $location_hotels[0]->location_description;

            $hotel_ids = array();
            foreach($location_hotels as $hotel) {
                $hotel_ids[] = $hotel->hotel_id;
            }

            $hotels = Hotel::select(array(
                '*',
                'hotels.name AS hotel_name',
                'hotels.description AS hotel_description',
                'hotels.id AS hotel_id',
                'hotels.slug AS hotel_slug',
                'hotels.default_photo_id AS hotel_photo_id',
            ))
                ->leftJoin('addresses', 'addresses.id', '=', 'hotels.address_id')
                ->leftJoin('photos', 'photos.id', '=', 'hotels.default_photo_id')
                ->where('hotels.status', '=', 1)
                ->whereIn('hotels.id', $hotel_ids)
                ->get();
        } else {
            App::abort(404);
        }

        $js_hotels = array();
        $hotels_count = 0;
        if ( $hotels ) {
            foreach ($hotels as $k => $hotel) {

                //get all hotel packages
                $hotel_packages = $package_model->get_hotel_packages(array('id' => $hotel->hotel_id));
                $min_package_price = 0;

                if ( $hotel_packages->count() ) {
                    //get package price
                    $min_package_price = $package_model->calculate_package_price($hotel_packages[0]->package_id);

                    //if packages more than one, find min price
                    if ( $hotel_packages->count() > 1 ) {
                        foreach($hotel_packages as $package) {
                            $p_price = $package_model->calculate_package_price($package->package_id);
                            if ( $p_price > 0 ) {
                                if ( $min_package_price == 0 ) { // if first min price 0
                                    $min_package_price = $p_price;
                                } else if ( $p_price < $min_package_price ) { // if first price more than 0, but bigger than current packet price
                                    $min_package_price = $p_price;
                                }
                            }
                        }
                    }
                }

                if ( $min_package_price > 0 ) {
                    $hotels_count++;
                }

                $hotels[$k]->min_package_price = $min_package_price;

                if ( $min_package_price > 0 ) {
                    // hotels info for google maps
                    $js_hotels[$hotel->hotel_id] = array(
                        'id' => $hotel->hotel_id,
                        'description' => strip_tags($hotel->hotel_description),
                        'name' => $hotel->hotel_name,
                        'lat' => $hotel->lat,
                        'lng' => $hotel->lng,
                        'url' => URL::route('frontend.hotel.highlights', array('name' => $hotel->hotel_slug)),
                        'booking_url' => URL::route('frontend.hotel.packages', array('name' => $hotel->hotel_slug)),
                        'photo'       => URL::route('frontend.image.photo', array( 'size' => 'medium', 'id' => $hotel->default_photo_id )),
                        'price'       => $hotel->min_package_price
                    );
                }
            }
        } else {
            App::abort(404);
        }

        $js_hotels = json_encode($js_hotels);

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';
        return View::make('frontend.hotel.search', array(
            'hotels'               => $hotels,
            'hotels_locations'     => $hotels_locations,
            'js_hotels'            => $js_hotels,
            'hotels_count'         => $hotels_count,
            'scripts'              => $scripts,
            'errors'               => $errors,
            'location_name'        => $location_name,
            'location_description' => $location_description,
            'title'                => Lang::get("seo.titles.search_location", array('location' => $location_name)),
            'meta_description'                => Lang::get("seo.meta_descriptions.search_location", array('location' => $location_name)),
        ));
    }

    /**
     * Booking page
     *
     * @param $hotel_slug
     * @param $package_slug
     * @return mixed
     */
    public function booking($hotel_slug, $package_slug)
    {
        $v = new \App\Services\Validators\Booking;
        $errors = NULL;
        $user   = Sentry::getUser();
        $user_name  = '';
        $user_phone = '';
        $user_email = '';
        $package_model = new Package();

        if ( Input::all() ) {
            if ( !$v->passes() ) {
                return Redirect::to(URL::previous())->with('error', $v->getErrors()->toArray());
            } else {
                Session::put('booking_data.package_id', Input::get('package_id'));
                Session::put('booking_data.date_from',  Input::get('from'));
                Session::put('booking_data.date_to',    Input::get('to'));
                Session::put('booking_data.persons',    Input::get('persons'));
                //Session::put('booking_data.nights',     Input::get('nights'));
            }
        }

        $package_id    = Session::get('booking_data.package_id');
        $date_from     = Session::get('booking_data.date_from');
        $date_to       = Session::get('booking_data.date_to');
        $persons       = Session::get('booking_data.persons');

        $package_validation = $package_model->checkPackageDates($date_from, $date_to, $package_id); // check package dates

        if ( $package_validation['result'] === FALSE ) {
            return Redirect::to(URL::previous())->with('error', $package_validation['message']);
        }

        if ( $user ) {
            $user_name  = $user->first_name;
            $user_phone = $user->phone;
            $user_email = $user->email;
        }

        if ( Session::get('error') ) {
            $errors = Notification::error( Session::get('error') );
            $errors = $errors->toArray();
        }

            $hotel = Package::select(
                array(
                    '*',
                    'hotels.name AS hotel_name',
                    'hotels.id AS hotel_id',
                    'hotels.slug AS hotel_slug',
                    'hotels.default_photo_id AS hotel_photo_id',
                    'hotels.description AS hotel_description',
                    'packages.id AS package_id',
                    'packages.default_photo_id AS package_photo_id',
                    'packages.slug AS package_slug'
                )
            )
                ->leftJoin('hotels', 'hotels.id', '=', 'packages.hotel_id')
                ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
                ->leftJoin('addresses', 'hotels.address_id', '=', 'addresses.id')
                ->where('hotels.slug', '=', $this->slugify($hotel_slug))
                ->where('spas.status', '=', 1)
                ->where('hotels.status', '=', 1)
                ->get();

            $hotel = $hotel[0];

            if ( $hotel->toArray() && $package_id ) {
                $package = Package::find($package_id);

                $nights_count = $package_model->getDatesDifference($date_from, $date_to, 'Y-m-d');
                if ( FALSE === $nights_count ) {
                    $nights_count = 1;
                }

                $package_includes = array();
                if ( $package->package_includes ) {
                    $package_includes = explode('|:|', rtrim($package->package_includes, '|:|'));
                    sort($package_includes);
                }

                $package_price = $package_model->calculate_package_price($package_id) * $nights_count;
                $full_price = $package_price * $persons;

                $js_translations['booking']['validation']['nameRequired']  = Lang::get('validation.required', ['attribute' => Lang::get('user.name')]);
                $js_translations['booking']['validation']['emailRequired'] = Lang::get('validation.required', ['attribute' => Lang::get('user.email')]);
                $js_translations['booking']['validation']['emailNotValid'] = Lang::get('validation.email', ['attribute' => Lang::get('user.email')]);
                $js_translations['booking']['validation']['phoneRequired'] = Lang::get('validation.required', ['attribute' => Lang::get('user.phone')]);;
                $js_translations['booking']['validation']['discountNotValid'] = Lang::get('view_booking.validation_discount_invalid');

                return View::make('frontend.hotel.booking', array(
                    'title'         => Lang::get('view_booking.title').' | Spason',
                    'hotel'         => $hotel,
                    'package'       => $package,
                    'date_from'     => $date_from,
                    'date_to'       => $date_to,
                    'package_price' => $package_price,
                    'nights_count'  => $nights_count,
                    'package_includes'  => $package_includes,
                    'package_residents' => $persons,
                    'full_price'        => $full_price,
                    'errors'            => $errors,
                    'user_name'         => $user_name,
                    'user_email'        => $user_email,
                    'user_phone'        => $user_phone,
                    'js_translations'   => json_encode($js_translations),

                ));
            } else {
                return Redirect::to(URL::route('frontend.hotel.search'));
            }
    }

    /**
     * Booking done page
     *
     * @return mixed
     */
    public function order()
    {
        $v = new \App\Services\Validators\Booking;

        if ( $v->passes('order') ) {
            $package_model = new Package();

            $errors = NULL;
            if ( Session::get('error') ) {
                $errors = Notification::error( Session::get('error') );
                $errors = $errors->toArray();
            }

            $data['package_id']    = Session::get('booking_data.package_id');
            $data['package']       = Package::find($data['package_id']);

            if ( !$data['package'] ) {
                return App::abort(404);
            }

            $data['date_from']     = Session::get('booking_data.date_from');
            $data['date_to']       = Session::get('booking_data.date_to');
            $data['persons']       = Session::get('booking_data.persons');

            $data['nights_count'] = $package_model->getDatesDifference($data['date_from'], $data['date_to'], 'Y-m-d');
            if ( FALSE === $data['nights_count'] ) {
                $data['nights_count'] = 1;
            }

            $data['package_price'] = $package_model->calculate_package_price($data['package_id']);
            $data['order_price']   = ($data['package_price'] * $data['persons']) * $data['nights_count'];

            $data['user_name']     = Input::get('name');
            $data['user_email']    = Input::get('email');
            $data['user_phone']    = Input::get('phone');
            $data['user_message']  = Input::get('addition');

            $discount = \Discount::where('code', '=', Input::get('discount'))
                ->where('status', '=', 1)
                ->where('expire', '>=', date('Y-m-d 00:00:00', time()))
                ->where('count', '<>', 0)
                ->first();

            if ( $discount && $discount->count > 0 ) { // discount check and decrement
                $data['discount_type']  = $discount->price_type;
                $data['discount_name']  = $discount->name;
                $data['discount']       = Input::get('discount');

                if ( $discount->price_type == 'person' ) {
                    $data['discount_price'] = $data['persons'] * $discount->discount;
                    $data['price_with_discount'] = $data['order_price'] - $data['discount_price'];
                } else {
                    $data['discount_price'] = $discount->discount;
                    $data['price_with_discount'] = $data['order_price'] - $data['discount_price'];
                }

                $d = \Discount::find($discount->id);
                $d->count = $d->count - 1;
                $d->save();
            }

            /* disabled registration
            TODO: delete this code, if user registration after booking don't need
            if ( !Sentry::check() ) { // if user not exists, register
                $user = User::where('email', '=', $data['user_email'])->get();

                if ( $user->count() == 0 ) {
                    $pass = str_random(8);

                    $user = Sentry::createUser(array(
                        'email'             => $data['user_email'],
                        'first_name'        => $data['user_name'],
                        'password'          => $pass,
                        'register_provider' => 'site',
                        'activated'         => 1
                    ));

                    // send email for user with registration password
                    Mail::send('emails.auth.register', array('password' => $pass), function($message) use ($data) {
                        $message->from('noreply@spason.se', 'Spason.se');
                        $message->subject('Spason registration password');
                        $message->to($data['user_email']);
                    });
                }
            }
            */

            // send email for user with with booking information
            Mail::send('emails.order.booking_done', $data, function($message) use ($data) {
                $message->from('noreply@spason.se', 'Spason.se');
                $message->subject('Tack för din bokning!');
                $message->to($data['user_email']);
            });

            $order_email = Settings::where('settings.name', 'order_email')->get();

            $result = Mail::send('emails.order.order', $data, function($message) use ($order_email, $data) {
                $message->from('noreply@spason.se', 'Spason.se');
                $message->subject('Bokning-'.$data['package']->hotel->name.'-'.$data['package']->name);
                $message->to($order_email[0]->value);
            });

            return View::make('frontend.hotel.booking_done', array(
                'title'  => Lang::get('booking_done.title').' | Spason',

                'errors' => $errors
            ));

        } else {
            return Redirect::to(URL::previous())->with('error', $v->getErrors()->toArray());
        }
    }

    /**
     * Map page
     *
     * @param $hotel_slug
     * @return mixed
     */
    public function map($hotel_slug)
    {

        $hotel        = array();
        $hotel_images = array();
        $errors       = NULL;

        $hotel = Hotel::select(array(
            '*',
            'hotels.name AS hotel_name',
            'hotels.description AS hotel_description',
            'hotels.id AS hotel_id',
            'hotels.slug AS hotel_slug',
            'hotels.default_photo_id AS hotel_photo_id',
            'spas.id AS spa_id',
        ))
            ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
            ->leftJoin('addresses', 'addresses.id', '=', 'hotels.address_id')
            ->leftJoin('photos', 'photos.id', '=', 'hotels.default_photo_id')
            ->where('hotels.status', '=', 1)
            ->where('hotels.slug', '=', $hotel_slug)
            ->get();

        if ( $hotel->count() ) {
            $hotel = $hotel[0];
            $hotel_images = Hotel::getHotelPhotos($hotel->hotel_id);
            $js_hotels = json_encode(array(
                array(
                    'lat' => $hotel->lat,
                    'lng' => $hotel->lng
                )
            ));
        } else {
            App::abort(404);
        }

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';
        return View::make('frontend.hotel.map', array(
            'title'        => $hotel->hotel_name . ' - '.Lang::get('map.title').' | Spason',
            'hotel'        => $hotel,
            'js_hotels'    => $js_hotels,
            'hotel_images' => json_encode($hotel_images),
            'errors'       => $errors,
            'scripts'      => $scripts,


        ));
    }

    /**
     * Spa info page
     *
     * @param $hotel_slug
     * @return mixed
     */
    public function spa($hotel_slug)
    {
        $errors       = NULL;
        $hotel        = array();
        $hotel_images = array();
        $available_services = array();
        $spa_treatments = array();

        $hotel = Hotel::select(array(
            '*',
            'hotels.name AS hotel_name',
            'hotels.description AS hotel_description',
            'hotels.id AS hotel_id',
            'hotels.slug AS hotel_slug',
            'hotels.default_photo_id AS hotel_photo_id',
            'spas.id AS spa_id',
            'spas.name AS spa_name',
            'spas.description AS spa_description'
        ))
            ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
            ->leftJoin('addresses', 'addresses.id', '=', 'hotels.address_id')
            ->leftJoin('photos', 'photos.id', '=', 'hotels.default_photo_id')
            ->where('hotels.status', '=', 1)
            ->where('hotels.slug', '=', $hotel_slug)
            ->get();

        if ( $hotel->count() ) {
            $hotel = $hotel[0];
            $hotel_images = Hotel::getHotelPhotos($hotel->hotel_id);

            $available_services = Service::where('content_type', '=', 'spa')
                                         ->where('content_id', '=', $hotel->spa_id)
                                         ->where('status', '=', 1)
                                         ->get();

            $spa_treatments = Treatment::where('spa_id', '=', $hotel->spa_id)
                                        ->where('status', '=', 1)
                                        ->get();

        } else {
            App::abort(404);
        }

        return View::make('frontend.hotel.spa', array(
            'title'        => $hotel->hotel_name . ' - '.Lang::get('spa.title').' | Spason',
            'hotel'        => $hotel,
            'hotel_images' => json_encode($hotel_images),
            'errors'       => $errors,
            'spa_services' => $available_services,
            'spa_treatments' => $spa_treatments,

        ));
    }

    /**
     * Hotel info page
     *
     * @param $hotel_slug
     * @return mixed
     */
    public function hotel($hotel_slug)
    {
        $errors       = NULL;
        $hotel        = array();
        $hotel_images = array();
        $available_services = array();
        $hotel_rooms  = array();

        $hotel = Hotel::select(array(
            '*',
            'hotels.name AS hotel_name',
            'hotels.description AS hotel_description',
            'hotels.id AS hotel_id',
            'hotels.slug AS hotel_slug',
            'hotels.default_photo_id AS hotel_photo_id',
            'spas.id AS spa_id',
            'spas.name AS spa_name',
            'spas.description AS spa_description'
        ))
            ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
            ->leftJoin('addresses', 'addresses.id', '=', 'hotels.address_id')
            ->leftJoin('photos', 'photos.id', '=', 'hotels.default_photo_id')
            ->leftJoin('rooms', 'rooms.hotel_id', '=', 'hotels.id')
            ->where('hotels.status', '=', 1)
            ->where('spas.status', '=', 1)
            ->where('hotels.slug', '=', $hotel_slug)
            ->get();

        if ( $hotel->count() ) {
            $hotel = $hotel[0];
            $hotel_images = Hotel::getHotelPhotos($hotel->hotel_id);

            $available_services = Service::where('content_type', '=', 'hotel')
                                         ->where('content_id', '=', $hotel->hotel_id)
                                         ->where('status', '=', 1)
                                         ->get();

            $hotel_restaurants = Restaurant::where('hotel_id', '=', $hotel->hotel_id)
                                           ->where('status', '=', 1)
                                           ->get();

            $hotel_rooms = Room::where('hotel_id', '=', $hotel->hotel_id)
                                ->where('status', '=', 1)
                                ->get();

            $hotel_activities = Amusement::where('hotel_id', '=', $hotel->hotel_id)
                                         ->where('status', '=', 1)
                                         ->get();

            $js_hotels = json_encode(array(
                array(
                    'lat' => $hotel->lat,
                    'lng' => $hotel->lng
                )
            ));

        } else {
            App::abort(404);
        }

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';
        return View::make('frontend.hotel.hotel', array(
            'title'        => $hotel->hotel_name . ' - '.Lang::get('hotel.title').' | Spason',
            'hotel'          => $hotel,
            'hotel_images'   => json_encode($hotel_images),
            'hotel_services' => $available_services,
            'hotel_rooms'    => $hotel_rooms,
            'hotel_restaurants' => $hotel_restaurants,
            'hotel_activities' => $hotel_activities,
            'js_hotels'      => $js_hotels,
            'errors'         => $errors,
            'scripts'        => $scripts,

        ));
    }

    /**
     * Hotel highlights page
     *
     * @param $hotel_slug
     * @return mixed
     */
    public function highlights($hotel_slug)
    {
        $errors       = NULL;
        $hotel        = array();
        $hotel_images = array();
        $available_services = array();

        $hotel = Hotel::select(array(
            '*',
            'hotels.name AS hotel_name',
            'hotels.description AS hotel_description',
            'hotels.id AS hotel_id',
            'hotels.slug AS hotel_slug',
            'hotels.default_photo_id AS hotel_photo_id',
            'spas.id AS spa_id',
            'spas.name AS spa_name',
            'spas.description AS spa_description'
        ))
            ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
            ->leftJoin('addresses', 'addresses.id', '=', 'hotels.address_id')
            ->leftJoin('photos', 'photos.id', '=', 'hotels.default_photo_id')
            ->where('hotels.status', '=', 1)
            ->where('hotels.slug', '=', $hotel_slug)
            ->get();

        if ( $hotel->count() ) {

            $package_model = new Package();

            $hotel = $hotel[0];
            $hotel_images = Hotel::getHotelPhotos($hotel->hotel_id, array('big', 'slider'));

            $highlights = \Highlight::where('hotel_id', '=', $hotel->hotel_id)
                                     ->where('status', '=', 1)
                                     ->get();

            $available_services = Service::where('content_type', '=', 'hotel')
                                         ->where('content_id', '=', $hotel->hotel_id)
                                         ->where('status', '=', 1)
                                         ->get();

            // get all hotel packages
            $hotel_packages = $package_model->get_hotel_packages(array('id' => $hotel->hotel_id));
            $min_package_price = 0;

            if ( $hotel_packages->count() ) {
                //get first package price
                $min_package_price = $package_model->calculate_package_price($hotel_packages[0]->package_id);

                //if packages more than one, find min price
                if ( $hotel_packages->count() > 1 ) {
                    foreach($hotel_packages as $package) {
                        $p_price = $package_model->calculate_package_price($package->package_id);
                        if ( $p_price > 0 ) {
                            if ( $min_package_price == 0 ) { // if first package min price 0
                                $min_package_price = $p_price;
                            } else if ( $p_price < $min_package_price ) { // if first price more than 0, but bigger than current package price
                                $min_package_price = $p_price;
                            }
                        }
                    }
                }
            }

            $hotel->min_package_price = $min_package_price;

            $js_hotels = json_encode(array(
                array(
                    'lat' => $hotel->lat,
                    'lng' => $hotel->lng
                )
            ));

        } else {
            App::abort(404);
        }

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';
        return View::make('frontend.hotel.highlights_new', array(
            'title'        => Lang::get("seo.titles.hotel",  array('hotel' => $hotel->hotel_name )),
            'meta_description' => Lang::get("seo.meta_descriptions.hotel",  array('hotel' => $hotel->hotel_name )),
            'hotel'        => $hotel,
            'hotel_images' => json_encode($hotel_images),
            'gallery'      => $hotel_images,
            'errors'       => $errors,
            'highlights'   => $highlights,
            'hotel_services' => $available_services,
            'js_hotels'      => $js_hotels,
            'scripts'        => $scripts,

        ));
    }

    /**
     * Discount validation method
     *
     * @return mixed
     */
    public function bookingValidateDiscount()
    {
        $valid = Input::get('valid');
        $discount_code = Input::get('discount_code');
        $package_id    = Input::get('package_id');

        $response = array('valid' => FALSE, 'error' => Lang::get('booking.discount_code_invalid'));

        if ( $valid !== FALSE && !empty($discount_code) && !empty($package_id) ) {
            $discount = \Discount::where('code', '=', $discount_code)
                     ->where('status', '=', 1)
                     ->where('expire', '>=', date('Y-m-d 00:00:00', time()))
                     ->where('count', '<>', 0)
                     ->first();

            $package_model   = new Package();
            $package_price   = $package_model->calculate_package_price($package_id);
            $persons = Session::get('booking_data.persons');

            if ( $discount ) {
                $response = array();
                $response['valid'] = TRUE; // code exists
                $response['discountName']   = $discount->name;
                $response['discountAmount'] = $discount->discount;
                $response['priceType']      = $discount->price_type;

                $package_full_price = $package_price*$persons;
                if ( $discount->price_type == 'person' ) {
                    $response['message'] = "Rabatt ({$discount->name}): $persons * {$discount->discount} = ".$persons*$discount->discount.' SEK <br>';
                    $response['message'] .= "Nytt pris $package_full_price - ".($persons*$discount->discount)." = ".($package_full_price-($persons*$discount->discount)).' SEK';
                } else {
                    $response['message'] = "Rabatt ({$discount->name}): {$discount->discount} SEK <br>";
                    $response['message'] .= "Nytt pris $package_full_price - ".$discount->discount." = ".($package_full_price-$discount->discount).' SEK';
                }

            } else {
                $response['valid']   = FALSE;
                $response['error'] = Lang::get('booking.discount_code_invalid');
            }
        }

        return Response::json($response);
    }

    public function packageValidateAvailability()
    {
        $response = array();

        $date_from = Input::get('date_from');
        $date_to   = Input::get('date_to');
        $guests    = (int)Input::get('guests');
        $hotel_id  = (int)Input::get('hotel_id');

        if ( $date_from && $date_to && $guests && $hotel_id ) {

            $date_from = date_parse_from_format('d/m - Y', $date_from);
            if ( $date_from ) {
                $date_from = $date_from['year'].'-'.$date_from['month'].'-'.$date_from['day'];
            }

            $date_to = date_parse_from_format('d/m - Y', $date_to);
            if ( $date_to ) {
                $date_to = $date_to['year'].'-'.$date_to['month'].'-'.$date_to['day'];
            }

            if ( $date_from && $date_to ) {
                $datetime1 = date_create($date_from);
                $datetime2 = date_create($date_to);

                $daterange = new \DatePeriod($datetime1, new \DateInterval('P1D'), $datetime2);
                $weekday_array = array();
                foreach($daterange as $date) {
                    $weekday_array[$date->format('N')] = $date->format('D');
                }

                $interval = date_diff($datetime1, $datetime2);
                $nights_count = $interval->format('%a');

                $package = Package::select(array(
                    '*'
                ))
                ->leftJoin('package_rooms', 'package_rooms.package_id', '=', 'packages.id')
                ->leftJoin('rooms', 'rooms.id', '=', 'package_rooms.room_id')
                ->where('packages.status', '=', 1)
                ->where('rooms.status', '=', 1)
                ->where(function($query) use ($weekday_array) {
                    if ( $weekday_array ) {
                        foreach($weekday_array as $weekday) {
                            $query->orWhere('packages.available_weekdays', 'LIKE', "%$weekday%");
                        }
                    }

                    $query->orWhere('packages.available_weekdays', '=', '');
                })
                ->where(function($query) use ($nights_count) {
                    $query->where('packages.overnights_min', '>=', $nights_count);
                    $query->where('packages.overnights_max', '<=', $nights_count);
                    $query->orWhere('packages.overnights_max', '=', 0);
                })
                ->where('packages.hotel_id', '=', $hotel_id)
                ->where('rooms.max_residents', '=', $guests)
                ->groupBy('packages.id')
                ->get();

                if ( $package->count() ) {
                    $response['has_packages'] = TRUE;
                    Session::put('packages_check.date_from', Input::get('date_from'));
                    Session::put('packages_check.date_to', Input::get('date_to'));
                } else {
                    $response['has_packages'] = FALSE;
                }
            }

        }

        return Response::json($response);
    }
}