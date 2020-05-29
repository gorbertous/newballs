<?php

/**
 * @author gorbertous
 */

namespace backend\models\base;

use Yii;
use yii\base\UserException;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\ImageInterface;
use yii\helpers\Url;
use common\helpers\Helpers;

/**
 * Description of TraitFileUploads
 *
 * @gorbertous
 */
trait TraitFileUploads
{

    /**
     * define standard file type lists which will be tested after upload
     */
    public $FI_IMAGES = ['gif', 'jpg', 'png'];
    public $FI_DOCUMENTS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
    public $FI_IMAGES_DOCUMETS = ['gif', 'jpg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

    /**
     * define standard sizes for resizing images after upload
     * LARGE = +- 20 cm at 300 dpi
     * @var int $IMGRESIZE_L_2048
     */
    public $IMGRESIZE_L_2048 = 2048;

    /**
     * define standard sizes for resizing images after upload
     * MEDIUM = +- 10 cm at 300 dpi
     * @var int $IMGRESIZE_M_1024
     */
    public $IMGRESIZE_M_1024 = 1024;

    /**
     * define standard sizes for resizing images after upload
     * SMALL = +- 5 cm at 300 dpi
     * @var int $IMGRESIZE_S_512
     */
    public $IMGRESIZE_S_512 = 512;

    /**
     * define standard qualities for resizing images after upload
     * HIGH = 90%
     * @var int $IMGQUALITY_H_90
     */
    public $IMGQUALITY_H_90 = 90;

    /**
     * define standard qualities for resizing images after upload
     * MEDIUM = 60%
     * @var int $IMGQUALITY_M_60
     */
    public $IMGQUALITY_M_60 = 60;

    /**
     * define standard qualities for resizing images after upload
     * LOW = 40%
     * @var int $IMGQUALITY_L_40
     */
    public $IMGQUALITY_L_40 = 40;

    /**
     *
     * @var string $table = lowercase of table name used to create the folders
     *                      to store the uploaded files
     */
    public $table;

    /**
     *
     * @var string $thumbs = lowercase "thumbs" used to create the subfolder
     *                      to store the thumbnails of the uploaded files
     */
    protected $thumbs;

    /**
     *
     * @var string $uploadsFolder = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR
     */
    public $uploadsFolder;

    /**
     *
     * @var string $uploadsFolder = Yii::getAlias('@uploadsURL') . '/'
     */
    public $uploadsUrl;

    /**
     * uploadsFolder . c_id . table
     *
     * @var string $uniqueFolder = the folder where the uploaded files are stored
     */
    public $uniqueFolder;

    /**
     * uploadsUrl . c_id . table;
     *
     * @var string $uniqueUrl = the url where the uploaded files are stored
     */
    public $uniqueUrl;

    /**
     * uploadsFolder . c_id . table . thumbs;
     *
     * @var string $thumbsFolder = the folder where the thumb files are stored
     */
    public $thumbsFolder;

    /**
     * uploadsUrl . c_id . table . thumbs;
     *
     * @var string $thumbsUrl = the url where the thumb files are stored
     */
    protected $thumbsUrl;

    /**
     * set the global paths, not related to any c_id
     * uploadsFolder, uploadsUrl, table, thumbs
     */
    public function init()
    {
        /** @noinspection PhpUndefinedClassInspection */
        parent::init();

        $this->uploadsFolder = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR;
        $this->uploadsUrl = Yii::getAlias('@uploadsURL') . '/';
        $this->table = strtolower($this->tableName()) . DIRECTORY_SEPARATOR;
        $this->thumbs = 'thumbs' . DIRECTORY_SEPARATOR;
    }

    /**
     * set the paths related to the c_id, tablename and thumbs
     * create folders if they do not exist
     * uniqueFolder, uniqueUrl, thumbsFolder, thumbsUrl
     * !! needs $this->c_id to be set
     *
     * @throws yii\base\UserException
     */
    public function setIdFolders()
    {
        if ($this->c_id === null) {
            if (Yii::$app->controller->id == 'clubs') {
                $this->c_id = \backend\models\Clubs::find()->select('c_id')->max('c_id') + 1;
            } else {
                throw new UserException('Error in setFolders, $this->c_id is null.');
            }
        }
        $this->uniqueFolder = $this->uploadsFolder . $this->c_id . DIRECTORY_SEPARATOR . $this->table;
        Helpers::createPath($this->uniqueFolder);
        $this->uniqueUrl = $this->uploadsUrl . $this->c_id . '/' . $this->table;
        $this->thumbsFolder = $this->uniqueFolder . $this->thumbs;
        Helpers::createPath($this->thumbsFolder);
        $this->thumbsUrl = $this->uniqueUrl . $this->thumbs;
    }

    /**
     * return unique filename for the log file in which we will log
     * ajax file uploads and deletions
     *
     * @param string $FFN = name of the ajaxfileinput as defined in the model
     * @param int $club_id = c_id
     * @return string
     * @throws \yii\base\UserException
     */
    public function getLogFolderFn(string $FFN, int $club_id)
    {
        if ($club_id === null) {
            throw new UserException('Error in getUniqueFn, $club_id is null.');
        }
        return $this->uploadsFolder .
            $club_id . '_' . Yii::$app->session->getId() . '_' . $FFN . '.log';
    }

    /**
     * return unique filename based on our original file name
     * used to store the file in the folder
     *
     * @param string $originalname = original full file name
     * @param int $club_id = c_id
     * @return string
     * @throws \yii\base\UserException
     * @throws \yii\base\Exception
     */
    public function getUniqueFn($originalname, $club_id)
    {
        if ($club_id === null) {
            throw new UserException('Error in getUniqueFn, $club_id is null.');
        }
        return $club_id . '_' .
            Yii::$app->security->generateRandomString(10) . '_' .
            Helpers::normalise(str_replace(' ', '', $originalname));
    }

    /**
     * return a short version based on our original file name
     * used to show as a caption in the fileinput widgets' preview
     *
     * @param string $filename
     * @return string
     */
    public function getShortFn($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $fn = pathinfo($filename, PATHINFO_BASENAME);
        return strlen($fn) > 23 ? mb_substr($fn, 0, 20) . '...' . $ext : $filename;
    }

    /**
     * return path with name of a specified thumb nail, create it if does not exist
     * return image in box with the exact size
     * picture is not distorted and placed in the center of a white box
     * width = maxwidth and height = maxheight (as defined in size)
     *
     * @param string $uniquename = the unique file name as the file is stored
     * @param int , array $size = int (x=y) or array ([x,y]) = size of thumbnail
     * @param bool $exact
     * @param bool $cropped
     * @return string
     * @throws Yii\base\UserException
     */
    public function getFixedImgPath($uniquename, $size, bool $exact = true, bool $cropped = false)
    {
        $ext = strtolower(pathinfo($uniquename, PATHINFO_EXTENSION));

        if ($ext == 'jpg' || $ext == 'gif' || $ext == 'png') {
            $this->setIdFolders();
            return $this->thumbsFolder . $this->AjaxCheckMakeThumb($uniquename, $size, $exact, $cropped);
        } else {
            return '';
        }
    }

    /**
     * return web url of a specified thumb nail, create it if does not exist
     * return image in box with the exact size
     * picture is not distorted and placed in the center of a white box
     * width = maxwidth and height = maxheight (as defined in size)
     *
     * @param string $uniquename = the unique file name as the file is stored
     * @param int , array $size = int (x=y) or array ([x,y]) = size of thumbnail
     * @param boolean $exact = true -> return unstretched image in box with the exact size
     *                         false -> return unstretched image with maximum horz/vert size
     * @param boolean $cropped = true -> return image cropped to size
     *                           false -> return image inset to size
     * @return string
     * @throws Yii\base\UserException
     */
    public function getThumbnailPath($uniquename, $size, bool $exact = true, bool $cropped = false)
    {
        $ext = strtolower(pathinfo($uniquename, PATHINFO_EXTENSION));
        if ($ext == 'jpg' || $ext == 'gif' || $ext == 'png') {
            $this->setIdFolders();
            return $this->thumbsFolder . $this->AjaxCheckMakeThumb($uniquename, $size, $exact, $cropped);
        } else {
            return '';
        }
    }

    /**
     * return web url of a specified thumbnail, create it if does not exist
     * thumbnail can be of exact size or resized
     * thumbnail can be cropped or inset
     * picture is not distorted and placed in the center of a white box
     * width = maxwidth and height = maxheight (as defined in size)
     *
     * @param string $uniquename = the unique file name as the file is stored
     * @param int , array $size = int (x=y) or array ([x,y]) = size of thumbnail
     * @param boolean $exact = true -> return unstretched image in box with the exact size
     *                         false -> return unstretched image with maximum horz/vert size
     * @param boolean $cropped = true -> return image cropped to size
     *                           false -> return image inset to size
     * @return string
     * @throws Yii\base\UserException
     */
    public function getThumbnailUrl($uniquename, $size, bool $exact = true, bool $cropped = false)
    {
        $ext = strtolower(pathinfo($uniquename, PATHINFO_EXTENSION));
        if ($ext == 'jpg' || $ext == 'gif' || $ext == 'png') {
            $this->setIdFolders();
            return $this->thumbsUrl . $this->AjaxCheckMakeThumb($uniquename, $size, $exact, $cropped);
        } else {
            return '';
        }
    }

    /**
     * delete the original file and all corresponding thumbs
     * !! needs $this->c_id to be set
     *
     * @param string $uniquename = the unique file name as the file is stored
     * @throws Yii\base\UserException
     */
    public function deleteFileAndThumbs($uniquename)
    {
        $this->setIdFolders();
        // delete file
        @unlink($this->uniqueFolder . $uniquename);
        $files = glob($this->thumbsFolder . '*x*-' . $uniquename);
        array_map('unlink', $files);
    }

    /**
     * copies all the files in Storefield from c_id to c_id
     *
     * @param string $FFN = name of the ajaxfileinput as defined in the model
     * @param int $fromID = source c_id
     * @param int $toID = destination c_id
     * @return string newStorefield
     * @throws \yii\base\Exception
     * @throws \yii\base\UserException
     */
    public function copyFiles($FFN, $fromID, $toID)
    {

        // get the $FFN configuration from the model
        // extract the AjaxUploadFields configuration, e.g.
        // 'ajaxfileinputPhoto' => [
        //    'Storefield' => 'Photo',
        //    ...
        // ];
        //var_dump($this->getAjaxfileinputs()[$FFN]);
        /* @var $Storefield string */
        /* @var $Origifield string */
        /* @var $optionsmultiple bool */
        /* @var $optionsaccept string */
        /* @var $allowedfileextensions array */
        /* @var $maxuploadfilesize int */
        /* @var $resizeimagestosize int */
        /* @var $resizeimagestoquality int */
        extract($this->getAjaxfileinputs()[$FFN]);

        // set all the paths depeding on the c_id 
        $this->setIdFolders();

        $StoreFn = preg_split('#//#', $this[$Storefield], null, PREG_SPLIT_NO_EMPTY);
        $OrigiFn = preg_split('#//#', $this[$Origifield], null, PREG_SPLIT_NO_EMPTY);

        $fromuniquefolder = $this->uploadsFolder . $fromID . DIRECTORY_SEPARATOR . $this->table;
        $touniquefolder = $this->uploadsFolder . $toID . DIRECTORY_SEPARATOR . $this->table;
        Helpers::createPath($touniquefolder);
        $newStoreFn = [];
        for ($i = 0; $i < min(count($StoreFn), count($OrigiFn)); $i++) {
            $newStoreFn[$i] = $this->getUniqueFn($OrigiFn[$i], $toID);
            copy($fromuniquefolder . $StoreFn[$i], $touniquefolder . $newStoreFn[$i]);
        }
        return join('//', $newStoreFn);
    }

    /**
     * return list of IMG tags with the images at a specified size
     * create thumbnail size image it if does not exist
     * !! needs $this->c_id to be set
     *
     * @param string $FFN = name of the ajaxfileinput as defined in the model
     * @param int , array $size = int (x=y) or array ([x,y]) = size of thumbnail
     * @param bool $exact
     * @param bool $cropped
     * @return string
     * @throws Yii\base\UserException
     */
    public function getIconPreviewAsHtml($FFN, $size, bool $exact = true, bool $cropped = false)
    {

        // get the $FFN configuration from the model
        // extract the AjaxUploadFields configuration, e.g.
        // 'ajaxfileinputPhoto' => [
        //    'Storefield' => 'Photo',
        //    'Origifield' => 'Orig_Photo',
        //    ...
        // ];
        /* @var $Storefield string */
        /* @var $Origifield string */
        /* @var $optionsmultiple bool */
        /* @var $optionsaccept string */
        /* @var $allowedfileextensions array */
        /* @var $maxuploadfilesize int */
        /* @var $resizeimagestosize int */
        /* @var $resizeimagestoquality int */
        extract($this->getAjaxfileinputs()[$FFN]);
        // set all the paths depeding on the c_id 
        $this->setIdFolders();
        $StoreFn = preg_split('#//#', $this[$Storefield], null, PREG_SPLIT_NO_EMPTY);
        $OrigiFn = preg_split('#//#', $this[$Origifield], null, PREG_SPLIT_NO_EMPTY);
        $aimgtags = [];
        for ($i = 0; $i < min(count($StoreFn), count($OrigiFn)); $i++) {
            array_push($aimgtags, $this->getAIMGtag($StoreFn[$i], $OrigiFn[$i], $size, false, $exact, $cropped));
        }
        return join('&nbsp;', $aimgtags);
    }

    /**
     * returns html tag with the image/icon for a particular $uniquename
     * !! this function is protected because it
     * !! relies on the paths already set with the setFolder function
     *
     * @param string $uniquename = the unique file name as the file is stored
     * @param string $originame = the original file name as the file was uploaded
     * @param int , array $size = int (x=y) or array ([x,y]) = size of thumbnail
     * @param bool|int $withdownloadattr = if true, provide a download attribute in the link with the original name
     * @param bool $exact
     * @param bool $cropped
     * @return string
     */
    protected function getAIMGtag($uniquename, $originame, $size, bool $withdownloadattr = false, bool $exact = true, bool $cropped = false)
    {

        if (!isset($originame)) {
            $originame = $uniquename;
        }
        if (!file_exists($this->uniqueFolder . $uniquename)) {
            // the source file is missing, show a question mark
            $aimgtag = '<span class="file-other-icon"><i class="fas fa-eye-slash text-danger"></i></span>';
            if (YII_ENV_DEV) {
                $aimgtag .= $this->uniqueFolder . $uniquename;
            }
        } else {
            $ext = strtolower(pathinfo($uniquename, PATHINFO_EXTENSION));
            $aimgtag = '';
            if ($withdownloadattr) {
                $aimgtag .= '<a data-pjax="0" target="_blank" href="' . $this->uniqueUrl . $uniquename . '" download="' . $originame . '">';
            } else {
                $aimgtag .= '<a data-pjax="0" target="_blank" href="' . $this->uniqueUrl . $uniquename . '">';
            }
            if ($ext == 'jpg' || $ext == 'gif' || $ext == 'png') {
                $thumbname = $this->AjaxCheckMakeThumb($uniquename, $size, $exact, $cropped);
                $aimgtag .= '<img src="' .
                    $this->thumbsUrl . $thumbname . '" ' .
                    'title="' . $originame . '" ' .
                    'alt="' . $originame . '" data-display-in="modal">';
            } else {
                $aimgtag .= $this->previewFileIcon($ext, $size);
            }
            $aimgtag .= '</a>';
        }
        return $aimgtag;
    }

    /**
     * return a unstretched thumbnail:
     * - exact size to fit exaclty maxsize (only possible if not cropped)
     * - or resized to fit into maxsize
     * - cropped to fill the box completely, crop the parts not fitting in
     * - or inset to fit inside the box, white borders are shown around
     * checks if a thumbnail exists, if not, the thumbnail is created
     * defaults to NOT exact and NOT cropped
     *
     * !! this function is protected because it
     * !! relies on the paths already set with the setFolder function
     *
     * @param string $uniquename = the unique file name as the file is stored
     * @param int , array $size = int (x=y) or array ([x,y]) = size of thumbnail
     * @param boolean $exact
     * @param boolean $cropped
     * @return string = the name of the thumbnail file generated, without path
     */
    protected function AjaxCheckMakeThumb($uniquename, $maxsize, $exact = false, $cropped = false)
    {

        if (is_array($maxsize)) {
            list($x, $y) = $maxsize;
        } else {
            $x = $y = $maxsize;
        }
        // E = exact size, R = resized with same proportions
        // C = cropped, I = inset
        $thumbname = $x . 'x' . $y . '-' . ($exact ? 'E' : 'R') . ($cropped ? 'C' : 'I') . '-' . $uniquename;
        // the picture is not streched:
        // if $cropped = false -> resized to fit
        // if $cropped = true -> cropped to fit
        if (!file_exists($this->thumbsFolder . $thumbname) &&
            file_exists($this->uniqueFolder . $uniquename)) {

            // generate the thumbnail
            $oldimage = Image::getImagine()->open($this->uniqueFolder . $uniquename);
            if ($cropped) {
                // - cropped to fill the box completely, crop the parts not fitting in
                // - will always be exact size
                $newimage = $oldimage->thumbnail(new Box($x, $y), ImageInterface::THUMBNAIL_OUTBOUND);
                $newimage->save($this->thumbsFolder . $thumbname, ['quality' => 90]);
            } else {
                if ($exact) {
                    // - inset to fit inside the box, white borders are shown around
                    // - exact size as specified
                    $resizedimage = $oldimage->thumbnail(new Box($x, $y), ImageInterface::THUMBNAIL_INSET);
                    $resizedimage_x = $resizedimage->getSize()->getWidth();
                    $resizedimage_y = $resizedimage->getSize()->getHeight();
                    $newimage = Image::getImagine()->create(new Box($x, $y));
                    $startX = $startY = 0;
                    if ($resizedimage_x < $x) {
                        $startX = ($x - $resizedimage_x) / 2;
                    }
                    if ($resizedimage_y < $y) {
                        $startY = ($y - $resizedimage_y) / 2;
                    }
                    $newimage->paste($resizedimage, new Point($startX, $startY));
                    $newimage->save($this->thumbsFolder . $thumbname, ['quality' => 90]);
                } else {
                    // - inset to fit inside the box, white borders are shown around
                    // - maximum size as specified
                    $newimage = $oldimage->thumbnail(new Box($x, $y), ImageInterface::THUMBNAIL_INSET);
                    $newimage->save($this->thumbsFolder . $thumbname, ['quality' => 90]);
                }
            }
        }
        return $thumbname;
    }

    /**
     * returns the file viewer to be used for zooming depending on the file extension
     *
     * @param string $filename
     * @return string
     */
    public function getviewertype($filename)
    {

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (preg_match("(gif|png|jpe?g)", $ext)) {
            return 'image';
        }
        if (preg_match("(pdf)", $ext)) {
            return 'pdf';
        }
        if (preg_match("(htm|html|js|css|json)", $ext)) {
            return 'html';
        }
        if (preg_match("(txt|md|csv|nfo|php|ini)", $ext)) {
            return 'text';
        }
        if (preg_match("(mp4|webm)", $ext)) {
            return 'video';
        }
        if (preg_match("(ogg|mp3|wav)", $ext)) {
            return 'audio';
        }
        if (preg_match("(swf)", $ext)) {
            return 'flash';
        }
        if (preg_match("(gif|png|jpe?g)", $ext)) {
            return 'image';
        }
        return 'other';
    }

    /**
     * return the complete configuration for the initil setup of a fileinput
     * widget, based on the settings defined in the model (getAjaxfileinputs)
     *
     * @param string $FFN = name of the ajaxfileinput as defined in the model
     * @return array
     * @throws \yii\base\UserException
     */
    public function getwidgetconfig($FFN)
    {

        // get the $FFN configuration from the model
        // extract the AjaxUploadFields configuration, e.g.
        // 'ajaxfileinputPhoto' => [
        //    'Storefield' => 'Photo',
        //    'Origifield' => 'Orig_Photo',
        //    'optionsmultiple' => false,
        //    'optionsaccept' => the mime types to accept e.q. 'image/*'
        //    'theme' => the template to use, defaults to 'explorer'
        //    'maxuploadfilesize' => 1024 * 1024 * 10,
        //    'resizeimagestosize' => $this->IMGRESIZE_S_512,
        //    'resizeimagestoquality' => $this->IMGQUALITY_H_90
        // ];
        // first set default values
        $theme = 'explorer';
        $optionsaccept = '';
        /* @var $Storefield string */
        /* @var $Origifield string */
        /* @var $optionsmultiple bool */
        /* @var $optionsaccept string */
        /* @var $allowedfileextensions array */
        /* @var $maxuploadfilesize int */
        /* @var $resizeimagestosize int */
        /* @var $resizeimagestoquality int */
        extract($this->getAjaxfileinputs()[$FFN]);
        // set all the paths depeding on the c_id 
        $this->setIdFolders();
        $StoreFn = preg_split('#//#', $this[$Storefield], null, PREG_SPLIT_NO_EMPTY);
        $OrigiFn = preg_split('#//#', $this[$Origifield], null, PREG_SPLIT_NO_EMPTY);

        $initialPreview = [];
        $initialPreviewConfig = [];
        for ($i = 0; $i < min(count($StoreFn), count($OrigiFn)); $i++) {
            array_push($initialPreview, $this->uniqueUrl . $StoreFn[$i]);
            array_push($initialPreviewConfig, [
                'type'          => $this->getviewertype($StoreFn[$i]),
                'previewAsData' => true,
                'caption'       => $this->getShortFn($OrigiFn[$i]),
                'size'          => (file_exists($this->uniqueFolder . $StoreFn[$i]) ? filesize($this->uniqueFolder . $StoreFn[$i]) : 0),
                'url'           => Url::to(['ajaxdelete?id=' . $this->c_id]),
                'key'           => $FFN . '/' . $StoreFn[$i]
            ]);
        }
        // empty our log file
        $uploadlog = $this->getLogFolderFn($FFN, $this->c_id);
        @unlink($uploadlog);
        return [
            'name'          => $FFN,
            // multiple file: $overwritePreview = false + options/multiple = true
            // single file: $overwritePreview = true + options/multiple = false
            'options'       => ['accept' => $optionsaccept, 'multiple' => $optionsmultiple],
            'pluginOptions' => [
                'theme'                   => $theme,
                'initialPreviewAsData'    => true,
                'overwriteInitial'        => !$optionsmultiple,
                'showRemove'              => false,
                'showUpload'              => false,
                'uploadAsync'             => true,
                'fileActionSettings'      => [
                    'showZoom'   => true,
                    'showDrag'   => false,
                    'showUpload' => false,
                    'showRemove' => true],
                'initialPreview'          => $initialPreview,
                'initialPreviewConfig'    => $initialPreviewConfig,
                'maxFileSize'             => $maxuploadfilesize,
                'uploadUrl'               => Url::to(['ajaxupload?id=' . $this->c_id]),
                'preferIconicPreview'     => true,
                'previewFileIcon'         => self::previewFileIcon('?', 20),
//                'allowedPreviewTypes' => null,
                // configure your icon file extensions
                'previewFileIconSettings' => self::allpreviewFileIcon(20),
                'previewSettings'         => [
                    'image' => ['width' => "100px", 'height' => "auto"]
                ],
                'purifyHtml'              => true,
//              'uploadExtraData' => [ 'imgresize' => 1024, 'imgquality' => 90],
            ],
            'pluginEvents'  => [
                // trigger upload method immediately after files are selected
                'filebatchselected' => 'function(event) { $(event.target).fileinput("upload"); }',
            ]
        ];
    }

    /**
     * returns all icon collection for different file extensions
     *
     * @param int , array $size = int (x=y) or array ([x,y]) = size of thumbnail
     * @return array
     */
    static function allpreviewFileIcon($size = null)
    {
        $fa = self::FileIconMagnification($size);

        return [
            'pdf'  => '<i class="fa fa-file-pdf-o text-danger ' . $fa . '"></i>',
            'doc'  => '<i class="fa fa-file-word-o text-primary' . $fa . '"></i>',
            'docx' => '<i class="fa fa-file-word-o text-primary' . $fa . '"></i>',
            'xls'  => '<i class="fa fa-file-excel-o text-success' . $fa . '"></i>',
            'xlsx' => '<i class="fa fa-file-excel-o text-success' . $fa . '"></i>',
            'ppt'  => '<i class="fa fa-file-powerpoint-o text-danger' . $fa . '"></i>',
            'pptx' => '<i class="fa fa-file-powerpoint-o text-danger' . $fa . '"></i>',
            'zip'  => '<i class="fa fa-file-archive-o text-muted' . $fa . '"></i>',
            'rar'  => '<i class="fa fa-file-archive-o text-muted' . $fa . '"></i>',
            'tar'  => '<i class="fa fa-file-archive-o text-muted' . $fa . '"></i>',
            'gzip' => '<i class="fa fa-file-archive-o text-muted' . $fa . '"></i>',
            'gz'   => '<i class="fa fa-file-archive-o text-muted' . $fa . '"></i>',
            '7z'   => '<i class="fa fa-file-archive-o text-muted' . $fa . '"></i>',
            'php'  => '<i class="fa fa-file-code-o text-info' . $fa . '"></i>',
            'js'   => '<i class="fa fa-file-code-o text-info' . $fa . '"></i>',
            'css'  => '<i class="fa fa-file-code-o text-info' . $fa . '"></i>',
            'htm'  => '<i class="fa fa-file-code-o text-info' . $fa . '"></i>',
            'html' => '<i class="fa fa-file-code-o text-info' . $fa . '"></i>',
            'json' => '<i class="fa fa-file-code-o text-info' . $fa . '"></i>',
            'txt'  => '<i class="fa fa-file-text-o text-info' . $fa . '"></i>',
            'ini'  => '<i class="fa fa-file-text-o text-info' . $fa . '"></i>',
            'md'   => '<i class="fa fa-file-text-o text-info' . $fa . '"></i>',
            'avi'  => '<i class="fa fa-file-movie-o text-warning' . $fa . '"></i>',
            'mpg'  => '<i class="fa fa-file-movie-o text-warning' . $fa . '"></i>',
            'mkv'  => '<i class="fa fa-file-movie-o text-warning' . $fa . '"></i>',
            'mov'  => '<i class="fa fa-file-movie-o text-warning' . $fa . '"></i>',
            'mp4'  => '<i class="fa fa-file-movie-o text-warning' . $fa . '"></i>',
            'wmv'  => '<i class="fa fa-file-movie-o text-warning' . $fa . '"></i>',
            'mp3'  => '<i class="fa fa-file-audio-o text-warning' . $fa . '"></i>',
            'wav'  => '<i class="fa fa-file-audio-o text-warning' . $fa . '"></i>'
        ];
    }

    /**
     * returns the icon to be used for different file extensions
     *
     * @param string $ext = not set or null returns all the collection
     *                      set returns the icon
     * @param int , array $size = int (x=y) or array ([x,y]) = size of thumbnail
     * @return string
     */
    public function previewFileIcon($ext, $size = null)
    {

        $fa = self::FileIconMagnification($size);
        $all = self::allpreviewFileIcon($size);
        $icon = '<i class="fa fa-file' . $fa . '"></i>';
        if (array_key_exists($ext, $all)) {
            $icon = $all[$ext];
        }
        return $icon;
    }

    /**
     * returns the magnification factor for font awesome icons
     * depending on a pixel size
     *
     * @param int , array $size = int (x=y) or array ([x,y]) = size of thumbnail
     * @return mixed
     */
    static function FileIconMagnification($size = null)
    {

        $fa = '';
        if ($size !== null) {
            if (is_array($size)) {
                list($x, $y) = $size;
            } else {
                $x = $y = $size;
            }
            $magnification = (int)max($x, $y) / 20;
            if ($magnification > 5)
                $magnification = 5;
            if ($magnification > 1) {
                $fa = ' fa-' . $magnification . 'x';
            }
        }
        return $fa;
    }

    /**
     * after form submit process the log file and update the Storefield and
     * Origifield of the model,
     * based on the settings defined in the model (getAjaxfileinputs)
     *
     * @param string $FFN = name of the ajaxfileinput as defined in the model
     * @throws \yii\base\UserException
     */
    public function AjaxUpdateModel($FFN)
    {

        // get the $FFN configuration from the model
        // extract the AjaxUploadFields configuration, e.g.
        // 'ajaxfileinputPhoto' => [
        //    'Storefield' => 'Photo',
        //    'Origifield' => 'Orig_Photo',
        //    ...
        // ];
        /* @var $Storefield string */
        /* @var $Origifield string */
        /* @var $optionsmultiple bool */
        /* @var $optionsaccept string */
        /* @var $allowedfileextensions array */
        /* @var $maxuploadfilesize int */
        /* @var $resizeimagestosize int */
        /* @var $resizeimagestoquality int */
        extract($this->getAjaxfileinputs()[$FFN]);
        // set all the paths depeding on the c_id 
        $this->setIdFolders();
        $StoreFn = preg_split('#//#', $this[$Storefield], null, PREG_SPLIT_NO_EMPTY);
        $OrigiFn = preg_split('#//#', $this[$Origifield], null, PREG_SPLIT_NO_EMPTY);

        // read our log file
        $uploadlog = $this->getLogFolderFn($FFN, $this->c_id);
        if (file_exists($uploadlog)) {
            $fh = fopen($uploadlog, 'r');
            flock($fh, LOCK_EX);
            $lines = preg_split('#' . PHP_EOL . '#', fread($fh, filesize($uploadlog)), null, PREG_SPLIT_NO_EMPTY);
            flock($fh, LOCK_UN);
            fclose($fh);

            foreach ($lines as $line) {
                list($token, $uniquename, $originalname) = explode("/", $line, 3);

                switch ($token) {
                    case 'upload':
                        $originalname = preg_replace("/[^.A-Za-z0-9]/", "", $originalname);
                        array_push($StoreFn, $uniquename);
                        array_push($OrigiFn, $originalname);
                        break;
                    case 'delete':
                        $i = array_search($uniquename, $StoreFn);
                        if ($i !== false) {
                            $StoreFn[$i] = null;
                            $OrigiFn[$i] = null;
                        }
                        $this->deleteFileAndThumbs($uniquename);
                        break;
                    case 'overwrite':
                        // delete all the files and their thumbnails
                        foreach ($StoreFn as $s) {
                            $this->deleteFileAndThumbs($s);
                        }
                        $StoreFn = [];
                        $OrigiFn = [];
                        break;
                }
            }

            $this[$Storefield] = join('//', array_filter($StoreFn));
            $this[$Origifield] = join('//', array_filter($OrigiFn));
            // empty our log file
            @unlink($uploadlog);
        }
        if (count($StoreFn) == 0 || count($OrigiFn) == 0) {
            // force clean up if one or the other array is empty
            $this[$Storefield] = '';
            $this[$Origifield] = '';
        }
    }

    /**
     * delete uploaded files and thumbnails for a ajaxupload field
     * based on the settings defined in the model (getAjaxfileinputs)
     *
     * @param string $FFN = name of the ajaxfileinput as defined in the model
     * @throws Yii\base\UserException
     */
    public function DeleteStoreFiles($FFN)
    {

        // get the $FFN configuration from the model
        // extract the AjaxUploadFields configuration, e.g.
        // 'ajaxfileinputPhoto' => [
        //    'Storefield' => 'Photo',
        //    ...
        // ];
        /* @var $Storefield string */
        /* @var $Origifield string */
        /* @var $optionsmultiple bool */
        /* @var $optionsaccept string */
        /* @var $allowedfileextensions array */
        /* @var $maxuploadfilesize int */
        /* @var $resizeimagestosize int */
        /* @var $resizeimagestoquality int */
        extract($this->getAjaxfileinputs()[$FFN]);
        // set all the paths depeding on the c_id 
        $this->setIdFolders();
        $StoreFn = preg_split('#//#', $this[$Storefield], null, PREG_SPLIT_NO_EMPTY);

        foreach ($StoreFn as $s) {
            $this->deleteFileAndThumbs($s);
        }
    }

}
