<?php namespace App\Controllers\Frontend;

use Illuminate\Support\Facades\Response;
use Image, File;
use \Photo;

class ImageController extends \BaseController {
	
	/**
	 * Output the photos
	 *
	 * @return Image Response
	 */
	
	public function photo($s = 'small', $id) {
		$sizes = array (
			'tiny'           => array(64, 64),
            'tiny_cropped'   => array(64, 64),
			'small'          => array(256, 256),
            'small_booking'  => array(250, 160),
            'large'          => array(640, 640),
            'large_cropped'  => array(650, 480),
            'slider'         => array(725, 543),
			'medium'         => array(800, 400),
            'big'            => array(1024, 1024),
            'header_bg'      => array(1400, 1400, 3),
		);
        $photo = Photo::find($id);
        if ($photo) {
            $file = $photo->file;
            $size = current($sizes);
            if ( array_key_exists( $s, $sizes ) ) {
                $size = $sizes[$s];
            }
            if ( File::exists( $file ) ) {

                switch($s) {
                    case 'tiny_cropped':
                        $img = Image::cache(function($image) use ($size, $file) {
                            $image_params = getimagesize($file);
                            $width = $image_params[0];
                            $height = $image_params[1];

                            if ( $width < $height ) {
                                return $image->make($file)->widen($size[0])->crop($size[0], $size[1]);
                            } else {
                                return $image->make($file)->heighten($size[0])->crop($size[0], $size[1]);
                            }

                        }, 10, true);
                    break;

                    case 'large_cropped':
                        $img = Image::cache(function($image) use ($size, $file) {
                            $image_params = getimagesize($file);
                            $width = $image_params[0];
                            $height = $image_params[1];

                            if ( $width < $height ) {
                                return $image->make($file)->widen($size[0])->crop($size[0], $size[1]);
                            } else {
                                return $image->make($file)->heighten($size[0])->crop($size[0], $size[1]);
                            }

                        }, 10, true);
                        break;

                    default:
                        $img = Image::cache(function($image) use ($size, $file) {
                            if ( isset($size[2]) ) { // blur image
                                return $image->make($file)->resize($size[0], $size[1], true, true)->blur($size[2]);
                            } else {
                                return $image->make($file)->resize($size[0], $size[1], true, false);
                            }
                        }, 10, true);
                }

                return $img->response('jpg', 95);
            }
        }
        return 'Error loading image';
	}
}