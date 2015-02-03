<?php namespace App\Services\Validators;

class LocationGroup extends Validator {
 
	public static $rules = array( 
		'default' => array (
			'name'            => 'required',
            'hotel_id'        => 'required',
            'status'          => 'required',
            'type'            => 'required',
            'location_hotels' => 'required'
        ),
        'update' => array (
            'name'            => 'required',
            'status'          => 'required',
            'type'            => 'required',
		)
	);
}
