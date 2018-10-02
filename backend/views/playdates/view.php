<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model backend\models\PlayDates */

$this->title = Yii::t('modelattr', 'View {modelClass}', [
    'modelClass' => 'Play Dates',
]) . ' #' . $model->termin_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Play Dates'), 'url' => ['index']];
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

        <div class="play-dates-view">


            <?= Html::a(Yii::t('modelattr', 'Manage'), ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Create'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Update'), ['update', 'id' => $model->termin_id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Delete'), ['delete', 'id' => $model->termin_id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
            'confirm' => Yii::t('modelattr', 'Are you sure you want to delete this item?'),
            'method' => 'post',
            ],
            ]) ?>

            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'termin_id',
            'c_id',
            'location_id',
            'termin_date',
            'active',
            'season_id',
            'session_id',
            'courts_no',
            'slots_no',
            'created_by',
            'updated_by',
            ],
            ]) ?>
        </div>

        <?php Panel::end() ?> 
    </div>
</div>


