<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[JCourtBooked]].
 *
 * @see JCourtBooked
 */
class JCourtBookedQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return JCourtBooked[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return JCourtBooked|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
