<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[JClubUser]].
 *
 * @see JClubUser
 */
class JClubUserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return JClubUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return JClubUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
