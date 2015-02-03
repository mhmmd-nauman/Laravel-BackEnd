<?php

class Spas extends \Eloquent {
	protected $fillable = array('name','description','hotel_id','status');

    public function hotel()
    {
        return $this->belongsTo('Hotel');
    }
}