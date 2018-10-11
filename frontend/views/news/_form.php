<?php

//use yii\helpers\Html;
use common\dictionaries\NewsCategories;
use common\helpers\Helpers;
use yii\helpers\ArrayHelper;
use backend\widgets\ActiveForm;
use backend\models\Tags;

/* @var $this yii\web\View */
/* @var $model backend\models\News */
/* @var $form yii\widgets\ActiveForm */

$alllang = Yii::$app->contLang->languages;

?>

<div class="news-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'form-news',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
  
    ?>

    <ul class="nav nav-pills" id="tabContent">
        <li class="active"><a href="#description" data-toggle="tab"><?= Yii::t('modelattr', 'Description') ?></a></li>
        <li><a href="#file" data-toggle="tab"><?= Yii::t('modelattr', 'Images') ?></a></li>
        <li><a href="#parameters" data-toggle="tab"><?= Yii::t('modelattr', 'Parameters') ?></a></li>
        <li><a href="#tags" data-toggle="tab"><?= Yii::t('modelattr', 'Tags') ?></a></li>
        <!-- Audit tab  -->
        <?php if(Yii::$app->user->can('team_memebr')): ?>
            <?= Helpers::getAuditTab() ?>
        <?php endif; ?>
    </ul>

    <div class="tab-content">

        <div class="tab-pane well active" id="description">
            <div class="row">
                <div class="col-xs-6">
                    <?= $form->hrwSelect2($model, 'category', [
                        'data'       => NewsCategories::all(),
                        'hideSearch' => true])
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?php
                    foreach ($alllang as $iso) {
                        echo $form->hrwTextInputMax($model, 'title_'.$iso);
                        echo $form->hrwTinyMce($model, 'content_'.$iso);
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="tab-pane well" id="file">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label"><?= Yii::t('modelattr', 'Featured image') ?></label>
                    <?= $form->hrwFileInput($model, 'ajaxfilefeatured') ?>
                    <br>
                    <br>
                    <?= $form->hrwTextInputMax($model, 'source_url') ?>
                </div>
                <div class="col-md-6">
                    <label class="control-label"><?= Yii::t('modelattr', 'Images') . ' / '. Yii::t('modelattr', 'Files') ?></label>
                    <?= $form->hrwFileInput($model, 'ajaxfilecontent') ?>
                </div>
            </div>
        </div>

        <div class="tab-pane well" id="parameters">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->hrwCheckboxX($model, 'is_public') ?>
                    <?= $form->hrwCheckboxX($model, 'is_valid') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->hrwCheckboxX($model, 'to_newsletter') ?>
                </div>
            </div>
        </div>
        <div class="tab-pane well" id="tags">
            <div class="row">
                <div class="col-md-12">
                    <?= $form->hrwSelect2($model, 'tags_ids', [
                        'data' => ArrayHelper::map(Tags::find()
                            ->all(), 'tag_id', 'nameFB'),
                        'options' => ['multiple' => true, 'id' => 'tagids'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'tags' => true,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 30
                        ]
                    ])
                    ?>
                </div>
            </div>
        </div>
        <!-- Audit tab content -->
        <?php  echo Helpers::getAuditTabContent($model)?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo Helpers::getModalFooter($model, null, null, [
                'buttons' => ['create_update', 'cancel']
            ]); ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>

