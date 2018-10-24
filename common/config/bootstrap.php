<?php

//Yii::setAlias('@common', dirname(__DIR__));
//Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
//Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
//Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
//Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');




$root = dirname(dirname(__DIR__));

if (!file_exists($root . '/backend/backup')) {
    mkdir('/backend/backup', 0755, true);
}

Yii::setAlias('backups', $root . '/backend/backup');
Yii::setAlias('backupsURL', '/admin/backup');

Yii::setAlias('common', $root . '/common');
Yii::setAlias('frontend', $root . '/frontend');
Yii::setAlias('backend', $root . '/backend');
Yii::setAlias('console', $root . '/console');
Yii::setAlias('api', $root . '/api');
Yii::setAlias('root', $root);
