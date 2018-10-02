<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[JNewsTags]].
 *
 * @see JNewsTags
 */
class JNewsTagsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return JNewsTags[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return JNewsTags|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
