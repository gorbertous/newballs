<?php

use common\helpers\TraitIndex;

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);

?>

<div class="authitem-index">

    <?php
    $gridColumn = [
       ['class' => 'yii\grid\SerialColumn'],

    	'name',
        'description:ntext'
    ];
   
    $gridColumn[] = TraitIndex::getActionColumn(
        '{update}{delete}' ,
        $currentBtn
    );

    $gridParams = [
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $gridColumn,

        // use default panelbefortemplate
        'panelBeforeTemplate' => null,

        // your toolbar can include the additional full export menu
        'toolbar' => [
            [
                'content' => TraitIndex::getNewbutton($currentBtn) . ' 
                         ' . TraitIndex::getResetgrida($currentBtn)
            ]
        ],

        'exportdataProvider' => $dataProvider,
        'exportcolumns'      => $gridColumn
    ];
    
    TraitIndex::echoGridView($gridParams, $context_array, $currentBtn);
    ?>

</div>


