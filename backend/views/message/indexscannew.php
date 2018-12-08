<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Sourcemessagescan;
use common\helpers\GridviewHelper;
use yii\widgets\Pjax;

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

?>
<div class="messagescannew-index">

    <?php
    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'value' => function ($model, $key, $index, $column) {
                if ($model->loccount > 0) {
                        return GridView::ROW_COLLAPSED ;
                } else {
                    return '';
                }
            },
            'detailUrl' => Url::toRoute('locations')
        ],

        [
            'label' => Yii::t('modelattr', 'Category'),
            'encodeLabel' => false,
            'attribute' => 'category',
            'value' => function ($model) {
                return $model->category;
            },
            'width' => '120px;',
            'filterType' => GridView::FILTER_SELECT2, 
            'filterWidgetOptions' => [ 
                'pluginOptions' => ['allowClear' => true], 
            ], 
            'filterInputOptions' => ['placeholder' => '', 'id' => 'grid-category-search-id'],
            'filter' => ArrayHelper::map(Sourcemessagescan::find()
                                ->groupBy(['category'])
                                ->all(), 'category', 'category'), 
        ],
        [
            'attribute' => 'message',
        ],
        [
            'attribute' => 'loccount',
        ],

        [
            'attribute' => 'id',
            'width' => '30px;',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{create}{blacklist}',
            'buttons' => [
                'create' => function ($url, $model) use ($currentBtn) {
                    return Html::button('<i class="fa fa-plus"></i>', [
                        'value' => Url::toRoute($currentBtn['create'], ['id' => $model->id]),
                        'class' => 'showModalButton btn btn-success',
                        'title' => $currentBtn['new_label']]);
                },
                'blacklist' => function ($url, $model) use ($currentBtn) {
                    return Html::a('<i class="fa fa-thumbs-down"></i>', 
                        Url::toRoute($currentBtn['blacklist'] . '/' . $model->id),
                        ['class' => 'btn btn-default',
                        'title' => 'Blacklist item']);
                }
            ]
        ]
    ];

    $header = GridviewHelper::getHeader($context_array);
//    $gridColumn[] = GridviewHelper::getActionColumn(
//        '{view}{update}{delete}',
//        $currentBtn);
    
    $lefttoolbar = GridviewHelper::getLefttoolbar($context_array, $currentBtn);
    
    // right toolbar + custom buttons
    $toolbar[] = [
    'content' =>
         GridviewHelper::getNewbutton($currentBtn) . ' ' .
         GridviewHelper::getResetgrida($currentBtn)
    ];
    $toolbar[] = '{export}';
    $toolbar[] = '{toggleData}';
    
    echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns'        => $gridColumn,
                'id' => 'gridview-club-id',
                'responsive'          => true,
                'responsiveWrap' => true,
                'condensed' => true,
                'panelBeforeTemplate' => GridviewHelper::getPanelBefore(),
                'panel' => [
                    'type'    => Gridview::TYPE_DEFAULT,
                    'heading' => $header,
                ],
                'toolbar'             => $toolbar,
                'itemLabelSingle'     => Yii::t('modelattr', 'record'),
                'itemLabelPlural'     => Yii::t('modelattr', 'records'),
                'replaceTags' => [
                    '{lefttoolbar}' => join(' ', $lefttoolbar)
                ],
            ]
        );
    Pjax::end();
 ?>
    
</div>




