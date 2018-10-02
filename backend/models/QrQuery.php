<?php

namespace backend\models;

use yii\db\ActiveQuery;

/**
 * Class QrQuery
 *
 * @see Qr
 * @package backend\models
 */
class QrQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
