<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;  
use Imagine\Image\Box;  
use common\helpers\Helpers;

class UploadForm extends Model
{

    /**
     * @var UploadedFile[]
     */
    public $imageFiles;
    public $albumyear;

    public function rules()
    {
        return [
            [['albumyear', 'imageFiles'], 'required'],
            [['imageFiles'], 'safe'],
            [['albumyear'], 'integer'],
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'albumyear'  => Yii::t('modelattr', 'Album Year'),
            'imageFiles' => Yii::t('modelattr', 'Pictures'),
        ];
    }

    public function upload(int $albumyear)
    {
        if ($this->validate()) {
            $c_id = Yii::$app->session->get('c_id');
            $uploads_dir = Yii::getAlias('@uploads') . DIRECTORY_SEPARATOR . $c_id . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . $albumyear . DIRECTORY_SEPARATOR;
            Helpers::createPath($uploads_dir);
            $thumbs_dir = $uploads_dir . DIRECTORY_SEPARATOR . 'thumbs'. DIRECTORY_SEPARATOR;
            Helpers::createPath($thumbs_dir);
            foreach ($this->imageFiles as $file) {
                $file_name = $this->getUniqueFileName($file->baseName, $c_id). '.' . $file->extension;
                $file_to_save = $uploads_dir . $file_name;
                $file_thumb = $thumbs_dir . $file_name;
                $file->saveAs($file_to_save);
                //resize images
                Image::thumbnail($file_to_save, 800, 600)->resize(new Box(800,600))->save($file_to_save, ['quality' => 90]);
                Image::thumbnail($file_to_save, 100, 60)->resize(new Box(100,60))->save($file_thumb, ['quality' => 90]);
//                unlink('../files/upload/' . $this->pictureFile->baseName . '.'  . $this->pictureFile->extension);
            }
            return true;
        } else {
            return false;
        }
    }
   
    
    protected function getUniqueFileName($originalname, $club_id)
    {
        if ($club_id === null) {
            throw new UserException('Error in getUniqueFn, $club_id is null.');
        }
        return $club_id . '_' .
            Yii::$app->security->generateRandomString(5) . '_' .
            Helpers::normalise(str_replace(' ', '', $originalname));
    }

}
