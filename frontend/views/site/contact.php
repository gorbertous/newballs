<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::t('app', 'If you have any questions, please fill out the following form to contact us. Thank you.'); ?>
    </p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?= $form->field($model, 'name')->textInput(
                    ['placeholder' => Yii::t('app', 'Enter your name'), 'autofocus' => true])
            ?>

            <?= $form->field($model, 'email')->input('email', ['placeholder' => Yii::t('app', 'Enter your e-mail')]) ?>

            <?= $form->field($model, 'subject')->textInput(['placeholder' => Yii::t('app', 'Enter the subject')]) ?>

            <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

            <?= $form->field($model, 'captcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::class); ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'contact-button'])?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
