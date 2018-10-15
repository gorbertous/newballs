<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MembershipType */

$this->title = Yii::t('modelattr', 'View {modelClass}', [
    'modelClass' => 'Membership Type',
]) . ' #' . $model->mem_type_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Membership Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="row">
    <div class="col-md-12">


        <div class="membership-type-view">


            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'mem_type_id',
            'c_id',
            'name_EN',
            'name_FR',
            'name_DE',
            'fee',
            ],
            ]) ?>
        </div>

         
    </div>
</div>


