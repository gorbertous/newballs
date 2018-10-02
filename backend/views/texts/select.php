<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

?>

<div class="texts-select">

    <?php yii\widgets\Pjax::begin(['id' => 'pjax-refresh-selects']); ?>

    <div class="well">
    <?php
        $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'          => Yii::t('app', 'Code'),
                'attribute'      => 'Code',
                'encodeLabel'    => false,
                'contentOptions' => ['style' => 'width:20px;']
            ],
            [
                'label'       => Yii::t('app', 'Text'),
                'encodeLabel' => false,
                'attribute'   => 'Text_EN',
                'format'      => 'raw',

                'value' => function ($model) {
                    return $model->Text_EN;
                }
            ]
        ];

        echo GridView::widget([
            'id'           => 'company-select',
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => $gridColumn,
            'layout'       => "{items}\n{pager}",
        ]);
    ?>
    </div>

    <div class="clearfix"></div>

    <div class="form-group pull-right">
        <?php if ($dataProvider->count > 0) { ?>
            <?= Html::a('<span class="fa fa-upload fa-rotate-180"></span>&nbsp;' .
                    Yii::t('app', 'Import all'), Url::toRoute(Yii::$app->controller->id.'/fromlibrary/'.implode('-',$dataProvider->getKeys())), [
                                'class' => 'btn btn-success']) ?>
        <?php } ?>

        <?= Html::Button('<span class="fa fa-times"></span>&nbsp;' .
                Yii::t('app', 'Cancel'), ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) ?>
    </div>

    <?php yii\widgets\Pjax::end(); ?>

    <div class="clearfix"></div>
</div>
