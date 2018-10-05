<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model backend\models\GamesBoard */

$this->title = Yii::t('modelattr', 'View {modelClass}', [
    'modelClass' => 'Games Board',
]) . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Games Boards'), 'url' => ['index']];
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

        <div class="games-board-view">


            <?= Html::a(Yii::t('modelattr', 'Manage'), ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Create'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
            'confirm' => Yii::t('modelattr', 'Are you sure you want to delete this item?'),
            'method' => 'post',
            ],
            ]) ?>

            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'id',
            'c_id',
            'termin_id',
            'member_id',
            'court_id',
            'slot_id',
            'status_id',
            'fines',
            'tokens',
            'late',
            ],
            ]) ?>
        </div>

        <?php Panel::end() ?> 
    </div>
</div>


