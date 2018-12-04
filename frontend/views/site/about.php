<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'About the Club');

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $model->home_page ?>
</div>
