<?php namespace App\Controllers\Frontend;

use Sentry, Input, Mail, Response, Redirect, URL, OAuth;
use \User;
use Whoops\Example\Exception;

class ClientController extends FrontendBaseController {

	public function register()
	{
        $pass = str_random(8);
        $email = Input::get('email');
        $response = array();

        if ( $email && filter_var($email, FILTER_VALIDATE_EMAIL) ) {

            try {
                $user = Sentry::createUser(array(
                    'email'    => $email,
                    'password' => $pass,
                    'register_provider' => 'site',
                    'activated' => 1
                ));

                // get users group
                $user_group = Sentry::getGroupProvider()->findByName('Users');
                // add user to users group
                $user->addGroup($user_group);

                /* TODO: I have descided to disable this mail until we have decided content. \\Kristoffer
                Mail::send('emails.auth.register', array('password' => $pass), function($message) use ($email) {
                    $message->from('noreply@spason.se', 'Spason.se');
                    $message->subject('Spason registration password');
                    $message->to($email);
                });
                */
                $credentials = array(
                    'email'    => $email,
                    'password' => $pass,
                );

                Sentry::authenticate($credentials);

                $user = Sentry::getUser();

                $response['result']['status'] = 1;
                $response['result']['user']['email'] = $user->email;

            } catch (\Exception $e) {
                $response['error'] = $e->getMessage();
            }

        } else {
            $response['error'] = 'Email are not valid';
        }

        return Response::json($response);
	}

    public function registerValidate()
    {
        $email    = Input::get('email');
        $response = array();

        if ( $email && filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            $user = User::where('email', '=', $email)->get();

            if ( $user->count() ) {
                $response['valid'] = FALSE; // user exists
            }  else {
                $response['valid'] = TRUE; // user not exists
            }

        } else {
            $response['valid'] = TRUE;
        }

        return Response::json($response);
    }

    public function update()
    {

        $response = array();

        if ( Sentry::check() ) { // user logged in
            $name  = Input::get('name');
            $phone = Input::get('phone');
            $pass  = Input::get('password');

            $user = Sentry::getUser();

            if ( $name ) {
                $user->first_name = $name;
            }

            if ( $phone ) {
                $user->phone = $phone;
            }

            if ( $pass ) {
                $resetCode = $user->getResetPasswordCode();

                if ( $user->checkResetPasswordCode($resetCode) ) {
                    $user->attemptResetPassword($resetCode, $pass);
                }

                $email = $user->email;

                Mail::send('emails.auth.password_change', array('password' => $pass), function($message) use ($email) {
                    $message->from('noreply@spason.se', 'Spason.se');
                    $message->subject('Spason password changed');
                    $message->to($email);
                });
            }

        } else {
            $response['error'] = 'User is not logged in';
        }

        $response['valid'] = TRUE;

        //return Response::json($response);
        return Redirect::to(URL::previous());
    }

    public function logout()
    {
        Sentry::logout();
        return Redirect::to(URL::previous());
    }

    public function loginWithFacebook()
    {

        // get data from input
        $code = Input::get( 'code' );

        // get fb service
        $fb = OAuth::consumer( 'Facebook' );

        // if code is provided get user data and sign in
        if ( !empty( $code ) ) {
            $token = $fb->requestAccessToken( $code );
            // Send a request with it
            $result = json_decode( $fb->request( '/me' ), true );

            $user = User::where('email', '=', $result['email'])->get();
            $pass = substr( md5($result['id']), 0, 8 );

            $credentials = array(
                'email'    => $result['email'],
                'password' => $pass,
            );

            if ( $user->count() ) { // if user exists, authentificate
                Sentry::authenticate($credentials);
            } else {
                $user = Sentry::createUser(array(
                    'email'             => $result['email'],
                    'first_name'        => $result['first_name'],
                    'password'          => $pass,
                    'register_provider' => 'facebook',
                    'activated'         => 1
                ));

                Sentry::authenticate($credentials);
            }

            return Redirect::route('frontend.hotel.search');

        } else {
            // get fb authorization
            $url = $fb->getAuthorizationUri();

            // return to facebook login url
            return Redirect::to( (string)$url );
        }

    }

    public function login()
    {
        try {

            $credentials = array(
                'email'    => Input::get('email'),
                'password' => Input::get('password'),
            );

            Sentry::authenticate($credentials);

        } catch (\Exception $e) {

        }

        return Redirect::to(URL::previous());
    }

}