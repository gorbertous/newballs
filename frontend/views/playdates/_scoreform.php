<?php

use backend\widgets\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Scores */
/* @var $form backend\widgets\ActiveForm */
?>

<div class="scores-form">

    <?php
    $form = ActiveForm::begin([
        'id'      => 'form-scores',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>

    <ul class="nav nav-pills" id="tabContent">
        <li class="nav-item"><a class="nav-link active" href="#scores" data-toggle="tab"><?= Yii::t('modelattr', 'Scores') ?></a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active card card-body bg-light" id="scores">
            <div class="row"> 
                 <div class="col-md-3">
                   <?= $form->hrwTextInputMax($model, 'set_one') ?>
                </div>
                <div class="col-md-3">
                   <?= $form->hrwTextInputMax($model, 'set_two') ?>
                </div>
                <div class="col-md-3">
                   <?= $form->hrwTextInputMax($model, 'set_three') ?>
                </div>
            </div>
             <div class="row">
                <div class="col-md-3">
                   <?= $form->hrwTextInputMax($model, 'set_four') ?>
                </div>
                 <div class="col-md-3">
                   <?= $form->hrwTextInputMax($model, 'set_five') ?>
                </div>
            </div>      
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('modelattr', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>

