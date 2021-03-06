<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$setactive = "$(document).ready(function(){
	$('.active').removeClass('active');
	$('#link-login').addClass('active');
});";

$this->registerJs($setactive);


$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-6 mb-5">

        <div class="card-body">
            <h2 class="card-title"><?= Html::encode($this->title) ?></h2>
        <p><?= Yii::t('app', 'Please fill out the following fields to login:')?></p>

       
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
                        <?= Yii::t('app', 'In case you have forgotten your password you can')?>&nbsp; <?= Html::a(Yii::t('app', 'reset it'), ['site/request-password-reset']) ?>.
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton($this->title, ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
           
        </div>
   
  </div>
</div>
