<?php

namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

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
            [['albumyear','imageFiles'], 'required'],
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
            'albumyear' => Yii::t('modelattr', 'Album') . ' '. Yii::t('modelattr', 'Year'),
            'imageFiles' => Yii::t('modelattr', 'Photos'),
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) { 
            foreach ($this->imageFiles as $file) {
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}
