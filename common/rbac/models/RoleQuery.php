<?php

namespace common\rbac\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Role]].
 *
 * @see Role
 */
class RoleQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     * @return Role[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Role|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
