<?php

use common\helpers\Helpers;
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

    <?php
    echo Helpers::getModalFooter($model, null, null, [
        'buttons' => ['create_update', 'cancel']
    ]);
    ?>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>

