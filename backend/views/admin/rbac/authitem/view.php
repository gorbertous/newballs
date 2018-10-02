<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\rbac\models\Authitem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Auth Item'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('modelattr', 'Auth Item').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a(Yii::t('modelattr', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('modelattr', 'Delete'), ['delete', 'id' => $model->name], [
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
        'name',
        'type',
        'description:ntext',
        [
            'attribute' => 'ruleName.name',
            'label' => Yii::t('modelattr', 'Rule Name'),
        ],
        'data:ntext',
        'created_at',
        'updated_at',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    
    <div class="row">
<?php
if($providerAuthAssignment->totalCount){
    $gridColumnAuthAssignment = [
        ['class' => 'yii\grid\SerialColumn'],
                        'user_id',
            'created_at',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerAuthAssignment,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-auth-assignment']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="fa fa-book"></span> ' . Html::encode(Yii::t('modelattr', 'Auth Assignment')),
        ],
        'export' => false,
        'columns' => $gridColumnAuthAssignment
    ]);
}
?>

    </div>
    <div class="row">
        <h4>AuthRule<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnAuthRule = [
        'name',
        'data:ntext',
        'created_at',
        'updated_at',
    ];
    echo DetailView::widget([
        'model' => $model->ruleName,
        'attributes' => $gridColumnAuthRule    ]);
    ?>
    
    <div class="row">
<?php
if($providerAuthItemChild->totalCount){
    $gridColumnAuthItemChild = [
        ['class' => 'yii\grid\SerialColumn'],
                            ];
    echo Gridview::widget([
        'dataProvider' => $providerAuthItemChild,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-auth-item-child']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="fa fa-book"></span> ' . Html::encode(Yii::t('modelattr', 'Auth Item Child')),
        ],
        'export' => false,
        'columns' => $gridColumnAuthItemChild
    ]);
}
?>

    </div>
</div>
