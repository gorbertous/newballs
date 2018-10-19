<?php

use kartik\grid\GridView;
use common\helpers\GridviewHelper;
use yii\helpers\ArrayHelper;
use backend\models\Members;
use yii\widgets\Pjax;

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);


$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';

?>

<div class="mandants-index">

    <?php 
    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label'          => 'ID',
            'attribute'      => 'member_id',
            'contentOptions' => ['style' => 'width:20px;'],
        ],
        [
            'label'          => 'UID',
            'attribute'      => 'user_id',
            'contentOptions' => ['style' => 'width:20px;'],
        ],
        [
            'attribute'           => 'member_id',
            'label'               => Yii::t('modelattr', 'Name'),
            'value'               => 'fullName',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(Members::find()
                ->select(['member_id', 'firstname', 'lastname'])
                ->all(), 'member_id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-members-search-member_id'],
        ],
        [
            'attribute'           => 'c_id',
            'label'               => Yii::t('modelattr', 'Club'),
            'value'               => 'club.name',
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
            'attribute'           => 'mem_type_id',
            'label'               => Yii::t('modelattr', 'Type'),
            'value'               => 'memType.nameFB',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(\backend\models\MembershipType::find()->all(), 'mem_type_id', 'nameFB'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-memtype-search-member_id'],
        ],
        [
            'attribute'           => 'nationality',
            'label'               => Yii::t('modelattr', 'Nationality'),
            'value'               => function($model) {
                    return $model->nationalitytranslated;
            },
            'contentOptions' => ['style' => 'width:90px;'],
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(backend\models\Countries::find()
                ->innerJoinWith('membersNat')
                ->all(), 'code', 'textFB'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-nat-search-ID_Clubs'],
        ],
        [
            'attribute' => 'is_active',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->is_active == 1) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
        [
            'attribute' => 'is_admin',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->is_admin == 1) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
        [
            'attribute' => 'has_paid',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->has_paid == 1) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
        [
            'attribute' => 'is_organiser',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->is_organiser == 1) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
        [
            'attribute' => 'is_visible',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->is_visible == 1) {
                    return $greencheck;
                } else {
                    return $redcross;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
        'created_at:datetime'
        
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
