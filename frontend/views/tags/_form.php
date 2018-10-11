
<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

$alllang = Yii::$app->contLang->languages;

?>
<div class="tags-form">

    <?php 
    $form = ActiveForm::begin([
        'id' => 'form-tags',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>

    <div class="well">
        <div class="row">
            <div class="col-md-12">
                <?php
                foreach ($alllang as $iso) {
                    echo $form->hrwTextInputMax($model, 'name_'.$iso);
                }
                ?>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">
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


