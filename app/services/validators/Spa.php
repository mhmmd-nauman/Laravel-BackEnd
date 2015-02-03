<?php namespace App\Services\Validators;

class Spa extends Validator {
 
	public static $rules = array( 
		'default' => array (
			'name'        => 'required',
			'description' => 'required',
			'hotel_id'    => 'required',
			'status'      => 'required',
		),
		'update' => array (
            'name'        => 'required',
            'description' => 'required',
            'hotel_id'    => 'required',
            'status'      => 'required',
		)
	);
}
