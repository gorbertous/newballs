<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\GamesBoard */

?>
<div class="games-board-view">

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'termin.location.address',
            'label' => Yii::t('app', 'Location'),
        ],
        [
            'attribute' => 'termin.termin_date',
            'label' => Yii::t('app', 'Date'),
        ],
        [
            'attribute' => 'member_id',
            'label' => Yii::t('app', 'Member'),
             'value' => function($model){
                return $model->member->name . ' ('. $model->member->memType->nameFB . ')';
             },
        ],
        
        'court_id',
        'slot_id',
      
        
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>