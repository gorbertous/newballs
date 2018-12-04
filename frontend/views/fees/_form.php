<?php

use backend\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\Helpers;

/* @var $this yii\web\View */
/* @var $model backend\models\Fees */
/* @var $form backend\widgets\ActiveForm */
?>

<div class="fees-form">

    <?php
    $form = ActiveForm::begin([
                'id'      => 'form-fees',
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>

    <ul class="nav nav-pills" id="tabContent">
        <li class="active"><a href="#fees" data-toggle="tab"><?= Yii::t('appMenu', 'Fees') ?></a></li>

        <!-- Audit tab  -->
        <?php if(Yii::$app->user->can('team_memebr')): ?>
            <?= Helpers::getAuditTab() ?>
        <?php endif; ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active well" id="fees">
            <div class="row">
                <div class="col-xs-6">
                    <?=
                    $form->hrwSelect2($model, 'mem_type_id', [
                        'data'          => ArrayHelper::map(\backend\models\MembershipType::find()->all(), 'mem_type_id', 'nameFB'),
                        'options'       => ['id' => 'mem-type-id'],
                        'pluginOptions' => ['allowClear' => true]
                    ])
                    ?>
                </div>
            </div>      
            <div class="row"> 
                <div class="col-xs-6">
                   <?= $form->hrwTextInputMax($model, 'mem_fee') ?>
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

