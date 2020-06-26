<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlayerStats */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-stats-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'member_id')->textInput() ?>

    <?= $form->field($model, 'season_id')->textInput() ?>

    <?= $form->field($model, 'token_stats')->textInput() ?>

    <?= $form->field($model, 'scheduled_stats')->textInput() ?>

    <?= $form->field($model, 'played_stats')->textInput() ?>

    <?= $form->field($model, 'cancelled_stats')->textInput() ?>

    <?= $form->field($model, 'coaching_stats')->textInput() ?>

    <?= $form->field($model, 'noshow_stats')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('modelattr', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
