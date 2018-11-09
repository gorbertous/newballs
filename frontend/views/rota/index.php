<?php

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RotaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\grid\GridView;
use common\dictionaries\OutcomeStatus;
use yii\helpers\Url;
use common\helpers\GridviewHelper;
//use yii\widgets\Pjax;

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);

$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';
if(!Yii::$app->session->get('member_has_paid')){
    echo Yii::$app->session->setFlash('danger', Yii::t('app', 'Unfortunatelly the club has not yet received your membership payment, currently you cannot book the games, please settle this or contact the club chairman!') );
}
if ($searchModel->timefilter == 1) {
    $rotatitle = '<h3>' . Yii::t('modelattr', 'Future Games');
} elseif ($searchModel->timefilter == 2) {
    $rotatitle = '<h3>' . Yii::t('modelattr', 'Past Games');
} else {
    $rotatitle = '<h3>' . Yii::t('modelattr', 'Entire Rota');
}
?>
<div class="rota-index">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?= Yii::t('app', 'Rota Search')?>&nbsp;&nbsp;<span class="caret" style="border-width: 5px;"></span></a>
            </h4>
          </div>
          <div id="collapse1" class="panel-collapse collapse">
              <div class="panel-body">
                    <div class="search-form">
                        <?=  $this->render('_search', ['model' => $searchModel]); ?>
                    </div>
              </div>
          </div>
        </div>
    </div>
   
    <?php
//    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
//        ['class' => 'yii\grid\SerialColumn', 
//            'contentOptions' => ['style' => 'width:20px;'],
//        ],
        [
             'attribute' => 'termin_id',
             'format' => 'raw',
             'value' => function($model){  
                $dispdate = Yii::$app->formatter->asDate($model->termin->termin_date);
                $disptime = Yii::$app->formatter->asTime($model->termin->termin_date, 'short');
                
                $url = Url::toRoute(['reserves/insert', 'id' => $model->termin_id]);
                $link = Html::a('click here to put your name on the reserves list!', $url, 
                [
                    'title' => Yii::t('app', 'add your name on the reserves list'),
                    'class' => 'text-success',
                    'data' => [
                        'confirm' => Yii::t('app', 'Warning, the reserve list operates on the first comes first served basis, in case a slot becomes available, the club admin will put your name on the rota '),
                        'method' => 'post',
                    ],
                ]);
                if(isset($model->termin->reserves)){
                    $list = [];
                    foreach ($model->termin->reserves as $reserves) {
                        $name = $reserves->member->name;
                        if (!in_array($name, $list)) {
                            array_push($list, $name);
                        }
                    }
                    $reserves_list = join('<br>', $list);
                }else{
                    $reserves_list = '';
                }
                $final_list = !empty($reserves_list) ? 'Current Reserves List:<br>'. $reserves_list: '';
                
                $slots_notification = $model->getSlotsLeft($model->termin_id) == 0 ? 'All the slots are taken - '.$link : '<small>'.$model->getSlotsLeft($model->termin_id).' Slots Left <small>';
                return $slots_notification.'<h4>'. $dispdate . ' at '. $disptime . '   - Location: '. $model->termin->location->address. '</h4>'.$final_list;                   
             },
             'group' => true,
             'groupedRow' => true,
             'groupOddCssClass' => 'kv-grouped-row',
             'groupEvenCssClass' => 'kv-grouped-row'
         ],
         [
             'attribute' => 'court_id',
             'value' => function($model){
                $url = Url::toRoute(['rota/bookcourt', 'id' => $model->termin_id, 'id2' => $model->court_id]);
                $link = Html::a('Court not yet booked!', $url, 
                [
                    'title' => Yii::t('app', 'book a court'),
                    'class' => 'text-success',
                    'data' => [
                        'confirm' => Yii::t('app', 'You are confirming that you have booked this court!'),
                        'method' => 'post',
                    ],
                ]);
                 $booked = $model->isCourtBooked($model->termin_id, $model->court_id);
                 $booked_by = !empty($booked) ? 'Court booked by '. $booked->bookedBy->name  : $link;
                 //show court booking link
                $show_booking_link = Yii::$app->session->get('club_court_booking')? $booked_by : '';
                 return  '<h4>Court No : '. $model->court_id . '</h4>' . $show_booking_link;                   
             },
             'format' => 'raw',
             //'label' => Yii::t('app', 'Court No'),
             'group' => true,
             'subGroupOf'=>1,
             'groupedRow' => true,
             'groupOddCssClass' => 'kv-group-even',
             'groupEvenCssClass' => 'kv-group-even'
         ],
         [
            'attribute' => 'slot_id',
            'label' => Yii::t('app', 'Slot No'),
            'encodeLabel' => false,
            'format'    => 'raw',
            'headerOptions' => ['style'=>'text-align:center'],
            'contentOptions' => function ($model, $key, $index, $column) {
                 switch($model->slot_id) {
                    case 1:
                        $bg_color = '#B3C7DC';
                        break;
                    case 2:
                        $bg_color = '#668EB9';
                        break;
                    case 3:
                        $bg_color = '#FFC2BB';
                        break;
                    case 4:
                        $bg_color = '#FF8883';
                        break;
                }
                return ['style' => 'background-color:' 
                    . $bg_color];
            },
            'value'     => function($model) {
               
                    return '<strong>'. $model->slot_id . '</strong>';
                
            },
            'enableSorting' => false,
            'width'      => '50px;',
        ],
         [
             'attribute' => 'member_id',
             'label' => Yii::t('app', 'Member'),
             'format' => 'raw',
             'value' => function($model){
                if($model->member_id == 1){
                    $url = Url::toRoute(['rota/insert', 'id' => $model->id]);
                    $link = Html::a($model->member->name, $url, 
                        [
                            'title' => Yii::t('app', 'Click the link to put your name on the rota'),
                            'class' => 'text-success',
                            'data' => [
                                'confirm' => Yii::t('app', 'Warning, clicking on the link you are commiting yourself to play on ' . $model->termin->termin_date),
                                'method' => 'post',
                            ],
                        ]);
                    return Yii::$app->session->get('member_has_paid') ? '<strong>'. $link . '</strong>': '<strong>'.$model->member->name . '</strong>';
                } else {
                    $class = $model->tokens ? 'text-danger' : 'text-primary';
                    $iscoach = isset($model->member->memType) && ($model->member->memType->mem_type_id == 5) ? ' <span class="badge bg-red pull-right">Coach</span>' : '';
                    return Html::tag('strong', $model->member->name, ['class' => $class]).$iscoach;
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

    $header = GridviewHelper::getHeader($context_array);
    if(Yii::$app->user->can('writer')){
        $gridColumn[] = GridviewHelper::getActionColumn(
            '{update}',
            $currentBtn);
    }
    
//special case for rota - to replace buttons
    $lefttoolbar = [$rotatitle];
    
    // right toolbar + custom buttons
    $toolbar[] = [
    'content' =>
//         GridviewHelper::getNewbutton($currentBtn) . ' ' .
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
//    Pjax::end();
 ?>
    
</div>
