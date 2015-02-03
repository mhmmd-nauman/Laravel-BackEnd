<?php

class SlugsSeederTableSeeder extends Seeder {

	public function run()
	{

        $slug = function($_) {
            $_ = mb_strtolower($_);
            $_ = str_replace(" ", "-", $_);
            $_ = str_replace("&", "och", $_);
            $_ = str_replace("å", "a", $_);
            $_ = str_replace("ä", "a", $_);
            $_ = str_replace("ö", "o", $_);
            $_ = preg_replace('/[^\w\d\-\_]/i', '', $_);

            return $_;
        };

        $packages = Package::all();
        $hotels = Hotel::all();

        foreach($packages as $package) {
            $package->slug = $slug( $package->name );
            $package->save();
        }

        foreach($hotels as $hotel) {
            $hotel->slug = $slug( $hotel->name );
            $hotel->save();
        }

	}

}