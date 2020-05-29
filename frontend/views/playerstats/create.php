<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlayerStats */

$this->title = Yii::t('modelattr', 'Create Player Stats');
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Player Stats'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-stats-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
