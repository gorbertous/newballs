<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Scores */

$this->title = Yii::t('modelattr', 'Create Scores');
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Scores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scores-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
