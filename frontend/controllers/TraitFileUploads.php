<?php

/**
 * @author gorbertous
 */

namespace frontend\controllers;

use Yii;
use Imagine\Image\ImageInterface;
use yii\helpers\Url;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * Trait TraitFileUploads
 * @package frontend\controllers
 */
trait TraitFileUploads
{
    /**
     * @return array
     */
    public static function FileUploadRules()
    {
        return [[
            'controllers' => [Yii::$app->controller->id],
            'actions'     => ['ajaxupload', 'ajaxdelete'],
            'allow'       => true,
            'roles'       => ['writer']
        ]];
    }

    /**
     * this action handles the file uploading of the file input widget
     * as this action is sessionless and we need also to upload pictures to the
     * mandant_id = 0 (library), we cannot rely on mandant_id stored in the session
     *
     * this action will save all the uploaded files in the unique folder
     * and write all the "upload" action in a logfile
     * once the form is submitted, the create/update action will read this
     * logfile and update the text fields accordingly
     *
     * @param int $id
     * @return string
     */
    public function actionAjaxupload(int $id)
    {
        $club_id = $id;
        /* @var $basemodel Clubs */
        $basemodel = $this->newModel();
        $basemodel->c_id = $id;
        $basemodel->setIdFolders();
        // refer to our Table/Model and our Form Field
        $FFN = key($_FILES);
        if (empty($FFN)) {
            return json_encode(['error' => 'Error uploading file.']);
        }
        // set default values
        $resizeimagestosize = $basemodel->IMGRESIZE_L_2048;
        $resizeimagestoquality = $basemodel->IMGQUALITY_H_90;
        // get the $FFN configuration from the model
        // extract the AjaxUploadFields configuration, e.g.
        // 'ajaxfileinputPhoto' => [
        //    'optionsmultiple' => false,
        //    'resizeimagestosize' => $this->IMGRESIZE_S_512,
        //    'resizeimagestoquality' => $this->IMGQUALITY_H_90
        //    ...
        // ];
        /* @var $Storefield string */
        /* @var $Origifield string */
        /* @var $optionsmultiple bool */
        /* @var $optionsaccept string */
        /* @var $allowedfileextensions string|array */
        /* @var $maxuploadfilesize int */
        /* @var $resizeimagestosize int */
        /* @var $resizeimagestoquality int */
        extract($basemodel->getAjaxfileinputs()[$FFN]);

        $uploadlog = $basemodel->getLogFolderFn($FFN, $club_id);
        // process new file
        $ajaxfile = $_FILES[$FFN];
        $ajaxname = $ajaxfile['name'];
        $ajaxtmp_name = $ajaxfile['tmp_name'];
        //$ajaxtype = $ajaxfile['type'];
        //$ajaxerror = $ajaxfile['error'];
        //$ajaxsize = $ajaxfile['size'];
        $uniquename = $basemodel->getUniqueFn($ajaxname, $club_id);
        // check file name length
        if (strlen($ajaxname) > 50) {
            return json_encode(['error' => 'Error filename length > 50 for ' . $ajaxname . '.']);
        }
        // move the file to final location
        if (!move_uploaded_file($ajaxtmp_name, $basemodel->uniqueFolder . $uniquename)) {
            return json_encode(['error' => 'Error processing ' . $ajaxname . '.']);
        }
        $ext = strtolower(pathinfo($uniquename, PATHINFO_EXTENSION));
        // check if we must test the file extension uploaded
        if (!empty($allowedfileextensions)) {
            if (is_array($allowedfileextensions)) {
                if (!in_array($ext, $allowedfileextensions)) {
                    return json_encode(['error' => 'File type not allowed, only ' . join(', ', $allowedfileextensions) . '.']);
                }
            } else {
                if ($ext != $allowedfileextensions) {
                    return json_encode(['error' => 'File type not allowed, only ' . $allowedfileextensions . '.']);
                }
            }
        }
        // if we uploaded an image file, check if we should resize it
        if ($ext == 'jpg' || $ext == 'gif' || $ext == 'png') {
            $img = Image::getImagine()->open($basemodel->uniqueFolder . $uniquename);
            // fix image orientation for iphone photos
            if ($ext == 'jpg') {
                if (method_exists($img, 'getImageProperty')) {
                    $orientation = $img->getImageProperty('exif:Orientation');
                } else {
                    $exif = @exif_read_data($basemodel->uniqueFolder . $uniquename);
                    $orientation = isset($exif['Orientation']) ? $exif['Orientation'] : 0;
                }
                switch ($orientation) {
                    case 3:
                        $img->rotate(180);
                        break;
                    case 6:
                        $img->rotate(90);
                        break;
                    case 8:
                        $img->rotate(-90);
                        break;
                }
            }
            // do the resizing if necessary
            $img_h = $img->getSize()->getHeight();
            $img_w = $img->getSize()->getWidth();
            $img_max_hw = ($img_h > $img_w ? $img_h : $img_w);
            if ($img_max_hw > $resizeimagestosize) {
                $fn = $basemodel->uniqueFolder . $uniquename;
                $newfn = substr($fn, 0, -4) . '_new.' . $ext;
//                $origfn = substr($fn, 0, -4) . '_orig.' . $ext;
                // we have no idea which compression the original picture has
                $newimg = $img->thumbnail(new Box($resizeimagestosize, $resizeimagestosize), ImageInterface::THUMBNAIL_INSET);
                // compute target file size
                $targetfilesize = (int)(filesize($fn) / $img_max_hw * $resizeimagestosize);
                $targetquality = $resizeimagestoquality;
                while (true) {
                    // ($ext=='png' ? (int) $targetquality * 0.09 : $targetquality)
                    $newimg->save($newfn, ['quality' => $targetquality]);
                    $newfilesize = filesize($newfn);
                    if ($newfilesize < $targetfilesize || $targetquality < 33) {
                        // we have reached the targetsize or lowest acceptable quality
                        //rename($fn, $origfn);
                        rename($newfn, $fn);
                        break;
                    } else {
                        unlink($newfn);
                        $targetquality -= 20;
                    }
                }
            }
        }
        // mark this attachment for adding, write it to our log file
        $fh = fopen($uploadlog, 'a');
        flock($fh, LOCK_EX);
        if (!$optionsmultiple) {
            fwrite($fh, 'overwrite' . '/dummy/' . $club_id . PHP_EOL);
        }
        fwrite($fh, 'upload' . '/' . $uniquename . '/' . $ajaxname . PHP_EOL);
        fflush($fh);
        flock($fh, LOCK_UN);
        fclose($fh);

        return json_encode([
            // initial preview return array with one element
            // previewAsData is true
            'initialPreview'       => [$basemodel->uniqueUrl . $uniquename],
            // this function is stateless, not bound to any record, so we must pass the mandant !
            // 'initialPreview' => [$basemodel->getIconPreviewHtml($uniquename, $ajaxname, [60, 60], $club_id)],
            // initial preview config return array in array with one element
            'initialPreviewConfig' => [[
                'type'          => $basemodel->getviewertype($uniquename),
                'previewAsData' => true,
                'caption'       => $basemodel->getShortFn($ajaxname),
                'size'          => filesize($basemodel->uniqueFolder . $uniquename),
                'url'           => Url::to(['ajaxdelete?id=' . $club_id]),
                'key'           => $FFN . '/' . $uniquename
            ]],
            'append'               => $optionsmultiple,
        ]);
    }

    /**
     * this action handles the file deletion of the file input widget
     * as this action is sessionless and we need also to upload pictures to the
     * mandant_id = 0 (library), we cannot rely on mandant_id stored in the session
     *
     * this action will write all the "delete" action in a logfile
     * once the form is submitted, the create/update action will read this
     * logfile and update the text fields accordingly
     *
     * @param int $id = c_id
     * @return string
     */
    public function actionAjaxdelete(int $id)
    {
        $club_id = $id;
        /* @var $basemodel \backend\models\Documents */
        $basemodel = $this->newModel();

        list($FFN, $uniquename) = explode('/', $_POST['key'], 2);
        // mark this attachment for deletion, write it to our log file
        $uploadlog = $basemodel->getLogFolderFn($FFN, $club_id);
        $fh = fopen($uploadlog, 'a');
        flock($fh, LOCK_EX);
        fwrite($fh, 'delete' . '/' . $uniquename . '/' . $club_id . PHP_EOL);
        fflush($fh);
        flock($fh, LOCK_UN);
        fclose($fh);

        return json_encode([
            'deleted' => 'File deleted.'
        ]);
    }
}
