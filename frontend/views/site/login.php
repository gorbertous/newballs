<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?php if (Yii::$app->user->enableAutoLogin) : ?>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <?php endif; ?>
            
                <?php if ($model->isVerifyRobotRequired) : ?>
                    <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
                        'template' => '{image}{input}',
                    ]) ?>
                <?php endif; ?>

                <div style="color:#999;margin:1em 0">
                    In case you have forgotten your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
