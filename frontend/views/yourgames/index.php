<?php

use kartik\grid\GridView;
use common\helpers\GridviewHelper;
use yii\helpers\ArrayHelper;
use common\dictionaries\OutcomeStatus;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\GamesboardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

//dd($this->title);

$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';

$no_days = \backend\models\GamesBoard::getDaystonextgame();

?>

<div class="games-board-index">
  
    
    <?php 
//    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
       
        ['class' => 'yii\grid\SerialColumn'],

        [
            'label'          => 'ID',
            'attribute'      => 'id',
            'contentOptions' => ['style' => 'width:20px;'],
            'visible' => Yii::$app->user->can('team_member'),
        ],
        [
            'attribute'           => 'termin_id',
            'label'               => Yii::t('modelattr', 'Date'),
            'contentOptions' => ['style' => 'width:150px;'],
            'value'               => 'termin.termin_date',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(backend\models\PlayDates::find()
                ->select(['termin_id', 'termin_date'])
                ->where(['c_id' => Yii::$app->session->get('c_id')])
                ->orderBy(['termin_id' => SORT_DESC])
                ->all(), 'termin_id', 'termin_date'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-board-search-ID_termin'],
        ],
        [
            'attribute'           => 'member_id',
            'label'               => Yii::t('modelattr', 'Member'),
            'value'               => 'member.name',
            'contentOptions' => ['style' => 'width:150px;'],
            
        ],
        'court_id',
        'slot_id',
        [
            'attribute'           => 'status_id',
            'label'               => Yii::t('modelattr', 'Status'),
            'contentOptions' => ['style' => 'width:100px;'],
            'value' => function($model) {
                return isset($model->status_id) ? OutcomeStatus::get($model->status_id) : null;
            },
            
        ],
//        'fines',
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
           
        ],
        [
        'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url) {
                    return Html::a('<i class="fa fa-trash"></i>', Url::to($url), [
                                'class'        => 'btn btn-default btn-style',
                                'title'        => 'take your name of the rota' ,
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to remove your name from the rota?'),
                                'data-method'  => 'post'
                            ]);
                },
            ],
            'visibleButtons' =>
            [
                'delete' => function ($model) {
                    $no_days = $model->getDaystonextgame($model->termin->termin_date);
                    
                    return $no_days > 2 ? true : false;
                },
            ]
        ],
        
    ];
    $header = GridviewHelper::getHeader($context_array);
    
//    $gridColumn[] = GridviewHelper::getActionColumn(
//        '{delete}',
//        $currentBtn);
   
    
    $lefttoolbar = GridviewHelper::getLefttoolbar($context_array, $currentBtn);
    
    // right toolbar + custom buttons
//    $toolbar[] = [
//    'content' =>
//         GridviewHelper::getNewbutton($currentBtn) . ' ' .
//         GridviewHelper::getResetgrida($currentBtn)
//    ];
    $toolbar[] = '{export}';
    $toolbar[] = '{toggleData}';
    
    echo GridView::widget([
                'dataProvider' => $dataProvider,
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
//    Pjax::end();
 ?>
 
</div>
