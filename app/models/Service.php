<?php

class Service extends \Eloquent {
	protected $fillable = array(
        'name',
        'status',
        'content_type',
        'content_id'
    );
}