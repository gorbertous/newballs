<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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

      

        <div class="games-board-view">



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


