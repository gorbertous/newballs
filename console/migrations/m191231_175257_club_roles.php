<?php

use yii\db\Schema;
use yii\db\Migration;

class m191231_175257_club_roles extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%club_roles}}',
            [
                'id'=> $this->primaryKey(11),
                'role'=> $this->string(255)->notNull(),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%club_roles}}');
    }
}
