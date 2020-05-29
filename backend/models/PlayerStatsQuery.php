<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[PlayerStats]].
 *
 * @see PlayerStats
 */
class PlayerStatsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PlayerStats[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PlayerStats|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
