<?php

namespace backend\models\base;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Qr
 *
 * @property int $id [int(11) unsigned]
 * @property string $hash_code [char(8)]
 * @property int $ID_Authorization [int(11) unsigned]
 * @property int $ID_Registeritem [int(11) unsigned]
 *
 * @package backend\models\base
 */
class Qr extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ID_Authorization', 'ID_Registeritem'], 'integer'],
            [['hash_code'], 'string'],
            [['hash_code'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qr';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'id',
            'ID_Authorization' => 'ID_Authorization',
            'ID_Registeritem'  => 'ID_Registeritem',
            'hash_code'        => Yii::t('modelattr', 'QR Code')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorizations()
    {
        return $this->hasOne(\backend\models\Authorizations::class, ['ID_Authorization' => 'ID_Authorization']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegisteritem()
    {
        return $this->hasOne(\backend\models\Registeritems::class, ['ID_Registeritem' => 'ID_Registeritem']);
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        return new \backend\models\QrQuery(get_called_class());
    }
}