<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model backend\models\Scores */

$this->title = Yii::t('modelattr', 'View {modelClass}', [
    'modelClass' => 'Scores',
]) . ' #' . $model->score_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Scores'), 'url' => ['index']];
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

        <div class="scores-view">


            <?= Html::a(Yii::t('modelattr', 'Manage'), ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Create'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Update'), ['update', 'id' => $model->score_id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a(Yii::t('modelattr', 'Delete'), ['delete', 'id' => $model->score_id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
            'confirm' => Yii::t('modelattr', 'Are you sure you want to delete this item?'),
            'method' => 'post',
            ],
            ]) ?>

            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'score_id',
            'termin_id',
            'court_id',
            'set_one',
            'set_two',
            'set_three',
            'set_four',
            'set_five',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            ],
            ]) ?>
        </div>

        <?php Panel::end() ?> 
    </div>
</div>


