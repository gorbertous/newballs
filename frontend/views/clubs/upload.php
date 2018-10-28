<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use common\dictionaries\Years;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var backend\models\UploadForm $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">    

        <h3 class="panel-title"><span class="fa fa-desktop"></span> Club&nbsp;<span class="fa fa-file-image-o"></span> Photos </h3>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <h4><?= Yii::t('modelattr', 'Max file size 8MB, to upload multiple files at the same time, select the files and drag & drop them directly from your file explorer window!') ?></h4>
        <?php
        $form = ActiveForm::begin([
                    'id'      => 'form-upload',
                    'options' => [
                        'enctype' => 'multipart/form-data'
                    ]
        ]);
        ?>
        <div class="row">
            <div class="col-md-10">
                <?=
                $form->field($model, 'albumyear')->widget(Select2::classname(), [
                    'data'          => Years::all(),
                    'options'       => ['placeholder' => 'Select a year ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
                <?=
                $form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
                    'options'       => ['multiple' => true, 'accept' => 'image/*'],
                    'pluginOptions' => ['previewFileType' => 'image']
                ]);
                ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
        <?= $gallery ?>
        

    </div>
</div>
