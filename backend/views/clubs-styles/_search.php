<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\ClubStylesSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="club-styles-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'c_css_id') ?>

    <?= $form->field($model, 'c_css') ?>

    <?= $form->field($model, 'c_menu_image') ?>

    <?= $form->field($model, 'c_top_image') ?>

    <?= $form->field($model, 'c_top') ?>

    <?php // echo $form->field($model, 'c_left') ?>

    <?php // echo $form->field($model, 'c_menu') ?>

    <?php // echo $form->field($model, 'c_right') ?>

    <?php // echo $form->field($model, 'c_footer') ?>

    <?php // echo $form->field($model, 'c_main_colour_EN') ?>

    <?php // echo $form->field($model, 'c_main_colour_FR') ?>

    <?php // echo $form->field($model, 'c_colour_sample') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('modelattr', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('modelattr', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
