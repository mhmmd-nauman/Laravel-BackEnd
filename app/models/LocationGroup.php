<?php

class LocationGroup extends \Eloquent {
	protected $fillable = array(
        'name',
        'description',
        'slug',
        'type',
        'status'
    );

    public function getLocationHotels($location_id, $status = -1) {

        $location_id = (int)$location_id;

        $hotels = self::select(
            array(
                'hotels.id as hotel_id',
                'hotels.name as hotel_name',
                'location_groups.id as location_id',
                'location_groups.name as location_name',
                'location_groups.description as location_description',
                'location_groups.slug as location_slug',
                'location_groups.type as location_type'
            )
        )
                      ->where('location_groups.id', '=', $location_id)
                      ->leftJoin('location_group_items', 'location_group_items.location_group_id', '=', 'location_groups.id')
                      ->leftJoin('hotels', 'location_group_items.item_id', '=', 'hotels.id');

        if ( 0 === $status || 1 === $status ) {
            $hotels->where('location_groups.status', '=', $status);
        }

        $hotels = $hotels->get();

        return $hotels;
    }

}