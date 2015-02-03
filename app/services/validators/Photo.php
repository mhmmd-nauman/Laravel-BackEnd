<?php namespace App\Services\Validators;

class Photo extends Validator {
 
	public static $rules = array( 
		'default' => array (
			'content_type' => 'required',
            'content_id' => 'required',
			'file' => 'required|mimes:jpeg,bmp,png|max:5000',
            'status' => 'required',
		),
		'update' => array (
			'venue_id' => 'required',
			'file' => 'mimes:jpeg,bmp,png|max:5000',
            'status' => 'required',
		)
	);
}
