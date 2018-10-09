<?php

use kartik\grid\GridView;
use common\helpers\TraitIndex;
use yii\helpers\ArrayHelper;
use backend\models\Members;

$this->title = TraitIndex::getTitle($context_array);
$currentBtn = TraitIndex::getCurrentBtn($context_array);

//dd($context_array);


$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';

?>

<div class="membership-index">

    <?php $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'label'          => Yii::t('modelattr', 'Photo'),
            'format'         => 'raw',
            'contentOptions' => ['style' => 'width:90px;'],
            'value'          => function ($model) {
                $gravatar = isset($model->user->email) ? $model->getGravatar($model->user->email) : null;
                return !empty($model->photo) ? $model->getIconPreviewAsHtml('ajaxfileinputPhoto', 60) : $gravatar;
            }
        ],
        [
            'attribute'           => 'member_id',
            'label'               => Yii::t('modelattr', 'Name'),
            'format'         => 'raw',
            'value'               => function($model) {
                $mobile = empty($model->phone_mobile) ? $model->phone : $model->phone_mobile;
                $ischair = $model->is_organiser ? ' <span class="badge bg-red pull-right">Club Chairman</span>' : '';
                $iswebmaster = $model->user_id == 1 ? ' <span class="badge bg-orange pull-right">Webmaster</span>' : '';
                $iscommemb = $model->is_admin? ' <span class="badge bg-green pull-right">Committee Member</span>' : '';
                $iscoach = isset($model->memType) && ($model->memType->mem_type_id == 5) ? ' <span class="badge bg-blue pull-right">Coach</span>' : '';
                return $model->name.'<br>'.$model->user->email.'<br>'.$mobile . $ischair . $iswebmaster . $iscommemb . $iscoach;
            },
//            'contentOptions' => function ($model, $key, $index, $column) {
//                return ['style' => 'background-color:' 
//                    . (!empty($model->coefTK_se) && $model->coefTK / $model->coefTK_se < 2
//                        ? 'red' : 'blue')];
//            },
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(Members::find()
                ->all(), 'member_id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-members-search-member_id'],
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
            'attribute' => 'coaching',
            'hAlign'    => GridView::ALIGN_CENTER,
            'format'    => 'raw',
            'value'     => function($model)use ($redcross, $greencheck) {
                if ($model->coaching == 1) {
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
       
    ];


    $gridParams = [
        'dataProvider'        => $dataProvider,
        'filterModel'         => $searchModel,
        'columns'             => $gridColumn,
        // use default panelbefortemplate
        'panelBeforeTemplate' => null,
        // your toolbar can include the additional full export menu
        'toolbar'             => [
            ['content' =>
                 TraitIndex::getNewbutton($currentBtn) 
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