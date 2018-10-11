<?php

use backend\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\Helpers;
use backend\models\Clubs;

/* @var $this yii\web\View */
/* @var $model backend\models\Scores */
/* @var $form backend\widgets\ActiveForm */
?>

<div class="reserves-form">

    <?php
    $form = ActiveForm::begin([
        'id'      => 'form-scores',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>

    <ul class="nav nav-pills" id="tabContent">
        <li class="active"><a href="#reserves" data-toggle="tab"><?= Yii::t('modelattr', 'Scores') ?></a></li>

        <!-- Audit tab  -->
        <?php if(Yii::$app->user->can('team_memebr')): ?>
            <?= Helpers::getAuditTab() ?>
        <?php endif; ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active well" id="reserves">
           <div class="row"> 
                <div class="col-xs-6">
                    <?=
                    $form->hrwSelect2($model, 'termin_id', [
                        'data'          => ArrayHelper::map(\backend\models\PlayDates::find()->all(), 'termin_id', 'termin_date'),
                        'options'       => ['id' => 'ter-id'],
                        'pluginOptions' => ['allowClear' => true]
                    ])
                    ?>
                </div>
               <div class="col-xs-6">
                     <?= $form->hrwTextInputMax($model, 'court_id') ?>
                </div>
            </div>
            <div class="row"> 
                 <div class="col-xs-3">
                   <?= $form->hrwTextInputMax($model, 'set_one') ?>
                </div>
                <div class="col-xs-3">
                   <?= $form->hrwTextInputMax($model, 'set_two') ?>
                </div>
                <div class="col-xs-3">
                   <?= $form->hrwTextInputMax($model, 'set_three') ?>
                </div>
            </div>
             <div class="row">
                <div class="col-xs-3">
                   <?= $form->hrwTextInputMax($model, 'set_four') ?>
                </div>
                 <div class="col-xs-3">
                   <?= $form->hrwTextInputMax($model, 'set_five') ?>
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

