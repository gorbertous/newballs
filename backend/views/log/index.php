<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('modelattr', 'Manage {modelClass}', [
            'modelClass' => 'Logs',
        ]);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">

        <?php
        Panel::begin(
                [
                    'header' => Html::encode($this->title),
                    'icon'   => 'users',
                ]
        )
        ?> 


        <div class="log-index">

           
            <?=
            \yiister\gentelella\widgets\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'hover'        => true,
                'filterModel'  => $searchModel,
                'columns'      => [
                    //['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'level',
                    'category',
                    'log_time',
                    'prefix:ntext',
                    // 'message:ntext',
                    [
                        'class'         => 'yii\grid\ActionColumn',
                        'headerOptions' => ['width' => '70'],
                        'template'      => '{view} {update} {delete} {link}',
                    ],
                ],
            ]);
            ?>



        </div>


<?php Panel::end() ?> 
    </div>
</div>



