<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[PlayDates]].
 *
 * @see PlayDates
 */
class PlayDatesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PlayDates[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PlayDates|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
