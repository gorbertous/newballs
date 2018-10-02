<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Scores */

$this->title = Yii::t('modelattr', 'Update {modelClass}', [
    'modelClass' => 'Scores',
]) . ' #' . $model->score_id;

$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Scores'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->score_id, 'url' => ['view', 'id' => $model->score_id]];

$this->params['breadcrumbs'][] = Yii::t('modelattr', 'Update');
?>
<div class="scores-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
