<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FeesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('modelattr', 'Manage {modelClass}', [
    'modelClass' => 'Fees',
]);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">

        <?php         Panel::begin(
        [
        'header' => Html::encode($this->title),
        'icon' => 'users',
        ]
        )
         ?> 


        <div class="fees-index">


            
            <div class="container">
                <div class="box-header with-border">
                    <div class="pull-left">
                        <?= Html::a(Yii::t('modelattr', 'Create Fees'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
                    </div>
                </div>
            </div>

                            <?= \yiister\gentelella\widgets\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'hover' => true,
                'filterModel' => $searchModel,
        'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                            'id',
            'c_id',
            'mem_type_id',
            'mem_fee',

                [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '70'],
                'template' => '{view} {update} {delete} {link}',
                ],
                ],
                ]); ?>
            
            

        </div>


        <?php Panel::end() ?> 
    </div>
</div>



