<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\GamesBoard */

$this->title = Yii::t('modelattr', 'Create Games Board');
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Games Boards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="games-board-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
