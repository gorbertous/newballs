<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[JClubCourts]].
 *
 * @see JClubCourts
 */
class JClubCourtsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return JClubCourts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return JClubCourts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
