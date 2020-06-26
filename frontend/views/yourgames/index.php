<?php

use kartik\grid\GridView;
use kartik\detail\DetailView;
use common\helpers\GridviewHelper;
use yii\helpers\ArrayHelper;
use common\dictionaries\OutcomeStatus;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\GamesboardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = GridviewHelper::getTitle($context_array);
$currentBtn = GridviewHelper::getCurrentBtn($context_array);


//dd($this->title);

$redcross = '<i class="text-danger fas fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fas fa-check fa-lg" aria-hidden="true"></i>';

$no_days = \backend\models\GamesBoard::getDaystonextgame();
$pendingGamesNo = \backend\models\Members::getPendingGamesNo();
//dd($pendingGamesNo);
?>

<div class="games-board-index">


    <?php
    if ($pendingGamesNo > 0) {
        $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'          => 'ID',
                'attribute'      => 'id',
                'contentOptions' => ['style' => 'width:20px;'],
                'visible'        => Yii::$app->user->can('team_member'),
            ],
            [
                'attribute'           => 'termin_id',
                'label'               => Yii::t('modelattr', 'Date'),
                'contentOptions'      => ['style' => 'width:150px;'],
                'value'               => 'termin.termin_date',
                'filterType'          => GridView::FILTER_SELECT2,
                'filter'              => ArrayHelper::map(backend\models\PlayDates::find()
                                ->select(['termin_id', 'termin_date'])
                                ->where(['c_id' => Yii::$app->session->get('c_id')])
                                ->orderBy(['termin_id' => SORT_DESC])
                                ->all(), 'termin_id', 'termin_date'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true]
                ],
                'filterInputOptions'  => ['placeholder' => '', 'id' => 'grid-board-search-ID_termin'],
            ],
            [
                'attribute'      => 'member_id',
                'label'          => Yii::t('modelattr', 'Member'),
                'value'          => 'member.name',
                'contentOptions' => ['style' => 'width:150px;'],
            ],
            'court_id',
            'slot_id',
            [
                'attribute'      => 'status_id',
                'label'          => Yii::t('modelattr', 'Status'),
                'contentOptions' => ['style' => 'width:100px;'],
                'value'          => function($model) {
                    return isset($model->status_id) ? OutcomeStatus::get($model->status_id) : null;
                },
            ],
            //        'fines',
            [
                'attribute' => 'tokens',
                'hAlign'    => GridView::ALIGN_CENTER,
                'format'    => 'raw',
                'value'     => function($model)use ($redcross, $greencheck) {
                    if ($model->tokens == 1) {
                        return $greencheck;
                    } else {
                        return $redcross;
                    }
                },
            ],
            [
                'attribute' => 'late',
                'hAlign'    => GridView::ALIGN_CENTER,
                'format'    => 'raw',
                'value'     => function($model)use ($redcross, $greencheck) {
                    if ($model->late == 1) {
                        return $greencheck;
                    } else {
                        return $redcross;
                    }
                },
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons'  => [
                    'delete' => function ($url) {
                        return Html::a('<i class="fas fa-trash-alt"></i>', Url::to($url), [
                                    'class'        => 'btn-outline-secondary btn-style',
                                    'title'        => 'take your name of the rota',
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to remove your name from the rota?'),
                                    'data-method'  => 'post'
                        ]);
                    },
                ],
                'visibleButtons' =>
                [
                    'delete' => function ($model) {
                        $no_days = $model->getDaystonextgame($model->termin->termin_date);

                        return $no_days > 2 ? true : false;
                    },
                ]
            ],
        ];
        $header = GridviewHelper::getHeader($context_array);

        //    $gridColumn[] = GridviewHelper::getActionColumn(
        //        '{delete}',
        //        $currentBtn);


        $lefttoolbar = [Yii::t('modelattr', 'List of your pending games')];


        // right toolbar + custom buttons
        //    $toolbar[] = [
        //    'content' =>
        //         GridviewHelper::getNewbutton($currentBtn) . ' ' .
        //         GridviewHelper::getResetgrida($currentBtn)
        //    ];
        $toolbar[] = '{export}';
        $toolbar[] = '{toggleData}';

        echo GridView::widget([
            'dataProvider'        => $dataProvider,
            'columns'             => $gridColumn,
            'id'                  => 'gridview-club-id',
            'tableOptions'        => ['class' => 'table table-responsive'],
            'responsive'          => true,
            'responsiveWrap' => false,
            'condensed' => false,
            'panelBeforeTemplate' => GridviewHelper::getPanelBefore(),
            'panel'               => [
                'type'    => Gridview::TYPE_PRIMARY,
                'heading' => $header,
            ],
            'toolbar'             => $toolbar,
            'itemLabelSingle'     => Yii::t('modelattr', 'record'),
            'itemLabelPlural'     => Yii::t('modelattr', 'records'),
            'replaceTags'         => [
                '{lefttoolbar}' => join(' ', $lefttoolbar)
            ],
                ]
        );
    } else {
        echo '<h5>' . Yii::t('app', 'No Games Scheduled!') . '</h5>';
    }
    //    dd($member_model->getMemberStats());
    echo DetailView::widget([
        'model'      => $member_model,
        'hover'      => true,
        'mode'       => DetailView::MODE_VIEW,
        'panel'      => [
            'heading' => Yii::t('modelattr', 'Your Stats - Current Season'),
            'type'    => DetailView::TYPE_PRIMARY,
        ],
        'buttons1'   => '',
        'attributes' => [
            [
                'attribute' => 'scheduled_stats',
                'format'=>'raw',
                'value'     => function($model)use ($member_model) {
                    return $member_model->getMemberStats();
                }
            ],
            [
                'attribute' => 'played_stats',
                'value'     => function($model)use ($member_model) {
                    return $member_model->getMemberStats(['status_id' => 1]);
                },
            ],
            [
                'attribute' => 'token_stats',
                'value'     => function($model)use ($member_model) {
                    return $member_model->getMemberStats(['tokens' => true]);
                },
            ],
            [
                'attribute' => 'coaching_stats',
                'value'     => function($model)use ($member_model) {
                    return $member_model->getCoachingStats();
                },
            ],
            [
                'attribute' => 'cancelled_stats',
                'value'     => function($model)use ($member_model) {
                    return $member_model->getMemberStats(['status_id' => 5]);
                },
            ],
            [
                'attribute' => 'noshow_stats',
                'format'    => 'raw',
                'value'     => function($model)use ($member_model) {
                    return $member_model->getMemberStats(['status_id' => [3, 7]]) > 0 ? '<div class="text-danger">' . $member_model->getMemberStats(['status_id' => [3, 7]]) . '</div>' : $member_model->getMemberStats(['status_id' => [3, 7]]);
                },
            ],
        ]
    ]);
    ?>

    <div class="info-box">
        <span class="info-box-icon bg-info"><i class="far fa-bookmark"></i></span>
        <div class="info-box-content">
            <span class="info-box-text"><?= Yii::t('modelattr', 'Scheduled') ?></span>
            <span class="info-box-number"><?= $member_model->getMemberStats() ?></span>
        </div>
    </div>

</div>
