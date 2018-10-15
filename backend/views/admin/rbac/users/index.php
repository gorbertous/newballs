<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Members;
use common\helpers\GridviewHelper;
use yii\widgets\Pjax;

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

?>

<div class="user-index">

    <?php
    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'format' => 'raw',
            'hAlign' => GridView::ALIGN_CENTER,
            'value'  => function ($model) {
                if (!empty($model->member->member_id)) {
                    return Html::button('<i class="fa fa-user"></i>', ['value' => Url::toRoute(['members/view', 'id' => $model->member->member_id]),
                                                                       'class' => 'showModalButton btn btn-default',
                                                                       'title' => Yii::t('appMenu', 'View member')]);
                } else {
                    return '';
                }
            }
        ],
        [
            'label'          => 'ID',
            'attribute'      => 'id',
            'contentOptions' => ['style' => 'width:20px;'],
        ],
        [
            'attribute'           => 'c_id',
            'label'               => Yii::t('modelattr', 'Club'),
            'value'               => 'member.club.name',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(Members::find()
                ->select(['clubs.c_id', 'clubs.name'])
                ->innerJoinWith('club')
                ->all(), 'c_id', 'club.name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-users-search-ID_Clubs'],
        ],
        [
            'attribute'           => 'member_id',
            'label'               => Yii::t('modelattr', 'Name'),
            'value'               => 'member.fullName',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(Members::find()
                ->select(['members.member_id', 'firstname', 'lastname'])
                ->innerJoinWith('user')
                ->all(), 'member_id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-users-search-member_id'],
        ],
        [
            'attribute' => 'username',
            'label'     => Yii::t('modelattr', 'Username'),

            'value' => 'username',
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
            }
        ],
        // role
        [
            'attribute' => 'item_name',
            'label'     => Yii::t('modelattr', 'Role'),

            'filter'     => ['' => Yii::t('modelattr', 'Any')] + $searchModel->rolesList,
            'filterType' => GridView::FILTER_SELECT2,
            'value'      => function ($data) {
                return $data->roleName;
            },
//            'contentOptions' => function($model, $key, $index, $column) {
//                return ['class' => CssHelper::roleCss($model->roleName)];
//            }
        ]

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
                'dataProvider'   => $dataProvider,
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
    Pjax::end();
 ?>
    
</div>



