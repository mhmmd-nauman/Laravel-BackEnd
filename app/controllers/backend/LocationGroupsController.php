<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \User, \Hotel, \LocationGroup, \LocationGroupItem;

class LocationGroupsController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('backend.location_groups.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
        $location_groups = LocationGroup::select(array (
            'location_groups.id',
            'location_groups.name',
            'location_groups.description',
            'location_groups.type',
            'location_groups.status',
            'location_groups.created_at'
        ))
        ->orderBy('location_groups.id', 'DESC');

		return Datatables::of($location_groups)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\LocationGroupsController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
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
        return View::make('backend.location_groups.create', array('user' => Sentry::getUser(), 'scripts' => $scripts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\LocationGroup;
        if($v->passes()) {
            try {

                $location_hotels = Input::get('location_hotels');

                //Save the LocationGroup
                $data = array();
                $data['hotel_id']          = Input::get('hotel_id');
                $data['name']              = Input::get('name');
                $data['description']       = Input::get('description');
                $data['status']            = Input::get('status');
                $data['type']              = Input::get('type');
                $data['slug']              = $this->slugify(Input::get('name'));

                $location_group = LocationGroup::create($data);

                foreach($location_hotels as $hotel_id) {
                    LocationGroupItem::create(
                        array(
                            'item_id'           => $hotel_id,
                            'location_group_id' => $location_group->id
                        )
                    );
                }

            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.location_groups.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new location group has been created');
            return Redirect::action('backend.location_groups');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.location_groups.create')->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {

        $location_group_model = new LocationGroup();

        $location_group    = LocationGroup::find($id);
        $location_hotels   = $location_group_model->getLocationHotels($id);

        $scripts  = '<script type="text/javascript" src="'.URL::asset('js/plugins/typeahead.bundle.min.js').'"></script>';
        return View::make('backend.location_groups.edit',
            array(
                'scripts'         => $scripts,
                'location_group'  => $location_group,
                'location_hotels' => $location_hotels
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
        $v = new \App\Services\Validators\LocationGroup;
        if($v->passes('update')) {
            try {

                $location_group = LocationGroup::find($id);
                $location_group->name        = Input::get('name');
                $location_group->description = Input::get('description');
                $location_group->status      = Input::get('status');
                $location_group->type        = Input::get('type');
                $location_group->slug        = Input::get('slug');
                $location_group->save();

                $location_hotels = Input::get('location_hotels');
                if ( $location_hotels ) {
                    foreach($location_hotels as $hotel_id) {
                        LocationGroupItem::create(
                            array(
                                'item_id'           => $hotel_id,
                                'location_group_id' => $location_group->id
                            )
                        );
                    }
                }

            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.location_groups.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The location group has been updated');
            return Redirect::action('backend.location_groups');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.location_groups.edit', array('id' => $id) )->withInput();
    }

    /**
     * Autocomplete hotels for location_groups
     *
     * @return Response
     */
    public function autocomplete()
    {

        $hotel_name = Input::get('name');

        $hotels = Hotel::select(array('id', 'name'))
                        ->where('name', 'like', "%$hotel_name%")
                        ->where('status', '=', 1)
                        ->take(10)
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
        $location_group = LocationGroup::find($id);
        $location_group->delete();

        $location_hotel = LocationGroupItem::where('location_group_id', '=', $id);
        $location_hotel->delete();

        return Response::json( array('result' => true, 'content' => 'The location group has been deleted') );
    }

    public function destroyHotel($id, $location_id)
    {
        $location_hotel = LocationGroupItem::where('item_id', '=', $id)
                                           ->where('location_group_id', '=', $location_id);

        $location_hotel->delete();
        return Response::json( array('result' => true, 'content' => 'The location hotel has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $location_group = LocationGroup::find($id);
            if ($location_group) {
                $location_group->status = 1;
                $location_group->save();
            }
        }
        return Redirect::route('backend.location_groups');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $location_group = LocationGroup::find($id);
            if ($location_group) {
                $location_group->status = 0;
                $location_group->save();
            }
        }
        return Redirect::route('backend.location_groups');
    }

}