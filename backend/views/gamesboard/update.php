<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\GamesBoard */

$this->title = Yii::t('modelattr', 'Update {modelClass}', [
    'modelClass' => 'Games Board',
]) . ' #' . $model->id;

$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Games Boards'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];

$this->params['breadcrumbs'][] = Yii::t('modelattr', 'Update');
?>
<div class="games-board-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
