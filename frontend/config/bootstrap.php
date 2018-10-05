<?php
$root = dirname(dirname(__DIR__));

if (!file_exists($root . '/frontend/web/img/uploads')) {
    mkdir('/frontend/web/img/uploads', 0755, true);
}
Yii::setAlias('uploads', $root . '/frontend/web/img/uploads');
Yii::setAlias('uploadsURL', '/img/uploads');

if (!file_exists($root . '/frontend/web/img/uploads/temp')) {
    mkdir($root . '/frontend/web/img/uploads/temp', 0755, true);
}
Yii::setAlias('temp', $root . '/frontend/web/img/uploads/temp');

