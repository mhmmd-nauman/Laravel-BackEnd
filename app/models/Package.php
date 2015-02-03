<?php

use \PackageTreatments, \PackageRoom;

class Package extends \Eloquent {
	protected $fillable = array(
        'name',
        'short_description',
        'description',
        'overnights_min',
        'overnights_max',
        'discount',
        'default_room_id',
        'last_minute',
        'campaign',
        'recommended',
        'status',
        'start_date',
        'end_date',
        'days_in_advence',
        'days_available',
        'package_includes',
        'hotel_id',
        'slug',
        'available_days',
        'price_per_person'
    );

    public function hotel()
    {
        return $this->belongsTo('Hotel');
    }

    public function default_room_id()
    {
        return $this->belongsTo('Room');
    }

    public function package_treatment()
    {
        return $this->hasMany('PackageTreatments');
    }

    /**
     * Calculate package price
     *
     * @param int $package_id
     * @return int
     */
    public function calculate_package_price($package_id)
    {
        $package_id = (int) $package_id;
        $package_price = 0;

        if ( $package_id ) {
            $package = self::find($package_id);
            if ( $package->count() ) {

                if ( !$package->price_per_person ) {
                    $package_treatments = PackageTreatments::leftJoin('treatments', 'treatments.id', '=', 'package_treatments.treatment_id')
                        ->where('package_treatments.status', '=', 1)
                        ->where('treatments.status', '=', 1)
                        ->where('package_treatments.package_id', '=', $package_id)
                        ->get();

                    $package_rooms = PackageRoom::leftJoin('rooms', 'package_rooms.room_id', '=', 'rooms.id')
                        ->leftJoin('room_prices', 'room_prices.room_id', '=', 'rooms.id')
                        ->where('rooms.status', '=', 1)
                        ->where('package_rooms.package_id', '=', $package_id)
                        ->get();

                    if ( $package_rooms->count() ) {
                        $package_price = $package_rooms[0]->price; // now, for simple calculate, get only one price (default monday price)
                    }

                    if ( $package_treatments->count() ) {
                        foreach($package_treatments as $treatment) {
                            $package_price += $treatment->price;
                        }
                    }

                    $package_price = ($package_price * $package->overnights_min) - $package->discount;
                } else {

                    $package_price = $package->price_per_person - $package->discount;

                }

            }
        }

        return $package_price;
    }

    /**
     * @param $hotel_info    array    get by id or slug
     * @param array $params  array    with_price - get packages with calculated price
     * @return array
     */
    public function get_hotel_packages($hotel_info, $params = array())
    {
        $packages = array();

        if ( isset($hotel_info['id']) || isset($hotel_info['slug']) ) {
            $hotel_packages = Package::select(
                array(
                    '*',
                    'hotels.name AS hotel_name',
                    'hotels.id AS hotel_id',
                    'hotels.slug AS hotel_slug',
                    'hotels.description AS hotel_description',
                    'packages.id AS package_id',
                    'packages.name AS package_name',
                    'packages.default_photo_id AS package_photo_id',
                    'packages.slug AS package_slug'
                )
            )
                ->leftJoin('hotels', 'hotels.id', '=', 'packages.hotel_id')
                ->leftJoin('addresses', 'hotels.address_id', '=', 'addresses.id')
                ->leftJoin('package_rooms', 'package_rooms.package_id', '=', 'packages.id')
                ->leftJoin('rooms', 'rooms.id', '=', 'package_rooms.room_id')
                ->where('hotels.status', '=', 1)
                ->where('rooms.status', '=', 1)
                ->where('packages.status', '=', 1)
                ->groupBy('packages.id');

            if ( isset($hotel_info['id']) ) {
                $hotel_packages->where('hotels.id', '=', $hotel_info['id']);
            } else {
                $hotel_packages->where('hotels.slug', '=', $hotel_info['slug']);
            }

            $packages = $hotel_packages->get();

            if ( $packages->count() && isset($params['with_price']) && $params['with_price'] == TRUE ) {
                foreach($packages as $k => $package) {
                    $price = $this->calculate_package_price($package->package_id);
                    if ( $price > 0 ) {
                        $packages[$k]->package_price = $price;
                    } else {
                        unset($packages[$k]);
                    }

                }
            }

        }

        return $packages;
    }

    public function findPackageRoomPersonCombinations($package_rooms, $max_available_persons = 8)
    {
        $result = array(array());
        $rooms_max_residents = array();
        $persons = array();

        // get unique room available persons
        if ( $package_rooms && $package_rooms->count() ) {
            foreach($package_rooms as $room) {
                $rooms_max_residents[] = $room->max_residents;
            }

            $rooms_max_residents = array_unique($rooms_max_residents);
        }

        //get unique combinations of available persons
        foreach($rooms_max_residents as $residents_num) {
            foreach($result as $combination) {
                $result[] = array_merge(array($residents_num), $combination);
            }
        }

        foreach($result as $combination) {
            if ( !empty($combination) ) {
                $persons_sum = array_sum($combination);
                $sum = $persons_sum;

                while( $sum <= $max_available_persons ) {
                    $persons[$sum] = $sum;
                    $sum += $persons_sum;
                }
            }
        }

        asort($persons);

        return $persons;
    }

