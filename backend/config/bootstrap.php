<?php
$root = dirname(dirname(__DIR__));

if (!file_exists($root . '/backend/web/img/uploads')) {
    mkdir('/backend/web/img/uploads', 0755, true);
}
Yii::setAlias('uploads', $root . '/backend/web/img/uploads');
Yii::setAlias('uploadsURL', '/admin/img/uploads');

if (!file_exists($root . '/backend/web/img/uploads/temp')) {
    mkdir($root . '/backend/web/img/uploads/temp', 0755, true);
}
Yii::setAlias('temp', $root . '/backend/web/img/uploads/temp');

