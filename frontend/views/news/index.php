<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\dictionaries\NewsCategories;
use common\helpers\TraitIndex;
use backend\models\News;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);

$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';

?>
<div class="news-index">

    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'id',
            'label' => 'ID',
            'contentOptions' => ['style' => 'width: 20px;'],
        ],
        [
            'attribute' => 'category',
            'value' => function($model) {
                return isset($model->category) ? NewsCategories::get($model->category) : null;
            },
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => NewsCategories::all(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-users-search-categories'],
        ],
        [
            'attribute' => News::ContLangFieldName('title'),
            'contentOptions' => ['style' => 'min-width: 150px;'],
            'format' => 'raw',
            'label' => Yii::t('app', 'Title'),
            'value' => function($model) {
                /** @var $model backend\models\News */
                $icons = $model->getIconPreviewAsHtml('ajaxfilefeatured', 90);
                return $model->titleFB . (!empty($icons) ? '<br>'.$icons :  '') . $model->isnewLabel;
            }
        ],
//        [
//            'attribute' => News::ContLangFieldName('content'),
//            'contentOptions' => ['style' => 'min-width: 260px;'],
//            'label' => Yii::t('modelattr', 'Content'),
//            'format' => 'raw',
//            'value' => function($model) {
//                /** @var $model backend\models\News */
//                return $model->contentFB;
//            }
//        ],

        [
            'attribute' => 'is_public',
            'visible' => Yii::$app->user->can('team_member'),
            'hAlign' => GridView::ALIGN_CENTER,
            'format' => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->is_public == true) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => [ -1 => Yii::t('modelattr', 'All'),
                          0 => Yii::t('modelattr', 'No'),
                          1 => Yii::t('modelattr', 'Yes')],
            'width' => '30px;',
        ],
        [
            'attribute' => 'is_valid',
            'visible' => Yii::$app->user->can('team_member'),
            'hAlign' => GridView::ALIGN_CENTER,
            'format' => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->is_valid == true) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => [ -1 => Yii::t('modelattr', 'All'),
                          0 => Yii::t('modelattr', 'No'),
                          1 => Yii::t('modelattr', 'Yes')],
            'width' => '30px;',
        ],
//        [
//            'attribute' => 'to_newsletter',
//            'visible' => Yii::$app->user->can('team_member'),
//            'hAlign' => GridView::ALIGN_CENTER,
//            'format' => 'raw',
//            'value'     => function($model)use ($redcross, $greencheck) {
//                if ($model->to_newsletter == true) {
//                    return $greencheck;
//                } else {
//                    return $redcross;
//                }
//            },
//            'filterType' => GridView::FILTER_SELECT2,
//            'filter' => [ -1 => Yii::t('modelattr', 'All'),
//                          0 => Yii::t('modelattr', 'No'),
//                          1 => Yii::t('modelattr', 'Yes')],
//            'width' => '30px;',
//        ]
    ];

    $gridColumn[] = Yii::$app->user->can('team_member') ? TraitIndex::getActionColumn(
        '{view}{update}{delete}', $currentBtn) :
        TraitIndex::getActionColumn('{view}', $currentBtn);

    $gridParams = [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        // use default panelbefortemplate
        'panelBeforeTemplate' => null,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            Yii::$app->user->can('team_member') ? ['content' =>
                                                       TraitIndex::getNewbutton($currentBtn) . ' ' .
                                                       TraitIndex::getResetgrida($currentBtn)
            ] :
                ['content' =>
                     TraitIndex::getResetgrida($currentBtn)
                ]
        ],
        'exportdataProvider' => $dataProvider,
        'exportcolumns' => $gridColumn
    ];

    //set CW_Type filtering to true
    /** @noinspection PhpUnhandledExceptionInspection */
    TraitIndex::echoGridView(
        $gridParams,
        $context_array,
        $currentBtn
    );
    ?>
</div>