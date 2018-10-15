<?php

//use yii\helpers\Html;
use backend\widgets\ActiveForm;
//use kartik\widgets\Select2;
//use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use common\helpers\Helpers;
use common\helpers\ViewsHelper;
use common\dictionaries\ClubSessions;
use common\dictionaries\Sports;
use common\dictionaries\Somenumbers;
use backend\models\Clubstyles;

/**
 * @var yii\web\View $this
 * @var backend\models\Clubs $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="clubs-form">

    <?php 
    $form = ActiveForm::begin([
        'id' => 'form-clubs',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>

        <ul class="nav nav-pills" id="tabContent">
            <li class="active"><a href="#general" data-toggle="tab"><?= Yii::t('modelattr', 'General') ?></a></li>
            <li><a href="#pages" data-toggle="tab"><?= Yii::t('modelattr', 'Main Pages') ?></a></li>
            <li><a href="#pagesoth" data-toggle="tab"><?= Yii::t('modelattr', 'Other Pages') ?></a></li>
            <li><a href="#data" data-toggle="tab"><?= Yii::t('modelattr', 'Data') ?></a></li>
            <!-- Audit tab  -->
            <?= Helpers::getAuditTab()?>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active well" id="general">

                <div class="row">
                    <div class="col-md-6">                        
                        <?= $form->hrwTextInputMax($model, 'name') ?>
                        <?= $form->hrwSelect2($model, 'sport_id', [
                                'data' => Sports::all(),
                                'hideSearch' => true,
                                'options' => ['placeholder' => '']
                        ]) ?>
                        <?= $form->hrwSelect2($model, 'season_id', [
                                'data' => Somenumbers::all(),
                                'hideSearch' => true,
                                'options' => ['placeholder' => '']
                        ]) ?>
                        <?= $form->hrwCheckboxX($model, 'type_id') ?>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label"><?= Yii::t('modelattr', 'Logo') ?></label>
                        <?= $form->hrwFileInput($model, 'ajaxfileinputLogo') ?>
                    </div>
                </div>
                <hr class="grey-dark" />

            </div>
            <div class="tab-pane well" id="pages">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->hrwTinyMce($model, 'home_page') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->hrwTinyMce($model, 'summary_page') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->hrwTinyMce($model, 'rules_page') ?>
                    </div>
                </div>
                
            </div>
            
            <div class="tab-pane well" id="pagesoth">
                
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->hrwTinyMce($model, 'subscription_page') ?>
                    </div>
                </div>
                 
                 <div class="row">
                    <div class="col-md-12">
                        <?= $form->hrwTinyMce($model, 'members_page') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->hrwTinyMce($model, 'rota_page') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->hrwTinyMce($model, 'tournament_page') ?>
                    </div>
                </div>
            </div>
               
            <div class="tab-pane well" id="data">
                 <div class="row">
                    <div class="col-md-6">
                        <?= $form->hrwSelect2($model, 'css_id', [
                                'data' => ArrayHelper::map(ClubStyles::find()->all(), 'c_css_id', 'c_css'),
                                'options' => ['id' => 'css_id'],
                                'pluginOptions' => [ 'allowClear' => true ]
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->hrwSelect2($model, 'session_id', [
                                'data' => ClubSessions::all(),
                                'hideSearch' => true,
                                'options' => ['placeholder' => '']
                        ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->hrwSelect2($model, 'admin_ids', [
                                'data' => ViewsHelper::getUserMembersList($model->c_id,['is_active' => true]),
                                'options' => ['multiple' => true,'id' => 'admin-id'],
                                'pluginOptions' => [ 'allowClear' => true ]
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                         <?= $form->hrwSelect2($model, 'chair_id', [
                                'data' => ViewsHelper::getMembersList($model->c_id),
                                'options' => ['id' => 'chair-id'],
                                'pluginOptions' => [ 'allowClear' => true ]
                        ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'coach_stats') ?>
                    </div>
                     <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'token_stats') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'play_stats') ?>
                    </div>
                     <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'match_instigation') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'court_booking') ?>
                    </div>
                     <div class="col-md-6">
                        <?= $form->hrwCheckboxX($model, 'money_stats') ?>
                    </div>
                </div>
            </div>

           
            <!-- Audit tab content -->
            <?php  echo Helpers::getAuditTabContent($model)?>
        </div>

        <?php echo Helpers::getModalFooter($model, null, null, [
            'buttons' => ['create_update', 'cancel']
        ]); ?>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>

