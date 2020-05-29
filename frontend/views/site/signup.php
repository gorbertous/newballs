<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\password\PasswordInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Clubs;

$setactive = "$(document).ready(function(){
	$('.active').removeClass('active');
	$('#link-signup').addClass('active');
});";

$this->registerJs($setactive);

$this->title = Yii::t('app', 'Join');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-6 mb-5">

        <div class="card-body">
            <h2 class="card-title"><?= Html::encode($this->title) ?></h2>
            <p><?= Yii::t('app', 'Please fill out the following fields to join the club:') ?></p>


            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?=
            $form->field($model, 'club')->widget(Select2::classname(), [
                'data'    => ArrayHelper::map(Clubs::find()->all(), 'c_id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Select club to join')]
            ]);
            ?>

            <?=
            $form->field($model, 'username')->textInput(
                    ['placeholder' => Yii::t('app', 'Create your username'), 'autofocus' => true])
            ?>

            <?= $form->field($model, 'email')->input('email', ['placeholder' => Yii::t('app', 'Enter your e-mail')]) ?>

            <?= $form->field($model, 'password')->widget(PasswordInput::classname(), ['options' => ['placeholder' => Yii::t('app', 'Create your password')]])
            ?>

                <?= $form->field($model, 'captcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::class); ?>

            <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php if ($model->scenario === 'rna'): ?>
                <div style="color:#666;margin:1em 0">
                    <i>*<?= Yii::t('app', 'We will send you an email with the account activation link!') ?></i>
                </div>
            <?php endif ?>

        </div>


    </div>
</div>

