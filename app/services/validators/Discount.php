<?php namespace App\Services\Validators;

class Discount extends Validator {
 
	public static $rules = array( 
		'default' => array (
			'name'        => 'required',
			'code'        => 'required',
			'count'       => 'required',
			'discount'    => 'required',
			'price_type'  => 'required',
            'status'      => 'required'
		),
		'update' => array (
            'name'        => 'required',
            'code'        => 'required',
            'count'       => 'required',
            'discount'    => 'required',
            'price_type'  => 'required',
            'status'      => 'required'
		)
	);
}
