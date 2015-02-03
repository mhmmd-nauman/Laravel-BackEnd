<?php namespace App\Controllers\Backend;

use Auth, Form, Input, Redirect, View, Notification, Sentry;
use App\Models\User;

class AuthController extends BackendBaseController {
 
	/**
	 * Display the login page
	 * @return View
	 */
	public function getLogin()
	{
		return View::make('backend.auth.login');
	}

	/**
	 * Login action
	 * @return Redirect
	 */
	public function postLogin()
	{
        $credentials = array(
            'email'    => Input::get('email'),
            'password' => Input::get('password')
        );

        try{
            $throttleProvider = Sentry::getThrottleProvider();
            $throttleProvider->disable();

            $user = Sentry::authenticate($credentials, FALSE);

            if ( $user ) {
                return Redirect::route('backend.default');
            }

        } catch(\Exception $e) {
			Notification::container('backendFeedback')->error($e->getMessage());
			return Redirect::route('backend.login')->withInput();
        }

	}

	/**
	 * Logout action
	 * @return Redirect
	 */
	public function getLogout()
	{
        Sentry::logout();
		return Redirect::route('backend.login');
	}
		
}