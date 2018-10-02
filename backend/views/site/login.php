<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Sign in');

?>

<div class="text-info"><h2><?= Yii::t('app', 'Sign in to'); ?> <strong><?= Yii::$app->name; ?></strong></h2></div>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => [
        'class' => 'secondary-form'
    ]
]); ?>

    <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) { ?>
        <div class="alert"><?= $message ?></div>
    <?php } ?>

    <?= $form->field($model, 'username')->input('text'); ?>
    <div class="sep"></div>
    <?= $form->field($model, 'password')->passwordInput()->input('password'); ?>

    <?php if ($model->isVerifyRobotRequired) : ?>
        <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::class); ?>
    <?php endif; ?>

    <div class="sep"></div>

    <?= $form->field($model, 'rememberMe')->checkbox(); ?>
  

    <div class="clear"></div>

    <?= Html::submitButton(Yii::t('app', 'Login')); ?>

    <span class="info-text">
        <?= Yii::t('modelattr', 'disclaimer') ?>
    </span>

<?php ActiveForm::end(); ?>
