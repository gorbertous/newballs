<?php

use backend\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\Helpers;

/* @var $this yii\web\View */
/* @var $model backend\models\Reserves */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reserves-form">

    <?php
    $form = ActiveForm::begin([
                'id'      => 'form-reserves',
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>

    <ul class="nav nav-pills" id="tabContent">
        <li class="active"><a href="#reserves" data-toggle="tab"><?= Yii::t('modelattr', 'Reserves') ?></a></li>

        <!-- Audit tab  -->
        <?= Helpers::getAuditTab() ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active well" id="reserves">
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
                <div class="col-xs-6">
                    <?=
                    $form->hrwSelect2($model, 'termin_id', [
                        'data'          => ArrayHelper::map(\backend\models\PlayDates::find()->all(), 'termin_id', 'termin_date'),
                        'options'       => ['id' => 'ter-id'],
                        'pluginOptions' => ['allowClear' => true]
                    ])
                    ?>
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
