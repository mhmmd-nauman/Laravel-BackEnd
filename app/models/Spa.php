<?php

class Spa extends \Eloquent {
	protected $fillable = array('name','description','hotel_id');

    public function hotel()
    {
        return $this->belongsTo('Hotel');
    }
}