    /**
     * Return in days difference between two dates
     *
     * @param string $dateFrom Datetime string in format strtotime
     * @param string $dateTo Datetime string in format strtotime
     * @param string $dateFormat [optional] Date format (default is d/m - Y)
     *
     * @return bool Return FALSE if dates is wrong, or days, when dates is Ok
     */
    public function getDatesDifference($dateFrom, $dateTo, $dateFormat = 'd/m - Y')
    {
        $difference = FALSE;
        $dateFrom = date_parse_from_format($dateFormat, $dateFrom);
        $dateTo   = date_parse_from_format($dateFormat, $dateTo);
        
        if ( $dateFrom ) {
            $dateFrom = $dateFrom['year'].'-'.$dateFrom['month'].'-'.$dateFrom['day'];
        } else {
            $dateFrom = strtotime($dateFrom);
        }

        if ( $dateTo ) {
            $dateTo = $dateTo['year'].'-'.$dateTo['month'].'-'.$dateTo['day'];
        } else {
            $dateTo = strtotime($dateTo);
        }

        if ( $dateFrom && $dateTo ) {
            $datetime1 = date_create($dateFrom);
            $datetime2 = date_create($dateTo);

            if ( $datetime1 && $datetime2 ) {
                $interval = date_diff($datetime1, $datetime2);
                $difference = $interval->format('%a'); // %a - in days

                return $difference;
            }
        }

        return $difference;
    }

    /**
     * Get hotel nights range list
     *
     * @param $hotelId
     * @return array
     */
    public function getHotelPackagesAvailableNights($hotelId) {
        $hotelId         = (int)$hotelId;
        $availableNights = array();

        if ( $hotelId ) {
            $minMaxNights = self::select(
                array(
                    DB::raw('MIN(overnights_min) AS min_nigts'),
                    DB::raw('MAX(overnights_max) AS max_nigts')
                )
            )
                ->where('status', '=', 1)
                ->where('hotel_id', '=', $hotelId)
                ->first();

            if ( $minMaxNights ) {
                $min = ($minMaxNights['min_nights'] == 0) ? 1 : $minMaxNights['min_nights'];
                $max = ($minMaxNights['max_nights'] == 0) ? 10 : $minMaxNights['max_nights'];

                foreach(range($min, $max) as $nights) {
                    $availableNights[$nights] = $nights;
                }
            }
        }

        return $availableNights;
    }

    /**
     * Invert package available weekdays. For example, if Monday in package available, make Monday unavailable
     *
     * @param $weekdays_str
     * @return string
     */
    public function invertPackageWeekdays($weekdays_str) {
        $package_available_weekdays = array();
        $new_weekdays_str = '';

        if ( !empty($weekdays_str) ) {
            $package_available_weekdays = explode('|:|', rtrim($weekdays_str, '|:|'));

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

            $reverse_week_days = array(
                '1' => 'Mon',
                '2' => 'Tue',
                '3' => 'Wed',
                '4' => 'Thu',
                '5' => 'Fri',
                '6' => 'Sat',
                '0' => 'Sun',
            );

            foreach($package_available_weekdays as $available_day) {
                unset($reverse_week_days[$available_day]);
            }

            $new_weekdays_str = implode('|:|', $reverse_week_days);
        }

        return $new_weekdays_str;
    }

    /**
     * Check package booking dates
     *
     * @param $date_from     date from, in format to strtotime
     * @param $date_to       date to, in format to strtotime
     * @param $package_id    package id which need to check
     *
     * @return array    array with keys, message(string), error_code(int), result(bool)
     */
    public function checkPackageDates($date_from, $date_to, $package_id) {
        $result = array(
            'message'    => '',
            'error_code' => NULL,
            'result'     => FALSE
        );

        $date_from = strtotime($date_from);
        $date_to   = strtotime($date_to);

        if ( $date_from && $date_to ) {

            if ( $date_to > $date_from ) {
                $package = self::find($package_id);

                if ( $package ) {
                    $date_from_weekday = strtolower(date('D', $date_from));
                    if ( !empty($package->available_weekdays) && mb_strpos(mb_strtolower($package->available_weekdays), $date_from_weekday) !== FALSE ) { // if can't find available weekday
                        $result['message']    = 'Date from is wrong. Can\'t find available week day for this date.';
                        $result['error_code'] = 1;
                        $result['result']     = FALSE;
                    }

                    if ( $package->days_in_advance ) { // check days in advance
                        $date_offset = strtotime(date('Y-m-d') . ' + '.$package->days_in_advance.' day');
                        if ( $date_from < $date_offset || $date_to < $date_offset ) {
                            $result['message']    = 'Date from or date to is wrong. Because days in advance is '. $package->days_in_advance;
                            $result['error_code'] = 2;
                            $result['result']     = FALSE;
                        }
                    }

                    if ( $package->overnights_min && $package->overnights_max > 0 ) {
                        $day_start = new DateTime(date('Y-m-d',$date_from));
                        $day_end  = new DateTime(date('Y-m-d',$date_to));
                        $days_diff = $day_start->diff($day_end);

                        if ( $days_diff->days > $package->overnights_max || $days_diff->days < $package->overnights_min ) {
                            $result['message']    = 'Date from or date to is wrong. Min overnights is '. $package->overnights_min . ' and max overnights is ';
                            $result['message']   .= ($package->overnights_max == 0) ? 'unlimited' : $package->overnights_max;
                            $result['message']   .= '. You want to book '.$days_diff->days.' nights.';
                            $result['error_code'] = 3;
                            $result['result']     = FALSE;
                        }
                    }

                    if ( $result['error_code'] == NULL ) { // if validation is ok
                        $result['message']    = 'All Ok';
                        $result['result']     = TRUE;
                    }
                }
            } else {
                $result['message']    = 'Date to cannot be earlier date from';
                $result['error_code'] = 5;
                $result['result']     = FALSE;
            }

        } else {
            $result['message']    = 'Wrong date from, or date to';
            $result['error_code'] = 4;
            $result['result']     = FALSE;
        }

        return $result;
    }

}