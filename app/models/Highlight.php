<?php

class Highlight extends \Eloquent {
    protected $table = 'hotel_highlights';
	protected $fillable = [
        'name',
        'description',
        'default_photo_id',
        'status',
        'hotel_id',
        'quote_text',
        'quote_author',
        'default_quote_photo_id'
    ];

    public function hotel()
    {
        return $this->belongsTo('Hotel');
    }

}