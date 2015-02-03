<?php namespace App\Services\Validators;

class Treatment extends Validator {
 
	public static $rules = array( 
		'default' => array (
			'name'        => 'required',
			'description' => 'required',
			'spa_id'      => 'required',
            'persons'     => 'required',
            'price'       => 'required',
			'status'      => 'required',
		),
		'update' => array (
            'name'        => 'required',
            'description' => 'required',
            'spa_id'      => 'required',
            'persons'     => 'required',
            'price'       => 'required',
            'status'      => 'required',
		)
	);
}
