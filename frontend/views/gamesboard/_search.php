<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\dictionaries\BooleanFilter;
//use yii\helpers\ArrayHelper;
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
        <div class="col-md-4">
            <?= $form->field($model, 'timefilter')->widget(Select2::classname(), [
                'data' => BooleanFilter::all(),
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
        <div class="col-md-4">
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
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
