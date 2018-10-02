<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Reserves]].
 *
 * @see Reserves
 */
class ReservesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Reserves[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Reserves|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
