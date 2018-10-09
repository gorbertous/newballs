<?php

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RotaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\grid\GridView;
use common\dictionaries\OutcomeStatus;
use yii\helpers\Url;
use common\helpers\TraitIndex;

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);

$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);

$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';
?>
<div class="rota-index">
    <p>
        <?= Html::a(Yii::t('app', 'Advanced Search'), '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn', 
            'contentOptions' => ['style' => 'width:20px;'],
        ],
//      [
//            'class' => 'kartik\grid\ExpandRowColumn',
//            'width' => '50px',
//            'value' => function ($model, $key, $index, $column) {
//                return GridView::ROW_COLLAPSED;
//            },
//            'detail' => function ($model, $key, $index, $column) {
//                return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
//            },
//            'headerOptions' => ['class' => 'kartik-sheet-style'],
//            'expandOneOnly' => true
//        ],
//        ['attribute' => 'id', 'visible' => false],
        
        [
             'attribute' => 'termin_id',
             
             'value' => function($model){  
                $dispdate = Yii::$app->formatter->asDate($model->termin->termin_date);
                $disptime = Yii::$app->formatter->asTime($model->termin->termin_date, 'short');
                return 'Date : '. $dispdate . ' at '. $disptime . '   - Location: '. $model->termin->location->address;                   
             },
             'group' => true,
             'groupedRow' => true,
             'groupOddCssClass' => 'kv-grouped-row',
             'groupEvenCssClass' => 'kv-grouped-row'
         ],
         [
             'attribute' => 'court_id',
             'value' => function($model){                   
                 return 'Court No : '. $model->court_id;                   
             },
             'group' => true,
             'groupedRow' => true,
             'groupOddCssClass' => 'kv-grouped-row',
             'groupEvenCssClass' => 'kv-grouped-row'
         ],
         [
             'attribute' => 'member_id',
             'label' => Yii::t('app', 'Member'),
             'format' => 'raw',
             'value' => function($model){
                if($model->member_id == 1){
                    $url = Url::toRoute(['rota/update', 'id' => $model->id]);
                    $link = Html::a($model->member->name, $url, 
                        [
                            'title' => Yii::t('app', 'Click the link to put your name on the rota'),
                            'class' => 'text-success',
                            'data' => [
                                'confirm' => Yii::t('app', 'Warning, clicking on the link you are commiting yourself to play on ' . $model->termin->termin_date),
                                'method' => 'post',
                            ],
                        ]);
                    return $link;
                } else {
                    $class = $model->tokens ? 'text-danger' : 'text-primary';
                    $iscoach = isset($model->member->memType) && ($model->member->memType->mem_type_id == 5) ? ' <span class="badge bg-red pull-right">Coach</span>' : '';
                    return Html::tag('span', $model->member->name, ['class' => $class]).$iscoach;
                }
             },
             'filterType' => GridView::FILTER_SELECT2,
             'filter' => \yii\helpers\ArrayHelper::map(\backend\models\Members::find()
                     ->where(['c_id' => Yii::$app->session->get('c_id')])
                     ->all(), 'member_id', 'name'),
             'filterWidgetOptions' => [
                 'pluginOptions' => ['allowClear' => true],
             ],
             'filterInputOptions' => ['placeholder' => 'Select Member', 'id' => 'grid-rota-search-member_id'],
             'enableSorting' => false,
         ],
        
        [
            'attribute' => 'slot_id',
            'label' => Yii::t('app', 'Slot No'),
            'enableSorting' => false,
        ],
        [
            'attribute' => 'tokens',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->tokens == 1) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
            'enableSorting' => false,
        ],
        [
            'attribute' => 'late',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->late == 1) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
            'enableSorting' => false,
        ],
        [
            'attribute'           => 'status_id',
            'label'               => Yii::t('modelattr', 'Status'),
            'value' => function($model) {
                return isset($model->status_id) ? OutcomeStatus::get($model->status_id) : null;
            },
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => OutcomeStatus::all(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-board-search-ID_status'],
            'enableSorting' => false,
        ],
        
    ]; 
//    
//   $gridColumn[] = TraitIndex::getActionColumn(
//        '{view}',
//        $currentBtn);


    $gridParams = [
        'dataProvider'        => $dataProvider,
        'filterModel'         => $searchModel,
        'columns'             => $gridColumn,
        // use default panelbefortemplate
        'panelBeforeTemplate' => null,
        // your toolbar can include the additional full export menu
        'toolbar'             => [
            ['content' =>
                 
                 TraitIndex::getResetgrida($currentBtn)
            ],
        ],
        'exportdataProvider'  => $dataProvider,
        'exportcolumns'       => $gridColumn
    ];

    TraitIndex::echoGridView(
        $gridParams,
        $context_array,
        $currentBtn
    );
    ?>
</div>
