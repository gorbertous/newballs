<?php

use yii\db\Migration;
use backend\models\Members;
use common\models\User;
use common\rbac\helpers\RbacHelper;
//use yii\db\Expression;

set_time_limit(3600); 

/**
 * Class m180918_100017_import_members
 */
class m180918_100017_import_members extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $srcmodels = Members::find()
                ->where(['!=', 'email', 'gorbertous@gmail.com'])
                
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
        echo "m180918_100017_import_members cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180918_100017_import_members cannot be reverted.\n";

      return false;
      }
     */
}
