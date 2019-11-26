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
if(!Yii::$app->session->get('member_has_paid') && Yii::$app->session->get('member_type_id') == 1){
    echo Yii::$app->session->setFlash('danger', Yii::t('app', 'Unfortunatelly the club has not yet received your membership payment, currently you cannot book the games, please settle this or contact the club chairman!') );
}elseif(Yii::$app->session->get('member_type_id') == 4){
    echo Yii::$app->session->setFlash('danger', Yii::t('app', 'You are a guest member, you cannot book the games, please contact the club admin to put your name down on the rota!') );
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
                $link = Html::a(Yii::t('app', 'click here to put your name on the reserves list!'), $url, 
                [
                    'title' => Yii::t('app', 'add your name on the reserves list'),
                    'class' => 'text-success',
                    'data' => [
                        'confirm' => Yii::t('app', 'Warning, the reserve list operates on the first comes first served basis, in case a slot becomes available, the club admin will put your name on the rota'),
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
                $final_list = !empty($reserves_list) ? Yii::t('app', 'Current Reserves List').':<br>'. $reserves_list: '';
                
                $slots_notification = $model->getSlotsLeft($model->termin_id) == 0 ? Yii::t('app', 'All the slots are taken').' - '.$link : '<small> ('.$model->getSlotsLeft($model->termin_id).' '. Yii::t('app', 'Slots Left').') </small>';
                return  $dispdate . ' at '. $disptime . '   <small>-  '.Yii::t('modelattr', 'Location').':'. $model->termin->location->address . '</small>' . $slots_notification. $final_list;                   
             },
             'group' => true,
             'groupedRow' => true,
             'groupOddCssClass' => 'kv-grouped-row',
             'groupEvenCssClass' => 'kv-grouped-row'
         ],
         [
             'attribute' => 'court_id',
             'value' => function($model){
                 
                $today = new DateTime();
                $play_date = new DateTime($model->termin->termin_date);
                
                if(!empty($model->getGameScore($model->termin_id, $model->court_id))){
                    $statsbutton = '<span class="custom-margin">' . Html::button(Yii::t('modelattr', 'View Score'), [
                        'value' => Url::toRoute(['playdates/scores', 'termin_id' => $model->termin_id, 'court_id' => $model->court_id]),
                        'class' => 'btn btn-info btn-style showModalButton',
                        'title' => Yii::t('modelattr', 'View Score')
                    ]).'</span>';
                }elseif(empty($model->getGameScore($model->termin_id, $model->court_id)) && $play_date < $today ){
                    $statsbutton = '<span class="custom-margin">' . Html::button(Yii::t('modelattr', 'Upload Score'), [
                        'value' => Url::toRoute(['playdates/uploadscore', 'termin_id' => $model->termin_id, 'court_id' => $model->court_id]),
                        'class' => 'btn btn-info btn-style showModalButton',
                        'title' => Yii::t('modelattr', 'Upload Score')
                    ]).'</span>';
                }else{
                    $statsbutton = '';
                }
                $url = Url::toRoute(['rota/bookcourt', 'id' => $model->termin_id, 'id2' => $model->court_id]);
                $link = Html::a(Yii::t('app', 'Court not yet booked!'), $url, 
                [
                    'title' => Yii::t('app', 'book a court'),
                    'class' => 'text-success',
                    'data' => [
                        'confirm' => Yii::t('app', 'You are confirming that you have booked this court!'),
                        'method' => 'post',
                    ],
                ]);
                
                $booked = $model->isCourtBooked($model->termin_id, $model->court_id);
                $booked_by = !empty($booked) ? Yii::t('app', 'Court booked by').' '. $booked->bookedBy->name  : $link;
                //show court booking link
                $show_booking_link = Yii::$app->session->get('club_court_booking')? $booked_by : '';
                return  '<strong>'.Yii::t('app', 'Court').' No : '. $model->court_id . '</strong>' . $show_booking_link  . $statsbutton;                   
             },
             'format' => 'raw',
             //'label' => Yii::t('app', 'Court No'),
             'group' => true,
             'subGroupOf'=>0,
             'groupedRow' => true,
             'groupOddCssClass' => 'kv-group-even',
             'groupEvenCssClass' => 'kv-group-even'
         ],
         [
            'attribute' => 'slot_id',
            'label' => Yii::t('app', 'Slot') . ' '.Yii::t('app', 'No'),
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
                    default:
                        $bg_color = '#FF8883';
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
             'label' => Yii::t('modelattr', 'Member'),
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
                    $iscoach = isset($model->member->memType) && ($model->member->memType->mem_type_id == 5) ? ' <span class="badge bg-red pull-right">'. $model->member->memType->nameFB . '</span>' : '';
                    $isguest = isset($model->member->memType) && ($model->member->memType->mem_type_id == 4) ? ' <span class="badge bg-info pull-right">'. $model->member->memType->nameFB . '</span>' : '';
                    return Html::tag('strong', $model->member->name, ['class' => $class]).$iscoach.$isguest;
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
        
//        [
//            'attribute' => 'member.memType.nameFB',
//            'label' => Yii::t('app', 'Type'),
//        ],
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
//            'visible' => Yii::$app->user->can('writer')
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
//    $toolbar[] = GridviewHelper::getExportMenu($dataProvider, $gridColumn);
    
    echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
//                'options' => [
//                    'class' => 'YourCustomTableClass',
//                 ],
                'export'=>[
                    'showConfirmAlert'=>false,
                    'target'=>GridView::TARGET_SELF,
                    'fontawsome' => true
                ],
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
