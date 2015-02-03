<?php namespace App\Controllers\Backend;

use Auth, Form, Input, Redirect, View, Notification, Datatables, Response;
use \User, \Hotel, \Settings;
use \RuleLog;

class DashboardController extends BackendBaseController {

	/**
	 * Display the dashboard page
	 * @return View
	 */
	public function index()
	{
        $users = array (
            'total' => User::count()
        );

        $hotels = array(
            'total' => Hotel::count()
        );

        $settings['email'] = Settings::where('name', '=', 'order_email')->get();
        $settings['ga']    = Settings::where('name', '=', 'google_analytics')->get();

        return View::make('backend.dashboard.index', compact('users', 'hotels', 'settings'));
	}
    
    /**
	 * Method to return the list of items for the dataTables. It handles pagination, sorting, filtering, etc.
	 *
	 * @return Response
	 */
	public function dataTables() 
	{
		
		$logs = RuleLog::leftJoin('users', 'logs.user_id', '=', 'users.id')
					   ->leftJoin('rules', 'logs.rule_id', '=', 'rules.id')
                       ->select( 
			array (
				'users.first_name',
				'users.last_name',
				'users.email',
				'rules.name',
				'logs.amount',
				'logs.updated_at',
				'logs.id'
			)
		);
		return Datatables::of($logs)
			->add_column('actions', '<a href="javascript:void(0);" onclick="deleteItem(\'{{$id}}\');" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-fw fa-times"></i></a>')
			->remove_column('id')
			->make();
	}
    
    /**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$log = RuleLog::find($id);
		$log->delete();
		return Response::json( array('result' => true, 'content' => 'The rule has been deleted') );
	}
	
	/**
	 * Display the settings page
	 * @return View
	 */
	public function getSettings()
	{
		$user = Auth::user();
		return View::make('backend.dashboard.settings', compact('user'));
	}
	
	/**
	 * Settings action
	 * @return Redirect
	 */
	public function putSettings()
	{
		$user = Auth::user();
		//Let's create our validator 
		$v = new \App\Services\Validators\User;
		if($v->passes('self'))
		{
			//If data is valid, let's update the user and to the right group.
			try {
				$user->email      = Input::get('email');
				$user->first_name = Input::get('first_name');
				$user->last_name  = Input::get('last_name');
				if (Input::has('password'))
					$user->password   = Hash::make(Input::get('password'));
				$user->save();
				//Let's logout and login the user again
				Auth::logout();
				Auth::login($user);
				Notification::container('backendFeedback')->success('Your profile has been updated');
			} 
			catch(\Exception $e) {
				Notification::container('backendFeedback')->error($e->getMessage());
			}
		} else {
			Notification::container('backendFeedback')->error($v->getErrors()->toArray());
		}
		return Redirect::route('backend.settings')->withInput();
	}

    public function storeSettings() {

        Settings::where('name', '=', 'order_email')->update(array('value' => Input::get('email')));
        Settings::where('name', '=', 'google_analytics')->update(array('value' => Input::get('google_analytics')));

        Notification::container('backendFeedback')->success('Settings has been updated');

        return Redirect::route('backend.default');
    }

}