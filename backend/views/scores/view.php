<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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

        <div class="scores-view">

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

         
    </div>
</div>


