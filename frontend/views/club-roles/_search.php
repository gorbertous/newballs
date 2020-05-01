<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ClubRolesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-club-roles-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id')->textInput(['placeholder' => 'Id']) ?>

    <?= $form->field($model, 'role')->textInput(['maxlength' => true, 'placeholder' => 'Role']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('modelattr', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('modelattr', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
