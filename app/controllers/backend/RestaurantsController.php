<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \Restaurant, \User, \Photo;
use Whoops\Example\Exception;

class RestaurantsController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('backend.restaurants.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
        $restaurants = Restaurant::select(array (
            'restaurants.id',
            'restaurants.name',
            'restaurants.description',
            'restaurants.status',
            'restaurants.created_at',
            'hotels.name AS hotel_name',
        ))
        ->leftJoin('hotels', 'hotels.id', '=', 'restaurants.hotel_id');

		return Datatables::of($restaurants)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\RestaurantsController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
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
        return View::make('backend.restaurants.create', array('user' => Sentry::getUser(), 'scripts' => $scripts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\Restaurant;
        if($v->passes()) {
            try {
                //Save the Restaurant
                $data = array();
                $data['hotel_id']            = Input::get('hotel_id');
                $data['name']                = Input::get('name');
                $data['description']         = Input::get('description');
                $data['status']              = Input::get('status');
                $restaurant = Restaurant::create($data);
            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.restaurants.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new restaurant has been created');
            return Redirect::action('backend.restaurants');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.restaurants.create')->withInput();
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
            $restaurant = Restaurant::with('Hotel')->find($id);
            $photos = Photo::select(array('*'))
                           ->where('photos.content_type', '=', 'restaurants')
                           ->where('photos.content_id', '=', $id)
                           ->get();

        } catch(Exception $e) {
           App::abort(404);
        }

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';
        return View::make('backend.restaurants.edit', array(
            'scripts'    => $scripts,
            'restaurant' => $restaurant,
            'photos'     => $photos
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
        $v = new \App\Services\Validators\Restaurant;

        if($v->passes('update')) {
            try {

                $restaurant = Restaurant::find($id);
                $restaurant->hotel_id    = Input::get('hotel_id');
                $restaurant->name        = Input::get('name');
                $restaurant->description = Input::get('description');
                $restaurant->status      = Input::get('status');
                $restaurant->save();

            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.restaurants.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The restaurant has been updated');
            return Redirect::action('backend.restaurants');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.restaurants.edit', array('id' => $id) )->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);
        $restaurant->delete();
        return Response::json( array('result' => true, 'content' => 'The restaurant has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $restaurant = Restaurant::find($id);
            if ($restaurant) {
                $restaurant->status = 1;
                $restaurant->save();
            }
        }
        return Redirect::route('backend.restaurants');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $restaurant = Restaurant::find($id);
            if ($restaurant) {
                $restaurant->status = 0;
                $restaurant->save();
            }
        }
        return Redirect::route('backend.restaurants');
    }

    function makeDefaultPhoto($restaurant_id, $photo_id) {
        $restaurant = Restaurant::find($restaurant_id);
        $restaurant->default_photo_id = $photo_id;
        $restaurant->save();
        return Response::json( array('result' => true, 'content' => 'The restaurant photo has been updated') );
    }

}