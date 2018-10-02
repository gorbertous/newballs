<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\ClubStyles $model
 */

$this->title = Yii::t('modelattr', 'Create {modelClass}', [
    'modelClass' => 'Club Styles',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Club Styles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="club-styles-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
