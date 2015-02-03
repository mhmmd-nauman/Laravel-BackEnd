<?php namespace App\Controllers\Frontend;

use BaseController;
use Symfony\Component\BrowserKit\Request;
use URL;
use View;
use Settings;

class FrontendBaseController extends BaseController {

    /**
     * Transform text to URI
     *
     * @param $_        string
     * @return mixed    string
     */
    public function slugify($_) {
        $_ = mb_strtolower($_);
        $_ = str_replace(" ", "-", $_);
        $_ = str_replace("&", "och", $_);
        $_ = str_replace("å", "a", $_);
        $_ = str_replace("ä", "a", $_);
        $_ = str_replace("ö", "o", $_);
        $_ = preg_replace('/[^\w\d\-\_]/i', '', $_);

        return $_;
    }

    public function __construct()
    {
        View::share('ga', Settings::getGoogleAnalyticsCode());
    }

}