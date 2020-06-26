<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = Yii::t('app', 'Login');

?>

<div class="row">
    <div class="col-md-6 mb-5">

        <div class="card-body">
            <h2 class="card-title"><?= Html::encode($this->title) ?></h2>
       
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

                    <div class="form-group">
                        <?= Html::submitButton($this->title, ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
           
        </div>
   
  </div>
</div>
