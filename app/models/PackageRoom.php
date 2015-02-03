<?php

class PackageRoom extends \Eloquent {
	protected $fillable = array('package_id','room_id');
    public $timestamps  = FALSE;

    public function room()
    {
        return $this->belongsTo('Room');
    }

    public function package()
    {
        return $this->belongsTo('Package');
    }

    public function getAllPackageRooms($package_id)
    {
        $package_rooms = Package::where('packages.id', '=', $package_id)
                                ->where('rooms.status', '=', 1)
                                ->leftJoin('package_rooms', 'package_rooms.package_id', '=', 'packages.id')
                                ->leftJoin('rooms', 'rooms.id', '=', 'package_rooms.room_id')
                                ->get();

        return $package_rooms;
    }

}