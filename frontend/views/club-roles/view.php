<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\ClubRoles */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Club Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="club-roles-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('modelattr', 'Club Roles').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a(Yii::t('modelattr', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('modelattr', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('modelattr', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        'role',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    
    <div class="row">
<?php
if($providerJClubMemRoles->totalCount){
    $gridColumnJClubMemRoles = [
        ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'member.member_id',
                'label' => Yii::t('modelattr', 'Member')
            ],
                ];
    echo Gridview::widget([
        'dataProvider' => $providerJClubMemRoles,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-j-club-mem-roles']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('modelattr', 'J Club Mem Roles')),
        ],
        'export' => false,
        'columns' => $gridColumnJClubMemRoles
    ]);
}
?>

    </div>
</div>
