<?php

use backend\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\Helpers;
use common\helpers\ViewsHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Location */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="location-form">

    <?php
    $form = ActiveForm::begin([
                'id'      => 'form-location',
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>

    <ul class="nav nav-pills" id="tabContent">
        <li class="nav-item"><a class="nav-link active" href="#location" data-toggle="tab"><?= Yii::t('modelattr', 'Location') ?></a></li>

        <!-- Audit tab  -->
        <?php if(Yii::$app->user->can('team_memebr')): ?>
            <?= Helpers::getAuditTab() ?>
        <?php endif; ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active card card-body bg-light" id="location">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->hrwTextInputMax($model, 'name') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->hrwTextInputMax($model, 'phone') ?>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-12">
                            <?= $form->hrwTextInputMax($model, 'google_par_one') ?>
                        </div>
                        
                    </div>
                    
                  
                </div>
                <div class="col-md-6">
                    <div class="row"> 
                        <div class="col-md-12">
                            <?= $form->hrwTextInputMax($model, 'address') ?>
                        </div>
                        
                    </div>
                    
                    <div class="row"> 
                        <div class="col-md-4">
                            <?= $form->hrwTextInputMax($model, 'zip') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->hrwTextInputMax($model, 'city') ?>
                        </div>
                        <div class="col-md-4">
                            <?=
                            $form->hrwSelect2($model, 'co_code', [
                                'data'          => ViewsHelper::getCountriesList(['continent' => 'EU']),
                                'pluginOptions' => ['allowClear' => true]
                            ])
                            ?>
                        </div>
                    </div>
                     <div class="row"> 
                        <div class="col-md-12">
                            <?= $form->hrwTextInputMax($model, 'google_par_two') ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        
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
