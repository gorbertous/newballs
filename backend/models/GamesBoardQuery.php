<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[GamesBoard]].
 *
 * @see GamesBoard
 */
class GamesBoardQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return GamesBoard[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return GamesBoard|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
