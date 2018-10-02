<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('index', Yii::$app->name . ' - ' . Yii::t('app', 'Reset password'));

?>

<div class="text-info"><h2><?= Yii::t('app', 'Reset your password') ?></h2></div>

<?php $form = ActiveForm::begin([
    'id' => 'request-password-reset-form',
    'enableClientValidation' => false,
    'options' => [
        'class' => 'secondary-form'
    ]
]); ?>

    <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) { ?>
        <div class="alert"><?= $message; ?></div>
    <?php } ?>

    <?= $form->field($model, 'email')->input('text', ['placeholder' => "email@example.com"]) ?>

    <div class="sep"></div>

    <?= $form->field($model, 'reCaptcha')->widget(
        \himiklab\yii2\recaptcha\ReCaptcha::class
    )->label(false); ?>

    <?= Html::submitButton(Yii::t('app', 'Send password reset email')) ?>

    <span class="info-text"><?= Yii::t('app', 'A link to reset your password will be sent to your email.') ?></span>

<?php ActiveForm::end(); ?>