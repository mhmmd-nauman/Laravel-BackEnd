<?php namespace App\Controllers\Frontend;

use View, URL, Redirect, Input, Response, App, DB, Session, Notification, Mail;
use \Spas, \Hotel, \Package, \PackageTreatments, \PackageRoom, \Settings;

class SpaController extends FrontendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return NULL;
	}

    public function general($id)
    {
        $id = (int)$id;

        $spa = Package::select(
            array(
                '*',
                'hotels.name AS hotel_name',
                'hotels.description AS hotel_description',
                'packages.id AS package_id'
                //'spas.name AS spa_name'
            )
        )
            ->leftJoin('hotels', 'hotels.id', '=', 'packages.hotel_id')
            ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
            ->leftJoin('addresses', 'hotels.address_id', '=', 'addresses.id')
            ->where('hotels.id', '=', $id)
            ->where('spas.status', '=', 1)
            ->where('hotels.status', '=', 1)
            ->get();

        $spa = $spa[0];
        //print_r($spa);

        return View::make('frontend.spa.general', array(
            'spa'   => $spa,
            'title' => Lang::get("seo.titles.hotel",  array('hotel' => $spa->hotel_name ))
        ));
    }

    public function spapackage($id)
    {
        $id = (int)$id;
        $errors = Notification::error(Session::get('error'));

        $spa = Package::select(
            array(
                '*',
                'hotels.name AS hotel_name',
                'hotels.description AS hotel_description',
                'hotels.id AS hotel_id',
                'spas.name AS spa_name',
                'packages.name AS package_name',
                'packages.description AS package_description',
                'packages.default_photo_id AS package_photo_id',
                'packages.id AS package_id',
                'rooms.max_residents AS room_max_residents'
            )
        )
            ->leftJoin('hotels', 'hotels.id', '=', 'packages.hotel_id')
            ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
            ->leftJoin('addresses', 'hotels.address_id', '=', 'addresses.id')
            ->leftJoin('package_rooms', 'package_rooms.package_id', '=', 'packages.id')
            ->leftJoin('rooms', 'rooms.id', '=', 'package_rooms.room_id')
            ->where('packages.id', '=', $id)
            ->where('spas.status', '=', 1)
            ->where('hotels.status', '=', 1)
            ->where('rooms.status', '=', 1)
            ->where('packages.status', '=', 1)
            ->get();

        if ( $spa->toArray() ) {
            $spa = $spa[0];

            $package_treatments = PackageTreatments::leftJoin('treatments', 'treatments.id', '=', 'package_treatments.treatment_id')
                ->where('package_treatments.status', '=', 1)
                ->where('treatments.status', '=', 1)
                ->where('package_treatments.package_id', '=', $spa['package_id'])
                ->get();

            $package_rooms = PackageRoom::leftJoin('rooms', 'package_rooms.room_id', '=', 'rooms.id')
                ->leftJoin('room_prices', 'room_prices.room_id', '=', 'rooms.id')
                ->where('rooms.status', '=', 1)
                ->where('package_rooms.package_id', '=', $spa['package_id'])
                ->get();

            $hotel_packages = Package::where('packages.hotel_id', '=', $spa->hotel_id)
                ->get();

            $package_price = $package_rooms[0]->price; // now, for simple calculate, get only one price (default monday price)

            foreach($package_treatments as $treatment) {
                $package_price += $treatment->price;
            }

            $package_price = $package_price * $spa->days_in_advance;

            $scripts  = '<script type="text/javascript" src="'.URL::asset('js/plugins/moment.min.js').'"></script>';
            $scripts  .= '<script type="text/javascript" src="'.URL::asset('js/plugins/nivo-lightbox.min.js').'"></script>';
            $scripts  .= '<script type="text/javascript" src="'.URL::asset('js/plugins/spason.calendar.js').'"></script>';

            return View::make('frontend.spa.spapackage', array(
                'spa'   => $spa,
                'title' => 'Spason - '.$spa->hotel_name,
                'hotel_packages' => $hotel_packages,
                'package_price'  => $package_price,
                'scripts' => $scripts,
                'errors'  => $errors
            ));
        }

    }

    public function search()
    {
        $query = e(Input::get('query'));
        $errors = NULL;

        if ( $query ) {
            $hotels = Hotel::select(array(
                '*',
                'hotels.name AS hotel_name',
                'hotels.description AS hotel_description',
                'hotels.id AS hotel_id',
                'hotels.default_photo_id AS hotel_photo_id',
                'spas.id AS spa_id',
            ))
                       ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
                       ->leftJoin('addresses', 'addresses.id', '=', 'hotels.address_id')
                       ->leftJoin('photos', 'photos.id', '=', 'hotels.default_photo_id')
                       ->where('spas.name', 'like', "%$query%")
                       ->orWhere('addresses.address', 'like', "%$query%")
                       ->orWhere('spas.description', 'like', "%$query%")
                       ->orWhere('hotels.description', 'like', "%$query%")
                       ->orWhere('hotels.name', 'like', "%$query%")
                       ->where('spas.status', '=', 1)
                       ->where('hotels.status', '=', 1)
                       ->get();

        } else {

            $hotels = Hotel::select(array(
                '*',
                'hotels.name AS hotel_name',
                'hotels.description AS hotel_description',
                'hotels.id AS hotel_id',
                'hotels.default_photo_id AS hotel_photo_id',
                'spas.id AS spa_id',
            ))
                        ->leftJoin('spas', 'spas.hotel_id', '=', 'hotels.id')
                        ->leftJoin('addresses', 'addresses.id', '=', 'hotels.address_id')
                        ->leftJoin('photos', 'photos.id', '=', 'hotels.default_photo_id')
                        ->where('spas.status', '=', 1)
                        ->where('hotels.status', '=', 1)
                        ->take(4)
                        ->get();


        }

//        $queries = DB::getQueryLog();
//        $last_query = end($queries);
//
//        var_dump($last_query, $hotels->toArray());
        $js_hotels = array();

        if ( $hotels ) {
            foreach ($hotels as $hotel) {
                $js_hotels[$hotel->hotel_id] = array(
                    'lat' => $hotel->lat,
                    'lng' => $hotel->lng,
                    'description' => $hotel->hotel_description,
                    'name' => $hotel->hotel_name,
                    'id' => $hotel->hotel_id,
                    'url' => URL::to('frontend.spa.general', array('id' => $hotel->hotel_id))
                );
            }
        }

        $js_hotels = json_encode($js_hotels);

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';
        return View::make('frontend.spa.search', array(
            'hotels'    => $hotels,
            'js_hotels' => $js_hotels,
            'scripts'   => $scripts,
            'errors'    => $errors
        ));
    }

    public function booking()
    {
        $v = new \App\Services\Validators\Booking;
        $errors = Notification::error(Session::get('error'));

        if ( Input::all() ) {
            if ( !$v->passes() ) {
                return Redirect::to(URL::previous())->with('error', $v->getErrors()->toArray());
            } else {
                Session::put('booking_data.package_id', Input::get('package_id'));
                Session::put('booking_data.date_from',  Input::get('from'));
                Session::put('booking_data.date_to',    Input::get('to'));
            }
        }

            $package_id = Session::get('booking_data.package_id');
            $date_from  = Session::get('booking_data.date_from');
            $date_to    = Session::get('booking_data.date_to');

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

            if ( $package_rooms->toArray() && $package_treatments->toArray() ) {
                $package_price = $package_rooms[0]->price; // now, for simple calculate, get only one price (default monday price)

                $package = Package::find($package_id);

                foreach($package_treatments as $treatment) {
                    $package_price += $treatment->price;
                }

                $package_price = $package_price * $package->days_in_advance;
                $package_residents = $package_rooms[0]->max_residents;
                $full_price = $package_residents * $package_price;

                return View::make('frontend.spa.booking', array(
                    'package' => $package,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'package_price' => $package_price,
                    'package_residents' => $package_residents,
                    'full_price' => $full_price,
                    'errors' => $errors
                ));
            }
    }

    public function order(){

        $v = new \App\Services\Validators\Booking;

        if ( $v->passes('order') ) {

            $data['package_id'] = Session::get('booking_data.package_id');
            $data['date_from'] = Session::get('booking_data.date_from');

            $data['package'] = Package::find($data['package_id']);
            $data['package_rooms'] = PackageRoom::leftJoin('rooms', 'package_rooms.room_id', '=', 'rooms.id')
                ->leftJoin('room_prices', 'room_prices.room_id', '=', 'rooms.id')
                ->where('rooms.status', '=', 1)
                ->where('package_rooms.package_id', '=', $data['package_id'])
                ->get();

            $data['user_name'] = Input::get('name');
            $data['user_email'] = Input::get('email');
            $data['user_phone'] = Input::get('phone');
            $data['user_message'] = Input::get('addition');

            //Mail::pretend();
            $order_email = Settings::where('settings.name', 'order_email')
                                   ->get();

            $result = Mail::send('emails.order.order', $data, function($message) use ($order_email) {
                $message->from('noreply@spason.se', 'Spason.se');
                $message->to($order_email[0]->value);
            });



        } else {
            return Redirect::to(URL::previous())->with('error', $v->getErrors()->toArray());
        }

        echo 'Thanks!<br>';
        var_dump( Input::all() );
    }

}