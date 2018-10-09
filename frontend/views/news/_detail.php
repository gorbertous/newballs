<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use backend\models\News;

/* @var $this yii\web\View */
/* @var $model backend\models\GamesBoard */

?>
<div class="news-detail-view">

    <div class="row">
    <?php 
        $gridColumn = [
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => News::ContLangFieldName('content'),
                'contentOptions' => ['style' => 'min-width: 260px;'],
                'label' => Yii::t('modelattr', 'Content'),
                'format' => 'raw',
                'value' => function($model) {
                    /** @var $model backend\models\News */
                    return $model->contentFB;
                }
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]); 
    ?>
    </div>
</div>