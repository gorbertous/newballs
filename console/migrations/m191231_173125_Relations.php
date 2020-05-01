<?php

use yii\db\Schema;
use yii\db\Migration;

class m191231_173125_Relations extends Migration
{

    public function init()
    {
       $this->db = 'db';
       parent::init();
    }

    public function safeUp()
    {
        $this->addForeignKey('fk_j_club_mem_roles_member_id',
            '{{%j_club_mem_roles}}','member_id',
            '{{%members}}','member_id',
            'CASCADE','CASCADE'
         );
        $this->addForeignKey('fk_j_club_mem_roles_role_id',
            '{{%j_club_mem_roles}}','role_id',
            '{{%club_roles}}','id',
            'CASCADE','CASCADE'
         );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_j_club_mem_roles_member_id', '{{%j_club_mem_roles}}');
        $this->dropForeignKey('fk_j_club_mem_roles_role_id', '{{%j_club_mem_roles}}');
    }
}
