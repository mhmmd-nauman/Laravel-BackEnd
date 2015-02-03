<?php

use \Photo;

class Hotel extends \Eloquent {
	protected $fillable = array(
        'user_id',
        'address_id',
        'name',
        'phone',
        'status',
        'reception_times',
        'description',
        'package_id',
        'offsite_booking_url',
        'offsite_booking',
        'slug',
        'summary'
    );

	public function user()
    {
        return $this->hasOne('User');
    }

	public function address()
    {
        return $this->belongsTo('Address');
    }

    public function spa()
    {
        return $this->hasOne('Spa');
    }

    public function package()
    {
        return $this->hasMany('Package');
    }

    static function getHotelPhotos($hotel_id, $sizes = array('big'))
    {
        $data = array();

        $hotel_images = Photo::where('content_type', '=', 'hotels')
            ->where('content_id', '=', $hotel_id)
            ->where('status', '=', 1)
            ->get();

        if ( $hotel_images->toArray() ) {
            foreach($hotel_images as $image) {
                foreach($sizes as $size) {
                    $data[$size][]['src'] = URL::route('frontend.image.photo', array( 'size' => $size, 'id' => $image->id ));
                }
            }
        }

        return $data;
    }

}