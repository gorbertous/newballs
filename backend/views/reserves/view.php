<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Reserves */

$this->title = Yii::t('modelattr', 'View {modelClass}', [
    'modelClass' => 'Reserves',
]) . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Reserves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="row">
    <div class="col-md-12">

        <div class="reserves-view">



            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'id',
            'member_id',
            'termin_id',
            'c_id',
            ],
            ]) ?>
        </div>

        <?php Panel::end() ?> 
    </div>
</div>


