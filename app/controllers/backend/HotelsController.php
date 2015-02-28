<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \Hotel, \Address, \User, \Photo, \Service;
use Whoops\Example\Exception;

class HotelsController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
            
                return View::make('backend.hotels.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
        $hotels = Hotel::select(array (
            'hotels.id',
            'hotels.name',
            'addresses.address',
            'hotels.reception_times',
            'hotels.phone',
            'hotels.status',
            'hotels.created_at'
        ))
        ->leftJoin('addresses','hotels.address_id', '=', 'addresses.id');

		return Datatables::of($hotels)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\HotelsController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
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
        return View::make('backend.hotels.create', array('user' => Sentry::getUser()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\Hotel;
        if($v->passes()) {
            try {
                //Save the Address (with Locations)
                $addressData = Address::make(Input::get('address'), Input::get('lat'), Input::get('lng'));
                $address = Address::create($addressData);

                //Save the Hotel
                $data = array();
                $data['user_id']             = Input::get('user_id');
                $data['address_id']          = $address->id;
                $data['name']                = Input::get('name');
                $data['phone']               = Input::get('phone');
                $data['description']         = Input::get('description');
                $data['offsite_booking_url'] = Input::get('offsite_booking_url');
                $data['offsite_booking']     = (Input::get('offsite_booking') == 'on') ? 1 : 0;
                $data['reception_times']     = Input::get('reception_times');
                $data['status']              = Input::get('status');
                $data['slug']                = $this->slugify($data['name']);
                $data['summary']             = Input::get('summary');
                $hotel = Hotel::create($data);
            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.hotels.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new hotel has been created');
            return Redirect::action('backend.hotels');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.hotels.create')->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        try {
            $hotel = Hotel::find($id);
            $photos = Photo::select(array('*'))
                           ->where('photos.content_type', '=', 'hotels')
                           ->where('photos.content_id', '=', $id)
                           ->get();
            $services = Service::where('content_type', '=', 'hotel')
                               ->where('content_id', '=', $hotel->id)
                               ->get();
        } catch(Exception $e) {
           App::abort(404);
        }

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';

        return View::make('backend.hotels.edit', array(
            'scripts'  => $scripts,
            'hotel'    => $hotel,
            'photos'   => $photos,
            'user'     => Sentry::getUser(),
            'services' => $services
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function update($id)
    {
        $v = new \App\Services\Validators\Hotel;

        if($v->passes('update')) {
            try {
                $hotel = Hotel::with('address')->find($id);
                $addressData = Address::make(Input::get('address'), Input::get('lat'), Input::get('lng'));
                $hotel->address()->update($addressData);
                $hotel->name                = Input::get('name');
                $hotel->phone               = Input::get('phone');
                $hotel->description         = Input::get('description');
                $hotel->reception_times     = Input::get('reception_times');
                $hotel->offsite_booking_url = Input::get('offsite_booking_url');
                $hotel->offsite_booking     = (Input::get('offsite_booking') == 'on') ? 1 : 0;
                $hotel->status              = Input::get('status');
                $hotel->slug                = $this->slugify(Input::get('name'));
                $hotel->summary             = Input::get('summary');
                $hotel->save();

                $available_services = Service::where('content_type', '=', 'hotel')
                                             ->where('content_id', '=', $hotel->id)
                                             ->get();

                if ( $available_services->count() ) {
                    foreach($available_services as $service) {
                        $service_data = Input::get('hotel_service_'.$service->id);
                        if ( $service_data ) {
                            $service->name = $service_data;
                            $service->save();
                        }
                    }
                }

                //add new services
                $services = Input::get('new_hotel_service');
                if ( $services ) {
                    foreach( $services as $service_name ) {
                        Service::create(
                            array(
                                'name'         => $service_name,
                                'content_id'   => $hotel->id,
                                'content_type' => 'hotel',
                                'status'       => 1
                            )
                        );
                    }
                }


            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.hotels.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The hotel has been updated');
            return Redirect::action('backend.hotels');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.hotels.edit', array('id' => $id) )->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $hotel = Hotel::find($id);
        $address = $hotel->address();
        $hotel->delete();
        $address->delete();
        return Response::json( array('result' => true, 'content' => 'The hotel has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $hotel = Hotel::find($id);
            if ($hotel) {
                $hotel->status = 1;
                $hotel->save();
            }
        }
        return Redirect::route('backend.hotels');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $hotel = Hotel::find($id);
            if ($hotel) {
                $hotel->status = 0;
                $hotel->save();
            }
        }
        return Redirect::route('backend.hotels');
    }

    function makeDefaultPhoto($hotel_id, $photo_id) {
        $hotel = Hotel::find($hotel_id);
        $hotel->default_photo_id = $photo_id;
        $hotel->save();
        return Response::json( array('result' => true, 'content' => 'The hotel photo has been updated') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroyHotelService($id)
    {
        $service = Service::find($id);
        $service->delete();
        return Response::json( array('result' => true, 'content' => 'The hotel service has been deleted') );
    }

}