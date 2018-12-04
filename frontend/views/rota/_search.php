<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\dictionaries\BooleanFilter;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model frontend\models\RotaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-games-board-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    
    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
            <?= $form->field($model, 'termin_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(\backend\models\PlayDates::find()
                        ->where(['c_id' => Yii::$app->session->get('c_id')])
                        ->orderBy(['termin_id' => SORT_DESC])->asArray()->all(), 'termin_id', 'termin_date'),
                'options' => ['placeholder' => Yii::t('app', 'Select Date')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'member_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(\backend\models\Members::find()
                        ->where(['c_id' => Yii::$app->session->get('c_id')])
                        ->orderBy('member_id')->all(), 'member_id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'Member')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>   
    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'timefilter')->widget(Select2::classname(), [
                'data' => BooleanFilter::all(),
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'seasonfilter')->widget(Select2::classname(), [
                'data' => common\dictionaries\Somenumbers::all(Yii::$app->session->get('club_season')),
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>    
    


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
