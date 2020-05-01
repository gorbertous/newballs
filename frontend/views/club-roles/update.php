<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ClubRoles */

$this->title = Yii::t('modelattr', 'Update {modelClass}: ', [
    'modelClass' => 'Club Roles',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Club Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('modelattr', 'Update');
?>
<div class="club-roles-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
