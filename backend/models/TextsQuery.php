<?php

namespace backend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Texts]].
 *
 * @see Texts
 */
class TextsQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Texts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Texts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}