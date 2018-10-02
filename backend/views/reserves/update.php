<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Reserves */

$this->title = Yii::t('modelattr', 'Update {modelClass}', [
    'modelClass' => 'Reserves',
]) . ' #' . $model->id;

$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Reserves'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];

$this->params['breadcrumbs'][] = Yii::t('modelattr', 'Update');
?>
<div class="reserves-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
