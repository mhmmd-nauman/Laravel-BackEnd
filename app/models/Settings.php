<?php

class Settings extends \Eloquent {
	protected $fillable = ['name', 'value'];
    public $timestamps  = FALSE;

    public static function getGoogleAnalyticsCode()
    {
        $ga = self::where('settings.name', '=', 'google_analytics')->get();

        if  ( isset($ga[0]->value) ) {
            return $ga[0]->value;
        }

        return '';
    }

}