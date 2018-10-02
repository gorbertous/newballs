<?php

namespace common\rbac\helpers;

use yii\rbac\DbManager;
use common\models\User;
use common\rbac\models\Role;

/**
 * Class RbacHelper
 * @package common\rbac\helpers
 */
class RbacHelper
{   
    /**
     * Assigns the appropriate role to the registered user.
     * If this is the first registered user in our system, he will get the
     * developer role (this should be you), if not, he will get the reader role.
     *
     * @param  integer $id The id of the registered user.
     * @return string      Role name.
     */
    public static function assignRole($id)
    {
        // make sure there are no leftovers
        Role::deleteAll(['user_id' => $id]);

        $usersCount = User::find()->count();

        $auth = new DbManager;

        // this is the first user in our system, give him developer role
        if ($usersCount == 1) {
            $role = $auth->getRole('developer');
            $auth->assign($role, $id);
        } else {
            $role = $auth->getRole('member');
            $auth->assign($role, $id);
        }

        // return assigned role name in case you want to use this method in tests
        return $role->name;
    }
}

