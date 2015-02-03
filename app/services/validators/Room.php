<?php namespace App\Services\Validators;

class Room extends Validator {
 
	public static $rules = array( 
		'default' => array (
			'name'          => 'required',
			'description'   => 'required',
			'hotel_id'      => 'required',
            'max_residents' => 'required',
			'status'        => 'required',
		),
		'update' => array (
            'name'          => 'required',
            'description'   => 'required',
            'hotel_id'      => 'required',
            'max_residents' => 'required',
            'status'        => 'required',
		)
	);
}
