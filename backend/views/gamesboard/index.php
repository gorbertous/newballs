<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\GamesboardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('modelattr', 'Manage {modelClass}', [
    'modelClass' => 'Games Boards',
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


        <div class="games-board-index">


            
            <div class="container">
                <div class="box-header with-border">
                    <div class="pull-left">
                        <?= Html::a(Yii::t('modelattr', 'Create Games Board'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
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
            'termin_id',
            'member_id',
            'court_id',
            // 'slot_id',
            // 'status_id',
            // 'fines',
            // 'tokens',
            // 'late',

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



