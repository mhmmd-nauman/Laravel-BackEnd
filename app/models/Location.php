<?php

class Location extends \Eloquent {
	protected $fillable = array('location_id', 'type','name','lat','lng');
	
	public function parent()
    {
        return $this->belongsTo('Location', 'location_id');
    }

    public function children()
    {
        return $this->hasMany('Location', 'location_id');
    }
	
}