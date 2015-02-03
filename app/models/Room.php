<?php

class Room extends \Eloquent {
	protected $fillable = array('name','description','max_residents','hotel_id', 'status');

    public function hotel()
    {
        return $this->belongsTo('Hotel');
    }

    public function roomPrice()
    {
        return $this->hasMany('RoomPrice');
    }
}