<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \Package, \Spas, \User, \Hotel, \Photo, \PackageTreatments, \PackageRoom, \Room;

class PackagesController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('backend.packages.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
        $packages = Package::select(array (
            'packages.id',
            'packages.name',
            'packages.short_description',
            'hotels.name AS hotel_name',
            'packages.status',
            'packages.start_date',
            'packages.end_date',
            'packages.created_at'
        ))
        ->leftJoin('hotels','hotels.id', '=', 'packages.hotel_id')
        ->orderBy('packages.id', 'DESC');

		return Datatables::of($packages)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\PackagesController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
            <a href="javascript:void(0);" onclick="deleteItem(\'{{$id}}\');" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-fw fa-times"></i></a>')
            ->edit_column ('id', '<span id="row-{{$id}}" class="checkbox-column"><input type="checkbox" value="{{$id}}" /></span>')
			->make();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $scripts  = '<script type="text/javascript" src="'.URL::asset('js/plugins/typeahead.bundle.min.js').'"></script>';
        return View::make('backend.packages.create', array('user' => Sentry::getUser(), 'scripts' => $scripts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\Package;
        if($v->passes()) {
            try {

                $available_weekdays_str = '';
                if ( Input::get('available_weekdays') ) {
                    foreach(Input::get('available_weekdays') as $weekday) {
                        $available_weekdays_str .= $weekday.'|:|';
                    }
                }

                //Save the Package
                $data = array();
                $data['hotel_id']          = Input::get('hotel_id');
                $data['name']              = Input::get('name');
                $data['short_description'] = Input::get('short_description');
                $data['description']       = Input::get('description');
                $data['overnights_min']    = Input::get('overnights_min');
                $data['overnights_max']    = Input::get('overnights_max');
                $data['discount']          = Input::get('discount');
                $data['last_minute']       = (Input::get('last_minute') == 'on') ? 1 : 0;
                $data['campaign']          = (Input::get('campaign') == 'on') ? 1 : 0;
                $data['recommended']       = (Input::get('recommended') == 'on') ? 1 : 0;
                $data['start_date']        = Input::get('start_date');
                $data['end_date']          = Input::get('end_date');
                $data['status']            = Input::get('status');
                $data['slug']              = $this->slugify(Input::get('name'));
                $data['available_days']    = 1;
                $data['price_per_person']  = Input::get('price_per_person');
                $data['available_weekdays'] = $available_weekdays_str;

                $package = Package::create($data);
            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.packages.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new package has been created');
            return Redirect::action('backend.packages');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.packages.create')->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $package    = Package::with('hotel')->find($id);
        $treatments = Spas::select(array('treatments.id AS treatment_id', 'treatments.name AS treatment_name'))
                            ->leftJoin('treatments', 'treatments.spa_id', '=', 'spas.id')
                            ->where('spas.hotel_id', '=', $package->hotel->id)
                            ->where('treatments.status', '=', 1)
                            ->get();
//dd($package->toArray());die;
        $rooms = Room::select(array('rooms.name', 'rooms.id'))
                      ->where('rooms.hotel_id', '=', $package->hotel->id)
                      ->where('rooms.status', '=', 1)
                      ->get();

        $package_treatments = PackageTreatments::where('package_id', '=', $id)
                                               ->where('status', '=', 1)
                                               ->get();

        $package_rooms = PackageRoom::where('package_id', '=', $id)
                                    ->get();

        $photos = Photo::select(array('*'))
                        ->where('photos.content_type', '=', 'packages')
                        ->where('photos.content_id', '=', $id)
                        ->get();

        $package_includes = array();
        if ( !empty($package->package_includes) ) {
            $package_includes = explode('|:|', $package->package_includes);
        }

        $available_weekday = array();
        if ( !empty($package->available_weekdays) ) {
            $available_weekday = explode('|:|', $package->available_weekdays);
        }

        $scripts  = '<script type="text/javascript" src="'.URL::asset('js/plugins/typeahead.bundle.min.js').'"></script>';
        return View::make('backend.packages.edit',
            array(
                'scripts'            => $scripts,
                'package'            => $package,
                'photos'             => $photos,
                'treatments'         => $treatments,
                'package_treatments' => $package_treatments,
                'rooms'              => $rooms,
                'package_rooms'      => $package_rooms,
                'package_includes'   => $package_includes,
                'available_weekday' => $available_weekday
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function update($id)
    {
        $all_inputs = Input::all();
        $pre_validate_room = FALSE;

        foreach($all_inputs as $input_name => $input_val) {
            if ( strstr($input_name, 'room_') || strstr($input_name, 'new_room') ) {
                $pre_validate_room = TRUE;
            }
        }

        if ( !$pre_validate_room ) {
            Notification::container('backendFeedback')->error('Package rooms can not be empty');
            return Redirect::route('backend.packages.edit', array('id' => $id) )->withInput();
        }

        $v = new \App\Services\Validators\Package;
        if($v->passes('update')) {
            try {

                $package_includes_str = '';
                if ( Input::get('package_includes') ) {
                    foreach(Input::get('package_includes') as $package_include) {
                        if ( !empty($package_include) ) {
                            $package_includes_str .= $package_include.'|:|';
                        }
                    }
                }

                $available_weekdays_str = '';
                if ( Input::get('available_weekdays') ) {
                    foreach(Input::get('available_weekdays') as $weekday) {
                        $available_weekdays_str .= $weekday.'|:|';
                    }
                }

                $package = Package::find($id);
                $package->hotel_id          = Input::get('hotel_id');
                $package->name              = Input::get('name');
                $package->short_description = Input::get('short_description');
                $package->description       = Input::get('description');
                $package->overnights_min    = Input::get('overnights_min');
                $package->overnights_max    = Input::get('overnights_max');
                $package->discount          = Input::get('discount');
                $package->last_minute       = (Input::get('last_minute') == 'on') ? 1 : 0;
                $package->campaign          = (Input::get('campaign') == 'on') ? 1 : 0;
                $package->recommended       = (Input::get('recommended') == 'on') ? 1 : 0;
                $package->start_date        = Input::get('start_date');
                $package->end_date          = Input::get('end_date');
                $package->status            = Input::get('status');
                $package->days_in_advance   = Input::get('days_in_advance');
                $package->slug              = $this->slugify(Input::get('name'));
                $package->package_includes  = $package_includes_str;
                $package->available_days    = 1;
                $package->price_per_person  = Input::get('price_per_person');
                $package->available_weekdays = $available_weekdays_str;
                $package->save();


                //get available package treatments
                $available_treatments = PackageTreatments::where('package_treatments.package_id', '=', $id)
                                                         ->where('package_treatments.status', '=', 1)
                                                         ->get();

                if ( $available_treatments ) {
                    foreach($available_treatments as $treatment) {
                        if ( Input::get('treatment_'.$treatment->id) ) {
                            $treatment->treatment_id = Input::get('treatment_'.$treatment->id);
                            $treatment->save();
                        }
                    }
                }

                //add new treatments
                $package_treatments = Input::get('new_treatment');
                if ( $package_treatments ) {
                    foreach( $package_treatments as $treatment_id ) {
                        PackageTreatments::create(
                            array(
                                'treatment_id' => $treatment_id,
                                'package_id'   => $id,
                                'status'       => 1
                            )
                        );
                    }
                }

                $available_rooms = PackageRoom::where('package_rooms.package_id', '=', $id)
                                              ->get();

                if ( $available_rooms ) {
                    foreach($available_rooms as $room) {
                        if ( Input::get('room_'.$room->room_id) ) {
                            $rooms = PackageRoom::where('package_rooms.package_id', '=', $id)
                                                ->where('package_rooms.room_id', '=', $room->room_id)
                                                ->update(array('room_id' => Input::get('room_'.$room->room_id)));
                        }
                    }
                }

                //add new rooms to package
                $package_rooms = Input::get('new_room');
                if ( $package_rooms ) {
                    foreach( $package_rooms as $room_id ) {
                        PackageRoom::create(
                            array(
                                'package_id'   => $id,
                                'room_id'      => $room_id
                            )
                        );
                    }
                }

            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.packages.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The package has been updated');
            return Redirect::action('backend.packages');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.packages.edit', array('id' => $id) )->withInput();
    }

    /**
     * Autocomplete hotels for packages
     *
     * @return Response
     */
    public function autocomplete()
    {

        $hotel_name = Input::get('name');

        $hotels = Hotel::select(array('id', 'name'))
                        ->where('name', 'like', "%$hotel_name%")
                        ->where('status', '=', 1)
                        ->get();

        $hotels_array = array();

        foreach($hotels as $hotel) {
            $hotels_array[] = $hotel->toArray();
        }

        return Response::json( $hotels_array );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $package = Package::find($id);
        $package->delete();
        return Response::json( array('result' => true, 'content' => 'The package has been deleted') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroyPackageTreatment($id)
    {
        $package_t = PackageTreatments::find($id);
        $package_t->delete();
        return Response::json( array('result' => true, 'content' => 'The package treatment has been deleted') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $package_id
     * @param  int  $room_id
     * @return Response
     */
    public function destroyPackageRoom($package_id, $room_id)
    {
        $package_r = PackageRoom::where('package_rooms.room_id', '=', $room_id)
                                 ->where('package_rooms.package_id', '=', $package_id);

        $package_r->delete();
        return Response::json( array('result' => true, 'content' => 'The package room has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $package = Package::find($id);
            if ($package) {
                $package->status = 1;
                $package->save();
            }
        }
        return Redirect::route('backend.packages');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $package = Package::find($id);
            if ($package) {
                $package->status = 0;
                $package->save();
            }
        }
        return Redirect::route('backend.packages');
    }

    function makeDefaultPhoto($package_id, $photo_id) {
        $package = Package::find($package_id);
        $package->default_photo_id = $photo_id;
        $package->save();
        return Response::json( array('result' => true, 'content' => 'The package photo has been updated') );
    }

}