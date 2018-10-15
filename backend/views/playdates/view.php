<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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


        <div class="play-dates-view">



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


