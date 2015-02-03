<?php

class RoomPrice extends \Eloquent {
	protected $fillable = array('room_id','price','weekday');
    public $timestamps  = FALSE;

    public function room()
    {
        return $this->belongsTo('Room');
    }

}