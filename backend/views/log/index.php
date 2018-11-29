<?php

use common\helpers\GridviewHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

?>
<div class="log-index">
    <?=Html::beginForm(['log/bulk'],'post');?>
    <div class="row">     
        <div class="col-xs-6">
            <?= Html::hiddenInput('bulkdelete', true)?>
            <?= Html::submitButton('Delete Logs', ['class' => 'btn btn-info',]);?>
        </div>
    </div>

    <?php
    //    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
       ['class' => 'yii\grid\CheckboxColumn'],
       ['class' => 'yii\grid\SerialColumn'],
    	'id',
        'level',
        'category',
        
        [
            'attribute'           => 'prefix',
            'label'               => Yii::t('modelattr', 'IP'),
            'value' => function($model) {
                $pos = strpos($model->prefix, ']');
                $ipaddr = substr($model->prefix,1,$pos - 1);
                $ip = Yii::$app->geoip->ip($ipaddr);
                return $ipaddr . ' (' . $ip->city . ' - ' . $ip->country . ')';
            }
        ],
        'log_time:datetime'
    ];
   
    $header = GridviewHelper::getHeader($context_array);
    $gridColumn[] = GridviewHelper::getActionColumn(
        '{view}{delete}',
        $currentBtn);
    
    $lefttoolbar = GridviewHelper::getLefttoolbar($context_array, $currentBtn);
    
    // right toolbar + custom buttons
    $toolbar[] = [
    'content' =>
//         GridviewHelper::getNewbutton($currentBtn) . ' ' .
         GridviewHelper::getResetgrida($currentBtn)
    ];
    $toolbar[] = GridviewHelper::getExportMenu($dataProvider, $gridColumn);
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
 <?= Html::endForm();?>   
</div>


