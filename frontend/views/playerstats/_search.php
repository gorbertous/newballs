<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlayerStatsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-stats-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'member_id') ?>

    <?= $form->field($model, 'season_id') ?>

    <?= $form->field($model, 'token_stats') ?>

    <?= $form->field($model, 'scheduled_stats') ?>

    <?php // echo $form->field($model, 'played_stats') ?>

    <?php // echo $form->field($model, 'cancelled_stats') ?>

    <?php // echo $form->field($model, 'coaching_stats') ?>

    <?php // echo $form->field($model, 'noshow_stats') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('modelattr', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('modelattr', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
