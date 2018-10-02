<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ReservesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reserves-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options'=>array('class'=>'form-inline')
    ]); ?>

<?= $form->field($model, 'id', [
                'template'=>'{input}<span class="input-group-btn"><button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-search"></i></button></span>',
                'options'=>['class'=>'input-group input-group-sm']])
                ->textInput(['placeholder'=>Yii::t('app', 'Search')]);
            ?>

    <?php ActiveForm::end(); ?>

</div>
