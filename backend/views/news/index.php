<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\dictionaries\NewsCategories;
use common\helpers\GridviewHelper;
use backend\models\News;
use kartik\grid\GridView;
use backend\models\Clubs;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';

?>
<div class="news-index">

    <?php
    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'id',
            'label' => 'ID',
            'contentOptions' => ['style' => 'width: 20px;'],
        ],
        [
            'attribute'           => 'c_id',
            'label'               => Yii::t('modelattr', 'Club'),
            'value'               => 'club.name',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(Clubs::find()
                ->select(['c_id', 'name'])
                ->all(), 'c_id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-users-search-Clubs'],
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

   $header = GridviewHelper::getHeader($context_array);
    $gridColumn[] = GridviewHelper::getActionColumn(
        '{view}{update}{delete}',
        $currentBtn);
    
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
