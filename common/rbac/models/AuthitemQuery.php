<?php

namespace common\rbac\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Authitem]].
 *
 * @see Authitem
 */
class AuthitemQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     * @return Authitem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Authitem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
