<?php

namespace common\models;

use yii\db\ActiveQuery;

/**
 * Class UserAuthLogQuery ( ActiveQuery class )
 *
 * @see UserAuthLog
 */
class UserAuthLogQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     *
     * @param null $db
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     *
     * @param null $db
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
