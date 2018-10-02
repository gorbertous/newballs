<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('modelattr', 'Manage {modelClass}', [
    'modelClass' => 'Scores',
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


        <div class="scores-index">


            
            <div class="container">
                <div class="box-header with-border">
                    <div class="pull-left">
                        <?= Html::a(Yii::t('modelattr', 'Create Scores'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
                    </div>
                </div>
            </div>

                            <?= \yiister\gentelella\widgets\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'hover' => true,
                'filterModel' => $searchModel,
        'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                            'score_id',
            'termin_id',
            'court_id',
            'set_one',
            'set_two',
            // 'set_three',
            // 'set_four',
            // 'set_five',
            // 'created_by',
            // 'updated_by',
            // 'created_at',
            // 'updated_at',

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



