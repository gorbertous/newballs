<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Create new password');

?>

<div class="text-info"><h2><?= Yii::t('app', 'Create new password') ?></h2></div>

<?php $form = ActiveForm::begin([
    'id' => 'reset-password-form',
    'options' => [
        'class' => 'secondary-form'
    ]
]); ?>

    <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) { ?>
        <div class="alert"><?= $message; ?></div>
    <?php } ?>

    <?= $form->field($model, 'password')->passwordInput()->input('password', [
        'placeholder' => Yii::t('app', 'Choose your new password'),
        'class' => 'field-needs-pw-validation'
    ]); ?>

    <div class="sep"></div>

    <?= $form->field($model, 'password_repeat')->passwordInput()->input('password', [
            'placeholder' => Yii::t('app', 'Repeat your new password')
    ]); ?>

    <?= Html::submitButton(Yii::t('app', 'Save')) ?>

<?php ActiveForm::end(); ?>