<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Sourcemessagescan;
use common\helpers\TraitIndex;

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);

?>
<div class="messagescannew-index">

    <?php
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

//     $gridColumn[] = TraitIndex::getActionColumn(
//        '{view}{update}{delete}' ,
//        $currentBtn);
            
    $gridParams = [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        // use default panelbefortemplate
        'panelBeforeTemplate' => null,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            ['content' => 
                TraitIndex::getResetgrida($currentBtn)
            ],
        ],
        'exportdataProvider' => $dataProvider,
        'exportcolumns' => $gridColumn
    ];  
    
    TraitIndex::echoGridView(
            $gridParams,
            $context_array,
            $currentBtn
    );
?>

</div>




