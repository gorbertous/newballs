<?php

use yii\db\Schema;
use yii\db\Migration;

class m191231_173124_j_club_mem_roles extends Migration
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
            '{{%j_club_mem_roles}}',
            [
                'id'=> $this->primaryKey(11),
                'member_id'=> $this->integer(11)->notNull(),
                'role_id'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );
        $this->createIndex('member_id','{{%j_club_mem_roles}}',['member_id'],false);
        $this->createIndex('role_id','{{%j_club_mem_roles}}',['role_id'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('member_id', '{{%j_club_mem_roles}}');
        $this->dropIndex('role_id', '{{%j_club_mem_roles}}');
        $this->dropTable('{{%j_club_mem_roles}}');
    }
}
