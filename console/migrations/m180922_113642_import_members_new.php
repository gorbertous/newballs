<?php

use yii\db\Migration;
use backend\models\Members;
use common\models\User;
use common\rbac\helpers\RbacHelper;

/**
 * Class m180922_113642_import_members_new
 */
class m180922_113642_import_members_new extends Migration
{
    /**
     * {@inheritdoc}
     */
   public function safeUp()
    {
        
        $srcmodels = Members::find()
                ->where(['>', 'member_id', 768])
                ->all();
        foreach ($srcmodels as $srcrow) {
            $useremail = User::findOne(['email' => $srcrow->email]);
            $username = User::findOne(['username' => $srcrow->username]);
            if (empty($username) && empty($useremail)) {
                // create new user account if not
                $user = new User();
                $user->email = $srcrow->email;
                $user->username = $srcrow->username;

                if ($srcrow->is_active) {
                    $user->status = User::STATUS_ACTIVE;
                } else {
                    $user->status = User::STATUS_DELETED;
                }
                $user->setPassword($srcrow->password);
                $user->generateAuthKey();
                
                $user->created_at = strtotime($srcrow->created_at);
                $user->updated_at = strtotime($srcrow->updated_at);
                $user->save(false);
            }

            RbacHelper::assignRole($user->getId());

            $srcrow->user_id = $user->getId();
            $srcrow->save(false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180922_113642_import_members_new cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180922_113642_import_members_new cannot be reverted.\n";

        return false;
    }
    */
}
