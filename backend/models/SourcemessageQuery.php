<?php

namespace backend\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Sourcemessage]].
 *
 * @see Sourcemessage
 */
class SourcemessageQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Sourcemessage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Sourcemessage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}