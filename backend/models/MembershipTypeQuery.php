<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[MembershipType]].
 *
 * @see MembershipType
 */
class MembershipTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MembershipType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MembershipType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
