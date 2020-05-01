<?php

use yii\db\Schema;
use yii\db\Migration;

class m191231_181451_club_rolesDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%club_roles}}',
                           ["id", "role"],
                            [
    [
        'id' => '1',
        'role' => 'Comitee Member',
    ],
    [
        'id' => '2',
        'role' => 'Chairman',
    ],
    [
        'id' => '3',
        'role' => 'Webmaster',
    ],
    [
        'id' => '4',
        'role' => 'Social',
    ],
    [
        'id' => '5',
        'role' => 'Financial',
    ],
]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%club_roles}} CASCADE');
    }
}
