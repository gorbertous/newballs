<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

?>
<div class="texts-form">

    <?php 
    $form = ActiveForm::begin([
        'id' => 'form-texts',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
  
    ?>

    <div class="well">
        <div class="row">
            <div class="col-md-12">
                <?= $form->hrwTextInputMax($model, 'code') ?>
                <?php 
                    foreach (Yii::$app->contLang->languages as $iso) {
                        echo $form->hrwTinyMce($model, 'text_'.$iso);
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6">
        <div class="form-group pull-right">
            <?= Html::submitButton('<span class="fa fa-check"></span>&nbsp;' .
                            ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')), ['class' => $model->isNewRecord ?
                                'btn btn-success' : 'btn btn-success']) ?>

            <?= Html::Button('<span class="fa fa-times"></span>&nbsp;' .
                    Yii::t('app', 'Cancel'), ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>

