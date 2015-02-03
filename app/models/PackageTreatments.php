<?php

class PackageTreatments extends \Eloquent {
	protected $fillable = array(
        'treatment_id',
        'package_id',
        'status'
    );

    public function treatment()
    {
        return $this->hasMany('Treatment');
    }

    public function package()
    {
        return $this->belongsTo('Package');
    }

}