<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\models\ClubStyles $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="club-styles-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'c_css_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Css ID...']],

            'is_active' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Is Active...']],

            'c_css' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Css...', 'maxlength' => 50]],

            'c_menu_image' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Menu Image...', 'maxlength' => 50]],

            'c_top_image' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Top Image...', 'maxlength' => 50]],

            'c_top' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Top...', 'maxlength' => 50]],

            'c_left' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Left...', 'maxlength' => 50]],

            'c_menu' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Menu...', 'maxlength' => 50]],

            'c_right' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Right...', 'maxlength' => 50]],

            'c_footer' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Footer...', 'maxlength' => 50]],

            'c_main_colour_EN' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Main Colour  En...', 'maxlength' => 50]],

            'c_main_colour_FR' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Main Colour  Fr...', 'maxlength' => 50]],

            'c_colour_sample' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter C Colour Sample...', 'maxlength' => 50]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    );
    ActiveForm::end(); ?>

</div>
