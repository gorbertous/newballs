<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model backend\models\Scores */
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

        <div class="scores-form">

            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'termin_id')->textInput() ?>

    <?= $form->field($model, 'court_id')->textInput() ?>

    <?= $form->field($model, 'set_one')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'set_two')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'set_three')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'set_four')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'set_five')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>


            <?= Html::submitButton($model->isNewRecord ? Yii::t('modelattr', 'Create') : Yii::t('modelattr', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


            <?php ActiveForm::end(); ?>

        </div>



        <?php Panel::end() ?> 
    </div>
</div>

