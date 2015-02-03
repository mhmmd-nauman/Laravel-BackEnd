<?php

class Discount extends \Eloquent {
	protected $fillable = [
        'name',
        'code',
        'count',
        'price_type',
        'expire',
        'discount',
        'status'
    ];

}