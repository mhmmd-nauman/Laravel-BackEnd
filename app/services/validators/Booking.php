<?php namespace App\Services\Validators;

class Booking extends Validator {
 
	public static $rules = array( 
		'default' => array (
			'package_id' => 'required|digits_between:1,10',
			'from'       => 'required|date',
			'to'         => 'required|date',
            'persons'    => 'required|digits_between:1,3'
		),
        'order' => array(
            'package_id' => 'required',
            'email'      => 'required|email',
            'name'       => 'required',
            'phone'      => 'required',
        )
	);
}
