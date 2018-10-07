<?php

use backend\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\helpers\Helpers;
//use common\helpers\ViewsHelper;
use backend\models\Location;
use kartik\widgets\DateTimePicker;
use kartik\depdrop\DepDrop;
use common\dictionaries\Somenumbers;
use common\dictionaries\ClubSessions;

/* @var $this yii\web\View */
/* @var $model backend\models\PlayDates */
/* @var $form yii\widgets\ActiveForm */

//$script = <<< JS
//$('#c_id').change(function(){
//        var season_id = $(this).val();
//        $.get('index.php?r=playdates/get-year-season',{c_id : c_id}), function(data){
//            var data = $.parseJSON(data);
//            alert();
//        $('#season_id').attr('value', data.season_id);
//    };
// });     
//JS;
//$this->registerJs($script);

?>

<div class="play-dates-form">

    <?php
    $form = ActiveForm::begin([
                'id'      => 'form-location',
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
    ]);
    ?>

    <ul class="nav nav-pills" id="tabContent">
        <li class="active"><a href="#playdate" data-toggle="tab"><?= Yii::t('modelattr', 'Play Date') ?></a></li>

        <!-- Audit tab  -->
        <?= Helpers::getAuditTab() ?>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active well" id="playdate">
            <div class="row">
                <div class="col-xs-6">
                    <?=
                    $form->hrwSelect2($model, 'location_id', [
                        'data'          => ArrayHelper::map(Location::find()->all(), 'location_id', 'name'),
                        'options'       => ['id' => 'loc-id'],
                        'pluginOptions' => ['allowClear' => true]
                    ])
                    ?>
                </div>
            </div>      
            <div class="row"> 
                <div class="col-xs-8">
                     <?= $form->field($model, 'termin_date')->widget(DateTimePicker::classname(), [
                            'options' => ['placeholder' => 'Enter play date time ...'],
                            'pluginOptions' => [
                                    'autoclose' => true
                            ]
                    ]);?>
                </div>
                <div class="col-xs-4">
                   <?= $form->hrwTextInputMax($model, 'recurr_no') ?>
                </div>
            </div>
             <div class="row">     
                <div class="col-xs-6">
                    <?php if ($model->isNewRecord): ?>
                        <?=  $form->field($model, 'season_id')->widget(DepDrop::classname(), [
                                'options'=>['id' =>'season-id'],
                                'pluginOptions' =>[
                                    'depends' => ['c-id'],
                                    'placeholder' => 'Select...',
                                    'url'=>Url::to(['/playdates/subcat'])
                                ]
                        ]);?>
                    <?php else: ?>
                         <?= $form->hrwSelect2($model, 'season_id', [
                            'data' => Somenumbers::all(),
                            'hideSearch' => true,
                            'disabled' => true,
                            'options' => ['placeholder' => '']
                        ]) ?>
                    <?php endif; ?>
                </div>
                 <div class="col-xs-6">
                    <?= $form->hrwSelect2($model, 'session_id', [
                        'data' => ClubSessions::all(),
                        'hideSearch' => true,
                        'options' => ['placeholder' => '']
                    ]) ?>
                </div>
            </div>
                    
            <div class="row"> 
                <div class="col-xs-4">
                    <?= $form->hrwTextInputMax($model, 'courts_no') ?>
                </div>
                <div class="col-xs-4">
                    <?= $form->hrwTextInputMax($model, 'slots_no') ?>
                </div>
                <div class="col-xs-4">
                   <?= $form->hrwCheckboxX($model, 'active') ?>
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

