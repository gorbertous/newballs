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

<div class="card" style="width: 100%;">  
    <div class="card-header text-white bg-primary">   

        <h5 class="panel-title"><span class="fas fa-desktop"></span> <?= Yii::t('appMenu', 'Club') ?>&nbsp;<span class="fas fa-file-image"></span> <?= Yii::t('appMenu', 'Photos') ?> </h5>
        <div class="clearfix"></div>
    </div>
    <div class="card-body">
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
                    'options'       => ['placeholder' => Yii::t('modelattr','Select a year ...')],
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
