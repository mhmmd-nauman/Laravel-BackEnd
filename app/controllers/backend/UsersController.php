<?php namespace App\Controllers\Backend;

use View, URL, Datatables, Notification, Redirect, Input, Auth, Response;
use \User;

class UsersController extends BackendBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('backend.users.index');
	}
	
	/**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
		$currentUser = Auth::user();
		$users = User::where('users.id', '!=', $currentUser->id)->select( 
			array (
                'users.id',
				'users.is_admin',
				'users.email',
				'users.first_name',
				'users.last_name',
				'users.created_at',
				'users.status'
				
			)
		);
		return Datatables::of($users)
			->edit_column('is_admin','@if($is_admin == 1) Yes @else No @endif') 
			->edit_column('status','@if($status == 1) Active @else Inactive @endif') 
			->add_column('actions', '
            <a href="{{ URL::action(\'App\Controllers\Backend\VenuesController@index\', array("user-".$id) ) }}" class="btn btn-default btn-sm" title="List Places"><i class="fa fa-fw fa-map-marker"></i></a>
            <a href="{{ URL::action(\'App\Controllers\Backend\PhotosController@index\', array("user-".$id) ) }}" class="btn btn-default btn-sm" title="List Photos"><i class="fa fa-fw fa-picture-o"></i></a>
            <a href="{{ URL::action(\'App\Controllers\Backend\ReviewsController@index\', array("user-".$id) ) }}" class="btn btn-default btn-sm" title="List Reviews"><i class="fa fa-fw fa-comments"></i></a>
            <a href="{{ URL::action(\'App\Controllers\Backend\UsersController@edit\', array($id) ) }}" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-fw fa-edit"></i></a>
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
		return View::make('backend.users.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Redirect
	 */
	public function store()
	{
		$v = new \App\Services\Validators\User;
		if($v->passes()) {
			try { 
                $data = array (
                    'email'      => Input::get('email'),
                    'first_name' => Input::get('first_name'),
                    'last_name'  => Input::get('last_name'),
                    'gender'     => Input::get('gender'),
                    'is_admin'   => Input::get('is_admin'),
                    'status'     => Input::get('status'),
                    'password'   => \Hash::make(Input::get('password')),
                );
				$user = User::create($data);
			} 
			catch(\Exception $e) {
				Notification::container('backendFeedback')->error($e->getMessage());
				return Redirect::action('backend.users.create')->withInput();
			}
			Notification::container('backendFeedback')->success('The new user has been created');
			return Redirect::action('backend.users');
		} 
		Notification::container('backendFeedback')->error($v->getErrors()->toArray());
		return Redirect::route('backend.users.create')->withInput();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function edit($id)
	{
		$user = User::find($id);
		return View::make('backend.users.edit', compact('user') );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function update($id)
	{
		$v = new \App\Services\Validators\User;
		if($v->passes('update')) {
			try {
				$user = User::find($id);
				$user->email      = Input::get('email');
				$user->first_name = Input::get('first_name');
				$user->last_name  = Input::get('last_name');
				$user->gender     = Input::get('gender');
				$user->is_admin   = Input::get('is_admin');
				$user->status     = Input::get('status');
				if (Input::has('password'))
					$user->password = \Hash::make(Input::get('password'));
				$user->save();
			} 
			catch(\Exception $e) {
				Notification::container('backendFeedback')->error($e->getMessage());
				return Redirect::route('backend.users.edit', array('id' => $id) )->withInput();
			}
			Notification::container('backendFeedback')->success('The user has been updated');
			return Redirect::action('backend.users');
		}
		Notification::container('backendFeedback')->error($v->getErrors()->toArray());
		return Redirect::route('backend.users.edit', array('id' => $id) )->withInput();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = User::find($id);
		$user->delete();
		return Response::json( array('result' => true, 'content' => 'The user has been deleted') );
	}
    
    function activate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $user = User::find($id);
            if ($user) {
                $user->status = 1;
                $user->save();
            }
        }
        return Redirect::route('backend.users');
    }
    
    function deactivate() {
        $ids = explode(",",Input::get('ids'));
        foreach ($ids as $id) {
            $user = User::find($id);
            if ($user) {
                $user->status = 0;
                $user->save();
            }
        }
        return Redirect::route('backend.users');
    }

}