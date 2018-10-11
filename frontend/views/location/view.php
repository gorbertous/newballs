<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model backend\models\Location */

$this->title = Yii::t('modelattr', 'View {modelClass}', [
    'modelClass' => 'Location',
]) . ' #' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('modelattr', 'Locations'), 'url' => ['index']];
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

        <div class="location-view">


            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
            'name',
            'address',
            'phone',
            'co_code',
            'google_par_one',
            'google_par_two',
            ],
            ]) ?>
        </div>

        <?php Panel::end() ?> 
    </div>
</div>


