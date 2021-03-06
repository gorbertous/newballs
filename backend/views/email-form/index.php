<?php


use common\helpers\GridviewHelper;
use kartik\grid\GridView;
//use yii\widgets\Pjax;

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

?>
<div class="authitem-index">

    <?php
    //    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
       ['class' => 'yii\grid\SerialColumn'],

    	'id',
        'user_id',
        'setToEmail:email',
        //'setToName',
        'setFromEmail:email',
        //'setFromName',
        'subject',
        [
            'attribute' => 'textBody',
            'value' => function ($model) {
                return \yii\helpers\StringHelper::truncate($model->textBody, 25);
            }
        ],
        'status',
        'created_at',
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
    
</div>

