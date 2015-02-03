<?php namespace App\Services\Validators;

class Package extends Validator {
 
	public static $rules = array( 
		'default' => array (
			'name'            => 'required',
            'hotel_id'        => 'required',
			'description'     => 'required',
            'overnights_min'  => 'required',
			'status'          => 'required'
		),
		'update' => array (
            'name'            => 'required',
            'hotel_id'        => 'required',
            'description'     => 'required',
            'overnights_min'  => 'required',
            'status'          => 'required'
		)
	);
}
