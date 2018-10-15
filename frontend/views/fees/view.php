<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Fees */

$this->title = Yii::t('modelattr', 'View {modelClass}', [
    'modelClass' => 'Fees',
]) . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Fees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="row">
    <div class="col-md-12">


        <div class="fees-view">


            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
         
            'mem_type_id',
            'mem_fee',
            ],
            ]) ?>
        </div>

         
    </div>
</div>


