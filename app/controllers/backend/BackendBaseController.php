<?php
namespace App\Controllers\Backend;

use BaseController;
use URL;

class BackendBaseController extends BaseController {

    /**
     * get links and scripts for jQuery UI autocomplete
     *
     * @return array
     */
    public function getJqueryUiSources()
    {
        $links   = '<link rel="stylesheet" href="'.URL::asset('//codeorigin.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css').'">';
        $scripts = '<script type="text/javascript" src="'.URL::asset('//codeorigin.jquery.com/ui/1.10.2/jquery-ui.min.js').'"></script>';
        return compact('scripts', 'links');
    }

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

}