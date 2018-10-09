<?php

use backend\widgets\ActiveForm;
//use yii\helpers\ArrayHelper;
use common\helpers\Helpers;
use common\dictionaries\Grades;
use common\dictionaries\ContactTitles;
use common\helpers\ViewsHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Members */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="members-form">

    <?php
    $form = ActiveForm::begin([
                'id'      => 'form-members',
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>

    <ul class="nav nav-pills" id="tabContent">
        <li class="active"><a href="#general" data-toggle="tab"><?= Yii::t('modelattr', 'Profile') ?></a></li>
        <?php if(Yii::$app->user->can('writer')): ?>
            <li><a href="#settings" data-toggle="tab"><?= Yii::t('modelattr', 'Settings') ?></a></li>
        <?php endif; ?>

        <!-- Audit tab  -->
        <?= Helpers::getAuditTab() ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active well" id="general">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-xs-12">
                            <?=
                            $form->hrwSelect2($model, 'mem_type_id', [
                                'data'          => ViewsHelper::getMemTypesList(),
                                'pluginOptions' => ['allowClear' => true]
                            ])
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <?=
                            $form->hrwSelect2($model, 'title', [
                                'data'       => ContactTitles::all(),
                                'hideSearch' => true])
                            ?>
                        </div>
                        <div class="col-xs-8">
                            <?= $form->hrwTextInputMax($model, 'firstname') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <?= $form->hrwTextInputMax($model, 'lastname') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <?=
                            $form->hrwSelect2($model, 'grade_id', [
                                'data'       => Grades::all(),
                                'hideSearch' => true,
                                'options'    => ['placeholder' => '']
                            ])
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <?= $form->hrwTextInputMax($model, 'phone') ?>
                        </div>
                        <div class="col-xs-6">
                            <?= $form->hrwTextInputMax($model, 'phone_mobile') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <?=
                            $form->hrwSelect2($model, 'nationality', [
                                'data'          => ViewsHelper::getCountriesList(),
                                'pluginOptions' => ['allowClear' => true]
                            ])
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="control-label"><?= Yii::t('modelattr', 'Photo') ?></label>
                            <?= /** @noinspection PhpUnhandledExceptionInspection */
                                $form->hrwFileInput($model, 'ajaxfileinputPhoto')
                            ?>
                            <?= $form->hrwTextInputMax($model, 'address') ?>
                        </div>
                    </div>
                    
                    <div class="row"> 
                        <div class="col-xs-4">
                            <?= $form->hrwTextInputMax($model, 'zip') ?>
                        </div>
                        <div class="col-xs-4">
                            <?= $form->hrwTextInputMax($model, 'city') ?>
                        </div>
                        <div class="col-xs-4">
                            <?=
                            $form->hrwSelect2($model, 'co_code', [
                                'data'          => ViewsHelper::getCountriesList(['continent' => 'EU']),
                                'pluginOptions' => ['allowClear' => true]
                            ])
                            ?>
                        </div>
                        <div class="row"> 
                            <div class="col-md-6">
                                <?= $form->hrwCheckboxX($model, 'coaching') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if(Yii::$app->user->can('writer')): ?>
            <div class="tab-pane well" id="settings">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'is_active') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'is_admin') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'has_paid') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'is_visible') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'ban_scoreupload') ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <!-- Audit tab content -->
        <?php echo Helpers::getAuditTabContent($model) ?>
    </div>

    <?php
    echo Helpers::getModalFooter($model, null, null, [
        'buttons' => ['create_update', 'cancel']
    ]);
    ?>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>
