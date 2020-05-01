<?php

use kartik\grid\GridView;
use common\helpers\GridviewHelper;
use yii\helpers\ArrayHelper;
use common\helpers\ViewsHelper;
use backend\models\ClubRoles;
//use yii\widgets\Pjax;


$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);

$redcross = '<i class="text-danger fa fa-times " aria-hidden="true">'.Yii::t('modelattr', 'No').'</i>';
$greencheck = '<i class="text-success fa fa-check " aria-hidden="true">'.Yii::t('modelattr', 'Yes').'</i>';
$club = \backend\models\Clubs::findOne(Yii::$app->session->get('c_id'));


?>
 
<div class="membership-index">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?= Yii::t('modelattr', 'Membership Payment Details')?>&nbsp;&nbsp;<span class="caret" style="border-width: 5px;"></span></a>
            </h4>
          </div>
          <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body"><?= $club->subscription_page?></div>
          </div>
        </div>
    </div>
    
    <div class="panel-group" id="accordion2">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordio2" href="#collapse2"><?= Yii::t('modelattr', 'Mailing List')?>&nbsp;&nbsp;<span class="caret" style="border-width: 5px;"></span></a>
            </h4>
          </div>
          <div id="collapse2" class="panel-collapse collapse">
              <div class="panel-body"><p style="word-break: break-all;"><?= \backend\models\Members::getMailingList()?></p></div>
          </div>
        </div>
    </div>
    
    <?php 
//    Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);
    $gridColumn = [
        [
            'class' => 'yii\grid\SerialColumn',
            'contentOptions' => ['style' => 'width: 10px;'],
        ],
        
        [
            'attribute'      => 'club_role',
            'label'          => Yii::t('modelattr', 'Role'),
            'contentOptions' => ['style' => 'width: 50px;'],
            'format'         => 'raw',
            'value'          => function($model) {
                $c_role = [];
                foreach ($model->memberRoles as $mem_role) {
                    $bcolor = ClubRoles::getRoleColor($mem_role->id);
                    $formated_string =  "<span class='$bcolor'>{$mem_role->role}</span>";
                    array_push($c_role, $formated_string);
                }
                return join('<br>', $c_role);
            },
        ],
        [
            'label'          => Yii::t('modelattr', 'Photo'),
            'contentOptions' => ['style' => 'width:80px;'],
            'format'         => 'raw',
            'value'          => function ($model) {
                $gravatar = isset($model->user->email) ? $model->getGravatar($model->user->email) : null;
                return !empty($model->photo) ? $model->getIconPreviewAsHtml('ajaxfileinputPhoto', 60) : $gravatar;
            }
        ],
        [
            'attribute'           => 'member_id',
            'label'               => Yii::t('modelattr', 'Name'),
            'contentOptions' => ['style' => 'width:140px;'],
            'format'         => 'raw',
            'value'               => function($model) {
                $mobile = empty($model->phone_mobile) ? $model->phone : $model->phone_mobile;
                $email = isset($model->user) ? $model->user->email : '';
                $iscoach = isset($model->memType) && ($model->memType->mem_type_id == 5) ? ' <span class="badge bg-blue pull-right">Coach</span>' : '';
                return $model->name .'<br>'. $email .'<br>'.$mobile . $iscoach;
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
            'contentOptions'      => ['style' => 'width:100px;'],
            'value'               => 'memType.nameFB',
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => ArrayHelper::map(\backend\models\MembershipType::find()->all(), 'mem_type_id', 'nameFB'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-memtype-search-member_id'],
        ],
        [
            'attribute' => 'grade_id',
            'label'          => Yii::t('modelattr', 'Level'),
            'contentOptions' => ['style' => 'width:90px;'],
            'format'         => 'raw',
            'value'          => function ($model) {
                return isset($model->grade_id) ? common\dictionaries\Grades::get($model->grade_id) : null;
            },
            'filterType'          => GridView::FILTER_SELECT2,
            'filter'              => common\dictionaries\Grades::all(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-nat-search-grades'],
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
        [
            'attribute' => 'created_at',
            'label'     => Yii::t('app', 'Member Since'),
            'value'     => function($model){
                 return Yii::$app->formatter->asDate($model->created_at);
            }
        ], 
    ];

    $header = GridviewHelper::getHeader($context_array);
    $lefttoolbar = GridviewHelper::getLefttoolbar($context_array, $currentBtn);
    
    // right toolbar + custom buttons
//    $toolbar[] = [
//    'content' =>
//         GridviewHelper::getNewbutton($currentBtn) . ' ' .
//         GridviewHelper::getResetgrida($currentBtn)
//    ];
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
// Pjax::end();
 ?>
    
</div>
