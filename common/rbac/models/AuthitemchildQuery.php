<?php

namespace common\rbac\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Authitemchild]].
 *
 * @see Authitemchild
 */
class AuthitemchildQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Authitemchild[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Authitemchild|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
