<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\dictionaries\NewsCategories;
use common\helpers\GridviewHelper;
use backend\models\News;
use kartik\grid\GridView;
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
        'created_at:datetime',

    ];

    $header = GridviewHelper::getHeader($context_array);
    $gridColumn[] = Yii::$app->user->can('team_member') ? GridviewHelper::getActionColumn(
        '{view}{update}{delete}', $currentBtn) :
        GridviewHelper::getActionColumn('{view}', $currentBtn);
    
    $lefttoolbar = GridviewHelper::getLefttoolbar($context_array, $currentBtn);
    
    // right toolbar + custom buttons
    $toolbar[] = [
        'content' => Yii::$app->user->can('writer') ? 
            GridviewHelper::getNewbutton($currentBtn) . ' ' .
            GridviewHelper::getResetgrida($currentBtn)
            :
            GridviewHelper::getResetgrida($currentBtn)
    ];
    $toolbar[] = '{export}';
    $toolbar[] = '{toggleData}';
    
    echo GridView::widget([
                'dataProvider'   => $dataProvider,
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
