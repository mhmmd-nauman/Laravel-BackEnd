<?php

class Treatment extends \Eloquent {
	protected $fillable = array(
        'name',
        'description',
        'persons',
        'price',
        'duration',
        'default_photo',
        'status',
        'spa_id'
    );

    public function default_photo()
    {
        return $this->belongsTo('Photo');
    }

    public function spa()
    {
        return $this->belongsTo('Spas');
    }

}