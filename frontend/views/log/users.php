<?php

use common\helpers\GridviewHelper;
use kartik\grid\GridView;
//use yii\widgets\Pjax;

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

?>
<div class="log-users-index">

    <?php
    //    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

    	[
            'attribute' => 'date',
            'format'    => 'raw',
            'value'     => function ($model) {
                return Yii::$app->formatter->format($model['date'], 'datetime') . '<br />' .
                        '<strong>' . Yii::$app->formatter->asRelativeTime($model['date']) . '</strong>';
            }
        ],
        'userId',
        [
            'attribute'           => 'member_id',
            'label'               => Yii::t('modelattr', 'Name'),
            'value'               => 'fullname',
        ],

        [
            'attribute' => 'name',
            'label' => Yii::t('appMenu', 'Club')
        ],
        [
            'attribute' => 'userAgent',
            'format'    => 'raw',
            'value'     => function ($model) {
                return "<span data-toggle='tooltip' title='" . $model['userAgent'] . "'>" . substr($model['userAgent'], 0, 20) . "...</span>";
            }
        ],
        [
            'attribute' => 'cookieBased',
            'value'     => function ($model) {
                return Yii::$app->formatter->asBoolean($model['cookieBased']);
            }
        ],
        [
            'attribute'           => 'ip',
            'label'               => Yii::t('modelattr', 'IP'),
            'value' => function($model) {
                
                $ip = Yii::$app->geoip->ip($model['ip']);
                return $model['ip'] . ' (' . $ip->city . ' - ' . $ip->country . ')';
            }
        ],
    ];
   
    $header = GridviewHelper::getHeader($context_array);
//    $gridColumn[] = GridviewHelper::getActionColumn(
//        '{view}{delete}',
//        $currentBtn);
    
    $lefttoolbar = GridviewHelper::getLefttoolbar($context_array, $currentBtn);
    
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
                'columns'        => $gridColumn,
                'id' => 'gridview-usrlog-id',
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


