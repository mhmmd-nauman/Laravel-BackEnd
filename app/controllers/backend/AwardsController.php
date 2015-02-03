<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \Award, \User, \Photo;
use Whoops\Example\Exception;

class AwardsController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('backend.awards.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
        $hotel_awards = Award::select(array (
            'hotel_awards.id',
            'hotel_awards.name',
            'hotel_awards.description',
            'hotel_awards.status',
            'hotel_awards.created_at',
            'hotels.name AS hotel_name',
        ))
        ->leftJoin('hotels', 'hotels.id', '=', 'hotel_awards.hotel_id');

		return Datatables::of($hotel_awards)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\AwardsController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
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
        return View::make('backend.awards.create', array('user' => Sentry::getUser(), 'scripts' => $scripts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\Award;
        if($v->passes()) {
            try {
                //Save the Award
                $data = array();
                $data['hotel_id']            = Input::get('hotel_id');
                $data['name']                = Input::get('name');
                $data['description']         = Input::get('description');
                $data['link']                = Input::get('link');
                $data['status']              = Input::get('status');
                $hotel_award = Award::create($data);
            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.hotel_awards.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new hotel_award has been created');
            return Redirect::action('backend.hotel_awards');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.hotel_awards.create')->withInput();
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
            $hotel_award = Award::with('Hotel')->find($id);
            $photos = Photo::select(array('*'))
                           ->where('photos.content_type', '=', 'awards')
                           ->where('photos.content_id', '=', $id)
                           ->get();

        } catch(Exception $e) {
           App::abort(404);
        }

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';
        return View::make('backend.awards.edit', array(
            'scripts'     => $scripts,
            'hotel_award' => $hotel_award,
            'photos'      => $photos
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
        $v = new \App\Services\Validators\Award;

        if($v->passes('update')) {
            try {

                $hotel_award = Award::find($id);
                $hotel_award->hotel_id    = Input::get('hotel_id');
                $hotel_award->name        = Input::get('name');
                $hotel_award->description = Input::get('description');
                $hotel_award->status      = Input::get('status');
                $hotel_award->link        = Input::get('link');
                $hotel_award->save();

            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.hotel_awards.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The hotel_award has been updated');
            return Redirect::action('backend.hotel_awards');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.hotel_awards.edit', array('id' => $id) )->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $hotel_award = Award::find($id);
        $hotel_award->delete();
        return Response::json( array('result' => true, 'content' => 'The hotel_award has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $hotel_award = Award::find($id);
            if ($hotel_award) {
                $hotel_award->status = 1;
                $hotel_award->save();
            }
        }
        return Redirect::route('backend.hotel_awards');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $hotel_award = Award::find($id);
            if ($hotel_award) {
                $hotel_award->status = 0;
                $hotel_award->save();
            }
        }
        return Redirect::route('backend.hotel_awards');
    }

    function makeDefaultPhoto($hotel_award_id, $photo_id) {
        $hotel_award = Award::find($hotel_award_id);
        $hotel_award->default_photo_id = $photo_id;
        $hotel_award->save();
        return Response::json( array('result' => true, 'content' => 'The hotel award photo has been updated') );
    }

}