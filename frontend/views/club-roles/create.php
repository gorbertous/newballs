<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ClubRoles */

$this->title = Yii::t('modelattr', 'Create Club Roles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Club Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="club-roles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
