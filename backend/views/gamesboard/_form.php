<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model backend\models\GamesBoard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <?php 
        Panel::begin(
            [
                'header' => Html::encode($this->title),
                'icon' => 'users',
            ]
        )
         ?> 

        <div class="games-board-form">

            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'c_id')->textInput() ?>

    <?= $form->field($model, 'termin_id')->textInput() ?>

    <?= $form->field($model, 'member_id')->textInput() ?>

    <?= $form->field($model, 'court_id')->textInput() ?>

    <?= $form->field($model, 'slot_id')->textInput() ?>

    <?= $form->field($model, 'status_id')->textInput() ?>

    <?= $form->field($model, 'fines')->textInput() ?>

    <?= $form->field($model, 'tokens')->textInput() ?>

    <?= $form->field($model, 'late')->textInput() ?>


            <?= Html::submitButton($model->isNewRecord ? Yii::t('modelattr', 'Create') : Yii::t('modelattr', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


            <?php ActiveForm::end(); ?>

        </div>



        <?php Panel::end() ?> 
    </div>
</div>

