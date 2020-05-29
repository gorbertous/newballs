
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

    <div class="card card-body bg-light">
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

   
    <div class="modal-footer">
        <?= Html::submitButton('<span class="fas fa-check"></span>&nbsp;' .
                        ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')), ['class' => $model->isNewRecord ?
                            'btn btn-success' : 'btn btn-success']) ?>

        <?= Html::Button('<span class="fas fa-times"></span>&nbsp;' .
                Yii::t('app', 'Cancel'), ['class' => 'btn btn-danger', 'data-izimodal-close' => 'modal']) ?>
    </div>
     
    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>


