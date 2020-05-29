<?php

use backend\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\Helpers;
use common\helpers\ViewsHelper;

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
        <li class="nav-item"><a class="nav-link active" href="#reserves" data-toggle="tab"><?= Yii::t('modelattr', 'Reserves') ?></a></li>

        <!-- Audit tab  -->
        <?php if(Yii::$app->user->can('team_memebr')): ?>
            <?= Helpers::getAuditTab() ?>
        <?php endif; ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active card card-body bg-light" id="reserves">
            <div class="row">
                <div class="col-md-6">
                    <?=
                    $form->hrwSelect2($model, 'member_id', [
                        'data'          => ViewsHelper::getMembersList(),
                        'options'       => ['id' => 'mem-id'],
                        'pluginOptions' => ['allowClear' => true]
                    ])
                    ?>
                </div>
            </div>      
            <div class="row"> 
                <div class="col-md-6">
                    <?=
                    $form->hrwSelect2($model, 'termin_id', [
                        'data'          => ArrayHelper::map(\backend\models\PlayDates::find()
                                ->select(['termin_id', 'termin_date'])
                                ->where(['c_id' => Yii::$app->session->get('c_id')])
                                ->orderBy(['termin_id' => SORT_DESC])
                                ->all(), 'termin_id', 'termin_date'),
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
