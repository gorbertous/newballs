<?php

use kartik\grid\GridView;
use yii\widgets\DetailView;

?> 

<h1>Inventory Item</h1>

<?php
$gridColumn = [
    [
        'attribute' => 'registeritem.registerplus.Brand_name',
        'label'     => Yii::t('modelattr', 'Brand name'),
    ],
    [
        'attribute' => 'registeritem.registerplus.Product_name',
        'label'     => Yii::t('modelattr', 'Product name'),
    ],
    [
        'attribute' => 'registeritem.registerplus.Qty_buy',
        'label'     => Yii::t('modelattr', 'Qty bought'),
    ],
    [
        'attribute' => 'registeritem.registerplus.Qty_stock',
        'label'     => Yii::t('modelattr', 'Qty in stock'),
    ],
    [
        'attribute' => 'registeritem.registerplus.iMonths',
        'label'     => Yii::t('modelattr', 'Interval (months)'),
    ],
    [
        'attribute' => 'registeritem.registerplus.Standard',
        'label'     => Yii::t('modelattr', 'Standard'),
    ],
    [
        'attribute' => 'registeritem.registerplus.Temperature',
        'label'     => Yii::t('modelattr', 'Temperature range'),
    ],
    [
        'attribute' => 'registeritem.registerplus.Material',
        'label'     => Yii::t('modelattr', 'Material'),
    ],
//        [
//            'label'  => Yii::t('app', 'File'),
//            'format' => 'raw',
//            'value'  => function ($model) {
//                return $model->registeritem->registerplus->getIconPreviewAsHtml('ajaxfileinputFile', 90);
//            }
//        ],
    'registeritem.Serial_number',
    'registeritem.Unit_price',
    'registeritem.Size',
    [
        'attribute' => 'registeritem.Date_fab',
        'format'    => ['date', 'dd/MM/yyyy']
    ],
    [
        'attribute' => 'registeritem.Date_exp',
        'format'    => ['date', 'dd/MM/yyyy']
    ]
];

$gridRegisterassigns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'member.Name',
        'label'     => Yii::t('modelattr', 'Worker'),
//        'visible'   => in_array($context_array['CW_type'], [StockCwTypes::Ppe, StockCwTypes::Transport, StockCwTypes::Workeq])
    ],
    [
        'attribute' => 'compartment.levelDescription',
        'label'     => Yii::t('modelattr', 'Compartment'),
//        'visible'   => in_array($context_array['CW_type'], [StockCwTypes::Techinst, StockCwTypes::Fireeq])
    ],
    [
        'label'     => Yii::t('modelattr', 'Assignment date'),
        'attribute' => 'Date_usage',
        'format'    => ['date', 'dd/MM/yyyy']
    ],
    [
        'attribute' => 'Qty_assign',
        'label'     => Yii::t('modelattr', 'Assignment qty'),
    ],
    [
        'label'     => Yii::t('modelattr', 'Return date'),
        'attribute' => 'Date_return',
        'format'    => ['date', 'dd/MM/yyyy']
    ],
    [
        'attribute' => 'Qty_return',
        'label'     => Yii::t('modelattr', 'Return qty'),
    ],
    [
        'attribute' => 'Qty_disc',
        'label'     => Yii::t('modelattr', 'Discarded qty'),
    ],
//    [
//        'attribute' => 'PDF_File',
//        'label'     => Yii::t('app', 'File'),
//        'format'    => 'raw',
//        'value'     => function($model) {
//            return $model->getIconPreviewAsHtml('ajaxfileinputFile', 40);
//        }
//    ]
];

$gridRegisterinspects = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'ID_Stockinspecttype',
        'format'    => 'raw',
        'label'     => Yii::t('modelattr', 'Intervention type'),
        'value'     => function($model) {
            return $model->stockinspecttype->nameFB;
        },
    ],
    [
        'attribute' => 'Inspect_result',
        'label'     => Yii::t('modelattr', 'Intervention result'),
    ],
    [
        'label'     => Yii::t('modelattr', 'Intervention requested'),
        'attribute' => 'Requestdate',
        'format'    => ['date', 'dd/MM/yyyy']
    ],
    [
        'label'     => Yii::t('modelattr', 'Intervention date'),
        'attribute' => 'Appointdate',
        'format'    => ['date', 'dd/MM/yyyy']
    ],
    [
        'label'  => Yii::t('modelattr', 'Closed'),
        'format' => 'raw',
        'value'  => function($model) {
            return $model->Passed ? Yii::t('app', '<span class="label label-success">Yes</span>') : Yii::t('app', '<span class="label label-danger">No</span>');
        }
    ],
    [
        'label'     => Yii::t('modelattr', 'Date of next intervention'),
        'attribute' => 'Nextdate',
        'format'    => ['date', 'dd/MM/yyyy']
    ],
    [
        'attribute' => 'contactW.Name',
        'label'     => Yii::t('modelattr', 'Internal intervention by'),
    ],
    [
        'attribute' => 'contactS.companyContact',
        'label'     => Yii::t('modelattr', 'External intervention by'),
    ],
    [
        'attribute' => 'iMonths',
        'label'     => Yii::t('modelattr', 'Interval (months)'),
    ],
//    [
//        'attribute' => 'PDF_File',
//        'label'     => Yii::t('modelattr', 'File'),
//        'format'    => 'raw',
//        'value'     => function($model) {
//            return $model->getIconPreviewAsHtml('ajaxfileinputFile', 90);
//        }
//    ]
];
?>

<div id="header">
    <img class="mandant-img" src="<?= $model->registeritem->mandant->getThumbnailUrl($model->registeritem->mandant->JPG_Logo, [200, 200]); ?>" >

    <br /><br />
    <h1><?= $model->registeritem->registerplus->Brand_name; ?></h1>
</div>

<div class="height"></div>
<div class="height"></div>
<br /><br />

<div class="text">

    <?=
    DetailView::widget([
        'model'      => $model,
        'attributes' => $gridColumn
    ]);
    ?>
</div>

<pagebreak />


<div id="header">
    <h1>• <?= Yii::t('appMenu', 'Assignments'); ?></h1>
    <span class="header"><?= $model->registeritem->mandant->Name; ?></span>
</div>

<div class="height"></div>

<?=
GridView::widget([
    'dataProvider'    => $modelRegisterassigns,
    'layout'          => '{items}',
    'columns'         => $gridRegisterassigns,
    'showPageSummary' => false
]);
?>

<pagebreak />


<div id="header">
    <h1>• <?= Yii::t('appMenu', 'Interventions'); ?></h1>
    <span class="header"><?= $model->registeritem->mandant->Name; ?> </span>
</div>

<div class="height"></div>
<?=
GridView::widget([
    'dataProvider'    => $modelRegisterinspects,
    'layout'          => '{items}',
    'columns'         => $gridRegisterinspects,
    'showPageSummary' => false
]);
?>
