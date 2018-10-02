<?php

namespace common\helpers;

use yii\imagine\Image;
use Imagine\Image\Box;

class Thumbnails {

    /**
     * generate thumbnails
     */
    public static function generateThumbs($filepath, $thumbspath, $uniquename) {
        
        Image::getImagine()->open($filepath)->thumbnail(new Box(250, 250))->save($thumbspath . '250x250-' . $uniquename, ['quality' => 90]);
        Image::getImagine()->open($filepath)->thumbnail(new Box(160, 160))->save($thumbspath . '160x160-' . $uniquename, ['quality' => 90]);
        Image::getImagine()->open($filepath)->thumbnail(new Box(90, 90))->save($thumbspath . '90x90-' . $uniquename, ['quality' => 90]);
        Image::getImagine()->open($filepath)->thumbnail(new Box(25, 25))->save($thumbspath . '25x25-' . $uniquename, ['quality' => 90]);
    
    }

    public static function deleteThumbs($thumbspath, $filename) {

        @unlink($thumbspath . '250x250-' . $filename);
        @unlink($thumbspath . '160x160-' . $filename);
        @unlink($thumbspath . '90x90-' . $filename);
        @unlink($thumbspath . '25x25-' . $filename);
    
    }
}