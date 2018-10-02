<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Sourcemessage;
use common\helpers\TraitIndex;

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);

// javascript to update the master server
// DON'T FORGET TO CHANGE ALSO THE CODE IN _FORM.PHP
$route = Url::toRoute('message/sync');
$script = <<< JS
$('#syncmessage').on('click', function(e) {
    $.ajax({
        type     :'POST',
        cache    : false,
        url  : '$route',
        success  : function(response) {
            if (response.startsWith('SUCCESS')) {
                $('#syncmessagespan').text('');
            }
            alert(response);
        }
    });
});
JS;
$this->registerJs($script);
?>
<div class="messagedupes-index">

    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'value' => function ($model, $key, $index, $column) {
                if (isset($model->sourceMessage->sourceMessageScan) &&
                    $model->sourceMessage->sourceMessageScan->loccount > 0) {
                        return GridView::ROW_COLLAPSED ;
                } else {
                    return '';
                }
            },
            'detailUrl' => Url::toRoute('locations')
        ],

        [
            'label' => Yii::t('modelattr', 'Category'),
            'encodeLabel' => false,
            'attribute' => 'category',
            'value' => function ($model) {
                return $model->sourceMessage->category;
            },
            'width' => '120px;',
            'filterType' => GridView::FILTER_SELECT2, 
            'filterWidgetOptions' => [ 
                'pluginOptions' => ['allowClear' => true], 
            ], 
            'filterInputOptions' => ['placeholder' => '', 'id' => 'grid-category-search-id'],
            'filter' => ArrayHelper::map(Sourcemessage::find()
                                ->groupBy(['category'])
                                ->all(), 'category', 'category'), 
        ],
        [
            'label' => Yii::t('app', 'Language'),
            'encodeLabel' => false,
            'attribute' => 'language',
            'hAlign' => GridView::ALIGN_CENTER,
            'format' => 'raw',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => [ 
                -1 => Yii::t('app', 'All'),
                'en' => Yii::t('app', 'English'),
                'fr' => Yii::t('app', 'French'),
                'de' => Yii::t('app', 'German')],
            'width' => '70px;',
        ],
        [
            'label' => Yii::t('appMenu', 'Source'),
            'encodeLabel' => false,
            'attribute' => 'sourceMessage',
            'value' => function ($model) {
                return $model->sourceMessage->message;
            }
        ],
        [
            'label' => Yii::t('appMenu', 'Translation'),
            'encodeLabel' => false,
            'attribute' => 'translation',
        ],
        [
            'label' => 'ID',
            'encodeLabel' => false,
            'attribute' => 'id',
            'format' => 'raw',
            'width' => '30px;',
            
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}{update}{delete}{copy}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::button('<i class="fa fa-eye"></i>', ['value' => Url::to(['message/view','id' => $model->id,'id2' => $model->language]),
                        'class' => 'showModalButton btn btn-default',
                        'transtitle' => Yii::t('appMenu', 'View translation'),
                        'title' => Yii::t('appMenu', 'View translation')]);
                },
                'update' => function ($url, $model) {
                    return Html::button('<i class="fa fa-pencil"></i>', ['value' => Url::to(['message/update','id' => $model->id,'id2' => $model->language]),
                        'class' => 'showModalButton btn btn-default',
                        'transtitle' => Yii::t('appMenu', 'Modify translation'),
                        'title' => Yii::t('appMenu', 'Modify translation')]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<i class="fa fa-trash"></i>', 
                            Url::to(['message/delete','id' => $model->id, 'id2' => $model->language]), 
                            ['class' => 'btn btn-default',
                            'title' => Yii::t('appMenu', 'Delete translation'),
                            'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?').
                            PHP_EOL.'cat: '.$model->sourceMessage->category.
                            PHP_EOL.'msg: '.$model->sourceMessage->message,
                            'data-method' => 'post']);
                },
                'copy' => function ($url, $model) {
                    return Html::a('<i class="fa fa-clone"></i>', 
                            Url::to(['message/copy','id' => $model->id]),
                            ['class' => 'btn btn-default',
                            'title' => Yii::t('appMenu', 'Copy translation')]);
                }
               
            ]
        ]
    ];

//     $gridColumn[] = TraitIndex::getActionColumn(
//        '{view}{update}{delete}' ,
//        $currentBtn);
            
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
                TraitIndex::getResetgrida($currentBtn) . ' ' .
                TraitIndex::getLangsyncbutton($pendinguploads)
            ],
        ],
        'exportdataProvider' => $dataProvider,
        'exportcolumns' => $gridColumn
    ];
    
    TraitIndex::echoGridView(
            $gridParams,
            $context_array,
            $currentBtn
    );
?>

</div>




