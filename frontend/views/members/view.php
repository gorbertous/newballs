<?php

use yii\widgets\DetailView;
use common\helpers\Helpers;

/* @var $this yii\web\View */
/* @var $model backend\models\Members */


$redcross = '<i class="text-danger fas fa-times fa-lg" aria-hidden="true"></i>';
$greencheck = '<i class="text-success fas fa-check fa-lg" aria-hidden="true"></i>';
?>

<div class="row">
    <div class="col-md-12">

        <div class="members-view">

            <?=
            DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    [
                        'label' => Yii::t('modelattr', 'Club'),
                        'value' => function ($model) {
                            return isset($model->club) ? $model->club->name : null;
                        },
                    ],
                    [
                        'label' => Yii::t('modelattr', 'Name'),
                        'value' => function ($model) {
                            return isset($model->name) ? $model->name : null;
                        },
                    ],
                    [
                        'label'  => Yii::t('modelattr', 'Photo'),
                        'format' => 'raw',
                        'value'  => function ($model) {
                            $gravatar = isset($model->user->email) ? $model->getGravatar($model->user->email) : null;
                            return !empty($model->photo) ? $model->getIconPreviewAsHtml('ajaxfileinputPhoto', 60) : $gravatar;
                        }
                    ],
                    'email:email',
                    [
                        'label' => Yii::t('modelattr', 'Membership'),
                        'value' => function ($model) {
                            return isset($model->memType) ? $model->memType->nameFB : null;
                        },
                    ],
                    [
                        'label'  => Yii::t('modelattr', 'Level'),
                        'format' => 'raw',
                        'value'  => function ($model) {
                            return isset($model->grade_id) ? common\dictionaries\Grades::get($model->grade_id) : null;
                        }
                    ],
                    'phone',
                    'phone_mobile',
                    [
                        'label'  => Yii::t('modelattr', 'Address'),
                        'format' => 'raw',
                        'value'  => function ($model) {
                            $address = !empty($model->address) ? $model->fullAddress : null;
                            return $address;
                        }
                    ],
                    [
                        'label' => Yii::t('modelattr', 'Nationality'),
                        'value' => function($model) {
                            return $model->nationalitytranslated;
                        },
                    ],
                    [
                        'label'  => Yii::t('modelattr', 'Is Committee'),
                        'format' => 'raw',
                        'value'  => function($model)use ($redcross, $greencheck) {
                            return $model->is_admin ? $greencheck : $redcross;
                        },
                    ],
                    [
                        'label'  => Yii::t('modelattr', 'Is Chairman'),
                        'format' => 'raw',
                        'value'  => function($model)use ($redcross, $greencheck) {
                            return $model->is_organiser ? $greencheck : $redcross;
                        },
                    ],
                    [
                        'label'  => Yii::t('modelattr', 'Is Active'),
                        'format' => 'raw',
                        'value'  => function($model)use ($redcross, $greencheck) {
                            return $model->is_active ? $greencheck : $redcross;
                        },
                    ],
                    [
                        'label'  => Yii::t('modelattr', 'Has Payed'),
                        'format' => 'raw',
                        'value'  => function($model)use ($redcross, $greencheck) {
                            return $model->has_paid ? $greencheck : $redcross;
                        },
                    ],
                    [
                        'label'  => Yii::t('modelattr', 'Visible'),
                        'format' => 'raw',
                        'value'  => function($model)use ($redcross, $greencheck) {
                            return $model->is_visible ? $greencheck : $redcross;
                        },
                    ],
                    [
                        'label'  => Yii::t('modelattr', 'Coaching Lessons'),
                        'format' => 'raw',
                        'value'  => function($model)use ($redcross, $greencheck) {
                            return $model->coaching ? $greencheck : $redcross;
                        },
                    ],
                    [
                        'label'  => Yii::t('modelattr', 'Scores upload ban'),
                        'format' => 'raw',
                        'value'  => function($model)use ($redcross, $greencheck) {
                            return $model->ban_scoreupload ? $greencheck : $redcross;
                        },
                        'visible' => Yii::$app->user->can('team_member'),
                    ],
                ],
            ])
            ?>
        </div>
       
        <div class="clearfix"></div> <br />
        <?php
        echo Helpers::getModalFooter($model, $model->member_id, 'view', [
            'buttons' => ['cancel']
        ]);
        ?>
    </div>
</div>


