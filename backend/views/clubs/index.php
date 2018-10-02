<?php

use kartik\grid\GridView;
use common\helpers\TraitIndex;


$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);


$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';

?>

<div class="clubs-index">

    <?php $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'label'          => Yii::t('modelattr', 'Logo'),
            'format'         => 'raw',
            'contentOptions' => ['style' => 'width:100px;'],
            'value'          => function ($model) {
                /* @var $model \backend\models\Mandants */
                return $model->getIconPreviewAsHtml('ajaxfileinputLogo', 90);
            }
        ],
        [
            'label'          => 'ID',
            'attribute'      => 'c_id',
            'contentOptions' => ['style' => 'width:20px;'],
        ],
       'name',
        
    ];

    $gridColumn[] = TraitIndex::getActionColumn(
        '{view}{update}{delete}',
        $currentBtn);


    $gridParams = [
        'dataProvider'        => $dataProvider,
        'filterModel'         => $searchModel,
        'columns'             => $gridColumn,
        // use default panelbefortemplate
        'panelBeforeTemplate' => null,
        // your toolbar can include the additional full export menu
        'toolbar'             => [
            ['content' =>
                 TraitIndex::getNewbutton($currentBtn) . ' ' .
                 TraitIndex::getResetgrida($currentBtn)
            ],
        ],
        'exportdataProvider'  => $dataProvider,
        'exportcolumns'       => $gridColumn
    ];

    TraitIndex::echoGridView(
        $gridParams,
        $context_array,
        $currentBtn
    );
    ?>

</div>
