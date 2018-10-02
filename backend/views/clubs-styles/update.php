<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\ClubStyles $model
 */

$this->title = Yii::t('modelattr', 'Update {modelClass}: ', [
    'modelClass' => 'Club Styles',
]) . ' ' . $model->c_css_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Club Styles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->c_css_id, 'url' => ['view', 'id' => $model->c_css_id]];
$this->params['breadcrumbs'][] = Yii::t('modelattr', 'Update');
?>
<div class="club-styles-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
