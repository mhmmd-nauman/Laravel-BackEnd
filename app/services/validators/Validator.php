<?php namespace App\Services\Validators;

abstract class Validator 
{
	protected $attributes;
	protected $errors;
	
	public function __construct($attributes = null) 
	{
		$this->attributes = $attributes ?: \Input::all();
	}
	
	public function passes($ruleset = 'default')
	{
		$rules = static::$rules[$ruleset];
		$validation = \Validator::make($this->attributes, $rules);
		if($validation->passes()) return true;
		$this->errors = $validation->messages();
		return false;
	}
	
	public function getErrors()
    {
		return $this->errors;
    }
}