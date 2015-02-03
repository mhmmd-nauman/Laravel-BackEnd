<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \Hotel, \Room, \RoomPrice, \Photo;

class RoomsController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('backend.rooms.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
        $spa = Room::select(array(
            'rooms.id',
            'rooms.name',
            'rooms.description',
            'hotels.name AS hotel_name',
            'rooms.max_residents',
            'rooms.status',
            'rooms.created_at'
        ))
        ->leftJoin('hotels', 'hotels.id', '=', 'rooms.hotel_id');

		return Datatables::of($spa)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\RoomsController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
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
        return View::make('backend.rooms.create', array('scripts' => $scripts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\Room;
        if($v->passes()) {
            try {
                //Save the Room
                $data = array();
                $data['hotel_id']        = Input::get('hotel_id');
                $data['name']            = Input::get('name');
                $data['description']     = Input::get('description');
                $data['max_residents']   = Input::get('max_residents');
                $data['status']          = Input::get('status');
                $room = Room::create($data);

                RoomPrice::create(array('price' => Input::get('mon_price'), 'weekday' => 'mon', 'room_id' => $room->id ));
                RoomPrice::create(array('price' => Input::get('tue_price'), 'weekday' => 'tue', 'room_id' => $room->id ));
                RoomPrice::create(array('price' => Input::get('wed_price'), 'weekday' => 'wed', 'room_id' => $room->id ));
                RoomPrice::create(array('price' => Input::get('thu_price'), 'weekday' => 'thu', 'room_id' => $room->id ));
                RoomPrice::create(array('price' => Input::get('fri_price'), 'weekday' => 'fri', 'room_id' => $room->id ));
                RoomPrice::create(array('price' => Input::get('sat_price'), 'weekday' => 'sat', 'room_id' => $room->id ));
                RoomPrice::create(array('price' => Input::get('sun_price'), 'weekday' => 'sun', 'room_id' => $room->id ));

            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.rooms.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new room has been created');
            return Redirect::action('backend.rooms');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.rooms.create')->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $room = Room::with('hotel')->find($id);
        $room_prices = RoomPrice::where('room_id', '=', $id)->get();
        $photos = Photo::select(array('*'))
                       ->where('photos.content_type', '=', 'rooms')
                       ->where('photos.content_id', '=', $id)
                       ->get();

        $scripts  = '<script type="text/javascript" src="'.URL::asset('js/plugins/typeahead.bundle.min.js').'"></script>';
        return View::make('backend.rooms.edit', array('room' => $room, 'scripts' => $scripts, 'room_prices' => $room_prices, 'photos' => $photos) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function update($id)
    {
        $v = new \App\Services\Validators\Room;
        if($v->passes('update')) {
            try {
                $room = Room::find($id);
                $room->name          = Input::get('name');
                $room->description   = Input::get('description');
                $room->status        = Input::get('status');
                $room->hotel_id      = Input::get('hotel_id');
                $room->max_residents = Input::get('max_residents');
                $room->save();

                $mon_price = RoomPrice::where('room_id', '=', $id)
                                        ->where('weekday', '=', 'mon')
                                        ->update( array('price' => Input::get('mon_price')) );

                $tue_price = RoomPrice::where('room_id', '=', $id)
                                        ->where('weekday', '=', 'tue')
                                        ->update( array('price' => Input::get('tue_price')) );

                $wed_price = RoomPrice::where('room_id', '=', $id)
                                        ->where('weekday', '=', 'wed')
                                        ->update( array('price' => Input::get('wed_price')) );

                $thu_price = RoomPrice::where('room_id', '=', $id)
                                        ->where('weekday', '=', 'thu')
                                        ->update( array('price' => Input::get('thu_price')) );

                $fri_price = RoomPrice::where('room_id', '=', $id)
                                        ->where('weekday', '=', 'fri')
                                        ->update( array('price' => Input::get('fri_price')) );

                $sat_price = RoomPrice::where('room_id', '=', $id)
                                        ->where('weekday', '=', 'sat')
                                        ->update( array('price' => Input::get('sat_price')) );

                $sun_price = RoomPrice::where('room_id', '=', $id)
                                        ->where('weekday', '=', 'sun')
                                        ->update( array('price' => Input::get('sun_price')) );

            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.rooms.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The room has been updated');
            return Redirect::action('backend.rooms');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.rooms.edit', array('id' => $id) )->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $room = Room::find($id);
        $room_prices = RoomPrice::where('room_id', '=', $room->id)
                                  ->delete();


        $room->delete();
        return Response::json( array('result' => true, 'content' => 'The room has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $room = Room::find($id);
            if ($room) {
                $room->status = 1;
                $room->save();
            }
        }
        return Redirect::route('backend.rooms');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $room = Room::find($id);
            if ($room) {
                $room->status = 0;
                $room->save();
            }
        }
        return Redirect::route('backend.rooms');
    }

    /**
     * Autocomplete hotels for spas
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

    public function makeDefaultPhoto($room_id, $photo_id) {
        $room = Room::find($room_id);
        $room->default_photo_id = $photo_id;
        $room->save();
        return Response::json( array('result' => true, 'content' => 'The room photo has been updated') );
    }

}