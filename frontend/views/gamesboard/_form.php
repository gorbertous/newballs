<?php

use backend\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\Helpers;
use common\dictionaries\OutcomeStatus;


/* @var $this yii\web\View */
/* @var $model backend\models\GamesBoard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="games-board-form">

    <?php
    $form = ActiveForm::begin([
                'id'      => 'form-location',
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>

    <ul class="nav nav-pills" id="tabContent">
        <li class="active"><a href="#rota" data-toggle="tab"><?= Yii::t('modelattr', 'Rota') ?></a></li>

        <!-- Audit tab  -->
        <?php if(Yii::$app->user->can('team_memebr')): ?>
            <?= Helpers::getAuditTab() ?>
        <?php endif; ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active well" id="rota">
            <div class="row">
                <div class="col-xs-6">
                    <?=
                    $form->hrwSelect2($model, 'member_id', [
                        'data'          => ArrayHelper::map(\backend\models\Members::find()->all(), 'member_id', 'name'),
                        'options'       => ['id' => 'mem-id'],
                        'pluginOptions' => ['allowClear' => true]
                    ])
                    ?>
                </div>
            </div>      
            <div class="row"> 
                <div class="col-xs-8">
                    <?=
                    $form->hrwSelect2($model, 'termin_id', [
                        'data'          => ArrayHelper::map(\backend\models\PlayDates::find()->all(), 'termin_id', 'termin_date'),
                        'options'       => ['id' => 'ter-id'],
                        'pluginOptions' => ['allowClear' => true]
                    ])
                    ?>
                </div>
                <div class="col-xs-4">
                   <?= $form->hrwTextInputMax($model, 'court_id') ?>
                   <?= $form->hrwTextInputMax($model, 'slot_id') ?>
                </div>
            </div>
             <div class="row">     
                <div class="col-xs-6">
                   <?= $form->hrwSelect2($model, 'status_id', [
                            'data' => OutcomeStatus::all(),
                            'hideSearch' => true,
                            'options' => ['placeholder' => '']
                        ]) ?>
                </div>
                 <div class="col-xs-6">
                     <?= $form->hrwCheckboxX($model, 'tokens') ?>
                     <?= $form->hrwCheckboxX($model, 'late') ?>
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

