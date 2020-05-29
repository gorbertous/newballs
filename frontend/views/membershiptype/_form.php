<?php

use backend\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\Helpers;

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
        <li class="nav-item"><a class="nav-link active" href="#fees" data-toggle="tab"><?= Yii::t('modelattr', 'Membership Types') ?></a></li>

        <!-- Audit tab  -->
        <?php if(Yii::$app->user->can('team_memebr')): ?>
            <?= Helpers::getAuditTab() ?>
        <?php endif; ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active card card-body bg-light" id="fees">
           
            <div class="row">
                <div class="col-md-12">
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

