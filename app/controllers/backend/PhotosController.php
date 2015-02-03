<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Auth, Response;
use \Photo, \Hotel, \Package, \User;

class PhotosController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id = null)
	{
		return View::make('backend.photos.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
		$photos = Photo::select( array (
                            'photos.id',
                            'photos.file',
                            'photos.content_type',
							'photos.created_at',
							'photos.status'
						));

		return Datatables::of($photos)
            ->edit_column('status','@if($status == 1) Active @else Inactive @endif') 
            ->edit_column('file','<img src="{{ URL::route(\'frontend.image.photo\', array( \'size\' => \'tiny\', \'id\' => $id ) ) }}" class="img-responsive" />')
            ->add_column('actions', '<a href="javascript:void(0);" onclick="deleteItem(\'{{$id}}\');" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-fw fa-times"></i></a>')
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
        $user = Auth::user();

        $sources = $this->getJqueryUiSources();
        $scripts = $sources['scripts'];
        $links   = $sources['links'];

        return View::make('backend.photos.create', compact('user', 'scripts', 'links') );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Redirect
	 */
	public function store()
	{
		$v = new \App\Services\Validators\Photo;
		if($v->passes()) {
			try { 
                $file   = \Str::random(20);
                $ext    = '.'.Input::file('file')->getClientOriginalExtension();
				$path   = 'uploads/'.Input::get('content_type').'/';
				$upload = Input::file('file')->move($path, $file.$ext);
				$data = array (
                    'content_type' => Input::get('content_type'),
                    'content_id'   => Input::get('content_id'),
                    'file'         => $path.$file.$ext,
                    'status'       => Input::get('status')
                );
				Photo::create($data);

                return Redirect::to(URL::previous());
			} 
			catch(\Exception $e) {
				Notification::container('backendFeedback')->error($e->getMessage());
				return Redirect::action('backend.photos.create')->withInput();
			}
			Notification::container('backendFeedback')->success('The new Photo has been created');
			return Redirect::action('backend.photos');
		} 
		Notification::container('backendFeedback')->error($v->getErrors()->toArray());
		return Redirect::route('backend.photos.create')->withInput();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function edit($id)
	{
		$photo = Photo::find($id);

        $sources = $this->getJqueryUiSources();
        $scripts = $sources['scripts'];
        $links   = $sources['links'];

		return View::make('backend.photos.edit', compact('photo', 'scripts', 'links') );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function update($id)
	{
		$v = new \App\Services\Validators\Photo;
		if($v->passes('update')) {
			try {
				$photo = Photo::find($id);
                if (Input::hasFile('file')) {
                    \File::delete($photo->file);
                    //TODO: Improve file name
                    $file   = \Str::random(20);
                    $ext    = '.'.Input::file('file')->getClientOriginalExtension();
                    $path   = 'uploads/venues/';
                    $upload = Input::file('file')->move($path, $file.$ext);
                    if ($upload) {
                        $photo->file = $path.$file.$ext;
                    }
                }
                $photo->venue_id = Input::get('venue_id');
                $photo->status = Input::get('status');
				$photo->save();
			} 
			catch(\Exception $e) {
				Notification::container('backendFeedback')->error($e->getMessage());
				return Redirect::route('backend.photos.edit', array('id' => $id) )->withInput();
			}
			Notification::container('backendFeedback')->success('The photo has been updated');
			return Redirect::action('backend.photos');
		}
		Notification::container('backendFeedback')->error($v->getErrors()->toArray());
		return Redirect::route('backend.photos.edit', array('id' => $id) )->withInput();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$photo = Photo::find($id);
        \File::delete($photo->file);
        $photo->delete();
		return Response::json( array('result' => true, 'content' => 'The photo has been deleted') );
	}
    
    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $photo = Photo::find($id);
            if ($photo) {
                $photo->status = 1;
                $photo->save();
            }
        }
        return Redirect::route('backend.photos');
    }
    
    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $photo = Photo::find($id);
            if ($photo) {
                $photo->status = 0;
                $photo->save();
            }
        }
        return Redirect::route('backend.photos');
    }

}