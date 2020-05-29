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

    <?= $form->field($model, 'player_stats_scheduled')->textInput() ?>

    <?= $form->field($model, 'player_stats_played')->textInput() ?>

    <?= $form->field($model, 'player_stats_cancelled')->textInput() ?>

    <?= $form->field($model, 'coaching_stats')->textInput() ?>

    <?= $form->field($model, 'status_stats')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('modelattr', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
