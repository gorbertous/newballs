<?php

namespace backend\models;

use yii\base\Model;
use backend\models\base\Qr;

/**
 * Class QrSearch
 *
 * @package backend\models
 */
class QrSearch extends Qr
{
    use TraitModelSearch;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ID_Authorization', 'ID_Registeritem'], 'integer'],
            [['hash_code'], 'safe'],
            [['hash_code'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function search($params)
    {

    }
}
