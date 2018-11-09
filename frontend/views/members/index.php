<?php

use kartik\grid\GridView;
use common\helpers\GridviewHelper;
use yii\helpers\ArrayHelper;
//use backend\models\Members;
//use yii\widgets\Pjax;
use common\helpers\ViewsHelper;

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';

?>

<div class="members-index">

<?php 
//    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
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
                $name = empty($model->firstname) ? '<span class="badge bg-red">Missing name - update this profile!</span>' : $model->name;
                $mobile = empty($model->phone_mobile) ? $model->phone : $model->phone_mobile;
                $email = isset($model->user) ? $model->user->email : '';
                $ischair = $model->is_organiser ? ' <span class="badge bg-red pull-right">Club Chairman</span>' : '';
                $iswebmaster = $model->user_id == 1 ? ' <span class="badge bg-orange pull-right">Webmaster</span>' : '';
                $iscommemb = $model->is_admin? ' <span class="badge bg-green pull-right">Committee Member</span>' : '';
                $iscoach = isset($model->memType) && ($model->memType->mem_type_id == 5) ? ' <span class="badge bg-blue pull-right">Coach</span>' : '';
                return $name .'<br>'. $email .'<br>'.$mobile . $ischair . $iswebmaster . $iscommemb . $iscoach;
            },
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ViewsHelper::getMembersList(),
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
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions'=>[
                'header'=>'Is Active',
                'formOptions'=>['action' => ['/members/editmember']], // point to the new action        
                'inputType'=>\kartik\editable\Editable::INPUT_SELECT2,
                'asPopover' => true,
                'options' => [
                    'data' => [
                        0  => Yii::t('modelattr', 'No'),
                        1  => Yii::t('modelattr', 'Yes')],
                    'pluginOptions' => []
                ]
            ],
            'value'     => function($model) {
                return $model->is_active ? Yii::t('modelattr', 'Yes') : Yii::t('modelattr', 'No');
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
        [
            'attribute' => 'has_paid',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions'=>[
                'header'=>'Has Paid',
                'formOptions'=>['action' => ['/members/editmember']], // point to the new action        
                'inputType'=>\kartik\editable\Editable::INPUT_SELECT2,
                'asPopover' => true,
                'options' => [
                    'data' => [
                        0  => Yii::t('modelattr', 'No'),
                        1  => Yii::t('modelattr', 'Yes')],
                    'pluginOptions' => []
                ]
            ],
            'value'     => function($model) {
                return $model->has_paid ? Yii::t('modelattr', 'Yes') : Yii::t('modelattr', 'No');
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
        [
            'attribute' => 'is_visible',
            'class' => 'kartik\grid\EditableColumn',
            'editableOptions'=>[
                'header'=>'Is Visible',
                'formOptions'=>['action' => ['/members/editmember']], // point to the new action        
                'inputType'=>\kartik\editable\Editable::INPUT_SELECT2,
                'asPopover' => true,
                'options' => [
                    'data' => [
                        0  => Yii::t('modelattr', 'No'),
                        1  => Yii::t('modelattr', 'Yes')],
                    'pluginOptions' => []
                ]
            ],
            'value'     => function($model) {
                return $model->is_visible ? Yii::t('modelattr', 'Yes') : Yii::t('modelattr', 'No');
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
//        [
//            'attribute' => 'is_visible',
//            'hAlign'    => GridView::ALIGN_CENTER,
//            'format'    => 'raw',
//            'value'     => function($model)use ($redcross, $greencheck) {
//                if ($model->is_visible == 1) {
//                    return $greencheck;
//                } else {
//                    return $redcross;
//                }
//            },
//            'filterType' => GridView::FILTER_SELECT2,
//            'filter'     => [-1 => Yii::t('modelattr', 'All'),
//                0  => Yii::t('modelattr', 'No'),
//                1  => Yii::t('modelattr', 'Yes')],
//            'width'      => '100px;',
//        ],
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
            'visible' => Yii::$app->user->can('team_member'),
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
            'visible' => Yii::$app->user->can('team_member'),
            'filterType' => GridView::FILTER_SELECT2,
            'filter'     => [-1 => Yii::t('modelattr', 'All'),
                0  => Yii::t('modelattr', 'No'),
                1  => Yii::t('modelattr', 'Yes')],
            'width'      => '100px;',
        ],
        
        [
            'attribute' => 'created_at',
            'label'     => Yii::t('app', 'Member Since'),
            'value'     => function($model){
                 return Yii::$app->formatter->asDate($model->created_at);
            }
        ], 
        
    ];

    $header = GridviewHelper::getHeader($context_array);
    $gridColumn[] = Yii::$app->user->can('team_member') ? 
        GridviewHelper::getActionColumn(
        '{view}{update}{delete}', $currentBtn) :
        GridviewHelper::getActionColumn('{view}{update}',  $currentBtn);

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
//    Pjax::end();
 ?>
    
</div>
