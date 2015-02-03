<?php

class Amusement extends \Eloquent {
	protected $fillable = [
        'name',
        'description',
        'default_photo_id',
        'status',
        'hotel_id'
    ];

    public function hotel()
    {
        return $this->belongsTo('Hotel');
    }

}