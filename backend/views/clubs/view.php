<?php

use common\helpers\Helpers;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Clubs */

$redcross = '<i class="text-danger fa fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fa fa-check fa-lg" aria-hidden="true"></i>';

$logo = (empty($model->logo) ? '' : $model->getThumbnailUrl($model->logo, [160, 120], false));

$gridColumn = [
    'season_id',
    //    [
    //         'label' => Yii::t('modelattr', 'About Page'),
    //        'value' => function($model) {
    //            return isset($model->season_id) ? \common\dictionaries\Sports::get($model->session_id) : null;
    //        },
    //    ],
    [
        'label' => Yii::t('modelattr', 'Club Chairman'),
        'value' => function($model) {
            return isset($model->chair) ? $model->chair->name : null;
        },
    ],
    [
        'label' => Yii::t('modelattr', 'Sport'),
        'value' => function($model) {
            return isset($model->sport_id) ? \common\dictionaries\Sports::get($model->sport_id) : null;
        },
    ],
    [
        'label' => Yii::t('modelattr', 'Games Average Duration'),
        'value' => function($model) {
            return isset($model->session_id) ? \common\dictionaries\ClubSessions::get($model->session_id) : null;
        },
    ],
    [
        'label'  => Yii::t('modelattr', 'About Page'),
        'format' => 'html',
        'value'  => $model->home_page,
    ],
    [
        'label'  => Yii::t('modelattr', 'Members Page'),
        'format' => 'html',
        'value'  => $model->members_page,
    ],
    [
        'label'  => Yii::t('modelattr', 'Rota Page'),
        'format' => 'html',
        'value'  => $model->rota_page,
    ],
    [
        'label'  => Yii::t('modelattr', 'Tournament Page'),
        'format' => 'html',
        'value'  => $model->tournament_page,
    ],
    [
        'label'  => Yii::t('modelattr', 'Summary Page'),
        'format' => 'html',
        'value'  => $model->summary_page,
    ],
    [
        'label'  => Yii::t('modelattr', 'Record Coaching Sessions'),
        'format' => 'html',
        'value'  => function($model)use ($redcross, $greencheck) {
            if ($model->coach_stats == 1) {
                return $greencheck;
            } else {
                return $redcross;
            }
        }
    ],
    [
        'label'  => Yii::t('modelattr', 'Balls/Tokens Responsibility count'),
        'format' => 'html',
        'value'  => function($model)use ($redcross, $greencheck) {
            if ($model->token_stats == 1) {
                return $greencheck;
            } else {
                return $redcross;
            }
        }
    ],
    [
        'label'  => Yii::t('modelattr', 'Record Player Games'),
        'format' => 'html',
        'value'  => function($model)use ($redcross, $greencheck) {
            if ($model->play_stats == 1) {
                return $greencheck;
            } else {
                return $redcross;
            }
        }
    ],
    [
        'label'  => Yii::t('modelattr', 'Score Uploading Facility'),
        'format' => 'html',
        'value'  => function($model)use ($redcross, $greencheck) {
            if ($model->scores == 1) {
                return $greencheck;
            } else {
                return $redcross;
            }
        }
    ],
    [
        'label'  => Yii::t('modelattr', 'Allow members to schedule games'),
        'format' => 'html',
        'value'  => function($model)use ($redcross, $greencheck) {
            if ($model->match_instigation == 1) {
                return $greencheck;
            } else {
                return $redcross;
            }
        }
    ],
    [
        'label'  => Yii::t('modelattr', 'Do you need to book courts'),
        'format' => 'html',
        'value'  => function($model)use ($redcross, $greencheck) {
            if ($model->court_booking == 1) {
                return $greencheck;
            } else {
                return $redcross;
            }
        }
    ],
    [
        'label'  => Yii::t('modelattr', 'Money Stats'),
        'format' => 'html',
        'value'  => function($model)use ($redcross, $greencheck) {
            if ($model->money_stats == 1) {
                return $greencheck;
            } else {
                return $redcross;
            }
        }
    ],
];
    
?>

<div class="clubs-view">
    <div class="row">
        <div class="col-md-6">
            <h4><?= $model->name ?></h4>
            <?= $model->location->fullAddress ?>
        </div>   

        <div class="col-md-6">              
            <?=
            !empty($model->logo) ? Html::img($logo, ['class' => 'img-rounded', 'alt' => Yii::t('modelattr', 'Logo'), 'title' => Yii::t('modelattr', 'Logo')]) :
                    Html::img('@frontend/img/profile-default90x90.png')
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            echo DetailView::widget([
                'model'      => $model,
                'attributes' => $gridColumn
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="clearfix"></div>
            <?php
            echo Helpers::getModalFooter($model, $model->c_id, 'view', [
                'buttons' => ['cancel']
            ]);
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

