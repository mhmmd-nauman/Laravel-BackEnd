<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Sentry, Response;
use \Highlight, \User, \Photo;
use Whoops\Example\Exception;

class HighlightsController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('backend.highlights.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
        $hotel_highlights = Highlight::select(array (
            'hotel_highlights.id',
            'hotel_highlights.name',
            'hotel_highlights.description',
            'hotel_highlights.status',
            'hotel_highlights.created_at',
            'hotels.name AS hotel_name',
        ))
        ->leftJoin('hotels', 'hotels.id', '=', 'hotel_highlights.hotel_id');

		return Datatables::of($hotel_highlights)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif')
            ->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\HighlightsController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
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
        return View::make('backend.highlights.create', array('user' => Sentry::getUser(), 'scripts' => $scripts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Redirect
     */
    public function store()
    {
        $v = new \App\Services\Validators\Highlight;
        if($v->passes()) {
            try {
                //Save the Highlight
                $data = array();
                $data['hotel_id']            = Input::get('hotel_id');
                $data['name']                = Input::get('name');
                $data['description']         = Input::get('description');
                $data['status']              = Input::get('status');
                $hotel_highlight = Highlight::create($data);
            }
            catch(\Exception $e) {
                var_dump ($e->getMessage());
                die();
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::action('backend.hotel_highlights.create')->withInput();
            }
            Notification::container('backendFeedback')->success('The new hotel_highlight has been created');
            return Redirect::action('backend.hotel_highlights');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.hotel_highlights.create')->withInput();
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
            $hotel_highlight = Highlight::with('Hotel')->find($id);
            $photos = Photo::select(array('*'))
                           ->where('photos.content_type', '=', 'highlights')
                           ->where('photos.content_id', '=', $id)
                           ->get();

            $quote_photos = Photo::select(array('*'))
                           ->where('photos.content_type', '=', 'highlights_quote')
                           ->where('photos.content_id', '=', $id)
                           ->get();

        } catch(Exception $e) {
           App::abort(404);
        }

        $scripts  = '<script type="text/javascript" src="'.URL::asset('http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false&amp;language=en').'"></script>';
        return View::make('backend.highlights.edit', array(
            'scripts'         => $scripts,
            'hotel_highlight' => $hotel_highlight,
            'photos'          => $photos,
            'quote_photos'    => $quote_photos
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
        $v = new \App\Services\Validators\Highlight;

        if($v->passes('update')) {
            try {

                $hotel_highlight = Highlight::find($id);
                $hotel_highlight->hotel_id     = Input::get('hotel_id');
                $hotel_highlight->name         = Input::get('name');
                $hotel_highlight->description  = Input::get('description');
                $hotel_highlight->status       = Input::get('status');
                $hotel_highlight->quote_text   = Input::get('quote_text');
                $hotel_highlight->quote_author = Input::get('quote_author');
                $hotel_highlight->save();

            }
            catch(\Exception $e) {
                Notification::container('backendFeedback')->error($e->getMessage());
                return Redirect::route('backend.hotel_highlights.edit', array('id' => $id) )->withInput();
            }
            Notification::container('backendFeedback')->success('The hotel_highlight has been updated');
            return Redirect::action('backend.hotel_highlights');
        }
        Notification::container('backendFeedback')->error($v->getErrors()->toArray());
        return Redirect::route('backend.hotel_highlights.edit', array('id' => $id) )->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $hotel_highlight = Highlight::find($id);
        $hotel_highlight->delete();
        return Response::json( array('result' => true, 'content' => 'The hotel_highlight has been deleted') );
    }

    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $hotel_highlight = Highlight::find($id);
            if ($hotel_highlight) {
                $hotel_highlight->status = 1;
                $hotel_highlight->save();
            }
        }
        return Redirect::route('backend.hotel_highlights');
    }

    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $hotel_highlight = Highlight::find($id);
            if ($hotel_highlight) {
                $hotel_highlight->status = 0;
                $hotel_highlight->save();
            }
        }
        return Redirect::route('backend.hotel_highlights');
    }

    function makeDefaultPhoto($hotel_highlight_id, $photo_id) {
        $hotel_highlight = Highlight::find($hotel_highlight_id);
        $hotel_highlight->default_photo_id = $photo_id;
        $hotel_highlight->save();
        return Response::json( array('result' => true, 'content' => 'The hotel highlight photo has been updated') );
    }

    function makeDefaultQuotePhoto($hotel_highlight_id, $photo_id) {
        $hotel_highlight = Highlight::find($hotel_highlight_id);
        $hotel_highlight->default_quote_photo_id = $photo_id;
        $hotel_highlight->save();
        return Response::json( array('result' => true, 'content' => 'The hotel highlight quote photo has been updated') );
    }

}