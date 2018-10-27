<?php

use common\helpers\Helpers;

//get album years
$albums = yii::getAlias('@uploads') . '/' . Yii::$app->session->get('c_id') . '/photos/';
if (!file_exists($albums)) {
    false;
} else {
    //    $dirs = \yii\helpers\FileHelper::findFiles($albums);
    //    $dirs = array_filter(glob($albums), 'is_dir');
    $dirs = \yii\helpers\FileHelper::findDirectories($albums, ['recursive' => false]);

    //show albums
    foreach ($dirs as $dir) {
        echo '<div class="row"><div class="col-md-12">';
        $album_year = substr($dir, strlen($albums));
        $header_text = '<h3 class="panel-title"><span class="fa fa-file-image-o"></span> Album Year ' . $album_year . ' </h3><br>';
        echo $header_text;


        //albums array
        $items = array();
        $dirname = yii::getAlias('@uploads') . '/' . Yii::$app->session->get('c_id') . '/photos/' . $album_year . '/';
        $imgURL = yii::getAlias('@uploadsURL') . '/' . Yii::$app->session->get('c_id') . '/photos/' . $album_year . '/';

        //    $listfiles = scandir($dirname);
        $images = glob($dirname . "*.{jpg,jpeg,gif,png}", GLOB_BRACE);

        foreach ($images as $image) {
            $image_name = substr($image, strlen($dirname));
            $imagebig = $imgURL . $image_name;
            $thumb = $imgURL . 'thumbs/' . $image_name;

            $itemsint = array();
            $itemsint = Helpers::array_push_assoc($itemsint, 'url', $imagebig);
            $itemsint = Helpers::array_push_assoc($itemsint, 'src', $thumb);
            array_push($items, $itemsint);
        }

        echo dosamigos\gallery\Gallery::widget([
            'items'           => $items,
            'options'         => [
                'id' => 'gallery_' . $album_year . '_' . Yii::$app->session->get('c_id')
            ],
            'templateOptions' => [
                'id' => 'blueimp-gallery-' . $album_year . '_' . Yii::$app->session->get('c_id')
            ],
            'clientOptions'   => [
                'container' => '#blueimp-gallery-' . $album_year . '_' . Yii::$app->session->get('c_id')
            ]
        ]);
        echo ' <br></div></div>';
    }
}
 