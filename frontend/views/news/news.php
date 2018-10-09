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
<div class="news-news">

    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
//        [
//            'attribute' => 'id',
//            'label' => 'ID',
//            'contentOptions' => ['style' => 'width: 20px;'],
//        ],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],
        ['attribute' => 'id', 'visible' => false],
       
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
        'created_at:datetime'
        
    ];

//    $gridColumn[] = Yii::$app->user->can('team_member') ? TraitIndex::getActionColumn(
//        '{view}{update}{delete}', $currentBtn) :
//        TraitIndex::getActionColumn('{view}', $currentBtn);

    $gridParams = [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        // use default panelbefortemplate
        'panelBeforeTemplate' => null,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            Yii::$app->user->can('team_member') ? 
            ['content' =>
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