<?php

use \Geocoder, \Location;

class Address extends \Eloquent {
	protected $fillable = array('country_id','state_id','city_id','neighbourhood_id','route_id','street_id','postal_code_id','address','lat','lng');
	
	public function venue() 
	{
		return $this->hasOne('Venue');
	}
	
	public function country() {
		return $this->belongsTo('Location', 'country_id');
	}
	
	public function state() {
		return $this->belongsTo('Location', 'state_id');
	}
	
	public function city() {
		return $this->belongsTo('Location', 'city_id');
	}
	
	public function neighbourhood() {
		return $this->belongsTo('Location', 'neighbourhood_id');
	}
	
	public function route() {
		return $this->belongsTo('Location', 'route_id');
	}
	
	public function street() {
		return $this->belongsTo('Location', 'street_id');
	}
	
	public function postalCode() {
		return $this->belongsTo('Location', 'postal_code_id');
	}
    
    public function addressFormated() {
        $address = '';
        if ($this->street) {
            $address .= $this->street->name." ";
        }
        if ($this->route) {
            $address .= $this->route->name.", ";
        }
        if ($this->neighbourhood) {
            $address .= $this->neighbourhood->name." ";
        }
        if ($this->city) {
            $address .= $this->city->name.", ";
        }
        if ($this->postalCode) {
            $address .= $this->postalCode->name." ";
        }
        if ($this->state) {
            $address .= $this->state->name." ";
        }
        return $address;
    }
    
    /* lat, lng = Latitude and Longitude of point (in decimal degrees) */ 
    /* unit = the unit you desire for results                          */ 
    /* where: 'M' is statute miles                                     */ 
    /*       'K' is kilometers (default)                               */ 
    /*       'N' is nautical miles                                     */ 
    public function distanceTo($lat, $lng, $unit = 'M') {
        $theta = $this->lng - $lng;
        $dist = sin(deg2rad($this->lat)) * sin(deg2rad($lat)) +  cos(deg2rad($this->lat)) * cos(deg2rad($lat)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
	
	public static function make($address = null, $lat = null, $lng = null) {
		if (!$address && !$lat && !$lng) {
			throw new \RuntimeException('You need to set the address or the lat/lng.');
		}
		
		if ($lat && $lng) {
			$geocoder = Geocoder::reverse($lat, $lng);
			if (!$address) {
				$address = '';
				if ($geocoder->getStreetNumber()) $address .= $geocoder->getStreetNumber()."  ";
				if ($geocoder->getStreetName()) $address .= $geocoder->getStreetName().", ";
				if ($geocoder->getCityDistrict()) $address .= $geocoder->getCityDistrict().", ";
				if ($geocoder->getCity()) $address .= $geocoder->getCity().", ";
				if ($geocoder->getRegion()) $address .= $geocoder->getRegion().", ";
				if ($geocoder->getZipcode()) $address .= $geocoder->getZipcode().", ";
				if ($geocoder->getCountry()) $address .= $geocoder->getCountry().", ";
				$address = substr($address,0,-2);
			}
		} else {
			$geocoder = Geocoder::geocode($address);
		}
		$addressData = array(
			'country_id' => null,
			'state_id' => null,
			'city_id' => null,
			'neighbourhood_id' => null,
			'route_id' => null,
			'street_id' => null,
			'lat' => $geocoder->getLatitude(),
			'lng' => $geocoder->getLongitude(),
			'address' => $address,
		);
		
		$location_id = null;
		
		if ($geocoder->getCountry() !== null) {
			$g = Geocoder::geocode($geocoder->getCountry());
			$data = array (
				'type' => 'country',
				'name' => $geocoder->getCountry(),
				'lat'  => $g->getLatitude(),
				'lng'  => $g->getLongitude()
			);
			$country = Location::firstOrCreate($data);
			$addressData['country_id'] = $country->id;
			$location_id = $country->id;
		}
			
		if ($geocoder->getRegion() !== null) {
			$g = Geocoder::geocode($geocoder->getRegion());
			$data = array (
				'location_id' => $location_id,
				'type' => 'state',
				'name' => $geocoder->getRegion(),
				'lat'  => $g->getLatitude(),
				'lng'  => $g->getLongitude()
			);
			$state = Location::firstOrCreate($data);
			$addressData['state_id'] = $state->id;
			$location_id = $state->id;		
		}
			
		if ($geocoder->getCity() !== null) {
			$g = Geocoder::geocode($geocoder->getCity());
			$data = array (
				'location_id' => $location_id,
				'type' => 'city',
				'name' => $geocoder->getCity(),
				'lat'  => $g->getLatitude(),
				'lng'  => $g->getLongitude()
			);
			$city = Location::firstOrCreate($data);
			$addressData['city_id'] = $city->id;
			$location_id = $city->id;		
		}
		
		if ($geocoder->getCityDistrict() !== null) {
			$g = Geocoder::geocode($geocoder->getCityDistrict());
			$data = array (
				'location_id' => $location_id,
				'type' => 'neighbourhood',
				'name' => $geocoder->getCityDistrict(),
				'lat'  => $g->getLatitude(),
				'lng'  => $g->getLongitude()
			);
			$neighbourhood = Location::firstOrCreate($data);
			$addressData['neighbourhood_id'] = $neighbourhood->id;
			$location_id = $neighbourhood->id;		
		}
		if ($geocoder->getStreetName() !== null) {
			$g = Geocoder::geocode($geocoder->getStreetName());
			$data = array (
				'location_id' => $location_id,
				'type' => 'route',
				'name' => $geocoder->getStreetName(),
				'lat'  => $g->getLatitude(),
				'lng'  => $g->getLongitude()
			);
			$route = Location::firstOrCreate($data);
			$addressData['route_id'] = $route->id;
			$location_id = $route->id;
		}
		if ($geocoder->getStreetNumber() !== null) {
			$data = array (
				'location_id' => $location_id,
				'type' => 'street',
				'name' => $geocoder->getStreetNumber(),
				'lat'  => $geocoder->getLatitude(),
				'lng'  => $geocoder->getLongitude()
			);
			$street = Location::firstOrCreate($data);
			$addressData['street_id'] = $street->id;
		}
		if ($geocoder->getZipcode() !== null) {	
			$g = Geocoder::geocode($geocoder->getZipcode().", ".$geocoder->getCountry());
			$data = array (
				'location_id' => $location_id,
				'type' => 'postal_code',
				'name' => $geocoder->getZipcode(),
				'lat'  => $g->getLatitude(),
				'lng'  => $g->getLongitude()
			);
			$postal_code = Location::firstOrCreate($data);
			$addressData['postal_code_id'] = $postal_code->id;
		}
		return $addressData;
	}
}