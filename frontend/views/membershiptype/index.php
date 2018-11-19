<?php

use kartik\grid\GridView;
use common\helpers\GridviewHelper;
//use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MembershipTypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);
?>

<div class="fees-index">

    <?php 
    //    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'label'          => 'ID',
            'attribute'      => 'mem_type_id',
            'contentOptions' => ['style' => 'width:20px;'],
        ],
       
        'name_EN',
        'name_FR',
        'name_DE',
        
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
    
</div>
