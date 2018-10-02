<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Scores]].
 *
 * @see Scores
 */
class ScoresQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Scores[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Scores|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
