<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Contacts;
use common\helpers\TraitIndex;
use kartik\grid\GridView;

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);

?>

<div class="user-index">

    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'format' => 'raw',
            'hAlign' => GridView::ALIGN_CENTER,
            'value'  => function ($model) {
                if (!empty($model->contact->ID_Contact)) {
                    return Html::button('<i class="fa fa-user"></i>', ['value' => Url::toRoute(['workers/view', 'id' => $model->contact->ID_Contact]),
                                                                       'class' => 'showModalButton btn btn-default',
                                                                       'title' => Yii::t('appMenu', 'View member')]);
                } else {
                    return '';
                }
            }
        ],
        [
            'attribute'           => 'ID_Contact',
            'label'               => Yii::t('modelattr', 'Name'),
            'encodeLabel'         => false,
            'value'               => 'contact.FullName',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(Contacts::find()
                ->select(['Contacts.ID_Contact', 'Firstname', 'Lastname'])
                ->innerJoinWith('user')
                ->where(['Contacts.ID_Mandant' => Yii::$app->session->get('mandant_id')])
                ->andWhere(['Contacts.CW_Type' => 'W'])
                ->all(), 'ID_Contact', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-medvisits-search-ID_Contact'],
        ],
        [
            'attribute'   => 'username',
            'label'       => Yii::t('modelattr', 'Username'),
            'encodeLabel' => false,
            'value'       => 'username',
        ],
        // status
        [
            'attribute' => 'status',
            'label'     => Yii::t('modelattr', 'Status'),
            'format'    => 'raw',

            'filter'     => [-2 => Yii::t('modelattr', 'Any')] + $searchModel->statusList,
            'filterType' => GridView::FILTER_SELECT2,
            'value'      => function ($data) {
                if ($data->statusName == Yii::t('modelattr', 'Active')) {
                    return '<span class="bg-success">&nbsp;' .
                        $data->statusName .
                        '&nbsp;</span>';
                } else {
                    return '<span class="bg-danger">&nbsp;' .
                        $data->statusName .
                        '&nbsp;</span>';
                }
            },
        ],
        // role
        [
            'attribute'   => 'item_name',
            'label'       => Yii::t('modelattr', 'Role'),
            'encodeLabel' => false,
            'filter'      => ['' => Yii::t('modelattr', 'Any')] + $searchModel->rolesList,
            'filterType'  => GridView::FILTER_SELECT2,
            'value'       => function ($data) {
                return $data->roleName;
            },
//            'contentOptions' => function($model, $key, $index, $column) {
//                return ['class' => CssHelper::roleCss($model->roleName)];
//            }
        ]

    ];

    $gridColumn[] = TraitIndex::getActionColumn(
        '{view}{update}{delete}{passresetemail}',
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



