<?php namespace App\Controllers\Backend;

use OAuth\Common\Exception\Exception;
use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \Spas, \Hotel, \Service;

class SpasController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('backend.spa.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
        $spa = Spas::select(array(
            'spas.id',
            'spas.name',
            'spas.description',
            'hotels.name AS hotel_name',
            'spas.status',
            'spas.created_at'
        ))
        ->leftJoin('hotels', 'hotels.id', '=', 'spas.hotel_id');

		return Datatables::of($spa)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\SpasController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
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
        return View::make('backend.spa.create', array('scripts' => $scripts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\Spa;
        if($v->passes()) {
            try {
                //Save the Spa
                $data = array();
                $data['hotel_id']        = Input::get('hotel_id');
                $data['name']            = Input::get('name');
                $data['description']     = Input::get('description');
                $data['status']          = Input::get('status');
                $spa = Spas::create($data);
            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.spa.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new spa has been created');
            return Redirect::action('backend.spa');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.spa.create')->withInput();
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
            $spa = Spas::with('hotel')->find($id);
            $services = Service::where('content_type', '=', 'spa')
                               ->where('content_id', '=', $spa->id)
                               ->get();
        } catch (Exception $e) {
            App::abort(404);
        }

        $scripts  = '<script type="text/javascript" src="'.URL::asset('js/plugins/typeahead.bundle.min.js').'"></script>';
        return View::make('backend.spa.edit', array(
            'spa' => $spa,
            'services' => $services,
            'scripts' => $scripts
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
        $v = new \App\Services\Validators\Spa;
        if($v->passes('update')) {
            try {
                $spa = Spas::find($id);
                $spa->name        = Input::get('name');
                $spa->description = Input::get('description');
                $spa->status      = Input::get('status');
                $spa->hotel_id    = Input::get('hotel_id');
                $spa->save();

                $available_services = Service::where('content_type', '=', 'spa')
                                             ->where('content_id', '=', $spa->id)
                                             ->get();

                if ( $available_services->count() ) {
                    foreach($available_services as $service) {
                        $service_data = Input::get('spa_service_'.$service->id);
                        if ( $service_data ) {
                            $service->name = $service_data;
                            $service->save();
                        }
                    }
                }

                //add new services
                $services = Input::get('new_spa_service');
                if ( $services ) {
                    foreach( $services as $service_name ) {
                        Service::create(
                            array(
                                'name'         => $service_name,
                                'content_id'   => $spa->id,
                                'content_type' => 'spa',
                                'status'       => 1
                            )
                        );
                    }
                }

            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.spa.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The spa has been updated');
            return Redirect::action('backend.spa');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.spa.edit', array('id' => $id) )->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $spa = Spas::find($id);
        $spa->delete();
        return Response::json( array('result' => true, 'content' => 'The spa has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $spa = Spas::find($id);
            if ($spa) {
                $spa->status = 1;
                $spa->save();
            }
        }
        return Redirect::route('backend.spa');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $spa = Spas::find($id);
            if ($spa) {
                $spa->status = 0;
                $spa->save();
            }
        }
        return Redirect::route('backend.spa');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroySpaService($id)
    {
        $service = Service::find($id);
        $service->delete();
        return Response::json( array('result' => true, 'content' => 'The spa service has been deleted') );
    }

}