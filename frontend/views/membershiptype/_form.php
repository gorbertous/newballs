<?php

use backend\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\Helpers;
use backend\models\Clubs;

/* @var $this yii\web\View */
/* @var $model backend\models\MembershipType */
/* @var $form backend\widgets\ActiveForm */

$alllang = Yii::$app->contLang->languages;
?>

<div class="membership-type-form">

    <?php
    $form = ActiveForm::begin([
                'id'      => 'membership-type-form',
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>

    <ul class="nav nav-pills" id="tabContent">
        <li class="active"><a href="#fees" data-toggle="tab"><?= Yii::t('modelattr', 'Membership Types') ?></a></li>

        <!-- Audit tab  -->
        <?= Helpers::getAuditTab() ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active well" id="fees">
            <div class="row">
                <div class="col-xs-6">
                    <?=
                    $form->hrwSelect2($model, 'c_id', [
                        'data'          => ArrayHelper::map(Clubs::find()->all(), 'c_id', 'name'),
                        'options'       => ['id' => 'c-id'],
                        'pluginOptions' => ['allowClear' => true]
                    ])
                    ?>
                </div>
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
                <div class="col-xs-12">
                    <?php
                    foreach ($alllang as $iso) {
                        echo $form->hrwTextInputMax($model, 'name_'.$iso);
                    }
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

