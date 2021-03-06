<?php

use kartik\grid\GridView;
use kartik\widgets\Select2;
//use yii\widgets\Pjax;
use common\helpers\GridviewHelper;
use yii\helpers\ArrayHelper;
use common\dictionaries\OutcomeStatus;
use common\helpers\ViewsHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\GamesboardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

$redcross = '<i class="text-danger fas fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fas fa-check fa-lg" aria-hidden="true"></i>';

$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);

if ($searchModel->timefilter == 1) {
    $rotatitle = '<h3>' . Yii::t('modelattr', 'Future Games');
} elseif ($searchModel->timefilter == 2) {
    $rotatitle = '<h3>' . Yii::t('modelattr', 'Past Games');
} else {
    $rotatitle = '<h3>' . Yii::t('modelattr', 'Entire Rota');
}
?>

<div class="games-board-index">
    <div class="panel-group" id="accordion">
        <div class="card" style="width: 100%;">     
            <div class="card-header">
            <h5 class="panel-title">
              <a class="card-link" data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?= Yii::t('app', 'Rota Search')?>&nbsp;&nbsp;<i class="right fas fa-angle-down"></i></a>
            </h5>
          </div>
          <div id="collapse1" class="panel-collapse">
              <div class="card-body">
                    <div class="search-form">
                        <?=  $this->render('_search', ['model' => $searchModel]); ?>
                    </div>
              </div>
          </div>
        </div>
    </div>
    <div class="card-body">
        <?php if ($searchModel->timefilter == 2): ?>
            <?=Html::beginForm(['gamesboard/bulk'],'post');?>
            <div class="row">     
                <div class="col-3">
                    <?= Select2::widget([
                        'name' => 'status_id',
                        'data' => OutcomeStatus::all(),
                        'options' => [
                            'placeholder' => '',
                        ],
                    ])?>
                </div>
                 <div class="col-3">
                    <?=Html::submitButton('Status Updates', ['class' => 'btn btn-primary',]);?>
                </div>
            </div>
        <?php elseif ($searchModel->timefilter == 1 && Yii::$app->user->can('team_member')): ?>
            <?=Html::beginForm(['gamesboard/sendemailreminder'],'post');?>
            <div class="row">     
                <div class="col-6">
                    <?= Html::hiddenInput('sendemail', true)?>
                    <?= Html::submitButton(Yii::t('app', 'Send Email Reminder'), ['class' => 'btn btn-primary',]);?>
                </div>
            </div>
            <?= Html::endForm();?> 
        <?php endif ?>
    </div>
    
    <?php 
//    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'contentOptions' => ['style' => 'width:10px;'],
        ],
        [
            'class' => 'yii\grid\SerialColumn',
            'contentOptions' => ['style' => 'width:10px;'],
        ],

        [
            'label'          => 'ID',
            'attribute'      => 'id',
            'contentOptions' => ['style' => 'width:20px;'],
            'visible' => Yii::$app->user->can('team_member'),
        ],
        [
            'attribute'           => 'termin_id',
            'label'               => Yii::t('modelattr', 'Date'),
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
            
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ViewsHelper::getMembersList(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-board-search-ID_member'],
        ],
//        'court_id',
        [
            'attribute' => 'court_id',
            'label' => Yii::t('app', 'Court No'),
            'encodeLabel' => false,
            'headerOptions' => ['style'=>'text-align:center'],
           
            'contentOptions' => function ($model, $key, $index, $column) {
                return ['style' => 'width:10px; background-color:' 
                    . (($model->court_id % 2) == 0
                        ? '#B3C7DC' : '#FFC2BB')];
            },
        ],
        [
            'attribute' => 'slot_id',
            'label' => Yii::t('app', 'Slot No'),
            'encodeLabel' => false,
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
                        break;
                }
                return ['style' => 'width:10px; background-color:' 
                    . $bg_color];
            },
        ],
//        'slot_id',
        [
            'attribute'           => 'status_id',
            'label'               => Yii::t('modelattr', 'Status'),
            'contentOptions' => ['style' => 'width:100px;'],
            'value' => function($model) {
                return isset($model->status_id) ? OutcomeStatus::get($model->status_id) : null;
            },
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => OutcomeStatus::all(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-board-search-ID_status'],
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
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
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
        ],
        [
            'attribute' => 'coaching',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->coaching == 1) {
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
        ],
        
        
    ];
    $header = GridviewHelper::getHeader($context_array);
    $gridColumn[] = GridviewHelper::getActionColumn(
        '{update}{delete}',
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
                'tableOptions' => ['class' => 'table table-responsive'],
                'responsive'          => true,
                'responsiveWrap' => false,
                'condensed' => false,
                'panelBeforeTemplate' => GridviewHelper::getPanelBefore(),
                'panel' => [
                    'type'    => Gridview::TYPE_PRIMARY,
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
 <?php if ($searchModel->timefilter  == 2): ?>
    <?= Html::endForm();?> 
 <?php endif ?>
    
</div>
