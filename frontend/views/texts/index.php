<?php

use common\helpers\TraitIndex;
use backend\models\Texts;

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);

?>

<div class="texts-index">

    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'code',
        ],
        [
            'attribute' => Texts::ContLangFieldName('text'),
            'contentOptions' => ['style' => 'width:200px;'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->textFB;
            }
        ]
    ];


    $gridColumn[] = TraitIndex::getActionColumn(
            '{view}{update}{delete}', 
            $currentBtn);

    $gridParams = [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        // use default panelbefortemplate
        'panelBeforeTemplate' => null,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            ['content' =>
                TraitIndex::getNewbutton($currentBtn) . ' ' .
                TraitIndex::getResetgrida($currentBtn)
            ],
        ],
        'exportdataProvider' => $dataProvider,
        'exportcolumns' => $gridColumn
    ];

    //set CW_Type filtering to true
    TraitIndex::echoGridView(
            $gridParams, 
            $context_array, 
            $currentBtn
    );
    ?>
</div>

