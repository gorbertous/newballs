<?php

use kartik\detail\DetailView;
use common\helpers\Helpers;

/**
 * @var yii\web\View $this
 * @var backend\models\Log $model
 */

$this->title = 'ID : '.$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-view">
  
    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title,
            'type' => DetailView::TYPE_PRIMARY,
        ],
        'attributes' => [
            'level',
            'category',
            'log_time',
            'prefix:ntext',
            'message:ntext',
        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model->id],
        ],
        'enableEditMode' => false,
    ]) ?>

 <?php echo Helpers::getModalFooter($model,  null, null, [
        'buttons' => ['cancel']
    ]); ?>

    <div class="clearfix"></div>
</div>
