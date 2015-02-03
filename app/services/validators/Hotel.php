<?php namespace App\Services\Validators;

class Hotel extends Validator {
 
	public static $rules = array( 
		'default' => array (
			'name' => 'required',
			'description' => 'required',
			'user_id' => 'required',
			'status' => 'required',
		),
		'update' => array (
            'name' => 'required',
            'description' => 'required',
            'user_id' => 'required',
            'status' => 'required',
		)
	);
}
