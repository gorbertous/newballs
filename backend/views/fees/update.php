<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Fees */

$this->title = Yii::t('modelattr', 'Update {modelClass}', [
    'modelClass' => 'Fees',
]) . ' #' . $model->id;

$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Fees'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];

$this->params['breadcrumbs'][] = Yii::t('modelattr', 'Update');
?>
<div class="fees-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
