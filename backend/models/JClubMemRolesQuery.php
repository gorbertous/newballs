<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[JClubMemRoles]].
 *
 * @see JClubMemRoles
 */
class JClubMemRolesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return JClubMemRoles[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return JClubMemRoles|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
