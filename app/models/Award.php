<?php

class Award extends \Eloquent {
    protected $table = 'hotel_awards';
	protected $fillable = [
        'name',
        'description',
        'default_photo_id',
        'status',
        'hotel_id',
        'link'
    ];

    public function hotel()
    {
        return $this->belongsTo('Hotel');
    }

}