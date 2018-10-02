<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Fees]].
 *
 * @see Fees
 */
class FeesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Fees[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Fees|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
