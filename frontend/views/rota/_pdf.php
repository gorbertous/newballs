<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\GamesBoard */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rota'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="games-board-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Rota').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        [
                'attribute' => 'c.name',
                'label' => Yii::t('app', 'C')
            ],
        [
                'attribute' => 'termin.termin_id',
                'label' => Yii::t('app', 'Termin')
            ],
        [
                'attribute' => 'member.title',
                'label' => Yii::t('app', 'Member')
            ],
        'court_id',
        'slot_id',
        'status_id',
        'fines',
        'tokens',
        'late',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>
