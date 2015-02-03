<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \Amusement, \User, \Photo;
use Whoops\Example\Exception;

class AmusementsController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('backend.amusements.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
        $amusements = Amusement::select(array (
            'amusements.id',
            'amusements.name',
            'amusements.description',
            'amusements.status',
            'amusements.created_at',
            'hotels.name AS hotel_name',
        ))
        ->leftJoin('hotels', 'hotels.id', '=', 'amusements.hotel_id');

		return Datatables::of($amusements)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\AmusementsController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
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
        return View::make('backend.amusements.create', array('user' => Sentry::getUser(), 'scripts' => $scripts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\Amusement;
        if($v->passes()) {
            try {
                //Save the Amusement
                $data = array();
                $data['hotel_id']            = Input::get('hotel_id');
                $data['name']                = Input::get('name');
                $data['description']         = Input::get('description');
                $data['status']              = Input::get('status');
                $amusement = Amusement::create($data);
            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.amusements.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new amusement has been created');
            return Redirect::action('backend.amusements');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.amusements.create')->withInput();
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
            $amusement = Amusement::with('Hotel')->find($id);
            $photos = Photo::select(array('*'))
                           ->where('photos.content_type', '=', 'activities')
                           ->where('photos.content_id', '=', $id)
                           ->get();

        } catch(Exception $e) {
           App::abort(404);
        }

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';
        return View::make('backend.amusements.edit', array(
            'scripts'    => $scripts,
            'amusement' => $amusement,
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
        $v = new \App\Services\Validators\Amusement;

        if($v->passes('update')) {
            try {

                $amusement = Amusement::find($id);
                $amusement->hotel_id    = Input::get('hotel_id');
                $amusement->name        = Input::get('name');
                $amusement->description = Input::get('description');
                $amusement->status      = Input::get('status');
                $amusement->save();

            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.amusements.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The amusement has been updated');
            return Redirect::action('backend.amusements');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.amusements.edit', array('id' => $id) )->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $amusement = Amusement::find($id);
        $amusement->delete();
        return Response::json( array('result' => true, 'content' => 'The amusement has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $amusement = Amusement::find($id);
            if ($amusement) {
                $amusement->status = 1;
                $amusement->save();
            }
        }
        return Redirect::route('backend.amusements');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $amusement = Amusement::find($id);
            if ($amusement) {
                $amusement->status = 0;
                $amusement->save();
            }
        }
        return Redirect::route('backend.amusements');
    }

    function makeDefaultPhoto($amusement_id, $photo_id) {
        $amusement = Amusement::find($amusement_id);
        $amusement->default_photo_id = $photo_id;
        $amusement->save();
        return Response::json( array('result' => true, 'content' => 'The amusement photo has been updated') );
    }

}